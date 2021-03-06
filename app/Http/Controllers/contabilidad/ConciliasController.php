<?php namespace App\Http\Controllers\contabilidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\library\Sity;
use URL, DB, Date, Session;

use App\Concilia;
use App\Dte_concilia;
use App\Ctmayore;
use App\Pago;
use App\Factura;
use App\Detallepagofactura;
use App\Catalogo;

class ConciliasController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // encuentra todas las conciliaciones
    $datos = Concilia::all();
        
    return view('contabilidad.concilias.index')
                ->with('datos', $datos); 
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($periodo_id)
  {
    //dd($periodo_id);
    
    // encuentra todas las conciliaciones
    $concilia = Concilia::where('pcontable_id', $periodo_id)->first();
    $concilia['f_endpresentdo'] = Date::parse($concilia->f_incioperiodo)->endOfMonth()->toFormattedDateString();
    $concilia['f_endlastpdo'] = Date::parse($concilia->f_incioperiodo)->subMonth()->endOfMonth()->toFormattedDateString();
    //dd($concilia);

    if ($concilia->contabilizada == 1) {
      Session::flash('warning', 'Conciliacion del presente periodo ya ha sido aprobada y contabilizada.');
      return redirect()->route('pcontables.index');
    }

    // encuentra todas la notas de credito
    $ncs = Dte_concilia::where('concilia_id', $concilia->id)->where('tipo', 'n/c')->get();
    
    // encuentra todas la notas de debito
    $nds = Dte_concilia::where('concilia_id', $concilia->id)->where('tipo', 'n/d')->get();    
    
    // encuentra todas la notas de credito
    $d_transitos = Dte_concilia::where('concilia_id', $concilia->id)->where('tipo', 'd_transito')->get();

    // encuentra todas la notas de credito
    $chq_circulacions = Dte_concilia::where('concilia_id', $concilia->id)->where('tipo', 'chq_circulacion')->get();
    
    // calcula el total en libro mas
    $t_libromas = Dte_concilia::where('concilia_id', $concilia->id)->where('seccion', 'libro')->where('masmenos', 'mas')->sum('monto');    

    // calcula el total en libro menos
    $t_libromenos = Dte_concilia::where('concilia_id', $concilia->id)->where('seccion', 'libro')->where('masmenos', 'menos')->sum('monto');    
 
    // calcula el total depositado del periodo
    $t_depositado = Ctmayore::where('pcontable_id', $periodo_id)->where('cuenta', 8)->where('pago_id', '!=', Null)->sum('debito');
    //dd($t_depositado);

    // calcula el total depositado en cheque
    $t_chq_girados = Factura::where('pcontable_id', $periodo_id)
            ->join('detallepagofacturas', 'detallepagofacturas.factura_id', '=', 'facturas.id')
            ->where('detallepagofacturas.etapa', 2)
            ->where('trantipo_id', 1)
            ->sum('monto');
    //dd($t_chq_girados); 

    //Encuentra todas las cuentas contables activos y gastos
    $catalogo6s = Catalogo::where('conciliacion', 'n/d')->orderBy('codigo')->get();
    //$catalogo6s = Catalogo::where('tipo', 6)->orderBy('codigo')->get();

    $catalogo6s->map(function ($x) {
      $x['nombre'] = $x->codigo.' '.$x->nombre;
    });
    
    //dd($catalogos->toArray());      
    $catalogo6s = $catalogo6s->pluck('nombre', 'id')->All();
    //dd($catalogo16s);  

    //Encuentra todas las cuentas contables pasivos e ingresos
    $catalogo4s = Catalogo::where('conciliacion', 'n/c')->orderBy('codigo')->get();

    $catalogo4s->map(function ($x) {
      $x['nombre'] = $x->codigo.' '.$x->nombre;
    });
    
    //dd($catalogos->toArray());      
    $catalogo4s = $catalogo4s->pluck('nombre', 'id')->All();
    //dd($catalogo16s, $catalogo24s);
    
    // encuentra el nombre de la cuenta banco
    $banco = Catalogo::find(8)->nombre;
   
    return view('contabilidad.concilias.show')
                ->with('ncs', $ncs)
                ->with('nds', $nds)
                ->with('d_transitos', $d_transitos)
                ->with('chq_circulacions', $chq_circulacions)
                ->with('t_libromas', $t_libromas)
                ->with('t_libromenos', $t_libromenos)
                ->with('t_depositado', $t_depositado)                
                ->with('t_chq_girados', $t_chq_girados) 
                ->with('catalogo6s', $catalogo6s) 
                ->with('catalogo4s', $catalogo4s) 
                ->with('banco', $banco) 
                ->with('concilia', $concilia); 
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
