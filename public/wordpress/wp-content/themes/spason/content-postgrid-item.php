<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(500,500)); ?>
<div class="col-md-4 col-lg-3 col-sm-6">
	<a href="<?php the_permalink() ?>" class="post-link">
	<div class="media-box" >
		<div class="image-container" style="background-image: url('<?php echo $large_image_url[0] ?>');">
		</div>
		<div class="info">
			<p class="category"><?php echo(get_the_category()[0]->name)?></p>

			<h2><?php the_title(); ?></h2>
		</div>
	</div>
	</a>
</div>