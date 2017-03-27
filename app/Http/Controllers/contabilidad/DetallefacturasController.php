<?php namespace App\Http\Controllers\contabilidad;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session, DB;
use App\library\Sity;
use App\Http\Helpers\Grupo;
use Validator;

use App\Org;
use App\Factura;
use App\Catalogo;
use App\Detallefactura;
use App\Serviproducto;
use App\Bitacora;

class DetallefacturasController extends Controller {
  public function __construct()
  {
     	$this->middleware('hasAccess');    
  }
  
  /*************************************************************************************
   * Despliega un grupo de registros en formato de tabla
   ************************************************************************************/	
	public function show($factura_id)
	{
    //$datos = Detallefactura::where('factura_id', $factura_id)
    				//->join('catalogos', 'catalogos.id', '=', 'detallefacturas.catalogo_id')
            //->select('detallefacturas.id','detallefacturas.cantidad','detallefacturas.detalle','detallefacturas.precio','detallefacturas.itbms','detallefacturas.factura_id','catalogos.codigo')
            //->get();
    
    $datos = Detallefactura::where('factura_id', $factura_id)
    				->join('serviproductos', 'serviproductos.id', '=', 'detallefacturas.serviproducto_id')
            ->select('detallefacturas.id','serviproductos.catalogo_id','serviproductos.nombre','detallefacturas.cantidad','detallefacturas.precio','detallefacturas.itbms')
            ->get();
    //dd($datos->toArray());		
		
		// encuentra todos los productos y servicios registrados en la factura
		$datos_2= $datos->pluck('nombre', 'id')->all();       
		//dd($datos_2);
   
    // encuentra los datos generales de la factura
    $factura= Factura::find($factura_id);
		//dd($factura->toArray());
    
    // encuentra los serviproductos asignados a un determinado proveedor
		$serviproductos = Org::find($factura->org_id)->serviproductos;
		$datos_1= $serviproductos->pluck('nombre', 'id')->all();   
    //dd($datos_1, $datos_2);

		// calcula y agrega el total
		$i=0;		
		$subTotal = 0;
		$totalItbms = 0;

		foreach ($datos as $dato) {
		    $datos[$i]["total"] = number_format((($dato->cantidad * $dato->precio) + $dato->itbms),2);
		    $datos[$i]["codigo"] = Catalogo::find($dato->catalogo_id)->codigo;
		    $subTotal = $subTotal + ($dato->cantidad * $dato->precio);
		    $totalItbms = $totalItbms + $dato->itbms;		    
		    $i++;
		}        

    // Subtrae de la lista total de productos y servicios registrados toda aquellas
    // que ya están asignadas a un factura
    // para evitar asignar productos y servicios previamente asignadas
		$serviproductos = array_diff($datos_1, $datos_2);		
		//dd($serviproductos);  

		return view('contabilidad.detallefacturas.show')
				 ->with('serviproductos', $serviproductos)
				 ->with('factura', $factura)
				 ->with('subTotal', $subTotal)
				 ->with('totalItbms', $totalItbms)
				 ->with('datos', $datos);      	
	}	

