<?php 
global $post;
$link = get_post_meta($post->ID, "link")[0];
$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(500,250))[0];
?>
<div class="col">
	<div class="promo" style="background-image: url('<?php echo $large_image_url ?>')">
		<a class="link" href="<?php echo $link?>"><?php echo the_title();?></a>
	</div>
</div>