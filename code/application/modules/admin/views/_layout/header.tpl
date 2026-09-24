<!DOCTYPE html>
<html>
	<head>
            {ci_config name="base_url"}
            {ci_language file="wpn_common" lang="$lang"}

		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <link rel="apple-touch-icon" sizes="76x76" href="{$base_url}/lib/img/favicon/apple-touch-icon.png">
                <link rel="icon" type="image/png" sizes="32x32" href="{$base_url}/lib/img/favicon/favicon-32x32.png">
                <link rel="icon" type="image/png" sizes="16x16" href="{$base_url}/lib/img/favicon/favicon-16x16.png">
                <link rel="manifest" href="{$base_url}/lib/img/favicon/site.webmanifest">
                <link rel="mask-icon" href="{$base_url}/lib/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
                <meta name="msapplication-TileColor" content="#998e5f">
                <meta name="theme-color" content="#ffffff">

		<title>CMS</title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<!-- Bootstrap 3.3.7 -->
		<link href="{$base_url}/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<!-- Font Awesome Icons -->
		<link href="{$base_url}/lib/bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- Ionicons -->
		<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />

                <!-- DataTables style -->
		<link href="{$base_url}/lib/plugins/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
                <!-- Theme style -->
		<link href="{$base_url}/lib/2.4/css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<!-- AdminLTE Skins. -->
		<link href="{$base_url}/lib/2.4/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
		<!-- CSS custom AdminLTE theme -->
                <link href="{$base_url}/lib/css/grande.css" rel="stylesheet" type="text/css" />
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- jQuery v3.3.1 -->
	    <script src="{$base_url}/lib/plugins/jQuery/jquery.min.js"></script>
	    <!--JQueryUI-->
	    <script src="{$base_url}/lib/plugins/jQueryUI/jquery-ui.min.js"></script>
	    <!-- Bootstrap 3.3.7  JS -->
	    <script src="{$base_url}/lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	    <!-- BootBox -->
	    <script src="{$base_url}/lib/plugins/bootbox/bootbox.min.js"></script>
	    <!-- DATA TABES SCRIPT -->
	    <script src="{$base_url}/lib/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
	    <!--Data Tables-->
	    <script src="{$base_url}/lib/plugins/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	    <!--Color-Picker-->
	    <script src="{$base_url}/lib/plugins/colorpicker/js/bootstrap-colorpicker.min.js" type="text/javascript"></script>
	    <link href="{$base_url}/lib/plugins/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
	    <!-- SlimScroll -->
	    <script src="{$base_url}/lib/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	    <!-- FastClick -->
	    <script src='{$base_url}/lib/plugins/fastclick/fastclick.min.js'></script>
	    <!-- AdminLTE App -->
	    <script src="{$base_url}/lib/2.4/js/adminlte.min.js" type="text/javascript"></script>
	    <!-- WPanel JS -->
	    <script src="{$base_url}/lib/js/wpanel.js"></script>
	    <script src="{$base_url}/assets/js/jquery.inputmask.js"></script>

            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<body class="skin-{$skin} sidebar-mini">

	<!-- Site wrapper -->
	<div class="wrapper">

		<header class="main-header">
			<!-- Logo -->
			<a href="{site_url url="admin"}" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>C</b>OST</span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg">COST</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!--<li>
						<?= anchor('', '<i class="fa fa-globe fa-lg fa-2x"></i>&nbsp;&nbsp;Visualizar o site', array('target'=>'_blank')); ?>
						</li> -->
						<!-- User Account: style can be found in dropdown.less -->
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="{$avatar}" class="user-image" alt="{$account_name}"/>
								<span class="hidden-xs">{$account_name}</span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">
									<img src="{$avatar}" class="img-circle" alt="{$account_name}" />
									<p>
										{$account_name}
										<small>{$since} {$login_created}</small>
                                                                                <small>{ci_language line="wpn_since"} {$since_created}</small>
									</p>

								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										{$anchor_profile}
									</div>
									<div class="pull-right">
										{$anchor_logout}
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- Left side column. contains the sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- Sidebar user panel -->
                                    <a href="{site_url url="admin/accounts/profile"}">
                                        <div class="user-panel">
                                            <div class="pull-left image">
                                                    <img src="{$avatar}" class="img-circle" alt="{$account_name}" />
                                            </div>
                                            <div class="pull-left info">
                                                    <p>{$account_name}</p>
                                            </div>
                                        </div>
                                    </a>
                                <!--<div class="user-panel">
                                    <div class="pull-left image">
                                      <img src="{$avatar}" class="img-circle" alt="{$account_name}">
                                    </div>
                                    <div class="pull-left info">
                                      <p>{$account_name}</p>
                                      <a href="{site_url url="admin/accounts/profile"}"><i class="fa fa-circle text-success"></i> Online</a>
                                    </div>
                                </div>-->

				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">
                                            {ci_language line="mod_mainoptions" default='MAIN OPTIONS'}
                                        </li>

					<li {if $activelink==='dashboard'}class="active"{/if} >
                                            <a href="{$base_url}/admin/dashboard"><i class="fa fa-dashboard"></i> <span>{ci_language line="mod_dashboard" default='Dashboard'}</span></a>
					</li>
                                        <!--old menu-->
					{*if wpn_link_permission('admin/posts') }
						<li {if $activelink==='posts'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/posts"><i class="fa fa-files-o"></i> <span>{ci_language line="mod_post" default='Posts'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/pages') }
						<li {if $activelink==='pages'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/pages"><i class="fa fa-files-o"></i> <span>{ci_language line="mod_page" default='Pages'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/agendas') }
						<li {if $activelink==='agendas'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/agendas"><i class="fa fa-calendar"></i> <span>{ci_language line="mod_event" default='Events'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/banners') }
						<li {if $activelink==='banners'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/banners"><i class="fa fa-shirtsinbulk"></i> <span>{ci_language line="mod_banner" default='Banners'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/albuns') }
						<li {if $activelink==='albuns'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/albuns"><i class="fa fa-camera"></i> <span>{ci_language line="mod_galery" default='Galery'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/videos') }
						<li {if $activelink==='videos'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/videos"><i class="fa fa-film"></i> <span>{ci_language line="mod_video" default='Videos'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/menus') }
                                                <li {if $activelink==='menus' || $activelink==='menuitens' }class="active"{/if} >
                                                    <a href="{$base_url}/admin/menus"><i class="fa fa-reorder"></i> <span>{ci_language line="mod_menu" default='Menus'}</span></a>
                                                </li>
					{/if}

                                        {if wpn_link_permission('admin/newsletters') }
						<li {if $activelink==='newsletters'}class="active"{/if} >
                                                    <a href="{$base_url}/admin/newsletters"><i class="fa fa-envelope-o"></i> <span>{ci_language line="mod_newsletter" default='Newsletters'}</span></a>
						</li>
					{/if*}
                                        

                                        {*if wpn_link_permission('admin/banner') }
						<li {if $activelink==='banner'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/banner"><i class="fa fa-shirtsinbulk"></i> <span>{ci_language line="mod_banner" default='Banners'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/projects') }
						<li {if $activelink==='projects'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/projects"><i class="fa fa-university" aria-hidden="true"></i> <span>{ci_language line="mod_project" default='Projects'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/floor') }
						<li {if $activelink==='floor'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/floor"><i class="fa fa-columns" aria-hidden="true"></i> <span>{ci_language line="mod_floor" default='Prject Floor'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/roomtype') }
						<li {if $activelink==='roomtype'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/roomtype"><i class="fa fa-braille" aria-hidden="true"></i> <span>{ci_language line="mod_roomtype" default='Room Type'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/rooms') }
						<li {if $activelink==='rooms'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/rooms"><i class="fa fa-building" aria-hidden="true"></i> <span>{ci_language line="mod_project_room" default='Project room'}</span></a>
						</li>
					{/if}


                                        {if wpn_link_permission('admin/roomtype_gallery') }
						<li {if $activelink==='roomtype_gallery'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/roomtype_gallery"><i class="fa fa-picture-o" aria-hidden="true"></i> <span>{ci_language line="mod_roomtype_gallery" default='Prject Roomtype gallery'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/gallery') }
						<li {if $activelink==='gallery'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/gallery"><i class="fa fa-picture-o" aria-hidden="true"></i> <span>{ci_language line="mod_gallery" default='Gallery'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/project_banner') }
						<li {if $activelink==='project_banner'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/project_banner"><i class="fa fa-picture-o"></i> <span>{ci_language line="mod_project_banner" default='Project banner'}</span></a>
						</li>
					{/if}

                                        {if wpn_link_permission('admin/facility') }
						<li {if $activelink==='facility'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/facility"><i class="fa fa-tree" aria-hidden="true"></i> <span>{ci_language line="mod_facility" default='Facility'}</span></a>
						</li>
					{/if*}

                                        {if wpn_link_permission('admin/quotationAP') }
						<li {if $activelink==='quotationAP'}class="active"{/if}>
                                                    <a href="{$base_url}/admin/quotationAP"><i class="fa fa-building"></i> <span>{ci_language line="mod_quotationAP" default='Quotation AP'}</span></a>
						</li>
					{/if}
                                        
                                        {*<li class="treeview {if $activelink==='members' || $activelink==='booking' ||  $activelink==='agents_booking' } active menu-open{/if}">
                                            <a href="#">
                                              <i class="fa fa-files-o"></i>
                                              <span>Report</span>
                                              <span class="pull-right-container">
                                                <span class="fa fa-angle-left pull-right"></span>
                                              </span>
                                            </a>
                                            <ul class="treeview-menu">
                                                {if wpn_link_permission('admin/members') }
                                                    <li {if $activelink==='members'}class="active"{/if} >
                                                        <a href="{$base_url}/admin/members"><i class="fa fa-users"></i> <span>{ci_language line="mod_member_report" default='Member Registration'}</span></a>
                                                    </li>
                                                {/if}
                                                {if wpn_link_permission('admin/booking') }
                                                    <li {if $activelink==='booking'}class="active"{/if} >
                                                        <a href="{$base_url}/admin/booking"><i class="fa fa-money"></i> <span>{ci_language line="mod_booking_report" default='Booking'}</span></a>
                                                    </li>
                                                {/if}
                                                {if wpn_link_permission('admin/agents_booking') }
                                                    <li {if $activelink==='agents_booking'}class="active"{/if} >
                                                        <a href="{$base_url}/admin/agents_booking"><i class="fa fa-money"></i> <span>{ci_language line="mod_agents_booking_report" default='Agents Booking'}</span></a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </li>*}

                                        {if wpn_link_permission('admin/accounts') || wpn_link_permission('admin/modulos') || wpn_link_permission('admin/configuracoes') }
                                            <li class="treeview {if $activelink==='accounts'|| $activelink==='modulos'|| $activelink==='configuracoes'} active menu-open{/if}">
                                                <a href="#">
                                                  <i class="fa fa-cogs"></i>
                                                  <span>System Management</span>
                                                  <span class="pull-right-container">
                                                    <span class="fa fa-angle-left pull-right"></span>
                                                  </span>
                                                </a>
                                                <ul class="treeview-menu">
                                                    {if wpn_link_permission('admin/accounts') }
                                                            <li {if $activelink==='accounts'}class="active"{/if} >
                                                                <a href="{$base_url}/admin/accounts"><i class="fa fa-users"></i> <span>{ci_language line="mod_user" default='Users'}</span></a>
                                                            </li>
                                                    {/if}
                                                    {if wpn_link_permission('admin/members') }
                                                            <li {if $activelink==='members'}class="active"{/if} >
                                                                <a href="{$base_url}/admin/members"><i class="fa fa-user"></i> <span>{ci_language line="mod_members" default='Members'}</span></a>
                                                            </li>
                                                    {/if}
                                                    {if wpn_link_permission('admin/teams') }
                                                            <li {if $activelink==='teams'}class="active"{/if} >
                                                                <a href="{$base_url}/admin/teams"><i class="fa fa-commenting"></i> <span>{ci_language line="mod_teams" default='Teams'}</span></a>
                                                            </li>
                                                    {/if}
                                                    {if wpn_link_permission('admin/projects') }
                                                            <li {if $activelink==='projects'}class="active"{/if} >
                                                                <a href="{$base_url}/admin/projects"><i class="fa fa-list-alt"></i> <span>{ci_language line="mod_projects" default='Projects'}</span></a>
                                                            </li>
                                                    {/if}

                                                    {if wpn_link_permission('admin/modulos') }
                                                            <li {if $activelink==='modulos'}class="active"{/if}>
                                                                <a href="{$base_url}/admin/modulos"><i class="fa fa-cogs"></i> <span>{ci_language line="mod_modules" default='Module'}</span></a>
                                                            </li>
                                                    {/if}

                                                    {if wpn_link_permission('admin/configuracoes') }
                                                            <li {if $activelink==='configuracoes'}class="active"{/if} >
                                                                <a href="{$base_url}/admin/configuracoes"><i class="fa fa-cog"></i> <span>{ci_language line="mod_configuration" default='Configurations'}</span></a>
                                                            </li>
                                                    {/if}
                                                </ul>
                                            </li>
                                        {/if}
				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

			{if !empty($msg_system) }
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="margin:5px 8px 5px 5px;" class="alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        {$msg_system}
                                    </div>
                                </div>
                            </div>
			{/if}
