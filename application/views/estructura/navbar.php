<?php
$usuario = new stdClass();
$usuario->nombre = "no disponible";
$usuario->avatar = base_url()."assets/images/default.png"; 
$usuario->correo = "no disponible";
if ($this->input->cookie('usuario')) {
    $this->session->set_userdata('usuario', unserialize($this->input->cookie('usuario')));
}


if ($session = $this->session->userdata('usuario')) {
    $usuario->nombre = $session->NOMBRE;
    $usuario->avatar = $session->AVATAR;
    $usuario->correo = $session->CORREO;
}
?>




<body class="layout-top-nav light-skin theme-oceansky fixed">
	
<div class="wrapper">
	
  <div class="art-bg">
	 
          
          	  <img src="<?=base_url()?>assets/style/images/art1.svg" alt="" class="art-img light-img">
	  <img src="<?=base_url()?>assets/style/images/art2.svg" alt="" class="art-img dark-img">
          
  </div>

  <header class="main-header">
	  <div class="inside-header">
		<!-- Logo -->
		<a href="<?=base_url()?>" class="logo">
		  <!-- mini logo -->
		  <div class="logo-mini">
                      <span class="light-logo"><img src="<?=base_url()?>assets/images/impact-blanco.png" alt="logo" style="width: 100px;"></span>
			  <!--<span class="dark-logo"><img src="<?=base_url()?>assets/style/images/logo-dark.png" alt="logo"></span>-->
		  </div>
		  <!-- logo-->
<!--		  <div class="logo-lg">
			  <span class="light-logo"><img src="<?=base_url()?>assets/style/images/logo-light-text.png" alt="logo"></span>
			  <span class="dark-logo"><img src="<?=base_url()?>assets/style/images/logo-dark-text.png" alt="logo"></span>
		  </div>-->
		</a>
		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top">
		  <div>		  
			  <!--<a id="toggle_res_search" data-toggle="collapse" data-target="#search_form" class="res-only-view" href="javascript:void(0);"><i class="mdi mdi-magnify"></i></a>-->
			  <form id="search_form" role="search" class="top-nav-search pull-left collapse ml-20">
<!--				<div class="input-group">
					<input type="text" name="example-input1-group2" class="form-control" placeholder="Search">
					<span class="input-group-btn">
					<button type="button" class="btn  btn-default" data-target="#search_form" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="mdi mdi-magnify"></i></button>
					</span>
				</div>-->
			  </form> 

		  </div>

		  <div class="navbar-custom-menu r-side">
			<ul class="nav navbar-nav">
			  <!-- full Screen -->
			  <li class="full-screen-btn">
				<a href="#" data-provide="fullscreen" title="Full Screen">
					<i class="mdi mdi-crop-free"></i>
				</a>
			  </li>			
			  <!-- Messages -->
<!--			  <li class="dropdown messages-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Messages">
				  <i class="mdi mdi-email"></i>
				</a>
				<ul class="dropdown-menu animated bounceIn">

				  <li class="header">
					<div class="p-20 bg-light">
						<div class="flexbox">
							<div>
								<h4 class="mb-0 mt-0">Messages</h4>
							</div>
							<div>
								<a href="#" class="text-danger">Clear All</a>
							</div>
						</div>
					</div>
				  </li>
				  <li>
					 inner menu: contains the actual data 
					<ul class="menu sm-scrol">
					  <li> start message 
						<a href="#">
						  <div class="pull-left">
							<img src="<?=base_url()?>assets/style/images/user2-160x160.jpg" class="rounded-circle" alt="User Image">
						  </div>
						  <div class="mail-contnet">
							 <h4>
							  Lorem Ipsum
							  <small><i class="fa fa-clock-o"></i> 15 mins</small>
							 </h4>
							 <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
						  </div>
						</a>
					  </li>
					   end message 
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="<?=base_url()?>assets/style/images/user3-128x128.jpg" class="rounded-circle" alt="User Image">
						  </div>
						  <div class="mail-contnet">
							 <h4>
							  Nullam tempor
							  <small><i class="fa fa-clock-o"></i> 4 hours</small>
							 </h4>
							 <span>Curabitur facilisis erat quis metus congue viverra.</span>
						  </div>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="<?=base_url()?>assets/style/images/user4-128x128.jpg" class="rounded-circle" alt="User Image">
						  </div>
						  <div class="mail-contnet">
							 <h4>
							  Proin venenatis
							  <small><i class="fa fa-clock-o"></i> Today</small>
							 </h4>
							 <span>Vestibulum nec ligula nec quam sodales rutrum sed luctus.</span>
						  </div>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="<?=base_url()?>assets/style/images/user3-128x128.jpg" class="rounded-circle" alt="User Image">
						  </div>
						  <div class="mail-contnet">
							 <h4>
							  Praesent suscipit
							<small><i class="fa fa-clock-o"></i> Yesterday</small>
							 </h4>
							 <span>Curabitur quis risus aliquet, luctus arcu nec, venenatis neque.</span>
						  </div>
						</a>
					  </li>
					  <li>
						<a href="#">
						  <div class="pull-left">
							<img src="<?=base_url()?>assets/style/images/user4-128x128.jpg" class="rounded-circle" alt="User Image">
						  </div>
						  <div class="mail-contnet">
							 <h4>
							  Donec tempor
							  <small><i class="fa fa-clock-o"></i> 2 days</small>
							 </h4>
							 <span>Praesent vitae tellus eget nibh lacinia pretium.</span>
						  </div>

						</a>
					  </li>
					</ul>
				  </li>
				  <li class="footer">				  
					  <a href="#" class="bg-light">See all e-Mails</a>
				  </li>
				</ul>
			  </li>-->
			  <!-- Notifications -->
