
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
			
			<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
				<img src="{{ asset('template/img/avatars/sunny.png')}}" alt="me" class="online" /> 
				<span>
					{{Auth::user()->name}}
				</span>
				<i class="fa fa-angle-down"></i>
			</a> 
			
		</span>
	</div>
	<!-- end user info -->

	<!-- NAVIGATION : This navigation is also responsive-->
	<nav>

		<ul>
			<li >
				<a href="/dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>

			</li>

			@if(Auth::user()->hasRole('Superadmin'))
			<li >
				<a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Access Control</span></a>
				<ul>
					<li >
						<a href="/users">User <i class="fa fa-external-link"></i></a>
					</li>
					<li >
						<a href="/role">Role</a>
					</li>
					<li >
						<a href="/permission">Permission</a>
					</li>

				</ul>
			</li>
			@endif


			<li >
				<a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i><span class="menu-item-parent">Setup</span></a>
				<ul>
					<li><a href="/agencies">Agencies</a></li>
					<li><a href="/lga">LGA</a></li>
					<li><a href="/revenue_heads">SubHeads</a></li>
					<li>Stations</a></li>
					<!-- <li><a href="#">Pool Account</a></li> -->
					<li>Agents</a></li>
					<li><a href="/pos">POS</a></li>
				</ul>
			</li>



			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Collection Record</span></a>
				<ul>
					<li >
						<a href="/all_collection">All Collections</a>
					</li>
					<li >
						<a href="/agency_collection">Agency Collection</a>
					</li>
					<li >
						<a href="/lga_collection">LGA Collection</a>
					</li>
					<li >
						<a href="/ebill_collection">Ebills Collections</a>
					</li>
					<li >
						<a href="/pos_collection">POS Collections</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Invoice/Remittance</span></a>
				<ul>
					<li>
						<a href="#">Remittances</a>
					</li>
					<li>
						<a href="#">Transactions </a>
					</li>
					<li>
						<a href="#">Manage Invoices</a>
					</ul>
				</li>

				<li>
					<a href="/logout"><i class="fa fa-lg fa-fw fa-list-alt"></i> <span class="menu-item-parent">Logout</span></a>
				</li>
			</ul>


	</nav>


	<span class="minifyme" data-action="minifyMenu"> 
		<i class="fa fa-arrow-circle-left hit"></i> 
	</span>

</aside>
<!-- END NAVIGATION