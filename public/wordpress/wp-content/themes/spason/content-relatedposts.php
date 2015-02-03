<div class="related-posts row">
<?php
	$orig_post = $post;
	$tags = wp_get_post_tags($post->ID);
	
	if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
	'tag__in' => $tag_ids,
	'post__not_in' => array($post->ID),
	'posts_per_page'=>4, // Number of related posts to display.
	'caller_get_posts'=>1
	);
	
	$my_query = new wp_query( $args );

	if($my_query->post_count>0) :
	?>
		<h3 class="text-center">Relaterade artiklar</h3>
	<?php
	endif; //$my_query->post_count>0
	while( $my_query->have_posts() ) {
	$my_query->the_post();
	?>
	
	<div class="col-md-6">
		<div class="related-post clearfix">
		<a rel="external" href="<?php the_permalink()?>">
			<?php the_post_thumbnail(array(150,100)); ?><br />
			<h4><?php the_title(); ?></h4>
		</a>
		</div>
	</div>
	
	<?php }
	}
	$post = $orig_post;
	wp_reset_query();
	?>
</div>