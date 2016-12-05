<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::auth();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'WelcomeController@index')->name('frontend');
Route::get('/home', 'HomeController@index')->name('backend');

Route::group(['namespace' => 'core'], function()
{
	//---------------------------------------------------------//
	// Funciones del controlador JdsController
	//---------------------------------------------------------// 	
	Route::resource('jds', 'JdsController');
    
	//---------------------------------------------------------//
	// Funciones del controlador BloquesController
	//---------------------------------------------------------// 	
  //rutas adicionales al resource controller
  Route::get('bloques/indexblqplus', 'BloquesController@indexblqplus')->name('indexblqplus');
  Route::get('bloques/showblqplus/{bloque_id}', 'BloquesController@showblqplus')->name('showblqplus');
  Route::get('bloques/createblq/{jd_id}', 'BloquesController@createblq')->name('createblq');
	Route::post('subirImagenBloque/{id}', 'BloquesController@subirImagenBloque')->name('subirImagenBloque');
	Route::resource('bloques', 'BloquesController');

	//---------------------------------------------------------//
	// Funciones del controlador SeccionesController
	//---------------------------------------------------------// 	
  //rutas adicionales al resource controller
  Route::get('secciones/indexsecplus/{bloque_id}', 'SeccionesController@indexsecplus')->name('indexsecplus');
  Route::get('secciones/showsecplus/{seccione_id}', 'SeccionesController@showsecplus')->name('showsecplus');
  Route::get('secciones/createsec/{bloque_id}, {tipo}', 'SeccionesController@createsec')->name('createsec');
	Route::post('subirImagenSeccion/{id}', 'SeccionesController@subirImagenSeccion')->name('subirImagenSeccion');
	Route::resource('secciones', 'SeccionesController');
	
	//---------------------------------------------------------//
	// Funciones del controlador UnsController
	//---------------------------------------------------------// 	
  //rutas adicionales al resource controller
  Route::get('uns/indexunall', 'UnsController@indexunall')->name('indexunall');
  Route::get('uns/indexunplus/{seccion_id}', 'UnsController@indexunplus')->name('indexunplus');
  Route::get('uns/showunplus/{seccione_id}', 'UnsController@showunplus')->name('showunplus');
  Route::get('uns/createun/{seccione_id}', 'UnsController@createun')->name('createun');
  Route::get('uns/createungrupo/{seccione_id}', 'UnsController@createungrupo')->name('createungrupo');
  Route::post('uns/storeungrupo', 'UnsController@storeungrupo')->name('storeungrupo');
	Route::resource('uns', 'UnsController');
	
	//---------------------------------------------------------//
	// Funciones del controlador BlqadminsController
	//---------------------------------------------------------// 	
  Route::get('blqadmins/indexblqadmin/{bloque_id}', 'BlqadminsController@indexblqadmin')->name('indexblqadmin');
  Route::get('blqadmins/desvincularblqdmin/{id}', 'BlqadminsController@desvincularblqdmin')->name('desvincularblqdmin');
	Route::resource('blqadmins', 'BlqadminsController');
	
	//---------------------------------------------------------//
	// Funciones del controlador PropsController
	//---------------------------------------------------------// 	
  Route::get('props/indexprops/{un_id},{seccione_id}', 'PropsController@indexprops')->name('indexprops');
  Route::get('props/createprop/{un_id},{seccione_id}', 'PropsController@createprop')->name('createprop');
  Route::get('desvincularprop/{user_id},{un_id}', 'PropsController@desvincularprop')->name('desvincularprop');
	Route::resource('props', 'PropsController');
	
	//---------------------------------------------------------//
	// Funciones del controlador OrgsController
	//---------------------------------------------------------// 	
  Route::get('orgs/desvincularSubcuenta{org_id}, {ksubcuenta_id}', 'OrgsController@desvincularSubcuenta')->name('desvincularSubcuenta');
  Route::get('orgs/catalogosPorOrg/{org_id}', 'OrgsController@catalogosPorOrg')->name('catalogosPorOrg');
	Route::resource('orgs', 'OrgsController');

	//---------------------------------------------------------//
	// Funciones del controlador UsersController
	//---------------------------------------------------------// 	
 	Route::post('subirImagenUser/{user_id}', 'BloquesController@subirImagenUser')->name('subirImagenUser');
	Route::resource('users', 'UsersController');

	//---------------------------------------------------------//
	// Funciones del controlador PhsController
	//---------------------------------------------------------// 	
	Route::post('subirImagenPh/{id}', 'PhsController@subirImagenPh')->name('subirImagenPh');
	Route::resource('phs', 'PhsController');

	//---------------------------------------------------------//
	// Funciones del controlador PermissionsController
	//---------------------------------------------------------// 	
	Route::resource('permissions', 'PermissionsController');
	
	//---------------------------------------------------------//
	// Funciones del controlador RolesController
	//---------------------------------------------------------// 	
  Route::get('roles/desvincularpermis{role_id}, {permis_id}', 'RolesController@desvincularpermis')->name('desvincularpermis');
  Route::get('roles/permisPorRole{role_id}', 'RolesController@permisPorRole')->name('permisPorRole');
	Route::resource('roles', 'RolesController');
	
	//---------------------------------------------------------//
	// Funciones del controlador BitacorasController
	//---------------------------------------------------------// 	
	Route::resource('bitacoras', 'BitacorasController');
});	

