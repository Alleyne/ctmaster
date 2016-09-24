<?php namespace App\Http\Controllers\contabilidad;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect, Session;
use App\library\Sity;
use App\Http\Helpers\Grupo;
use Validator;

use App\Org;
use App\Factura;
use App\Catalogo;
use App\Detallepagofactura;
use App\Bitacora;
use App\Pcontable;
use App\Ctdiario;

class DetallepagofacturasController extends Controller {
    public function __construct()
    {
       	$this->middleware('hasAccess');    
    }
    
    /*************************************************************************************
     * Despliega un grupo de registros en formato de tabla
     ************************************************************************************/	
	public function show($factura_id)
	{
        $datos = Detallepagofactura::where('factura_id', $factura_id)->get();
        //dd($datos->toArray());		
	    
	    $factura= Factura::find($factura_id);
		
		return view('contabilidad.detallepagofacturas.show')
				->with('factura', $factura)
				->with('datos', $datos);     	
	}	

    /*************************************************************************************
     * Almacena un nuevo registro en la base de datos
     ************************************************************************************/	
	public function store()
	{
        //dd(Input::all());
        $input = Input::all();

        $rules = array(
            'factura_id'		=> 'required',
            'fecha'    			=> 'required|Date',
            'detalle'    		=> 'Required',
            'monto'    			=> 'required|Numeric|min:0.01'
        );
    
        $messages = [
            'required'		=> 'Informacion requerida!',
        	'numeric'		=> 'Solo se admiten valores numericos!',
        	'date'			=> 'Fecha invalida!',
        	'min'			=> 'Se requiere un valor mayor que cero!'
        ];        
            
        $validation = \Validator::make($input, $rules, $messages);      	

		if ($validation->passes())
		{
			// encuentra el monto total de la factura			
        	$factura= Factura::find(Input::get('factura_id'));
        	$totalfactura=round(floatval($factura->total),2);
			//dd($totalfactura);			
			
		    // verifica que el monto del nuevo detalle no sobrepase al monto total de la factura
		    if (Input::get('monto')>$totalfactura) {
				Session::flash('danger', '<< Error >>: No se pudo agregar el nuevo detalle ya que su monto sobrepasa al monto total de la factura!');
				return Redirect::route('detallepagofacturas.show', $factura->id);
		    } 

		    // cuenta la cantidad de detalles en la factura
	 	    $cantDetalles= Detallepagofactura::where('factura_id', Input::get('factura_id'))->count('id');
	 	    
	 	    // si no existen detalles en la factura y el monto total de la factura es igual al monto del nuevo detalle
	 	    // entonces se trata de un pago completo
	 	    if ($cantDetalles==0 && ($totalfactura == Input::get('monto'))) {
				// salva el nuevo detalle
				$dato = new Detallepagofactura;
				$dato->factura_id       	= Input::get('factura_id');
				$dato->fecha 	   			= Input::get('fecha');
				$dato->detalle 	       		= Input::get('detalle');
				$dato->monto 	       		= Input::get('monto');
				$dato->pagotipo				= 1;
				$dato->save();	
				
				// actualiza el monto de los detalles en la factura
				$factura->totalpagodetalle= Input::get('monto');
				$factura->save();
	 	    
	 	    } else {	// se trata de un pago parcial
			    // calcula el monto total de los detalles de la presente factura mas el monto del nuevo detalle
		 	    $totaldetalles= Detallepagofactura::where('factura_id', Input::get('factura_id'))->sum('monto');		    
				$totaldetalles=round(floatval($totaldetalles + Input::get('monto')),2);
				//dd($totaldetalles);

			    // verifica que el monto total de los detalle incluyendo al nuevo no sobrepase al monto total de la factura
			    if ($totaldetalles > $totalfactura) {
					Session::flash('danger', '<< Error >>: No se pudo agregar el nuevo detalle ya con su monto sobrepasaria al monto total de la factura!');
					return Redirect::route('detallepagofacturas.show', $factura->id);
			   
			    } else {
					// salva el nuevo detalle
					$dato = new Detallepagofactura;
					$dato->factura_id       	= Input::get('factura_id');
					$dato->fecha 	   			= Input::get('fecha');
					$dato->detalle 	       		= Input::get('detalle');
					$dato->monto 	       		= Input::get('monto');
					$dato->pagotipo				= 0;
					$dato->save();	
			    
					// actualiza el monto de los detalles en la factura
					$factura->totalpagodetalle= $totaldetalles;
					$factura->save();
			    }
	 	    }
	 	    
			//Sity::RegistrarEnBitacora(1, 'detallepagofacturas', $dato->id, Input::all());
			Session::flash('success', 'El detalle de factura No. ' .$dato->id. ' ha sido creado con éxito.');
			return Redirect::route('detallepagofacturas.show', $dato->factura_id);
		}

        Session::flash('warning', '<< ATENCION >> Se encontraron errores en su formulario, recuerde llenar todos los campos!');
        return Redirect::back()->withInput()->withErrors($validation);
	}
    
