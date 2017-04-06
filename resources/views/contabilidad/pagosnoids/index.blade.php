@extends('templates.backend._layouts.smartAdmin')

@section('title', '| Informes de diario de Caja Chica')

@section('content')

<div class="row show-grid">
    <div class="col-xs-12 col-sm-6 col-md-12">        
        <!-- NEW WIDGET START -->
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="true" data-widget-deletebutton="false">
            <!-- widget options:
            usage: <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">

            data-widget-colorbutton="false"
            data-widget-editbutton="false"
            data-widget-togglebutton="false"
            data-widget-deletebutton="false"
            data-widget-fullscreenbutton="false"
            data-widget-custombutton="false"
            data-widget-collapsed="true"
            data-widget-sortable="false"-->

            <header>
                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                <h2>Pagos no indentificados </h2>
                <div class="widget-toolbar">
                    @if (Cache::get('esAdminkey'))
                        <a href="{{ URL::route('pagosnoids.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Registrar pago no identificado</a>
                    @endif  
                </div>
            </header>

            <div><!-- widget div-->
                <div class="jarviswidget-editbox"><!-- widget edit box -->
                    <!-- This area used as dropdown edit box -->
                </div><!-- end widget edit box -->
                
                <div class="widget-body no-padding"><!-- widget content -->
                    <div class="widget-body-toolbar">
                        <div class="col-xs-3 col-sm-7 col-md-7 col-lg-11 text-right">
                        </div>
                    </div>
                    
                    <table id="dt_basic" class="table table-hover">
                        <thead>
                            <tr>
                                <th>FECHA</th>  
                                <th>BANCO</th>
                                <th>TIPO</th>
                                <th>MONTO</th> 
                                <th>UNIDAD</th>
                                <th>PROPIETARIOS</th>
                                <th>IDENT</th>
                                <th class="text-center"><i class="fa fa-gear fa-lg"></i></th>   
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos as $dato)
                                <tr>
                                    <td col width="80px" align="left">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $dato->f_pago)->format('M j\\, Y') }}</td>
                                    <td>{{ $dato->banco }}</td>
                                    <td col width="20px" align="left">{{ $dato->tipo }}</td>                                    
                                    <td col width="50px" align="left">{{ $dato->monto }}</td>  
                                    <td col width="80px"><strong>{{ $dato->codigo }}</strong></td>
                                    <td>{{ $dato->propietarios }}</td>
                                    <td col width="50px">{{ $dato->identificado ? "Si" : 'No' }}</td>
                                    @if (Cache::get('esAdminkey'))
                                        <td col width="125px" align="right">
                                            <ul class="demo-btns">
                                                @if ($dato->contabilizado == 0)
                                                    <li>
                                                        <a href="{{ URL::route('identificarPagoCreate', $dato->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search"></i></a>
                                                    </li> 
                                                    <li>
                                                        <a href="{{ URL::route('contabilizaPagonoid', array($dato->id, $dato->f_pago, $dato->un_id, $dato->monto, $dato->banco_id, $dato->doc_no)) }}" class="btn btn-warning btn-xs">Contabilizar</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <span class="label label-success">Pago contabilizado</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                                                           
                        </tbody>
                    </table>
                
                </div><!-- end widget content -->
            </div><!-- end widget div -->
        </div>
        <!-- end widget -->
        <!-- WIDGET END -->
    </div>        
</div>
@stop

@section('relatedplugins')
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/jquery.dataTables-cust.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/ColReorder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/DT_bootstrap.js') }}"></script>
    
    <script type="text/javascript">
    $(document).ready(function() {
        pageSetUp();
 
        $('#dt_basic').dataTable({
            "sPaginationType" : "bootstrap_full"
        });
    })
    </script>
@stop