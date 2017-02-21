<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
			
			<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
				<img src="img/avatars/sunny.png" alt="me" class="online" /> 
				<span>
					john.doe 
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
					<li><a href="orders.html">Agencies</a></li>
					<li><a href="products-view.html">Revenue Heads</a></li>
					<li><a href="products-detail.html">Stations</a></li>
					<li><a href="products-detail.html">POS</a></li>
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
				<a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Revenue Heads</span></a>
			</li>
			<li>
				<a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Collection Record</span></a>
				<ul>
				<li>
					<a href="/all_collection">All Collections</a>
				</li>
					<li>
						<a href="#">Ebills Collection</a>
					</li>
					<li>
						<a href="#">POS</a>
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
				<a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Profile Management</span></a>
				<ul>
					<li>
						<a href="form-elements.html">Smart Form Elements</a>
					</li>
					<li>
						<a href="form-templates.html">Smart Form Layouts</a>
					</li>
					<li>
						<a href="validation.html">Smart Form Validation</a>
					</li>
					<li>
						<a href="bootstrap-forms.html">Bootstrap Form Elements</a>
					</li>
					<li>
						<a href="bootstrap-validator.html">Bootstrap Form Validation</a>
					</li>
					<li>
						<a href="plugins.html">Form Plugins</a>
					</li>
					<li>
						<a href="wizard.html">Wizards</a>
					</li>
					<li>
						<a href="other-editors.html">Bootstrap Editors</a>
					</li>
					<li>
						<a href="dropzone.html">Dropzone</a>
					</li>
					<li>
						<a href="image-editor.html">Image Cropping</a>
					</li>
					<li>
						<a href="ckeditor.html">CK Editor</a>
					</li>
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