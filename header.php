<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <!-- Required meta tags -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>

  </head>
  <body>

    <!-- Header Section Start -->
    <header id="home" class="hero-area-2">    
      <div class="overlay"></div>
      <nav class="navbar navbar-expand-md bg-inverse fixed-top scrolling-navbar menu-bg">
        <div class="container">
          <a href="<?php bloginfo('url'); ?>/" class="navbar-brand"><img src="<?=get_template_directory_uri();?>/img/logo.png" alt=""></a>  
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="lni-menu"></i>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto w-100 justify-content-end">
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#app-features">Features</a>
              </li>  
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#screenshots">Screenshots</a>
              </li>                            
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#testimonial">Testimonial</a>
              </li> 
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#pricing">Plans</a>
              </li>  
              <li class="nav-item">
                <a class="nav-link page-scroll" href="#download">Download</a>
              </li>
              <li class="nav-item">
                <a class="btn btn-trial" href="#">Join Now</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- <div class="container">      
        <div class="row space-100">
          <div class="col-lg-7 col-md-12 col-xs-12">
            <div class="contents">
              <h2 class="head-title">Creative Solution for <br> SaaS, Business, and Apps</h2>
              <p>lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse unde blanditiis nostrum mollitia aliquam sed. Numquam ipsum unde repellendus similique autem non ab quibusdam enim provident distinctio! Fugit tenetur, iusto.</p>
              <div class="header-button">
                <a href="#" class="btn btn-border-filled">Learn More</a>
                <a href="#" class="btn btn-border">Get Started</a>
              </div>
            </div>
          </div>
          <div class="col-lg-5 col-md-12 col-xs-12">
            <div class="intro-img">
              <img src="<?=get_template_directory_uri();?>/img/intro-mobile.png" alt="">
            </div>            
          </div>
        </div> 
      </div> -->

    </header>
    <!-- Header Section End --> 