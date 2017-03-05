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
		<li>Profile Management </li><li>Change Pasword</li>
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
					<h2>Password Change</h2>				
					
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
						
						<form id="smart-form-register" action="/change_password" method="POST" class="smart-form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{Auth::user()->id}}">
							<header>
								Password Change
							</header>

							<fieldset>
								<section>
									<label class="input"> <i class="icon-append fa fa-lock txt-color-teal"></i>
										<input type="password" name="old_password" placeholder="Enter Old Password">
										<b class="tooltip tooltip-bottom-right">Enter Old Password</b> </label>
								</section>

								<section>
									<label class="input"> <i class="icon-append fa fa-lock txt-color-teal"></i>
										<input type="password" name="new_password" placeholder="Enter New Password">
										<b class="tooltip tooltip-bottom-right">Enter New Password</b> </label>
								</section>

								<section>
									<label class="input"> <i class="icon-append fa fa-lock txt-color-teal"></i>
										<input type="password" name="confirm_new_password" placeholder="Enter Confirm New Password">
										<b class="tooltip tooltip-bottom-right">Enter Confirm New Password</b> </label>
								</section>
							
							<footer>
								<button type="submit" class="btn btn-primary">
									Done
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