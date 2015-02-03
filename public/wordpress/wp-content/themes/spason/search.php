<?php
	get_header('magazine');
	
	if(have_posts()) :
	
?>
<div class="container content">
	<header id="post-list-header" class="col-xs-12">
			<h1>Visar <?php echo $wp_query->post_count == 1 ? "1 artikel" : $wp_query->post_count . " artiklar"; ?> som matchar "<?php echo get_search_query(); ?>"</h1>
	</header>

<?php
	
		get_template_part('content', 'postgrid');

	else :
	
		get_template_part('content', 'none');
	
	endif;?>
</div>
	<?php 
	
	get_footer();
?>