  /*************************************************************************************
   * Almacena un nuevo registro en la base de datos
   ************************************************************************************/	
	public function store()
	{
        
		DB::beginTransaction();
		try {
      //dd(Input::all());
      $input = Input::all();
      $rules = array(
        'serviproducto_id'	=> 'required',
        'cantidad'  				=> 'Required|Numeric',
        'precio'    				=> 'required|Numeric|min:0.01',
        'itbms'    					=> 'required|Numeric|min:0'
      );
  
      $messages = [
        'required'	=> 'Informacion requerida!',
      	'numeric'		=> 'Solo se admiten valores numericos!',
      	'min'			  => 'El precio del servicio debe ser mayor que cero!'
      ];        
          
      $validation = \Validator::make($input, $rules, $messages);      	

			if ($validation->passes())
			{
				
				// encuentra el valor total en la factura			
      	$factura= Factura::find(Input::get('factura_id'));
      	$totalfactura= $factura->total;

				// encuentra los datos del serviproducto
      	$serviproducto= Serviproducto::find(Input::get('serviproducto_id'));
				
				$dato = new Detallefactura;
				$dato->serviproducto_id  	= Input::get('serviproducto_id');
				$dato->nombre					  	= $serviproducto->nombre;
				$dato->cantidad 	       	= Input::get('cantidad');
				$dato->precio 	       		= Input::get('precio');
				$dato->itbms 	       			= Input::get('itbms');
				$dato->factura_id	  			= Input::get('factura_id');
				$dato->catalogo_id				= $serviproducto->catalogo->id;
				$dato->codigo	  					= $serviproducto->catalogo->codigo;
				$dato->cuenta	  					= $serviproducto->catalogo->nombre;
				$dato->save();
				
				// Registra en bitacoras
				$det=	'serviproducto_id= '.	$dato->serviproducto_id. ', '.
							'nombre= '				  .	$dato->nombre. ', '.
							'cantidad= '				.	$dato->cantidad. ', '.
							'precio= '					.	$dato->precio. ', '.
							'itbms= '					  .	$dato->itbms. ', '.
							'factura_id= '			.	$dato->factura_id. ', '.
							'catalogo_ido= '		.	$dato->catalogo_id. ', '.
							'codigo= '				  .	$dato->codigo. ', '.
							'cuenta= '				  .	$dato->cuenta;

				$totaldetalles= 0;

			  //calcula el total de detallefacturas para la presente factura
		 	  $detalles= Detallefactura::where('factura_id', Input::get('factura_id'))->get();		    
				//dd($detalles->toArray());
				
				foreach ($detalles as $detalle) {
					$totaldetalles = $totaldetalles + ($detalle->precio + $detalle->itbms);
				}
				//dd($totaldetalles);
			    
		    if (round(floatval($totalfactura),2) < round(floatval($totaldetalles),2)) {
	        Session::flash('danger', '<< ERROR >> El valor total de los detalles no puede sobrepasar al valor total de la factura de egresos. Intente nuevamente!');
	        return back();
		    	
		    } elseif (round(floatval($totalfactura),2) > round(floatval($totaldetalles),2)) {
					$factura->totaldetalle= $totaldetalles;
					$factura->save();
			    Session::flash('warning', '<< ATENCION >> El valor total de los detalles es inferior al valor total de la factura egresos. Continue ingresando detalles!');
		    
		    } elseif (round(floatval($totalfactura),2) == round(floatval($totaldetalles),2)) {
					$factura->totaldetalle= $totaldetalles;
					$factura->etapa= 2;
					$factura->save();		
		    }
			    
				Sity::RegistrarEnBitacora(1, 'detallefacturas', $dato->id, $det);
				Session::flash('success', 'El detalle de factura No. ' .$dato->id. ' ha sido creado con éxito.');
		    DB::commit();				
				return redirect()->route('detallefacturas.show', $dato->factura_id);
			}
	        
      Session::flash('danger', '<< ATENCION >> Se encontraron errores en su formulario, recuerde llenar todos los campos!');
      return back()->withInput()->withErrors($validation);
		
		} catch (\Exception $e) {
			DB::rollback();
			Session::flash('warning', ' Ocurrio un error en el modulo DetallefacturasController.store, la transaccion ha sido cancelada! '.$e->getMessage());

			return back()->withInput()->withErrors($validation);
		}
	}

  /*************************************************************************************
   * Borra registro de la base de datos
   ************************************************************************************/	
	public function destroy($detallefactura_id)
	{
		
		DB::beginTransaction();
		try {
			//dd($detallefactura_id);
			$dato = Detallefactura::find($detallefactura_id);
			$dato->delete();			

			// Registra en bitacoras
			$det =	'Borra detalle de Factura '.$dato->no. ', '.
					'cantidad= '.   		$dato->cantidad. ', '.
					'detalle= '.   			$dato->detalle. ', '.
					'precio= '.   			$dato->precio. ', '.
					'itbms= '.   			$dato->itbms. ', '.
					'factura_id= '. 		$dato->factura_id;
			
			$totaldetalles=0;

	    //calcula el total de detallefacturas para la presente factura
 	    $detalles= Detallefactura::where('factura_id', $dato->factura_id)->get();		    
			//dd($detalles->toArray());
			foreach ($detalles as $detalle) {
				$totaldetalles=$totaldetalles +(($detalle->precio * $detalle->cantidad)+$detalle->itbms);
			}

	    $factura= Factura::find($dato->factura_id);
	    if (round(floatval($factura->total),2) == round(floatval($totaldetalles),2)) {
			$factura->totaldetalle= $totaldetalles;
			$factura->etapa= 2;
			$factura->save();		
	    	
	    } else {
			$factura->totaldetalle= $totaldetalles;
			$factura->etapa= 1;
			$factura->save();
	    }
			
			Sity::RegistrarEnBitacora(3, 'detallefacturas', $dato->id, $det);
			Session::flash('success', 'El detalle "' .$dato->detalle .'" ha sido borrado permanentemente de la base de datos.');
			DB::commit();
			return redirect()->route('detallefacturas.show', $dato->factura_id);
		
		} catch (\Exception $e) {
	    DB::rollback();
    	Session::flash('warning', ' Ocurrio un error en el modulo DetallefacturasController.destroy, la transaccion ha sido cancelada! '.$e->getMessage());

    	return back()->withInput()->withErrors($validation);
		}
	}
} 