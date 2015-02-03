<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="google-site-verification" content="MWuFRYlYz4t-qxdUsVouO_NpimhkxRD1cfi0mzFRyQI" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
	 <link href="<?php bloginfo('template_directory')?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />

	<meta property="fb:app_id" content="406899602728684"/>

	<?php
	 $description = "På spason.se hittar du lägsta priserna för spahotell och spaweekend i hela Sverige. Ett enklare och mer inspirerande sätt att boka. Klicka här och läs mer!";
	 $image = get_bloginfo('template_directory')."/images/screenshot.png";
	 $author = "Spason AB";
	 $title = wp_title(' | ', false, 'right')  . get_bloginfo('name');
	 $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	 $keywords="";
	 $type = "article list";

	 if(is_front_page()){
	 	$title = get_bloginfo('description')." | ".get_bloginfo('name');
	 }

	 if(is_single()){
	 	global $post;
	 	$type = "article";
	 	$excerpt = get_the_excerpt($post->ID);
	 	if(strlen($excerpt) > 0) {
	 		$description = $excerpt;
	 	}else {
	 		$description = strip_tags(implode(' ', array_slice(explode(' ', $post->post_content), 0, 55)));
	 	}
	 	$image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(500,500))[0];
	 	$author = get_the_author_meta('display_name', $post->post_author);
	 	$keywords = implode(', ', wp_get_post_tags( get_the_ID(), array('fields' => 'names') ) );
	 }else{
	 	$description = "På Spason.se kan du hitta inspiration om spa och boka din drömvistelse på spahotell i hela Sverige.";
	 }
	?>
	<!-- for Google -->
	<meta name="description" content="<?php echo $description ?>" />
	<meta name="keywords" content="<?php echo $keywords ?>" />
	<meta name="application-name" content="Spason.se" />
	<meta name="author" content="<?php echo $author ?>" />
	

	<!-- for Facebook -->          
	<meta property="og:title" content="<?php echo $title ?>" />
	<meta property="og:type" content="<?php echo $type?>" />
	<meta property="og:image" content="<?php echo $image ?>" />
	<meta property="og:url" content="<?php echo $url ?>" />
	<meta property="og:description" content="<?php echo $description ?>" />

	<!-- for Twitter -->          
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo $title ?>" />
	<meta name="twitter:description" content="<?php echo $description ?>" />
	<meta name="twitter:image" content="<?php echo $image ?>" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />

	<title><?php echo $title ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/bootstrap/transition.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/bootstrap/modal.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/bootstrap/dropdown.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/bootstrap/affix.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/scripts.js"></script>

</head>

<body>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K76J5L"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K76J5L');</script>
<!-- End Google Tag Manager -->

<div id="fb-root"></div>
 <script src="//connect.facebook.net/sv_SV/all.js"></script>
 <script>
   FB.init({
     appId  : '406899602728684',
     status: true, // check login status
     cookie: true, // enable cookies to allow server to access session,
     xfbml: true, // enable XFBML and social plugins
     oauth: true, // enable OAuth 2.0
     channelUrl: '<?php bloginfo("template_url"); ?>/channel.html' //custom channel
   });
 </script>

<div id="page-wrap">
<div id="page">	
	<header id="main-header">
		<div class="container">
			<div class="col-xs-12">
				<a href="<?php echo home_url()?>" class="logo">Spason</a>
				<nav class="site-nav">
					<a href="#" class="show-menu visible-xs elegant-icon">&#x61;</a>
					<div class="site-menu">
						<a href="#" class="elegant-icon hide-menu visible-xs">&#x3d;</a>
						<ul class='menu' id="main-menu-1">
							<?php wp_nav_menu( array( 
								'theme_location' => 'main-menu',
								'items_wrap' => '%3$s',
								'container' => ''
							 ) ); ?>
							 <li class="menu-item hidden-xs">
							 	<div class="dropdown">
								 	<a href="#" data-toggle="dropdown" class="more">Mer</a>
								 	<ul class="more-menu dropdown-menu" role="menu">
									 	<?php wp_nav_menu( array(
									 		'theme_location' => 'more-menu',
									 		'items_wrap' => '%3$s',
											'container' => ''
									 	));?>
								 	</ul>
							 	</div>
						 	</li>
						 	<li class="menu-item visible-xs">
						 	<?php wp_nav_menu( array(
							 		'theme_location' => 'more-menu',
									'container' => ''
							 	));?>
						 	</li>
						 </ul>
					</div>
				</nav>
			</div>
		</div>
	</header>