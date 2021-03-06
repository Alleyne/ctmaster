<?php namespace App\Http\Controllers\catalogo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\library\Sity;
use App\Catalogo;
use Session, DB;

class CatalogosController extends Controller {
    
  public function __construct()
  {
     	$this->middleware('hasAccess');    
  }
  
  /*************************************************************************************
   * Despliega el registro especificado en formato formulario sólo lectura
   ************************************************************************************/	
	public function index()
	{

    $datos = Catalogo::all();
    //dd($datos->toArray());
    
    if($datos) {
			return view('catalogo.index')->with('datos', $datos);
		
		} else {
			Session::flash('danger', 'View no existe!');
			return back();	    	
    }
	}

  /*************************************************************************************
   * Despliega formulario para crear un nuevo registro
   ************************************************************************************/	
	public function createCuenta($id)
	{
		return view('catalogo.createCuenta')->with('id', $id);
	}     
    
  /*************************************************************************************
   * Almacena un nuevo registro en la base de datos
   ************************************************************************************/	
	public function store(Request $request)
	{
		
	 DB::beginTransaction();
	  try {
			//dd($request->All());
	    $codigo= $request->input('id');
	    //dd($codigo);

	    if ($codigo==1 || $codigo==2) {
				$this->validate($request, array(
					'nombre'    			=> 'required',
					'codigo'    			=> 'required|between:7,7',
					'corriente_siono' => 'required'
				));
	    
	    } elseif ($codigo==6) {
				$this->validate($request, array(
					'nombre'    			=> 'required',
					'codigo'    			=> 'required|between:7,7'
				));
	    
	    } else {
				$this->validate($request, array(
					'nombre'    => 'required',
					'codigo'    => 'required|between:7,7'
				));
	    }
	    
			$exist= Catalogo::where('codigo', $codigo)->first();
			
			if ($exist) {
				Session::flash('danger', 'La cuenta '.$codigo.' ya existe, no puede haber duplicados.');
				return back()->withInput();

			} elseif ($codigo[0] != $request->input('id')) {
				Session::flash('danger', 'La cuenta '.$codigo.' debe comenzar con '.$request->input('id'));
				return back()->withInput();	
			}	

			$dato = new Catalogo;
			if ($codigo[0]=='1' || $codigo[0]=='2') {
			$dato->nombre       	 = $request->input('nombre');
			$dato->codigo		       = $request->input('codigo');
			$dato->tipo			  	   = $request->input('id');
			$dato->corriente_siono = $request->input('corriente_siono');
			$dato->save();	

			// Registra en bitacoras
			$detalle =	'nombre= '.		     		$dato->nombre. ', '.
									'codigo= '.   		 		$dato->codigo. ', '.
									'corriente_siono= '. 	$dato->corriente_siono. ', '.
									'tipo= '.		     			$dato->tipo;

			} elseif ($codigo[0]=='3' || $codigo[0]=='4') {
				$dato->nombre       	 = $request->input('nombre');
				$dato->codigo		       = $request->input('codigo');
				$dato->tipo			  	   = $request->input('id');
				$dato->save();	

				// Registra en bitacoras
				$detalle =	'nombre= '.		    $dato->nombre. ', '.
										'codigo= '.   		$dato->codigo. ', '.
										'tipo= '.		    	$dato->tipo;

			} elseif ($codigo[0]=='6') {
				$dato->nombre       	 = $request->input('nombre');
				$dato->codigo		       = $request->input('codigo');
				$dato->tipo			  	   = $request->input('id');
				$dato->save();	

				// Registra en bitacoras
				$detalle =	'nombre= '.		    	$dato->nombre. ', '.
										'codigo= '.   			$dato->codigo. ', '.
										'tipo= '.		    		$dato->tipo;
			}

	  	Sity::RegistrarEnBitacora($dato, $request->All(), 'Catalogo', 'Crea nueva cuenta contable');
	  	
	  	DB::commit();			
			Session::flash('success', 'La cuenta -'.$dato->nombre.'- ha sido creada con éxito.');
			return redirect()->route('catalogos.index');

	  } catch (\Exception $e) {
	    DB::rollback();
	    Session::flash('warning', ' Ocurrio un error en CatalogosController@store, la transaccion ha sido cancelada!');
	    return back()->withInput();
	  }
	}

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //dd($id);
    $cuenta = Catalogo::find($id);
    return view('catalogo.edit')->withCuenta($cuenta);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
	{
	  DB::beginTransaction();
	  try {
			
			//dd($request->toArray());
			$cuenta= Catalogo::find($id);
	   
			$this->validate($request, array(
	    'nombre' => 'required'
	  	));			

			$cuenta->nombre = $request->nombre;		
			if ($request->concilia_radios == 1) {
			
			} elseif ($request->concilia_radios == 2) {
				$cuenta->conciliacion = 'n/c';
			
			} elseif ($request->concilia_radios == 3) {
				$cuenta->conciliacion = 'n/d';			
			}
			
			$cuenta->save();
	  	
	  	DB::commit();			
			
			Session::flash('success', 'La cuenta ' .$cuenta->nombre. ' ha sido editada con exito');
	    return redirect()->route('catalogos.index'); 

	  } catch (\Exception $e) {
	    DB::rollback();
	    Session::flash('warning', ' Ocurrio un error en CatalogosController@update, la transaccion ha sido cancelada!');
	    return back()->withInput();
	  }
	}

}