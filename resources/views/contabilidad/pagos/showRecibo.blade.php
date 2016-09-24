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
                <h2>Recibo </h2>
                <div class="widget-toolbar">
                    <a href="{{ URL::route('indexPagos', $pago->un_id) }}" class="btn btn-default btn-large"><i class="glyphicon glyphicon-arrow-left"></i></a>            
                </div>
            </header>

            <div><!-- widget div-->
                <div class="jarviswidget-editbox"><!-- widget edit box -->
                    <!-- This area used as dropdown edit box -->
                </div><!-- end widget edit box -->
                
                <div class="widget-body padding"><!-- widget content -->
                    
                    <div class="row"><!-- row -->
                        <div class="col-xs-10">
                            <address>
                              <strong>{{ $ph->nombre }}</strong><br>
                              795 Folsom Ave, Suite 600<br>
                              San Francisco, CA 94107<br>
                              <abbr title="Phone">P:</abbr> (123) 456-7890
                            </address>
                        </div>
                        <div class="col-xs-2">
                            <img style="height: 40px; border-radius: 8px;" src="{{asset('assets/backend/img/logo.png')}}" class="img-responsive" alt="Responsive image">
                        </div>
                    </div><!-- end row -->

                    <!-- <HR WIDTH=95% ALIGN=CENTER COLOR="BLACK"> -->

                    <div class="row">
                      <div class="col-xs-12">
                        <h4><p class="text-center"><strong>RECIBO DE PAGO NO.  {{ $pago->id }}</strong></p></h4>
                        <h7><p class="text-center"><strong>UNIDAD NO. {{ $un->codigofull }}</strong></p></h7>       
                        <h7><p class="text-center"><strong>Fecha: {{ Date::parse($pago->f_pago)->toFormattedDateString() }}</strong></p></h7>
                        @if ($pago->anulado==1)
                            <h1><p class="text-center"><strong>ANULADO</strong></p></h1>
                        @endif
                      </div>
                    </div>  

                    <div class="row">
                        <div class="col-xs-4">
                            <address>
                              <strong>{{ $prop->user->nombre_completo }}</strong><br>
                              795 Folsom Ave, Suite 600<br>
                              San Francisco, CA 94107<br>
                              <abbr title="Phone">P:</abbr> (123) 456-7890
                            </address>
                        </div>
                            
                        <div class="col-xs-2">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <strong><tr>
                                        <th col width="40px">No</th>
                                        <th>Detalle</th>
                                        <th col width="90px" class="text-center">Monto</th>                     
                                    </tr></strong>
                                </thead>
                                <tbody>
                                    @foreach ($detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->no }}</td>
                                            <td>{{ $detalle->detalle }}</td>
                                            <td col width="90px" align="right">{{ $detalle->monto }}</td>                      
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>    
                        </div><!-- end row -->
                        
                        <div class="row">
                              <div class="col-xs-11">
                                <p class="text-right"><strong>Total pagado</strong></p>
                              </div>
                              <div class="col-xs-1">
                                <p class="text-right"><strong>{{ $total }}</strong></p>
                              </div>
                        </div>

                        <div class="row">
                              <div class="col-xs-11">
                                <p class="text-right"></p>
                              </div>
                              <div class="col-xs-1">
                                <p class="text-right">========</p>
                              </div>
                        </div>
                        
                        <div class="row">
                              <div class="col-xs-11">
                                <p class="text-left">{{ $nota }}</p>
                              </div>
                              <div class="col-xs-1">
                                <p class="text-right"></p>
                              </div>
                        </div>          
            
                </div><!-- end widget content -->
            </div><!-- end widget div -->
        </div>
        <!-- end widget -->
        <!-- WIDGET END -->
    </div>        
</div>
@stop