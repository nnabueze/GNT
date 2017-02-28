Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
			
			<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
				<img src="{{ asset('template/img/avatars/sunny.png')}}" alt="me" class="online" /> 
				<span>
					Emeka Aka 
				</span>
				<i class="fa fa-angle-down"></i>
			</a> 
			
		</span>
	</div>
	<!-- end user info -->

	<!-- NAVIGATION : This navigation is also responsive-->
	<nav>

		<ul>
			<li class="<?php if ($sidebar == "dashbaord"){echo "active";}else{echo "";}?>">
				<a href="/dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>

			</li>

			@if(Auth::user()->hasRole('Superadmin'))
			<li class="<?php if ($sidebar == "user_sidebar"||$sidebar == "permission" ||$sidebar == "role"){echo "active";}else{echo "";}?>">
				<a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Access Control</span></a>
				<ul>
					<li class="<?php if ($sidebar == "user_sidebar"){echo "active";}else{echo "";}?>">
						<a href="/users">User <i class="fa fa-external-link"></i></a>
					</li>
					<li class="<?php if ($sidebar == "role"){echo "active";}else{echo "";}?>">
						<a href="/role">Role</a>
					</li>
					<li class="<?php if ($sidebar == "permission"){echo "active";}else{echo "";}?>">
						<a href="/permission">Permission</a>
					</li>

				</ul>
			</li>
			@endif


			<li class="<?php if ($sidebar == "pos"||$sidebar == "agancy"|| $sidebar == "lga" ||$sidebar == "station"||$sidebar == "agent"||$sidebar == "heads"){echo "active";}else{echo "";}?>">
				<a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i><span class="menu-item-parent">Setup</span></a>
				<ul>
					<li class="<?php if ($sidebar == "agancy"){echo "active";}else{echo "";}?>"><a href="/agencies">Agencies</a></li>
					<li class="<?php if ($sidebar == "lga"){echo "active";}else{echo "";}?>"><a href="/lga">LGA</a></li>
					<li class="<?php if ($sidebar == "heads"){echo "active";}else{echo "";}?>"><a href="/revenue_heads">SubHeads</a></li>
					<li class="<?php if ($sidebar == "station"){echo "active";}else{echo "";}?>"><a href="/station">Stations</a></li>
					<!-- <li><a href="#">Pool Account</a></li> -->
					<li class="<?php if ($sidebar == "agent"){echo "active";}else{echo "";}?>"><a href="/agent">Agents</a></li>
					<li class="<?php if ($sidebar == "pos"){echo "active";}else{echo "";}?>"><a href="/pos">POS</a></li>
				</ul>
			</li>



			<li class="<?php if ($sidebar == "all_collection"||$sidebar == "agency" ||$sidebar == "lga"){echo "active";}else{echo "";}?>">
				<a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Collection Record</span></a>
				<ul>
					<li class="<?php if ($sidebar == "all_collection"){echo "active";}else{echo "";}?>">
						<a href="/all_collection">All Collections</a>
					</li>
					<li class="<?php if ($sidebar == "agency"){echo "active";}else{echo "";}?>">
						<a href="/agency_collection">Agency Collection</a>
					</li>
					<li class="<?php if ($sidebar == "lga_collection"){echo "active";}else{echo "";}?>">
						<a href="/lga_collection">LGA Collection</a>
					</li>
					<li class="<?php if ($sidebar == "ebill_collection"){echo "active";}else{echo "";}?>">
						<a href="/ebill_collection">Ebills Collections</a>
					</li>
					<li class="<?php if ($sidebar == "pos_collection"){echo "active";}else{echo "";}?>">
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