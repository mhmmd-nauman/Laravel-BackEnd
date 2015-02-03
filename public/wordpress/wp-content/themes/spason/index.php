<?php
	get_header("magazine");
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array( 'post_type' => 'post', 'posts_per_page' => get_option( 'posts_per_page' ), 'paged' => $paged);
	$magazine_query = new WP_Query( $args );
?>
	<div class="container content">
<?php 
	//hack to make pagination work:
	$temp = $wp_query; 
	$wp_query = null; 
	$wp_query = $magazine_query;
	if(have_posts()) : the_post();
		$query = $articles;
		get_template_part('content', 'postgrid');

	else :
	
		get_template_part('content', 'none');
	
	endif;
	//reset query
	$wp_query = null;
	$wp_query = $temp;
?>
	</div>
<?php
	get_footer();
?>
