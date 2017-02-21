@extends('admin')
@section('content')

<!-- MAIN CONTENT -->
<div id="content">

	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i> 
				Table 
				<span>> 
					Data Tables
				</span>
			</h1>
		</div>

	</div>

	<!-- widget grid -->
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
					
					<!-- end widget -->
					
					<section id="widget-grid" class="">
						<!-- row -->
						
							<div class="col-sm-3">				
								<div class="">
									<div class="">
									<form action="/all_collection" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<select name="lga" class="select" onchange="this.form.submit()">
										@if(count($igr->mdas) > 0)
											<option value="">Select Table</option>
											@foreach($igr->mdas as $mda)
											<option value="{{$mda->id}}">{{$mda->mda_name}}</option>
											@endforeach
										@else
										<option value="">Select Table</option>
										@endif
										</select> 
										</form>     
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3">				
							<div class="form-group">
								<div class="input-group">
									<input class="form-control" id="to" type="text" placeholder="Select a date" name="dateto">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>							
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<div class="input-group">
									<input class="form-control" id="from" type="text" placeholder="From" name="datefrm">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="button" id="searchreport" name="biller_srch" id="billersearch" value="Go">
								</div>
							</div>
						</div>
						

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
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

					
					
					
					
					
					
					
					
					
					
					

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
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
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Export to PDF / Excel</h2>

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
						<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
						           <thead>
						            <tr>
						             <th data-hide="phone">Transaction ID</th>
						             <th data-class="expand">Payer Name</th>
						             <th>Payer ID</th>
						             <th data-hide="phone,tablet">Revenue Head</th>
						             <th data-hide="phone">Amount</th>
						             <th data-hide="phone,tablet">Channel</th>
						             <th data-hide="phone,tablet">Date</th>
						             
						            </tr>
						           </thead>
						           <tbody>
						            <tr>
						             <td>NEMXpRGIefMLpMe</td>
						             <td>test tester </td>
						             <td>112233445566</td>
						             <td>Registration of Night Soil</td>
						             <td>1000</td>
						             <td>pos</td>
						             <td>2017-02-13</td>
						            </tr>
						            <tr>
						             <td>7YwvC4y2cvK59OB</td>
						             <td>test tester</td>
						             <td>112233445566</td>
						             <td>Contract Registration (Per Contract)- Cat F. (501,000,000 - 999,999,999).</td>
						             <td>21000</td>
						             <td>pos</td>
						             <td>2017-02-13</td>
						            </tr>
						            <tr>
						             <td>rOMeXsKumkWdoPG</td>
						             <td>test tester</td>
						             <td>102030405060</td>
						             <td>Saw-Mill License(Per Annum)</td>
						             <td>2000</td>
						             <td>pos</td>
						             <td>02/13/14</td>
						            </tr>
						            <tr>
						             <td>S6HueeNi2ahSltS</td>
						             <td>test tester</td>
						             <td>102030401010</td>
						             <td>Mass Transit Hiring/Services(per Day)- Medium Bus</td>
						             <td>3000</td>
						             <td>pos</td>
						             <td>01/01/13</td>
						            </tr>
						            
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
		
		<!-- end row -->
		
	</section>
	<!-- end widget grid -->

</div>
<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->


@stop
@push('scripts')
<script type="text/javascript">
	
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	
	$(document).ready(function() {
		
		pageSetUp();
		
		/* // DOM Position key index //
	
		l - Length changing (dropdown)
		f - Filtering input (search)
		t - The Table! (datatable)
		i - Information (records)
		p - Pagination (paging)
		r - pRocessing 
		< and > - div elements
		<"#id" and > - div with an id
		<"class" and > - div with a class
		<"#id.class" and > - div with an id and class
		
		Also see: http://legacy.datatables.net/usage/features
		*/	

		/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
			"t"+
			"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},
			"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
				}
			});

		/* END BASIC */
		
		/* COLUMN FILTER  */
		var otable = $('#datatable_fixed_column').DataTable({
	    	//"bFilter": false,
	    	//"bInfo": false,
	    	//"bLengthChange": false
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
	    	"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
	    	"t"+
	    	"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
	    	"autoWidth" : true,
	    	"oLanguage": {
	    		"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
	    	},
	    	"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}		

		});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
	    	
	    	otable
	    	.column( $(this).parent().index()+':visible' )
	    	.search( this.value )
	    	.draw();

	    } );
	    /* END COLUMN FILTER */   

	    /* COLUMN SHOW - HIDE */
	    $('#datatable_col_reorder').dataTable({
	    	"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
	    	"t"+
	    	"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
	    	"autoWidth" : true,
	    	"oLanguage": {
	    		"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
	    	},
	    	"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_col_reorder) {
					responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_col_reorder.respond();
			}			
		});

	    /* END COLUMN SHOW - HIDE */

	    /* TABLETOOLS */
	    $('#datatable_tabletools').dataTable({

			// Tabletools options: 
			//   https://datatables.net/extensions/tabletools/button_options
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
			"t"+
			"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},		
			"oTableTools": {
				"aButtons": [
				"copy",
				"csv",
				"xls",
				{
					"sExtends": "pdf",
					"sTitle": "SmartAdmin_PDF",
					"sPdfMessage": "SmartAdmin PDF Export",
					"sPdfSize": "letter"
				},
				{
					"sExtends": "print",
					"sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
				}
				],
				"sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
			},
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_tabletools) {
					responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_tabletools.respond();
			}
		});

	    /* END TABLETOOLS */

	})

</script>

<!-- Your GOOGLE ANALYTICS CODE Below -->
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>
@endpush