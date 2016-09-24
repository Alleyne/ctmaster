@extends('backend._layouts.default')

@section('main')<!-- MAIN PANEL -->
	<!-- widget grid -->
	<section id="widget-grid" class="">
	
		<!-- row -->
		<div class="row">
	
			<!-- NEW WIDGET START -->
			<article class="col-sm-12 col-md-12 col-lg-9">
	
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
						<h2>Editar Junta Directiva</h2>
	
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
							{!! Form::model($dato, array('class' => 'form-horizontal', 'method' => 'put', 'route' => array('jds.update', $dato->id))) !!}
									{{ csrf_field() }}
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">Nombre</label>
											<div class="col-md-10">
												{!! Form::text('nombre', $dato->nombre, array('class' => 'form-control input-sm', 'title' => 'Escriba el nombre del Ph...')) !!}
												{!! $errors->first('nombre', '<li style="color:red">:message</li>') !!}
											</div>
										</div>					
										<div class="form-group">
											<label class="col-md-2 control-label">Descripción</label>
											<div class="col-md-10">
												{!! Form::text('descripcion', $dato->descripcion, array('class' => 'form-control input-sm', 'title' => 'Escriba el nombre del Ph...')) !!}
												{!! $errors->first('descripcion', '<li style="color:red">:message</li>') !!}
											</div>
										</div>
									</fieldset>
									
									<div class="form-actions">
										{!! Form::submit('Salvar', array('class' => 'btn btn-success btn-save btn-large')) !!}
										<a href="{{ URL::route('jds.index') }}" class="btn btn-large">Cancelar</a>
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