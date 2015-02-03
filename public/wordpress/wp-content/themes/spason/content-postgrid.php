<?php 
$i = 0;
isset($show_about)? null : $show_about = true;
isset($show_newsletter_signup)? null : $show_newsletter_signup = true;
$about_pos = rand(0,7);
global $wp_query;
$newsletter_signup_pos = rand($wp_query->post_count - 7, $wp_query->post_count - 1);
?>

<section id="post-grid" class="clearfix">
<?php if(get_previous_posts_link()) {?>
<div class="col-md-4 col-lg-3 col-sm-6">
<?php previous_posts_link( '<div class="media-box posts-nav">Nyare artiklar</div>' );?>
</div>
<?php } ?>

<?php while($wp_query->have_posts()) :
	$wp_query->the_post();
?>
<?php 
//about text about spason. Random position
if($i == $about_pos && $show_about):?>
	<div class="col-md-4 col-lg-3 col-sm-6">
		<a href="<?php echo esc_url( home_url( '/om-spason' )) ?>">
			<div class="media-box about-spason">
				<h2>Vad är Spason?</h2>
				<p class="big">Här kan du hitta inspiration om allt inom spavärlden. Du kan också enkelt hitta spahotell och boka din spavistelse.</p>
			</div>
		</a>
	</div>
<?php endif; // $i == ... ?>

<?php 

//about 
if($i == $newsletter_signup_pos && $show_newsletter_signup):?>
	<div class="col-md-4 col-lg-3 col-sm-6">
		<div class="media-box newsletter-signup">
			<?php get_template_part("newsletter-signup");?>
		</div>
	</div>
<?php endif; // $i == ... ?>




<?php if(has_category( 'Quotes', $post )|| has_category( 'Citat', $post )): ?>
	<div class="col-md-4 col-lg-3 col-sm-6">
		<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(500,500)); ?>
		<div class="quote media-box" style="background-image: url('<?php echo $large_image_url[0] ?>');"></div>
	</div>
<?php else: ?>
	<?php echo get_template_part("content", "postgrid-item") ?>
<?php endif; //has category?>

	<?php
	 $i++; 
	endwhile;
	?>
	<?php if(get_next_posts_link()) { ?>
	<div class="col-md-4 col-lg-3 col-sm-6">
	<?php next_posts_link( '<div class="media-box posts-nav">Äldre artiklar</div>' );?>
	</div>
	<?php } ?>
</section>

