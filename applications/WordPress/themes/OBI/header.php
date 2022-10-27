<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="container obi_header_wrapper">
		<nav class="navbar navbar-default obi_header_navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand navbar-left" href="/"><img class="col-sm-7 col-lg-8 col-xs-11" src="/wp-content/themes/OBI/images/obi_logo.jpg" /></a>
          </div>
          
       <!--  <div id="flags" class="flags navbar-collapse collapse">
           <a class="nav-link" href="/"><img class="" src="/wp-content/themes/OBI/images/el.png" /></a>
           <a class="nav-link" href="/εφαρμογή/υπο-κατασκευή"><img class="" src="/wp-content/themes/OBI/images/en.png" /></a>
           <a class="nav-link" href="/εφαρμογή/υπο-κατασκευή"><img class="" src="/wp-content/themes/OBI/images/fr.png" /></a>
           <a class="nav-link" href="/εφαρμογή/υπο-κατασκευή"><img class="" src="/wp-content/themes/OBI/images/ge.png" /></a>
           
           
          </div> -->
          <div id="navbar" class="navbar-collapse collapse">

           

            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item active">
            <a class="nav-link" href="/"><?php _e( 'Home', 'obi' ); ?><span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/εφαρμογή/αιτήσεις/"><?php _e( 'Applications', 'obi' ); ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/οδηγίες/ενότητες/"><?php _e( 'Help', 'obi' ); ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="http://www.obi.gr"><?php _e( 'OBI', 'obi' ); ?></a>
          </li>
          
          			
          
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
    </div>
<div class="container">
    <div class="row blog-header">
		 <div class="col-sm-9 col-xs-12">
		 <div class="col-sm-12 col-xs-12">	 
         <h3 class="col-sm-8 blog-title"><?php _e( 'Electronic submission of applications', 'obi' ); ?></h3>
         </div>
         <!--
         <div class="col-sm-12 d-none d-sm-block">
         <p class="lead blog-description"><?php _e( 'Submit forms easyly', 'obi' ); ?></p>
         </div>
         -->
         </div>	
         <div class="col-sm-3 d-none d-sm-block">
			 <span class="float-right" id="bounceApplication">
			   <a href="/εφαρμογή/αιτήσεις/"><img class="img-responsive" src="/wp-content/themes/OBI/images/application.png" style="background-image: none; background-color: transparent;">
			   <h4 class="btn btn-danger"><strong class="danger">Για αιτήσεις πατήστε εδώ.</strong></h4>
			   </a>
	         </span>
	         
	     </div>
    </div>
    
	<div id="user-dashboard" class="row user-dashboard" class="col-xs-12" style="display:none;">
	<div class="pull-left"><div id="user-details"><?php _e('Connect', 'obi')?>: <span id="user-email">.</span></div></div>
	<div class="pull-right">
	<button id="user-submissions" type="button" class="btn btn-default btn-xs" aria-label="submissions" style="display:none;">
      <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <?php _e( 'Submissions', 'obi' )?>
    </button>	
	<button id="user-login" type="button" class="btn btn-default btn-xs" aria-label="login" style="display:none;">
      <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php _e( 'Login', 'obi' )?>
    </button>
	<button id ="user-logout" type="button" class="btn btn-default btn-xs" aria-label="logout" style="display:none;">
      <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php _e( 'Logout', 'obi' )?>
    </button>

	</div>
	</div>
    
    <div class="row blog-main-wrapper">
<!--End Of Header.php -->
