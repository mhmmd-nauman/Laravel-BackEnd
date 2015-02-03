<?php 
update_popularity_messures();
get_header('magazine'); ?>
<div class="container content">
<div class="col-xs-12">
	<section id="single-post" class="clearfix">
	<?php
		while(have_posts()) :
				the_post();
	?>
				<div id="post" class="col-md-9">
				<?php the_post_next_prev() ?>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
					<?php
						wp_link_pages(array(
							'before'           => '<p class="tr post-pages">' . __('Pages:', 'fluxipress'),
							'after'            => '</p>',
							'nextpagelink'     => __('Next page', 'fluxipress'),
							'previouspagelink' => __('Previous page', 'fluxipress'),
						));
					?>
					<?php the_tags('<ul class="tags"><li>', '</li><li>', '</li></ul>'); ?>
					<fb:like data-layout="standard" data-href="<?php the_permalink() ?>" data-action="like" data-show-faces="true" data-share="true"></fb:like>
					<fb:like-count></fb:like-count>
					<div class="author-meta clearfix">
						<img class="avatar" src="<?php echo get_gravatar( get_the_author_meta( 'user_email' ), 128 ); ?>"/>
						<div class="description">
							<h4><?php the_author_meta('display_name'); ?></h4>
							<p><?php the_author_meta('description'); ?><br>
							<a href="<?php the_author_meta('user_url'); ?>" target="_blank"><?php the_author_meta('user_url'); ?></a></p>
						</div>	
					</div>
					<p class="updated small">Uppdaterad <?php the_modified_date(null , null, null,true); ?></p>
					 
					 <?php get_template_part('content', 'relatedposts'); ?>
					
					
					<h1>Kommentarer</h1>
					<fb:comments data-href="<?php the_permalink() ?>" ></fb:comments> 
					<?php the_post_next_prev() ?>
				</div>
				
	<?php endwhile; ?>

	<?php get_sidebar('single'); ?>
	</section>
</div>
</div>
<?php get_footer(); ?>