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
                <h2>Administracion de Caja Chica </h2>
                <div class="widget-toolbar">
                    @if (Cache::get('esAdminkey'))
                        @if($cerrada == 1)
                            <a href="{{ URL::route('cajachicas.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Abrir Caja chica</a>
                         @endif  
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
                    
                    <table id="dt_basic" class="display compact" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>F_INICIO</th>  
                                <th>RESPONSABLE POR FONDO</th>
                                <th>SALDO</th>
                                <th>F_CIERRE</th> 
                                <th>CERRADA</th>
                                <th class="text-center"><i class="fa fa-gear fa-lg"></i></th>   
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos as $dato)
                                <tr>
                                    <td col width="30px">{{ $dato->id }}</td>
                                    <td col width="95px" align="left">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $dato->f_inicio)->format('M j\\, Y') }}</td>
                                    <td>{{ $dato->responsable }}</td>
                                    <td col width="60px"><strong>{{ $dato->saldo }}</strong></td>
                                    @if(is_null($dato->f_cierre))
                                        <td></td>                                    
                                    @else
                                        <td col width="75px" align="left">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $dato->f_cierre)->format('M j\\, Y') }}</td>
                                    @endif 
                                    <td col width="70px">{{ $dato->cerrada ? "Si" : 'No' }}</td>
                                    @if (Cache::get('esAdminkey'))
                                        <td col width="265px" align="right">
                                            <ul class="demo-btns">
                                                @if(is_null($dato->f_cierre))
                                                    <li>
                                                        <a href="{{ URL::route('verDesembolsos', $dato->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Desembolsos</a>
                                                    </li> 
                                                    <li>
                                                        <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::route('aumentarCajachicaCreate', $dato->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::route('disminuirCajachicaCreate', $dato->id) }}" class="btn bg-color-purple txt-color-white btn-xs"><i class="fa fa-minus"></i></a>
                                                    </li>                                       
                                                    <li>
                                                        <a href="{{ URL::route('cerrarCajachicaCreate', $dato->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-lock"></i></a>
                                                    </li> 
                                                    <li>
                                                        <a href="{{ URL::route('dte_cajachicas.show', $dato->id) }}" class="btn btn-warning btn-xs"> Diario</a>
                                                    </li>
                                                @else    

                                                    <li>
                                                        <a href="{{ URL::route('verDesembolsos', $dato->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Desembolsos</a>
                                                    </li> 
                                                    <li>
                                                        <a href="{{ URL::route('dte_cajachicas.show', $dato->id) }}" class="btn btn-default btn-xs"> Historial de Caja Chica</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>
                                    @elseif (Cache::get('esContadorkey'))
                                        <td col width="235px" align="right">
                                            <ul class="demo-btns">
                                                @if(is_null($dato->f_cierre))
                                                    <li>
                                                        <a href="{{ URL::route('verDesembolsos', $dato->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Desembolsos</a>
                                                    </li> 
                                                    <li>
                                                        <a href="{{ URL::route('dte_cajachicas.show', $dato->id) }}" class="btn btn-warning btn-xs"> Diario</a>
                                                    </li>
                                                @else    

                                                    <li>
                                                        <a href="{{ URL::route('verDesembolsos', $dato->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Desembolsos</a>
                                                    </li> 
                                                    <li>
                                                        <a href="{{ URL::route('dte_cajachicas.show', $dato->id) }}" class="btn btn-default btn-xs"> Historial de Caja Chica</a>
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

  <script type="text/javascript">
    $(document).ready(function() {

      $('#dt_basic').dataTable({
        "paging": false,
        "scrollY": "393px",
        "scrollCollapse": true,
        "stateSave": true,

        "language": {
            "decimal":        "",
            "emptyTable":     "No hay datos disponibles para esta tabla",
            "info":           "&nbsp;&nbsp;  Mostrando _END_ de un total de _MAX_ registros",
            "infoEmpty":      "",
            "infoFiltered":   "",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ unidades",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "zeroRecords":    "No se encontró ninguna unidad con ese filtro",
            "paginate": {
              "first":      "Primer",
              "last":       "Último",
              "next":       "Próximo",
              "previous":   "Anterior"
            },
            "aria": {
              "sortAscending":  ": active para ordenar ascendentemente",
              "sortDescending": ": active para ordenar descendentemente"
            }
        }
      });
    })
    </script>
@stop