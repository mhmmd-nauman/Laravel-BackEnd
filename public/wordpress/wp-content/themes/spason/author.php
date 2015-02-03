<?php
	get_header();
	
	if(have_posts()) :
	
		the_post();
		
		$authorDisplayName = get_the_author_meta('display_name');
		$authorDescription = get_the_author_meta('description');
		$authorLink = get_the_author_meta('user_url');
?>
		<div id="post">
			<div class="post-content post-list-content">
				<h1><?php echo $authorDisplayName; ?></h1>
				<?php if(!empty($authorDescription)) : ?><p><?php echo $authorDescription; ?></p><?php endif; ?>
				<?php if(!empty($authorLink)) : ?><a href="<?php echo $authorLink; ?>" title="<?php echo htmlspecialchars($authorDisplayName); ?>' Website"><?php echo $authorLink; ?></a><?php endif; ?>
			</div>
		</div>
<?php
		rewind_posts();
		
		get_template_part('content', 'postgrid');
		
	else :
	
		get_template_part('content', 'none');
	
	endif;
	
	get_footer();
?>