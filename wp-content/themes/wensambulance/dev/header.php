<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta content="width=device-width, initial-scale=1" name="viewport" />
<title><?php bloginfo( 'name' ); ?> | <?php bloginfo( 'description' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Favicon -->
<link rel="Shortcut Icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/img/favicon.ico" />

<!-- Analytics -->
<!-- If staging or webhosting, don't index -->
<?php if(stristr( $_SERVER['SERVER_NAME'], "webhosting" ) || stristr($_SERVER['SERVER_NAME'], "staging" )): ?>
	<meta name="robots" content="noindex, nofollow">
<?php endif; ?>

<!-- Make IE recognise HTML5 elements for styling -->
<!--[if lte IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/libraries/css3-mediaqueries.js"></script>
<![endif]-->

<!-- Make IE recognise media queries for styling -->

<!-- build:css dist/css/vendor.css -->
<!-- bower:css -->

<!-- endbower -->
<!-- endbuild -->

<link href="https://fonts.googleapis.com/css?family=Lato|Oswald|Source+Sans+Pro" rel="stylesheet">
<!-- Theme styles -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/dist/css/style-site.css" />
</head>

<body <?php body_class(); ?>>
	<header>
	</header>

	<nav class="row expanded">
		<div class="columns sm-12">
			<a href="/" title="home">
				<img src="<?= get_bloginfo('template_url') ?>/dist/img/logo-2.png ?>">
			</a>
			<?php wp_nav_menu( array('menu' => 'main-menu')); ?>
			<div class="button-container">
				<a href="/aanvraag" class="button primary">Aanvraag</a>
				<a href="/doneer" class="button secondary">Doneer</a>
			</div>

			<button class="hamburger hamburger--squeeze" type="button">
			  <span class="hamburger-box">
			    <span class="hamburger-inner"></span>
			  </span>
			</button>
		</div>
	</nav>

	<div class="content">
	