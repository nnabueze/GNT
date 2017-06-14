@extends('admin')
@section('content')

				<!-- MAIN CONTENT -->
				<div id="content">

					<div class="row">
						<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
							<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
						</div>
						<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
							<ul id="sparks" class="">
								<li class="sparks-info">
								<?php

								if (Auth::user()->hasRole('Staff')) {

								$amount_today = 0;
								foreach ($collection_today as $collection_today)
								{
									$amount_today += $collection_today->amount;
								}
								
								$amount_thismonth = 0;
								foreach ($collection_thismonth as $collection_thismonth)
								{
									$amount_thismonth += $collection_thismonth->amount;
								}
								
								$amount_lastmonth = 0;
								foreach ($collection_lastmonth as $collection_lastmonth)
								{
									$amount_lastmonth += $collection_lastmonth->amount;
								}

								$sum_amount = $today_collection_user;
								}

								if(Auth::user()->hasRole(['Lga','Mda'])) {
									$amount_today = $today_collection;
									$amount_thismonth = $thismonth_collection;
									$amount_lastmonth = $lastmonth_collection;
									$sum_amount = $today_collection_user;
								}

								if(Auth::user()->hasRole(['Admin','Superadmin'])) {
									$amount_today = $today_collection;
									$amount_thismonth = $thismonth_collection;
									$amount_lastmonth = $lastmonth_collection;
									$sum_amount = $today_collection_user;
								}

								?>
									<h5> Today <span class="txt-color-blue">₦<?php echo number_format($amount_today,2); ?></span></h5>
									<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
										1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
									</div>
								</li>
								<li class="sparks-info">
									<h5> This Month<span class="txt-color-purple">₦<?php echo number_format($amount_thismonth,2); ?></span></h5>
									<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
										110,150,300,130,400,240,220,310,220,300, 270, 210
									</div>
								</li>
								<li class="sparks-info">
									<h5> Last Month <span class="txt-color-greenDark">₦<?php echo number_format($amount_lastmonth,2); ?></span></h5>
									<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
										110,150,300,130,400,240,220,310,220,300, 270, 210
									</div>
								</li>
							</ul>
						</div>
					</div>
					<!-- widget grid -->
					<section id="widget-grid" class="">

						<!-- row -->
						<div class="row">
							<article class="col-sm-12">
								<!-- new widget -->
								<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
										<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
										<h2>Live Feeds </h2>

										<ul class="nav nav-tabs pull-right in" id="myTab">
											<li class="active">
												<a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">Top Collections</span></a>
											</li>

											<!--<li>
												<a data-toggle="tab" href="#s2"><i class="fa fa-facebook"></i> <span class="hidden-mobile hidden-tablet">Social Network</span></a>
											</li>

											<li>
												<a data-toggle="tab" href="#s3"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">Revenue</span></a>
											</li>-->
										</ul>

									</header>

									<!-- widget div-->
									<div class="no-padding">
										<!-- widget edit box -->
										<div class="jarviswidget-editbox">

										
										</div>
										<!-- end widget edit box -->

										<div class="widget-body">
											<!-- content -->
											<div id="myTabContent" class="tab-content">
												<div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
													<div class="row no-space">
														<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
															<span class="demo-liveupdate-1"> <span class="onoffswitch-title">Live switch</span> <span class="onoffswitch">
																	<input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="start_interval">
																	<label class="onoffswitch-label" for="start_interval"> 
																		<span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
																		<span class="onoffswitch-switch"></span> </label> </span> </span>
															<div id="updating-chart" class="chart-large txt-color-blue"></div>

														</div>
														<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">

														<div class="row">
														<?php
														
																	if(Auth::user()->hasRole(['Lga','Mda'])) {						
																	
																	$line = 0;
																	
															
																		foreach ($collection_users as $collection_users ){
																		
																		$user_amount = $collection_users->sum;
																		$user_name = $collection_users->mda->mda_name;

																		$line = $user_amount / $sum_amount * 100; 
																
																echo		
															
																'<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> '.$user_name .' <span class="pull-right">₦'.number_format($user_amount,2).'</span> </span>
																	<div class="progress">
																		<div class="progress-bar bg-color-blueDark" style="width: '.$line.'%;"></div>
																	</div> </div>';

																		}	

															
															

																	}


																	if(Auth::user()->hasRole(['Admin','Superadmin'])) {						
																	
																	$line = 0;
																	
															
																		foreach ($collection_users as $collection_users ){
																		$user_amount = $collection_users->sum;
																		$user_name = $collection_users->mda->mda_name;

																		$line = $user_amount / $sum_amount * 100; 

																echo		
															
																'<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> '.$user_name .' <span class="pull-right">₦'.number_format($user_amount,2).'</span> </span>
																	<div class="progress">
																		<div class="progress-bar bg-color-blueDark" style="width: '.$line.'%;"></div>
																	</div> </div>';

																		}	

															
															

																	}


																	
																	if (Auth::user()->hasRole('Staff')) {						
																	
																	$line = 0;
																	foreach ($collection_users as $collection_users)
																	{
																		$user_amount = $collection_users->sum;
																		if($collection_users->worker_id > 0){
																		$user_name = $collection_users->worker->worker_name;
																		} else {
																			$user_name = "Ebills";
																		}

																		$line = $user_amount / $sum_amount * 100; 

																echo		
															
																'<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> '.$user_name .' <span class="pull-right">₦'.number_format($user_amount,2).'</span> </span>
																	<div class="progress">
																		<div class="progress-bar bg-color-blueDark" style="width: '.$line.'%;"></div>
																	</div> </div>';
															
																	}

																	}

														?>

														
																<!--<span class="show-stat-buttons"> <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Generate PDF</a> </span> <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Report a bug</a> </span> </span>
																	-->

															</div>
														</div>
													</div>

													<!--<div class="show-stat-microcharts">
														<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

															<div class="easy-pie-chart txt-color-orangeDark" data-percent="33" data-pie-size="50">
																<span class="percent percent-sign">35</span>
															</div>
															<span class="easy-pie-title"> Server Load <i class="fa fa-caret-up icon-color-bad"></i> </span>
															<ul class="smaller-stat hidden-sm pull-right">
																<li>
																	<span class="label bg-color-greenLight"><i class="fa fa-caret-up"></i> 97%</span>
																</li>
																<li>
																	<span class="label bg-color-blueLight"><i class="fa fa-caret-down"></i> 44%</span>
																</li>
															</ul>
															<div class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
																130, 187, 250, 257, 200, 210, 300, 270, 363, 247, 270, 363, 247
															</div>
														</div>
														<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
															<div class="easy-pie-chart txt-color-greenLight" data-percent="78.9" data-pie-size="50">
																<span class="percent percent-sign">78.9 </span>
															</div>
															<span class="easy-pie-title"> Disk Space <i class="fa fa-caret-down icon-color-good"></i></span>
															<ul class="smaller-stat hidden-sm pull-right">
																<li>
																	<span class="label bg-color-blueDark"><i class="fa fa-caret-up"></i> 76%</span>
																</li>
																<li>
																	<span class="label bg-color-blue"><i class="fa fa-caret-down"></i> 3%</span>
																</li>
															</ul>
															<div class="sparkline txt-color-blue hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
																257, 200, 210, 300, 270, 363, 130, 187, 250, 247, 270, 363, 247
															</div>
														</div>
														<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
															<div class="easy-pie-chart txt-color-blue" data-percent="23" data-pie-size="50">
																<span class="percent percent-sign">23 </span>
															</div>
															<span class="easy-pie-title"> Transfered <i class="fa fa-caret-up icon-color-good"></i></span>
															<ul class="smaller-stat hidden-sm pull-right">
																<li>
																	<span class="label bg-color-darken">10GB</span>
																</li>
																<li>
																	<span class="label bg-color-blueDark"><i class="fa fa-caret-up"></i> 10%</span>
																</li>
															</ul>
															<div class="sparkline txt-color-darken hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
																200, 210, 363, 247, 300, 270, 130, 187, 250, 257, 363, 247, 270
															</div>
														</div>
														<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
															<div class="easy-pie-chart txt-color-darken" data-percent="36" data-pie-size="50">
																<span class="percent degree-sign">36 <i class="fa fa-caret-up"></i></span>
															</div>
															<span class="easy-pie-title"> Temperature <i class="fa fa-caret-down icon-color-good"></i></span>
															<ul class="smaller-stat hidden-sm pull-right">
																<li>
																	<span class="label bg-color-red"><i class="fa fa-caret-up"></i> 124</span>
																</li>
																<li>
																	<span class="label bg-color-blue"><i class="fa fa-caret-down"></i> 40 F</span>
																</li>
															</ul>
															<div class="sparkline txt-color-red hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
																2700, 3631, 2471, 2700, 3631, 2471, 1300, 1877, 2500, 2577, 2000, 2100, 3000
															</div>
														</div>
													</div>

												-->
												</div>
												<!-- end s1 tab pane -->

												<!--<div class="tab-pane fade" id="s2">
													<div class="widget-body-toolbar bg-color-white">

														<form class="form-inline" role="form">

															<div class="form-group">
																<label class="sr-only" for="s123">Show From</label>
																<input type="email" class="form-control input-sm" id="s123" placeholder="Show From">
															</div>
															<div class="form-group">
																<input type="email" class="form-control input-sm" id="s124" placeholder="To">
															</div>

															<div class="btn-group hidden-phone pull-right">
																<a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
																<ul class="dropdown-menu pull-right">
																	<li>
																		<a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
																	</li>
																	<li>
																		<a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
																	</li>
																</ul>
															</div>

														</form>

													</div>
													<div class="padding-10">
														<div id="statsChart" class="chart-large has-legend-unique"></div>
													</div>

												</div>
												<!-- end s2 tab pane -->

											
												<!--<div class="tab-pane fade" id="s3">

													<div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

														<div class="inline-group">

															<label for="gra-0" class="checkbox">
																<input type="checkbox" name="gra-0" id="gra-0" checked="checked">
																<i></i> Target </label>
															<label for="gra-1" class="checkbox">
																<input type="checkbox" name="gra-1" id="gra-1" checked="checked">
																<i></i> Actual </label>
															<label for="gra-2" class="checkbox">
																<input type="checkbox" name="gra-2" id="gra-2" checked="checked">
																<i></i> Signups </label>
														</div>

														<div class="btn-group hidden-phone pull-right">
															<a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
															<ul class="dropdown-menu pull-right">
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
																</li>
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
																</li>
															</ul>
														</div>

													</div>

													<div class="padding-10">
														<div id="flotcontainer" class="chart-large has-legend-unique"></div>
													</div>
												</div>
												<!-- end s3 tab pane -->
											</div>

											<!-- end content -->
										</div>

									</div>
									<!-- end widget div -->
								</div>
								<!-- end widget -->

							</article>
						</div>

						<!-- end row -->

						<!-- row -->
							<div class="row">
	                                                <div class="col-md-12">
	                                                    <div class="panel">
	                                                        <div class="panel-heading">
	                                                            <h3 class="panel-title text-left"> Last 5 Transactions</h3>
	                                                        </div>
	                                                        <div class="panel-body">
	                                                            <div class="table-responsive">
	                                                                <table class="table table-no-border table-middle table-primary">
	                                                                <thead>
	                                                                    <tr>
	                                                                   
	                                                                        <th data-hide="phone">TRANSACTION ID</th>
	                                                                        <th data-hide="phone">TRANSACTION AMOUNT</th>
	                                                                        <th data-hide="phone,tablet">TRANSACTION DATE</th>
	                                                                        <th data-hide="phone,tablet">SUB HEAD</th>
	                                                                        <th data-hide="phone,tablet">CHANNEL</th>
	                                                                        <th data-hide="phone,tablet" class="text-center">REMARK</th>
	                                                                    </tr>
	                                                                </thead>
	                                                                <!--tbody section is required-->
	                                                                <tbody>

																	@if (Auth::user()->hasRole('Staff'))
																							
																		@if($collection_ts)
																			@foreach($collection_ts as $collection_t)
																			<tr  class="border-warning">
																				<td><b>{{$collection_t->collection_key}}</b></td>
																				<td>{{$collection_t->amount}}</td>
																				<td>{{date("d-M-Y", strtotime($collection_t->created_at))}}</td>
																				<td>{{$collection_t->subhead->subhead_code}}</td>
																				
																				@if($collection_t->collection_type == "pos")
																				<td>{{$collection_t->worker->worker_name}}</td>
																				<td class="text-center"><span class="label label-success">{{$collection_t->collection_type}}</span></td>
																				@else
																				<td>A/C</td>
																				<td class="text-center">><span class="label label-primary">ebills</span></td>
																				@endif
																				
																			</tr>
																			@endforeach
																		@endif

																		@endif

																	@if(Auth::user()->hasRole(['Lga','Mda']))
																							
																		@if($collection_ts)
																			@foreach($collection_ts as $collection_t)
																			<tr  class="border-warning">
																				<td><b>{{$collection_t->collection_key}}</b></td>
																				<td>{{$collection_t->amount}}</td>
																				<td>{{date("d-M-Y", strtotime($collection_t->created_at))}}</td>
																				<td>{{$collection_t->subhead->subhead_code}}</td>
																				
																				@if($collection_t->collection_type == "pos")
																				<td>{{$collection_t->mda->mda_name}}</td>
																				<td class="text-center"><span class="label label-success">{{$collection_t->collection_type}}</span></td>
																				@else
																				<td>{{$collection_t->mda->mda_name}}</td>
																				<td class="text-center"><span class="label label-primary">ebills</span></td>
																				@endif
																				
																			</tr>
																			@endforeach
																		@endif

																		@endif

																	@if(Auth::user()->hasRole(['Admin','Superadmin']))
																							
																		@if($collection_ts)
																			@foreach($collection_ts as $collection_t)
																			<tr  class="border-warning">
																				<td><b>{{$collection_t->collection_key}}</b></td>
																				<td>{{$collection_t->amount}}</td>
																				<td>{{date("d-M-Y", strtotime($collection_t->created_at))}}</td>
																				<td>{{$collection_t->subhead->subhead_code}}</td>
																				
																				@if($collection_t->collection_type == "pos")
																				<td>{{$collection_t->mda->mda_name}}</td>
																				<td class="text-center"><span class="label label-success">{{$collection_t->collection_type}}</span></td>
																				@else
																				<td>A/C</td>
																				<td class="text-center">><span class="label label-primary">ebills</span></td>
																				@endif
																				
																			</tr>
																			@endforeach
																		@endif

																		@endif

	                                                                    
	                                                                    </tbody>
	                                                                </table>
	                                                            </div>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                        

	                                    </div>

	                                </div>
						
						<!-- end row -->

					</section>
					<!-- end widget grid -->

				</div>
				<!-- END MAIN CONTENT -->

			</div>
			<!-- END MAIN PANEL -->
@stop