Route::group(['namespace' => 'contabilidad'], function()
{
	//---------------------------------------------------------//
	// Funciones del controlador PagosController
	//---------------------------------------------------------// 		
  Route::get('procesaChequeRecibido/{pago_id}', 'PagosController@procesaChequeRecibido')->name('procesaChequeRecibido');
  Route::get('indexPagos/{un_id}', 'PagosController@indexPagos')->name('indexPagos');
  Route::get('createPago/{un_id}', 'PagosController@createPago')->name('createPago');
  Route::get('showRecibo/{pago_id}', 'PagosController@showRecibo')->name('showRecibo');
  Route::get('procesaAnulacionPago/{pago_id}, {un_id}', 'PagosController@procesaAnulacionPago')->name('procesaAnulacionPago');
  Route::get('eliminaPagoCheque/{pago_id}', 'PagosController@eliminaPagoCheque')->name('eliminaPagoCheque');
	Route::resource('pagos', 'PagosController');
		
	//---------------------------------------------------------//
	// Funciones del controlador FacturasController
	//---------------------------------------------------------// 		
  Route::get('contabilizaDetallesFactura/{factura_id}', 'FacturasController@contabilizaDetallesFactura')->name('contabilizaDetallesFactura');
  Route::get('pagarfacturas', 'FacturasController@pagarfacturas')->name('pagarfacturas');
	Route::resource('facturas', 'FacturasController');
	
	//---------------------------------------------------------//
	// Funciones del controlador DetallefacturasController
	//---------------------------------------------------------// 		
    //Route::get('createDetalleFactura/{factura_id}', 'ContabilidadController@createDetalleFactura')->name('createDetalleFactura');
	Route::resource('detallefacturas', 'DetallefacturasController');

	//---------------------------------------------------------//
	// Funciones del controlador DetallepagofacturasController
	//---------------------------------------------------------// 		
	Route::get('contabilizaDetallePagoFactura/{detallepagofactura_id}', 'DetallepagofacturasController@contabilizaDetallePagoFactura')->name('contabilizaDetallePagoFactura');
	Route::resource('detallepagofacturas', 'DetallepagofacturasController');

	//---------------------------------------------------------//
	// Funciones del controlador CtdasmsController
	//---------------------------------------------------------// 	
  Route::get('ecuentas/{un_id}, {tipo}', 'CtdasmsController@ecuentas')->name('ecuentas');
	Route::resource('ctdasms', 'CtdasmsController');		

	//---------------------------------------------------------//
	// Funciones del controlador PcontablesController
	//---------------------------------------------------------// 	
  Route::get('crearPeriodoInicial/{todate}', 'PcontablesController@crearPeriodoInicial')->name('crearPeriodoInicial');
  Route::get('cerrarPeriodoContable/{pcontable_id}', 'PcontablesController@cerrarPeriodoContable')->name('cerrarPeriodoContable');
	Route::resource('pcontables', 'PcontablesController');

	//---------------------------------------------------------//
	// Funciones del controlador Ctdiarios
	//---------------------------------------------------------// 	
  Route::get('diarioFinal/{pcontable_id}', 'CtdiariosController@diarioFinal')->name('diarioFinal');
	Route::resource('ctdiarios', 'CtdiariosController');

	//---------------------------------------------------------//
	// Funciones del controlador HojadetrabajosController
	//---------------------------------------------------------// 	
  Route::get('estadoderesultado/{pcontable_id}', 'HojadetrabajosController@estadoderesultado')->name('estadoderesultado');
  Route::get('er/{pcontable_id}', 'HojadetrabajosController@er')->name('er');
  Route::get('bg/{pcontable_id}', 'HojadetrabajosController@bg')->name('bg');
  Route::get('balancegeneral/{pcontable_id},{periodo}', 'HojadetrabajosController@balancegeneral')->name('balancegeneral');
  Route::get('hojadetrabajo/{periodo}', 'HojadetrabajosController@hojadetrabajo')->name('hojadetrabajo');
  Route::get('verMayorAux/{periodo}, {cuenta}', 'HojadetrabajosController@verMayorAux')->name('verMayorAux');
  Route::get('cierraPeriodo/{pcontable_id},{periodo},{fecha}', 'HojadetrabajosController@cierraPeriodo')->name('cierraPeriodo');
	Route::resource('hojadetrabajos', 'HojadetrabajosController');

	//---------------------------------------------------------//
	// Funciones del controlador AjustesController
	//---------------------------------------------------------// 	
  //Route::get('anularAjuste/{id}, {codigo}', 'AjustesController@anularAjuste')->name('anularAjuste');
  Route::get('verAjustes/{id}, {periodo}, {cuenta}, {codigo}', 'AjustesController@verAjustes')->name('verAjustes');
  Route::get('createAjustes/{periodo}', 'AjustesController@createAjustes')->name('createAjustes');
	Route::resource('ajustes', 'AjustesController');
	
	//---------------------------------------------------------//
	// Funciones del controlador InicializarController
	//---------------------------------------------------------// 		
  Route::get('inicializaUn/{un_id}', 'InicializaunController@inicializaUn')->name('inicializaUn');
  Route::post('storeInicializacion', 'InicializaunController@storeInicializacion')->name('storeInicializacion');

	//---------------------------------------------------------//
	// Funciones del controlador DashboardController
	//---------------------------------------------------------// 		
  Route::get('dashboard/historicos', 'DashboardController@historicos')->name('historicos');

	
  // RUTAS PARA HACER PRUEBAS, BORRAR EN PRODUCCION
	Route::get('/lim','PruebasController@lim');
	Route::get('/truncateAll','PruebasController@truncateAll');
	Route::get('/bbb','PruebasController@bbb');
});	

