<?php
/*
 * Template Name: News
 * Description: A page to display spason press and spason news.
 */

	get_header('page');
	$args = array( 'post_type' => 'newsitem', 'posts_per_page' => 10 );
	$newsitems = new WP_Query( $args );
?>
<div class="container content">
<div class="col-xs-12">
	<div id="single-page">
	<div class="row">
	<div class="col-md-9">
	<?php while(have_posts()) : the_post(); ?>		
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php while ( $newsitems->have_posts() ) : $newsitems->the_post();?>
		<div class="post">
		<h3><?php echo get_the_date() . ": " . get_the_title();?></h3>
		<?php the_content();?>
		</div>
	<?php endwhile;	?>
	</div>
	</div>
	</div>
</div>
</div>
<?php 
	get_footer();
?>