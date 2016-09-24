@extends('backend._layouts.default')

@section('main')<!-- MAIN PANEL -->
	<!-- widget grid -->
	<section id="widget-grid" class="">
	
		<!-- row -->
		<div class="row">
	
			<!-- NEW WIDGET START -->
			<article class="col-sm-12 col-md-12 col-lg-7">
	
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
	
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
						<span class="widget-icon"> <i class="fa fa-lg fa-calendar"></i> </span>
						<h2>Editar datos del Administrador de Propiedades Horizontales</h2>
	
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
								{!! Form::model($bloque, array('class' => 'form-horizontal', 'method' => 'put', 'route' => array('bloques.update', $bloque->id))) !!}
									{{ csrf_field() }}
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">Nombre</label>
											<div class="col-md-10">
												{!! Form::text('nombre', $bloque->nombre, array('class' => 'form-control input-sm', 'title' => 'Escriba el nombre del Bloque...', 'autocomplete' => 'off')) !!}
												{!! $errors->first('nombre', '<li style="color:red">:message</li>') !!}
											</div>
										</div>					
										
										<div class="form-group">
											<label class="col-md-2 control-label">Descripción</label>
											<div class="col-md-10">
												{!! Form::textarea('descripcion', $bloque->descripcion, array('class' => 'form-control input-sm', 'rows' => '7', 'title' => 'Escriba la descripción del Bloque...', 'autocomplete' => 'off')) !!}
											   {{-- {!! Form::textarea('message', null, array('required', 'class'=>'form-control', 'placeholder'=>'Your message')) !!} --}}
											    {!! $errors->first('descripcion', '<li style="color:red">:message</li>') !!}
											</div>
										</div>	
									</fieldset>
									
									<div class="form-actions">
										{!! Form::submit('Salvar', array('class' => 'btn btn-success btn-save btn-large')) !!}
										<a href="{{ URL::route('indexblqplus', array($bloque->jd_id)) }}" class="btn btn-large">Cancelar</a>
									</div>
								{!! Form::close() !!}
							</div>
							<!-- end widget content -->
					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
			</article>
			<!-- WIDGET END -->
	
			<!-- NEW WIDGET START -->
			<article class="col-sm-12 col-md-12 col-lg-5">
	
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-blue" id="wid-id-1" data-widget-editbutton="false" data-widget-deletebutton="false">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
	
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
						<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
						<h2>Imagen del Bloque</h2>
	
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
			              	<p>
								<img style="height: 275px; border-radius: 8px;" src="{{asset($bloque->imagen_L)}}" class="img-responsive" alt="Responsive image">
			             	</p>
				         	{!! Form::open(array('route' => array('subirImagenBloque', $bloque->id),'files'=>true)) !!}
								<div class="form-actions">
				         		<div>
									{!! Form::file('file') !!}
								</div>						
									{!! Form::submit('Subir imagen', array('class' => 'btn btn-success btn-save btn-large')) !!}
								</div>
			          		{!! Form::close() !!}			            
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
	
		<!-- row -->
	
		<div class="row">
	
		</div>
	
		<!-- end row -->
	
	</section>
	<!-- end widget grid -->
@stop

@section('relatedplugins')
<!-- PAGE RELATED PLUGIN(S) -->

<script type="text/javascript">
// DO NOT REMOVE : GLOBAL FUNCTIONS!
$(document).ready(function() {
	pageSetUp();
	
	// PAGE RELATED SCRIPTS

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
})
</script>

@stop