Route::group(['namespace' => 'catalogo'], function()
{
	//---------------------------------------------------------//
	// Funciones del controlador CatalogosController
	//---------------------------------------------------------// 	
  Route::get('createCuenta/{id}', 'CatalogosController@createCuenta')->name('createCuenta');
	Route::resource('catalogos', 'CatalogosController');
});


Route::group(['namespace' => 'emails'], function()
{
	Route::get('/email', 'EmailsController@emailNuevoEcuentas');		
});


Route::group(['namespace' => 'blog'], function()
{	
	// Categories
	Route::resource('categories', 'CategoryController');
	Route::resource('tags', 'TagController', ['except' => ['create']]);
	
	// Comments
	Route::post('comments/{post_id}', ['uses' => 'CommentsController@store', 'as' => 'comments.store']);
	Route::get('comments/{id}/edit', ['uses' => 'CommentsController@edit', 'as' => 'comments.edit']);
	Route::put('comments/{id}', ['uses' => 'CommentsController@update', 'as' => 'comments.update']);
	Route::delete('comments/{id}', ['uses' => 'CommentsController@destroy', 'as' => 'comments.destroy']);
	Route::get('comments/{id}/delete', ['uses' => 'CommentsController@delete', 'as' => 'comments.delete']);

	Route::get('blog/{slug}', ['as' => 'blog.single', 'uses' => 'BlogController@getSingle'])->where('slug', '[\w\d\-\_]+');
	//Route::get('blog', ['uses' => 'BlogController@getIndex', 'as' => 'blog.index']);
  Route::get('blog', 'BlogController@getIndex')->name('blog');

  Route::get('contact', 'PagesController@getContact')->name('contact');
  Route::post('contact', 'PagesController@postContact');
	Route::get('about', 'PagesController@getAbout')->name('about');
	Route::get('pages', 'PagesController@getIndex')->name('pages');
	Route::resource('posts', 'PostController');
});

