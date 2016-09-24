<?php namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Grupo;
use App\library\Sity;
use Redirect, Session;

use App\Role;
use App\Permission;
use App\Bitacora;

class RolesController extends Controller
{
    public function __construct()
    {
       	$this->middleware('hasAccess');    
    }
    
    /*************************************************************************************
     * Despliega un grupo de registros en formato de tabla
     ************************************************************************************/	
	public function index()
	{
		//Encuentra todos los roles registrados 
		$datos = Role::all();
		//dd($datos->toArray());
        
        //return response()->json($datos->toArray());
 		return view('backend.roles.index')->with('datos', $datos);
	}   

    /*************************************************************************************
     * Despliega un grupo de registros en formato de tabla
     ************************************************************************************/	
	public function permisPorRole($role_id)
	{
		//Encuentra todos los permisos registrados 
		$datos = Role::find($role_id)->permissions;
		$datos_1 = $datos->pluck('name', 'id')->all();
	    //dd($datos_1);

	    //Obtiene todos los permisos registrados en la base de datos
 	    $datos_2= Permission::orderBy('name')->get();
	    $datos_2= $datos_2->pluck('name', 'id')->all();      
	    //dd($datos_1, $datos_2);
        
        // Subtrae de la lista total de permisos registrados todos aquellos
        // permisos que ya están vinculados a un role
        // para evitar vincular permisos previamente vinculados.
		$permisos = array_diff($datos_2, $datos_1);		
		//dd($permisos);  
 		
 		return view('backend.roles.permisPorRole')
 				->with('datos', $datos)
 				->with('role_id', $role_id)
 				->with('permisos', $permisos);
	}

    /*************************************************************************************
     * Almacena un nuevo registro en la base de datos
     ************************************************************************************/	
	public function store()
	{
        //dd(Input::all());
        $input = Input::all();
        $rules = array(
            'id'    	=> 'required'
        );
    
        $messages = [
            'required' => 'El campo :attribute es requerido!',
            'unique'   => 'Este :attribute ya existe, no se admiten duplicados!'
        ];        
            
        $validation = \Validator::make($input, $rules, $messages);      	

		if ($validation->passes())
		{
			
			$role=Role::find(Input::get('role_id'));
			$role->permissions()->attach(Input::get('id'));		
			
			$permisname = Permission::find(Input::get('id'));

			// Registra en bitacoras
			$detalle =	'Vincula permiso '.	$permisname->name. ' a role '. $role->name;
 			Sity::RegistrarEnBitacora(10, 'permission-role',1, $detalle);
			
			//return response()->json(["mensaje" => 'La Junta Directiva ' .$dato->nombre. ' ha sido creada con éxito.']); //api
			Session::flash('success', 'El permiso ' .$permisname->name. ' ha sido vinculado al role '. $role->name);
			//return Redirect::route('permisPorRole');
			return Redirect::back();
		}
        return Redirect::back()->withInput()->withErrors($validation);
	}


    /*************************************************************************************
     * Almacena un nuevo registro en la base de datos
     ************************************************************************************/	
	public function desvincularpermis($role_id, $permis_id)
	{
		
		$role=Role::find($role_id);
		$role->permissions()->detach($permis_id);		

		$permisname = Permission::find($permis_id);

		// Registra en bitacoras
		$detalle =	'Desvincula permiso '.$permisname->name. ' del role '. $role->name;
		Sity::RegistrarEnBitacora(11, 'permission-role',1, $detalle);
		
		Session::flash('success', 'El permiso ' .$permisname->name. ' ha sido desvinculado del role '. $role->name);
		return Redirect::route('permisPorRole', $role_id);
 	}
}