@extends('templates.backend._layouts.smartAdmin')

@section('title', '| Registrar Factura')

@section('content')
	<!-- widget grid -->
	<section id="widget-grid" class="">
	
		<!-- row -->
		<div class="row">
	
			<!-- NEW WIDGET START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
	
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
	
					data-widget-colorbutton="false"
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true"
					data-widget-sortable="false" -->
	
					<header>
						<span class="widget-icon"> <i class="fa fa-lg fa-calendar"></i> </span>
						<h2>Caja Chica</h2>
					</header>
	
					<!-- widget div-->
					<div>
	
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->
	
						</div>
						<!-- end widget edit box -->
	
							<!-- widget content -->
							<div class="widget-body">
							{{ Form::open(array('class' => 'form-horizontal', 'route' => 'cerrarCajachicaStore')) }}		
									
									<fieldset>
	 									{{ csrf_field() }}
                		{{ Form::hidden('cajachica_id', $cajachica_id) }}
									
									<!-- Form Name -->
										<legend>Cerrar Caja Chica</legend>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Fecha</label>
                        <div class="col-md-9">
													<div class="input-group">
														<input type="text" name="fecha" placeholder="Seleccione la fecha de cierre ..." class="form-control datepicker" data-dateformat="yy/mm/dd" value={{ old('fecha') }}>
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													</div>
                        	<p>{!! $errors->first('fecha', '<li style="color:red">:message</li>') !!}</p> 
                        </div>
                    </div>  
								
										<hr />										

										<div class="form-group">
											<label class="col-md-3 control-label">Aprobado por</label>
											<div class="col-md-9">
												{{ Form::select('aprueba_id', ['' => 'Selecione una persona responsable por aprobar el cierre de caja chica ...'] + $usuarios, 0, ['class' => 'form-control']) }}
												{!! $errors->first('aprueba_id', '<li style="color:red">:message</li>') !!}
											</div>
										</div>

									</fieldset>
									
									<div class="form-actions">
						        {{Form::button('Salvar', array(
						            'class' => 'btn btn-success btn-large',
						            'data-toggle' => 'modal',
						            'data-target' => '#confirmAction',
						            'data-title' => 'Cerrar Caja chica',
						            'data-message' => 'Esta seguro(a) que desea cerrar la Caja chica?',
						            'data-btntxt' => 'SI, cerrar Caja chica',
						            'data-btncolor' => 'btn-success'
						        ))}}
										<a href="{{ URL::route('cajachicas.index') }}" class="btn btn-large">Cancelar</a>
									</div>
								{{ Form::close() }}
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
    <!-- Incluye la modal box -->
    @include('templates.backend._partials.modal_confirm')

	</section>
	<!-- end widget grid -->
@stop

@section('relatedplugins')
<!-- PAGE RELATED PLUGIN(S) -->
<!-- Incluye javascript -->
<script src="{{ URL::asset('assets/backend/js/modalconfirm.js') }}"></script>

<script type="text/javascript">
// DO NOT REMOVE : GLOBAL FUNCTIONS!
$(document).ready(function() {
	pageSetUp();
	
	$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
	$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
		var children = $(this).parent('li.parent_li').find(' > ul > li');
		if (children.is(':visible')) {
			children.hide('fast');
			$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
		} else {
			children.show('fast');
			$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
		}
		e.stopPropagation();
	});

		$('#fecha').datepicker({
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
			}
		});
		
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			prevText: '<Ant',
			nextText: 'Sig>',
			currentText: 'Hoy',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			weekHeader: 'Sm',
			dateFormat: 'yy/mm/dd',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''
			};
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function () {
			$("#fecha").datepicker();
		});


    $("input[type='submit']").attr("disabled", false);
    $("form").submit(function(){
      $("input[type='submit']").attr("disabled", true).val("Por favor espere mientras se envia la informacion . . .");
      return true;
    });
})

</script>
@stop