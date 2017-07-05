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
		<li>Fundsweep </li><li>Upload</li>
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
					<h2>Edit Upload</h2>				
					
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
						
					<form id="login-form" method="POST" action="/edit_upload" class="smart-form" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<fieldset>
								<input type="hidden" name="id" value="{{$upload->id}}">
								<section>
									<div class="row">
										<label class="label col col-2">Agency</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="agency" value="{{$upload->agency}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Collected Amount</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="collected_amount" value="{{$upload->collected_amount}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Agency Amount</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="agency_amount" value="{{$upload->agency_amount}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Remitted Amount</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="remitted_amount" value="{{$upload->remitted_amount}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Remitted Date</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="remitted_date" value="{{$upload->remitted_date}}">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Payment Date</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="payment_date" value="{{$upload->payment_date}}">
											</label>
										</div>
									</div>
								</section>
						
							
							<footer>
								<button type="submit" class="btn btn-primary">
									Update
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