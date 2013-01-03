<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8" />

<!-- Set the viewport width to device width for mobile -->
<meta name="viewport" content="width=device-width" />

<title>Welcome to Weather Report Card</title>

<!-- Included CSS Files (Uncompressed) -->
<!--
<link rel="stylesheet" href="stylesheets/foundation.css">
-->

<!-- Included CSS Files (Compressed) -->

<link rel="stylesheet" href="<?=site_url('html/stylesheets/foundation.css');?>">
<link rel="stylesheet" href="<?=site_url('html/stylesheets/app.css');?>">
<link rel="stylesheet" href="<?=site_url('html/stylesheets/custom.css');?>">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />

<script src="<?=site_url('html/javascripts/modernizr.foundation.js');?>"></script>
<!-- <script type='text/javascript' src="http://code.jquery.com/jquery-1.8.2.js"></script> -->

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>
<body>

	<header>
		<div class="row">
			<div class="twelve columns">
				<h2><a href='<?=site_url();?>'>WEATHER&nbsp;&nbsp;&nbsp;&nbsp;REPORT&nbsp;&nbsp;&nbsp;&nbsp;CARD</a></h2>
				<!-- <h5>- public accountibility for weather forecasters -</h5> -->
				<?if(!$this->session->userdata('name')):?>
					<h5><a class='white_link' href='<?=site_url('login');?>'>Log In</a> - or - <a class='white_link' href='<?=site_url('signup');?>'>Sign Up</a></h5>
				<?else:?>
				<?
					$greetings = array('Welcome', 'Hello', 'Howdy', 'Sup');
					$rand = array_rand($greetings);
				?>
					<h5><?=$greetings[$rand].' '.$this->session->userdata('name');?>!</h5>
				<?endif;?>
			</div>
		</div>
	</header>