@extends('backend._layouts.default')

@section('main')
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
                <h2>Pagos </h2>
                <div class="widget-toolbar">
                    <a href="{{ URL::route('uns.show', $un_id) }}" class="btn btn-default btn-large"><i class="glyphicon glyphicon-arrow-left"></i></a>            
                    @if (Cache::get('esAdminkey'))
                        <a href="{{ URL::route('createPago', $un_id) }}" class="btn btn-success"><i class="fa fa-plus"></i> Registrar pago</a>
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
                                <th>ID</th>
                                <th>TIPO</th>  
                                <th>NO</th>
                                <th>BANCO</th>                          
                                <th class="text-left">FECHA</th>                                   
                                <th class="text-right">MONTO</th> 
                                <th class="text-center"><i class="fa fa-gear fa-lg"></i></th>                                            
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos as $dato)
                                <tr>
                                    <td col width="60px"><strong>{{ $dato->id }}</strong></td>
                                    <td col width="100px" align="left">{{ $dato->trans_tipo }}</td>
                                    <td col width="70px"><strong>{{ $dato->trans_no }}</strong></td>
                                    <td>{{ $dato->nombre }}</td>
                                    <td col width="90px" align="left">{{ $dato->f_pago }}</td>
                                    <td col width="90px" align="right">{{ $dato->monto }}</td>
                                    
                                    @if (Cache::get('esAdminkey'))
                                        <td col width="175px" align="right">
                                            <ul class="demo-btns">
                                                
                                            @if ($dato->entransito==0)
                                                <li>
                                                     <a href="{{ URL::route('showRecibo', $dato->id) }}" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-list"></i></a>
                                                </li>                
                                                @if ($dato->anulado==0) 
                                                    <div id="ask_2" class="btn btn-default btn-xs">
                                                        <a href="{{ URL::route('procesaAnulacionPago', array($dato->id, $un_id)) }}" title="Anular pago"><i class="fa fa-search"></i> Anular pago</a>
                                                    </div>
                                                @else
                                                    <li>
                                                        <span class="label label-warning">Anulado</span>
                                                    </li>
                                                @endif
                                            @else
                                                <li>
                                                    <a href="{{ URL::route('procesaChequeRecibido', $dato->id) }}" class="btn btn-primary btn-xs"> Contabilizar</a>
                                                </li>
                                                <div id="ask_4" class="btn btn-default btn-xs">
                                                    <a href="{{ URL::route('eliminaPagoCheque', $dato->id) }}" title="Eliminar pago"><i class="fa fa-search"></i> Eliminar</a>
                                                </div>

                                            @endif
                                            </ul>
                                        </td>
                                    @elseif (Cache::get('esAdminDeBloquekey'))
                                        <td col width="60px" align="right">
                                            <ul class="demo-btns">
                                                <li>
                                                     <a href="{{ URL::route('showRecibo', $dato->id) }}" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-folder-open"></i></a>
                                                </li>                
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
    <!-- PAGE RELATED PLUGIN(S) -->
    <!-- <script src="js/plugin/datatables/jquery.dataTables-cust.min.js"></script> -->
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/jquery.dataTables-cust.min.js') }}"></script>
    
    <!-- <script src="js/plugin/datatables/ColReorder.min.js"></script> -->
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/ColReorder.min.js') }}"></script>

    <!-- <script src="js/plugin/datatables/FixedColumns.min.js"></script> -->
    <!--<script src="{{ URL::asset('assets/backend/js/plugin/datatables/FixedColumns.min.js') }}"></script> -->

    <!-- <script src="js/plugin/datatables/ColVis.min.js"></script> -->
    <!-- <script src="{{ URL::asset('assets/backend/js/plugin/datatables/ColVis.min.js') }}"></script> -->

    <!-- <script src="js/plugin/datatables/ZeroClipboard.js"></script> -->
    <!-- <script src="{{ URL::asset('assets/backend/js/plugin/datatables/ZeroClipboard.js') }}"></script> -->
    
    <!-- <script src="js/plugin/datatables/media/js/TableTools.min.js"></script> -->
    <!--<script src="{{ URL::asset('assets/backend/js/plugin/datatables/media/js/TableTools.min.js') }}"></script> -->
    
    <!-- <script src="js/plugin/datatables/DT_bootstrap.js"></script> -->
    <script src="{{ URL::asset('assets/backend/js/plugin/datatables/DT_bootstrap.js') }}"></script> -->
    
    <script type="text/javascript">
    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    $(document).ready(function() {
        pageSetUp();
        
        /*
         * BASIC
         */
        $('#dt_basic').dataTable({
            "sPaginationType" : "bootstrap_full"
        });

        /* END BASIC */

        /* Add the events etc before DataTables hides a column */
        $("#datatable_fixed_column thead input").keyup(function() {
            oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
        });

        $("#datatable_fixed_column thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#datatable_fixed_column thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
            }
        });
        $("#datatable_fixed_column thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
            }
        });     
        

        var oTable = $('#datatable_fixed_column').dataTable({
            "sDom" : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            //"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
            "oLanguage" : {
                "sSearch" : "Search all columns:"
            },
            "bSortCellsTop" : true
        });     

        /*
         * COL ORDER
         */
        $('#datatable_col_reorder').dataTable({
            "sPaginationType" : "bootstrap",
            "sDom" : "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "fnInitComplete" : function(oSettings, json) {
                $('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
            }
        });
        
        /* END COL ORDER */

        /* TABLE TOOLS */
        $('#datatable_tabletools').dataTable({
            "sDom" : "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "oTableTools" : {
                "aButtons" : ["copy", "print", {
                    "sExtends" : "collection",
                    "sButtonText" : 'Save <span class="caret" />',
                    "aButtons" : ["csv", "xls", "pdf"]
                }],
                "sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "fnInitComplete" : function(oSettings, json) {
                $(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
                    $(this).addClass('btn-sm btn-default');
                });
            }
        });

    /* END TABLE TOOLS */
    })
    </script>
    
@stop