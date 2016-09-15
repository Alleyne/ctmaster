<?php namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\library\Sity;
use Redirect, Session;
use Validator;
//use Debugbar;
use Carbon\Carbon;

use App\Ctmayore;
use App\Catalogo;
use App\Pcontable;
use App\Ctdiario;

class AjustesController extends Controller {
  
    public function __construct()
    {
        $this->middleware('hasAccess');    
    }
 
    /*************************************************************************************
     * Despliega el registro especificado en formato formulario sólo lectura
     ************************************************************************************/  
    public function verAjustes($id, $periodo, $cuenta, $codigo)
    {

      $datos= Ctmayore::where('pcontable_id', $periodo)
                   ->where('ajuste_siono', 1)
                   ->where('cuenta', $cuenta)
                   ->get();
      //dd($datos->toArray());
      
      return view('contabilidad.ajustes.verAjustes')
        ->with('datos', $datos)
        ->with('periodo', $periodo);
    }

    /*************************************************************************************
     * Despliega formulario para crear un nuevo registro
     ************************************************************************************/  
    public function createAjustes($periodo_id)
    {
      //dd($periodo_id);
      $datos= Catalogo::where('activa', 1)->orderBy('codigo')->get();  
     //dd($datos->toArray());
      return view('contabilidad.ajustes.createAjuste')
             ->with('periodo_id', $periodo_id)
             ->with('datos', $datos);
    }
   
