<?php
/*
 * Template Name: About
 * Description: A page to display spason press and spason news.
 */
	get_header();
	get_template_part('secondary_header');
?>
<?php while(have_posts()) : the_post();
	global $post; 
	$coworkers = (get_post_meta($post->ID, "coworker"));
?>
<div class="content">
<div id="main-banner" class="home" style="background-image: url('<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full')[0]; ?>')">
	<div class="container">
	<hgroup>
		<h1 class='text-center'><?php the_title();?></h1>
	</hgroup>
	</div>
</div>
<div class="container">
<div class="col-xs-12" id="about">
	<div class="row">
		<div class="col-md-4">
			<h2>Varför Spason?</h2>
		</div>
		<div class="col-md-8">
			<?php the_content(); ?>
		</div>
	 </div>
	<div class="row">
		<div class="col-md-4">
			<h2>Vi bygger Spason</h2>
		</div>
		<div class="col-md-8">
			<ul class="profiles list-unstyled">
			<?php foreach ($coworkers as $coworker) {
				$coworker = explode(",", $coworker);
				$name = $coworker[0];
				$title = $coworker[1];
				$email = $coworker[2];
				$image_url = get_gravatar($email);
			?>
				<li class="clearfix">
				    <img src="<?php echo $image_url ?>" class="img-circle" />
				    <div class="info">
				        <h3 class="name"><?php echo $name?></h3>
				        <h4 class="title"><?php echo $title?></h4>
				    </div>
				</li>
			<?php } ?>
			</ul>
		</div>
	 </div>	
 <?php endwhile; ?>
</div>
</div>
</div>
<?php 
	get_footer();
?>