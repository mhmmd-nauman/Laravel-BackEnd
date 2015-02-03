<?php
	get_header();
	
	if(have_posts()) :
	
?>

		<div id="post">
			<div class="post-content post-list-content">
				<h1><?php printf(__('Tag Archives: %s', 'fluxipress'), '<em>' . single_tag_title('', false) . '</em>'); ?></h1>
			</div>
		</div>

<?php
	
		get_template_part('content', 'postgrid');

	else :
	
		get_template_part('content', 'none');
	
	endif;
	
	get_footer();
?>