//---------------------------------------------------------//
// Informes financieros
//---------------------------------------------------------//
Route::get('/balance_general', array('as' => 'balance_general', function() {
    return view('finanzas.bg');
}));

Route::get('/estado_resultado', array('as' => 'estado_resultado', function() {
    return view('finanzas.er');
}));



Route::get('/test', function () {
/***********************************************  
  $diaFact= 1;
  $f_ocobro= "2016/01/01";
  $m_vence= 0; 
  $d_vence= 9;
	  +"date": "2016-01-09 00:00:00.000000"

  $diaFact= 1;
  $f_ocobro= "2016/02/01";
  $m_vence= 0; 
  $d_vence= 31; 
	  +"date": "2016-02-29 23:59:59.000000"
  
  $diaFact= 1;
  $f_ocobro= "2016/01/01";
  $m_vence= 1; 
  $d_vence= 9;
	  +"date": "2016-02-09 00:00:00.000000"
  
  $diaFact= 1;
  $f_ocobro= "2016/01/01";
  $m_vence= 10; 
  $d_vence= 31;
  		+"date": "2016-11-30 23:59:59.000000"
  
  $diaFact= 1;
  $f_ocobro= "2016/01/01";
  $m_vence= 10; 
  $d_vence= 23;
  		+"date": "2016-11-23 00:00:00.000000"
 **************************************************/   
  
/***********************************************  
  $diaFact= 16;
  $f_ocobro= "2016/01/16";
  $m_vence= 0; 
  $d_vence= 24;
  	+"date": "2016-01-24 00:00:00.000000"
  
  $diaFact= 16;
  $f_ocobro= "2016/02/16";
  $m_vence= 0; 
  $d_vence= 31; 
	  +"date": "2016-02-29 23:59:59.000000"
 

  $diaFact= 16;
  $f_ocobro= "2016/01/16";
  $m_vence= 1; 
  $d_vence= 31;
  	+"date": "2016-02-29 23:59:59.000000"


  $diaFact= 16;
  $f_ocobro= "2016/01/16";
  $m_vence= 10; 
  $d_vence= 31;
  	+"date": "2016-11-30 23:59:59.000000"
   
  $diaFact= 16;
  $f_ocobro= "2016/01/16";
  $m_vence= 10; 
  $d_vence= 23;
  	+"date": "2016-11-23 00:00:00.000000"
  

  $f_vence= Sity::fechaLimiteRecargo($diaFact, $f_ocobro, $m_vence, $d_vence);
  dd('fecha limite para recargo: ',$f_vence);
  return $f_vence;
**************************************************/
});

use App\Seccione;
use App\User;
use App\Pago;

Route::get('/query', function () {

//$data= Seccione::find(1)->secapto;

/*$datas= Seccione::find(1)->uns;
foreach ($datas as $data) {
	echo $data->codigo;
}*/

//$data= Seccione::find(1)->ph;

//$datas= Seccione::with('uns')->get();
//return [$datas];


/*$user = User::find(Auth::user()->id);

//->roles()->get();
dd($user->roles);

foreach ($roles as $role) {
	if($role->name==='Admin') {
		$es = true;
	}
}*/

	//Encuentra todos lo registros de pago 
/*	$datos = Pago::where('un_id', $un_id)
				 ->join('bancos', 'bancos.id', '=', 'pagos.banco_id')
         ->select('pagos.entransito','pagos.id','bancos.nombre','pagos.f_pago','pagos.monto','pagos.un_id','pagos.anulado','pagos.trans_tipo','pagos.trans_no')
         ->get();*/
	
	//Encuentra todos lo registros de pago 
	//$datos = Pago::where('un_id', 1)->get();
	//$dato = Pago::where('un_id', 1)->first();
	//dd($datos->toArray());
	
	//dd($dato->banco->nombre, $dato->un->codigo, $dato->trantipo->nombre);
	//dd($dato->un->codigo);

$datos = DB::table('ctdasms')
                ->groupBy('pcontable_id')
                ->having('pcontable_id', '>', 1)
                ->get();
dd($datos->toArray());
});