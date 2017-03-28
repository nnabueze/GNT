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
		<li>Setup </li><li>Biller</li>
	</ol>


</div>
<!-- MAIN CONTENT -->
			<div id="content">



<!-- widget grid -->
<section id="widget-grid" class="">


	<!-- START ROW -->

	<div class="row">

		<!-- NEW COL START -->
		<article class="col-sm-12 col-md-6 col-lg-6">
			@include('include.message')
			@include('include.warning')
			@include('include.error')
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
	
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Edit Biller</h2>				
					
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
				
				
	

		<!-- NEW COL START -->
		
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">

			

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
					<form id="login-form" method="POST" action="/igr/edit" class="smart-form" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<fieldset>
								<input type="hidden" name="id" value="{{$igr->id}}">
								<section>
									<div class="row">
										<label class="label col col-2">State Name</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="state_name" value="{{$igr->state_name}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Abbrev</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="igr_abbre" value="{{$igr->igr_abbre}}">
											</label>
										</div>
									</div>
								</section>
								
							
								<section>
									<div class="row">
										<label class="label col col-2">Logo</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="file" name="file">
											</label>
										</div>
									</div>
								</section>
								
						
							
							<footer>
								<button type="submit" class="btn btn-primary">
									Onboard
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



</div>-->
			<!-- END MAIN CONTENT -->

</div>
		</div>
		<!-- END MAIN PANEL -->

@stop