<!--			  <li class="dropdown notifications-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Notifications">
				  <i class="mdi mdi-bell"></i>
				</a>
				<ul class="dropdown-menu animated bounceIn">

				  <li class="header">
					<div class="bg-light p-20">
						<div class="flexbox">
							<div>
								<h4 class="mb-0 mt-0">Notifications</h4>
							</div>
							<div>
								<a href="#" class="text-danger">Clear All</a>
							</div>
						</div>
					</div>
				  </li>

				  <li>
					 inner menu: contains the actual data 
					<ul class="menu sm-scrol">
					  <li>
						<a href="#">
						  <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc suscipit blandit.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu sapien elementum, in semper diam posuere.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-users text-danger"></i> Donec at nisi sit amet tortor commodo porttitor pretium a erat.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-shopping-cart text-success"></i> In gravida mauris et nisi
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-user text-danger"></i> Praesent eu lacus in libero dictum fermentum.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-user text-primary"></i> Nunc fringilla lorem 
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-user text-success"></i> Nullam euismod dolor ut quam interdum, at scelerisque ipsum imperdiet.
						</a>
					  </li>
					</ul>
				  </li>
				  <li class="footer">
					  <a href="#" class="bg-light">View all</a>
				  </li>
				</ul>
			  </li>	-->

			  <!-- User Account-->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="User">
						<img src="<?= base_url() ?>assets/images/fotoperfiles/<?= $usuario->avatar ?>" class="float-left rounded-circle" alt="User Image">					  
				</a>
				<ul class="dropdown-menu animated flipInX">
				  <!-- User image -->
				  <li class="user-header bg-img" data-overlay="3">
					  <div class="flexbox align-self-center">					  
					  <img src="<?= base_url() ?>assets/images/fotoperfiles/<?= $usuario->avatar ?>" class="float-left rounded-circle" alt="User Image">					  
						<h4 class="user-name align-self-center">
						  <span><?=$usuario->nombre?></span>
						  
						  <br>
						  <small><?=$usuario->correo?></small>
						</h4>
					  </div>
				  </li>
				  <!-- Menu Body -->
				  <li class="user-body">
<!--						<a class="dropdown-item" href="javascript:void(0)"><i class="ion ion-person"></i> My Profile</a>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ion ion-bag"></i> My Balance</a>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ion ion-email-unread"></i> Inbox</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ion ion-settings"></i> Account Setting</a>-->
						<div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?= base_url()?>home/salir"><i class="ion-log-out"></i> Cerrar Sesión</a>
<!--						<div class="dropdown-divider"></div>
						<div class="p-10"><a href="javascript:void(0)" class="btn btn-sm btn-rounded btn-success">View Profile</a></div>-->
				  </li>
				</ul>
			  </li>	

			 

			</ul>
		  </div>
		</nav>
  	  </div>
  </header>