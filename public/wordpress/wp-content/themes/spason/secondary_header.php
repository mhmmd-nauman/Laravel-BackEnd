<header id="secondary-header">
	<div class="container">
		<div class="hidden-sm hidden-xs col-md-6">
			<div class="nav-breadcrumb">
				<?php the_breadcrumb(); ?>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<nav class="menu nav-more">
				<ul>
				 	<?php wp_nav_menu( array(
				 		'theme_location' => 'more-menu',
				 		'items_wrap' => '%3$s',
						'container' => ''
				 	));?>
			 	</ul>
			</nav>
		</div>
	</div>
</header>