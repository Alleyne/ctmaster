@extends('templates.backend._layouts.smartAdmin')

@section('title', '| Conciliacion')

@section('content')

	<!-- widget grid -->
	<section id="widget-grid" class="">
	
		<!-- row -->
		<div class="row">
	
			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
	
					data-widget-colorbutton="false"
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true"
					data-widget-sortable="false"
					-->
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Conciliaciones </h2>
						<div class="widget-toolbar">
						</div>
					</header>
	
					<!-- widget div-->
					<div>
	
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->
	
						</div>
						<!-- end widget edit box -->
	
						<!-- widget content -->
						<div class="widget-body no-padding">
							<div class="widget-body-toolbar">
								<div class="col-xs-3 col-sm-7 col-md-7 col-lg-11 text-right">

								</div>
							</div>
							
							<table id="dt_basic" class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>PERIODO</th>
										<th>SALDO_LIBRO</th>
										<th class="text-center"><i class="fa fa-gear fa-lg"></i></th>										
									</tr>
								</thead>
								<tbody>
									@foreach ($datos as $dato)
										<tr>
											<td col width="40px">{{ $dato->id }}</td>
											<td>{{ $dato->periodo }}</td>
											<td col width="40px">{{ $dato->saldo_libro }}</td>
							
											<td col width="210px" align="right">
												<ul class="demo-btns">
													<li>
														<span class="label label-warning">Registrando</span>
													</li>
													<li>
														<a href="{{ URL::route('concilias.show', $dato->id) }}" class="btn btn-primary btn-xs"> Detalles</a>
													</li>					
												</ul>												
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<!-- end widget content -->
	
					</div>
					<!-- end widget div -->
	
				</div>
				<!-- end widget -->
			</article>
			<!-- WIDGET END -->
		</div>
		<!-- end row -->
	
	</section>
	<!-- end widget grid -->

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