    /*************************************************************************************
     * Borra registro de la base de datos
     ************************************************************************************/	
	public function destroy($detallepagofactura_id)
	{
		//dd($detallefactura_id);
		$dato = Detallepagofactura::find($detallepagofactura_id);
		$dato->delete();			

		// Registra en bitacoras
		/*$det =	'Borra detalle de Factura '.$dato->no. ', '.
				'cantidad= '.   		$dato->cantidad. ', '.
				'detalle= '.   			$dato->detalle. ', '.
				'precio= '.   			$dato->precio. ', '.
				'itbms= '.   			$dato->itbms. ', '.
				'factura_id= '. 		$dato->factura_id;*/
		
	    // actualiza el totalpagodetalle en la tabla facturas
	    $factura= Factura::find($dato->factura_id);
		$factura->totalpagodetalle= $factura->totalpagodetalle - $dato->monto;
		$factura->save();		
		
		//Sity::RegistrarEnBitacora(3, 'detallefacturas', $dato->id, $det);
		Session::flash('success', 'El detalle de factura "' .$dato->detalle .'" con monto de B/.'. $dato->monto.' ha sido borrado permanentemente de la base de datos.');

		return Redirect::route('detallepagofacturas.show', $dato->factura_id);
	}

  /****************************************************************************************
   * Esta function registra en libros el pago de una factura ya se parcial o completa
   *****************************************************************************************/
  public static function contabilizaDetallePagoFactura($detallepagofactura_id)
  {

    // determina el periodo contable vigente
    $periodo= Pcontable::where('cerrado', 0)->first();
    //dd($periodo);
    
    // encuentra los datos del detalle de pago en estudio
    $dato= Detallepagofactura::find($detallepagofactura_id);
    //dd($dato->toArray());
    
    // encuentra el proveedor de la factura
    $factura= Factura::find($dato->factura_id);
    
    // almacena el total de la factura 
    $totalfactura= round(floatval($factura->total),2);
    
    // encuentra los datos de la organizacion
    $org= Org::find($factura->org_id);
    //dd($org->toArray());    

    // verifica si se trata de un pago completo o parcial
    if ($dato->pagotipo==1) {
    	$pagotipo='completo';
    
    } else {
    	$pagotipo='parcial';
    } 
    
	// registra en ctmayores una disminucion en la cuenta de Cuetas por pagar a proveedores
 	Sity::registraEnCuentas(
			$periodo->id,
			'menos', 
			2,
			6,
			$dato->fecha,
	    	'Registra pago '. $pagotipo. ' de la factura '. $factura->no,
	    	$dato->monto,
	       	Null,
	       	$org->id
	       );
	
    // registra en Ctdiario principal
    $diario = new Ctdiario;
    $diario->pcontable_id  = $periodo->id;
    $diario->fecha   = $dato->fecha;
    $diario->detalle = 'Cuenta por pagar a proveedores';
    $diario->debito  = $dato->monto;
    $diario->save(); 
	
	// registra en ctmayores una disminucion en la cuenta Banco
 	Sity::registraEnCuentas(
			$periodo->id,
			'menos',
			1, 
			8,
			$dato->fecha,
	    	'   Banco No. 1',
	    	$dato->monto,
	       	Null,
	       	$org->id
	       );

    // registra en Ctdiario principal
    $diario = new Ctdiario;
    $diario->pcontable_id  = $periodo->id;
    $diario->detalle = '   Banco No. 1';
    $diario->credito = $dato->monto;
    $diario->save(); 
    
    // registra en Ctdiario principal
    $diario = new Ctdiario;
    $diario->pcontable_id  = $periodo->id;
    $diario->detalle = 'Para registra pago '. $pagotipo. ' de la factura '. $factura->no;
    $diario->save(); 

	// registra el detalle de pago de factura como contabilizado	
	$dato->contabilizado = 1;
	$dato->save();

	
	// verifica si hay algun detalle que no ha sido contabilizado
    $sinContabilizar= Detallepagofactura::where('factura_id', $factura->id)
										->where('contabilizado', 0)
    									->count('contabilizado');		    
	//dd($sinContabilizar);

    // calcula el monto total de los detalles de la presente factura
	$totaldetalles= Detallepagofactura::where('factura_id', $factura->id)->sum('monto');		    
	$totaldetalles=round(floatval($totaldetalles),2);
	//dd($totaldetalles, $sinContabilizar, $totalfactura, $factura->id);	

    // si el total de la factura es igual al total de los detalles y no exiten detalles por contabilizar
    // entonces registra la factura como pagada en su totalidad
    if (($totalfactura == $totaldetalles) && $sinContabilizar==0) {
		$factura->pagada= 1;
		$factura->save();		
    	
    } elseif ($totaldetalles < $totalfactura) {
		$factura->pagada= 0;
		$factura->save();
    }

	// Registra en bitacoras
	$detalle =	'Registra pago '.$pagotipo. 
				' de la factura '.$factura->no. 
				' de '. $org->nombre. 
				' por la suma de '.$dato->monto. 
				', periodo contable '.$periodo->periodo.
				', fecha= '.$dato->fecha;

	Sity::RegistrarEnBitacora(18, 'facturas', $factura->id, $detalle);
	Session::flash('success', 'Detalle de pago de factura No. ' .$factura->no. ' ha sido cotabilizado.');

	return Redirect::route('detallepagofacturas.show', $factura->id);
  }
} 