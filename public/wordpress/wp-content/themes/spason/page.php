<?php get_header('page'); ?>

<div class="container content">
<div class="col-xs-12">
	<div id="single-page">
		<?php while(have_posts()) : the_post(); ?>		
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
</div>
</div>
<?php get_footer(); ?>