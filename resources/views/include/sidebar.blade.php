<!-- Left panel : Navigation area -->
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
			<li class="active">
				<a href="/dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
	
			</li>
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i><span class="menu-item-parent">Setup</span></a>
				<ul>
					<li><a href="#">Agencies</a></li>
					<li><a href="#">Revenue Heads</a></li>
					<li><a href="#">Stations</a></li>
					<li><a href="#">POS</a></li>
				</ul>
			</li>	
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Access Control</span></a>
				<ul>
					<li>
						<a href="/users">User <i class="fa fa-external-link"></i></a>
					</li>
					<li>
						<a href="/role">Role</a>
					</li>
					<li>
						<a href="/permission">Permission</a>
					</li>

				</ul>
			</li>
			<li class="top-menu-invisible">
				<a href="/revenue_heads"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Revenue Heads</span></a>
			</li>
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Collection Record</span></a>
				<ul>
				<li>
					<a href="/all_collection">All Collections</a>
				</li>
					<li>
						<a href="/ebill_collection">Ebills Collection</a>
					</li>
					<li>
						<a href="/pos_collection">POS</a>
				</ul>
			</li>
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Reports & Analystics</span></a>
				<ul>
				<li>
					<a href="#">All Collections</a>
				</li>
					<li>
						<a href="#">Ebills Collection</a>
					</li>
					<li>
						<a href="#">POS</a>
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
<!-- END NAVIGATION -->