    /*************************************************************************************
     * Almacena un nuevo registro en la base de datos
     ************************************************************************************/  
    public function store()
    {
        //dd(Input::all());
        $input= Input::all();
       
        $ajustes= Input::all();          
        array_forget($ajustes, 'descripcion');
        //dd($ajustes);      
        
        $ajustes = array_slice($ajustes, 2);
        $ajustes =array_chunk($ajustes, 3);

        foreach ($ajustes as $ajuste) {
            if (floatval($ajuste[1])>0 && $ajuste[2]>0 ) {
                Session::flash('danger', 'No puede ajustar una cuenta por el lado debito y credito al mismo tiempo.');
                return Redirect::back();
            }
        }

        $data=array();    
        $i=0;

        $periodo= Input::get('periodo_id');
        //dd($periodo);

        $rules = array(
            'descripcion'=> 'required'
        );
    
        $messages = [
            'required'=> 'Informacion requerida!'
        ];         
        //dd($rules, $messages);
        
        $validation = \Validator::make($input, $rules, $messages);          
        if ($validation->passes())
        {
            foreach ($ajustes as $ajuste) {
              $cuenta= Catalogo::find(intval($ajuste[0]));
              //dd($cuenta->toArray());
              $dato = new Ctmayore;             
              if ($cuenta->tipo=='1') {     // cuenta de activo aumenta
                  if (floatval($ajuste[1])>0 && $ajuste[2]=="" ) {
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 1;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');               
                    $dato->debito  = $ajuste[1];
                    $dato->credito  = 0;
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                                 
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[1];
                    $datas[$i]['tipo']= 1;
                    $i++;
                  
                  } elseif ($ajuste[1]=="" && floatval($ajuste[2])>0 ) {    // cuenta de activo disminuye    
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 1;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = 0;
                    $dato->credito  = $ajuste[2];
                    $dato->ajuste_siono  = 1;
                    $dato->save();

                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[2];
                    $datas[$i]['tipo']= 2;
                    $i++;
                  }       

              } elseif ($cuenta->tipo=='2') {       // cuenta de pasivo aumenta
                  if ($ajuste[1]=="" && floatval($ajuste[2])>0 ) {
                    $dato->pcontable_id = $periodo;
                    $dato->tipo = 2;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = 0;
                    $dato->credito  = $ajuste[2];
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                    
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[2];
                    $datas[$i]['tipo']= 2;
                    $i++;

                  } elseif (floatval($ajuste[1])>0 && $ajuste[2]=="" ) {    // cuenta de pasivo disminuye
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 2;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = $ajuste[1];
                    $dato->credito  = 0;
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                    
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[1];
                    $datas[$i]['tipo']= 1;
                    $i++;  
                  }
              
              } elseif ($cuenta->tipo=='3') {   // cuenta de patrimonio aumenta
                  if ($ajuste[1]=="" && floatval($ajuste[2])>0) {
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 3;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = 0;
                    $dato->credito  = $ajuste[2];
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                    
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[2];
                    $datas[$i]['tipo']= 2;
                    $i++; 
                  
                  } elseif (floatval($ajuste[1])>0 && $ajuste[2]=="" ) {    // cuenta de patrimonio disminuye
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 3;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = $ajuste[1];
                    $dato->credito  = 0;
                    $dato->ajuste_siono  = 1;
                    $dato->save();

                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[1];
                    $datas[$i]['tipo']= 1;
                    $i++; 
                  }
              
              } elseif ($cuenta->tipo=='6') {   // cuenta de gasto aumenta
                  if (floatval($ajuste[1])>0 && $ajuste[2]=="" ) {
                    $dato->pcontable_id = $periodo;
                    $dato->tipo = 6;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = $ajuste[1];
                    $dato->credito  = 0;
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                    
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[1];
                    $datas[$i]['tipo']= 1;
                    $i++; 

                  } elseif ($ajuste[1]=="" && floatval($ajuste[2])>0 ) {    // cuenta de gasto disminuye
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 6;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;                
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');
                    $dato->debito   = 0;
                    $dato->credito  = $ajuste[2];
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                    
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[2];
                    $datas[$i]['tipo']= 2;
                    $i++; 
                  }
                  
              } elseif ($cuenta->tipo=='4') {   // cuenta de ingreso aumenta
                  if (floatval($ajuste[1])>0 && $ajuste[2]=="") {
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 4;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito   = $ajuste[1];
                    $dato->credito  = 0;
                    $dato->ajuste_siono  = 1;
                    $dato->save();
                  
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[1];
                    $datas[$i]['tipo']= 1;
                    $i++; 

                  } elseif ($ajuste[1]=="" && floatval($ajuste[2])>0) {   // cuenta de ingreso disminuye
                    $dato->pcontable_id = $periodo;                
                    $dato->tipo = 4;
                    $dato->cuenta  = $ajuste[0];
                    $dato->codigo  = $cuenta->codigo;
                    $dato->fecha   = Carbon::today();
                    $dato->detalle = Input::get('descripcion');                
                    $dato->debito  = 0;
                    $dato->credito  = $ajuste[2];
                    $dato->ajuste_siono = 1;
                    $dato->save();
                  
                    $datas[$i]['cuenta->nombre']= $cuenta->nombre;
                    $datas[$i]['monto']= $ajuste[2];
                    $datas[$i]['tipo']= 2;
                    $i++; 
                  }
              
              } else {
                return 'Error en controller AjustesController@store function';
              }
            }

            // Procede a ordenar por fecha ascendentemente
            $tipos = array();
            foreach ($datas as $data) {    
                $tipos[] = $data['tipo'];
            }

            // Procede a ordernar el array principal
            array_multisort($tipos, SORT_ASC, $datas);
            //dd($datas);

            $i= 0;
            $flag= 0;
            foreach ($datas as $data) {    
                if ($datas[$i]['tipo']=='1') {
                    // registra en diario principal    
                    $diario = new Ctdiario;
                    $diario->pcontable_id  = $periodo;                
                    if ($i==0) {
                        $diario->fecha   = Carbon::today();
                        $flag= 1;
                    }             
                    $diario->detalle = $datas[$i]['cuenta->nombre'];
                    $diario->debito  = $datas[$i]['monto'];
                    $diario->save();
                }
                $i++;
            }
            
            $i= 0;
            foreach ($datas as $data) {    
                if ($datas[$i]['tipo']=='2') {
                    // registra en diario principal    
                    $diario = new Ctdiario;
                    $diario->pcontable_id  = $periodo;                
                    if ($flag==0) {
                        $diario->fecha   = Carbon::today();
                        $flag= 1;
                    }             
                    $diario->detalle = $datas[$i]['cuenta->nombre'];
                    $diario->credito  = $datas[$i]['monto'];
                    $diario->save();
                }
                $i++;
            }

            // registra en Ctdiario principal
            $diario = new Ctdiario;
            $diario->pcontable_id  = $periodo;
            $diario->detalle = Input::get('descripcion');
            $diario->save();
            
            // Registra en bitacoras
            /*$detalle ='org_id= '.         $dato->org_id. ', '.
                        'no= '.             $dato->no. ', '.
                        'total= '.          $dato->total. ', '.
                        'fecha= '.          $dato->fecha;
        
            Sity::RegistrarEnBitacora(1, 'facturas', $dato->id, $detalle);*/
            Session::flash('success', 'El ajuste ha sido creado con éxito.');
            return Redirect::route('hojadetrabajos.show', $periodo);
        }
        return Redirect::back()->withInput()->withErrors($validation);
     } // fin de function
}