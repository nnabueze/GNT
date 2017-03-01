@extends('admin')
@section('content')
<div id="ribbon">

	<span class="ribbon-button-alignment"> 
		<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
			<i class="fa fa-refresh"></i>
		</span> 
	</span>

	<!-- breadcrumb -->
	<ol class="breadcrumb">
		<li>Access Control</li><li>Permission</li>
	</ol>
	<!-- end breadcrumb -->

	<!-- You can also add more buttons to the
	ribbon for further usability

	Example below:

	<span class="ribbon-button-alignment pull-right">
	<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
	<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
	<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
</span> -->

</div>
<!-- MAIN CONTENT -->
<div id="content">




	<!-- widget grid -->
	<section id="widget-grid" class="">


		<!-- START ROW -->

		<div class="row">
@include('include.message')
@include('include.warning')
			<!-- NEW COL START -->
			<article class="col-sm-12 col-md-12 col-lg-6">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
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
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Permission</h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					
					<section id="widget-grid" class="">

						<!-- row -->
						<div class="row">

							<!-- NEW WIDGET START -->
							<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
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

							<!-- widget div-->


							<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
								<thead>			                
									<tr>
										<th data-hide="phone">ID</th>
										<th data-hide="phone">Name</th>
										<th data-hide="phone,tablet"><i class="fa fa-fw fa-map-marker txt-color-blue hidden-md hidden-sm hidden-xs"></i>Display Name</th>
										<th data-hide="phone,tablet">Description</th>
										<th data-hide="phone,tablet">Action</th>



									</tr>
								</thead>
								<tbody>
								<?php $i= 1;?>
							@if($permissions)
								@foreach($permissions as $permission)
									<tr>
										<td>{{$i}}</td>
										<td>{{$permission->name}}</td>
										<td>{{$permission->display_name}}</td>
										<td>{{$permission->description}}</td>
										<td><a href="#" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit"><span class="glyphicon glyphicon-edit"></span></a> &nbsp;&nbsp;<a href="/permission/{{$permission->id}}" class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete"><span class="glyphicon glyphicon-trash"></span></a> </td>

									</tr>
									<?php $i++;?>
								@endforeach
							@endif
								</tbody>
							</table>



						</div>
						<!-- end widget div -->
						{!! $permissions->render() !!}
					</div>
					<!-- end widget -->

					<!-- Widget ID (each widget will need unique ID)-->

					<!-- end widget -->				

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-7" data-widget-editbutton="false" data-widget-custombutton="false">
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
				<!-- widget div-->
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->	

		</article>
		<!-- END COL -->

		<!-- NEW COL START -->
		<article class="col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
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


				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form id="smart-form-register" action="/permission" class="smart-form" method="POST">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<header>
								Permission form
							</header>

							<fieldset>
								<section>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="text" name="name" placeholder="Name">
										<b class="tooltip tooltip-bottom-right">Enter Permission</b> </label>
									</section>



									<section>
										<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
											<input type="text" name="display_name" placeholder="Display Nmae">
											<b class="tooltip tooltip-bottom-right">Needed to verify your account</b> </label>
										</section>
										<section>
											<label class="textarea"> <i class="icon-append fa fa-comment"></i>           
												<textarea rows="5" name="description" placeholder="Permission Description"></textarea> 
												<b class="tooltip tooltip-bottom-right">Permission Description</b> </label>
											</label>
										</section>

									</fieldset>


									<footer>
										<button type="submit" class="btn btn-primary">
											Create
										</button>
									</footer>
								</form>						

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

					<!-- Widget ID (each widget will need unique ID)-->
					<!-- end widget -->


					<!-- Widget ID (each widget will need unique ID)-->
					<!-- end widget -->								


				</article>
				<!-- END COL -->		

			</div>

			<!-- END ROW -->

		</section>
		<!-- end widget grid -->


		</div>
		</div>
		<!-- END MAIN CONTENT -->

		@stop