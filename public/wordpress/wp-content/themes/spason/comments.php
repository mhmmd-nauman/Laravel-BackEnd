<div id="comments">

<?php if ( post_password_required() ) : ?>
	<p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'fluxipress' ); ?></p>
</div><!-- #comments -->
<?php return; endif; ?>



<?php if(have_comments()) : ?>
	<h2><?php 
		echo get_comments_number() . ' ';
		_e(get_comments_number() > 1 ? 'Comments' : 'Comment', 'fluxipress');
	?></h2>
	
	<?php
	$comments = get_comments(
		array(
			'order' => 'ASC',
			'post_id' => get_the_ID(),
			'status' => 'approve'
		)
	);
		
	wp_list_comments(
		array(
			'style'		=> 'div'
		),
		$comments
	);
?>
	<p class="tr"><?php paginate_comments_links(); ?></p>
<?php

else : /* or, if we don't have comments */

	if(!comments_open()) : ?><p class="nocomments"><?php _e('Comments are closed.', 'fluxipress'); ?></p><?php endif; ?>

<?php endif; // end have_comments() ?>

<?php if (comments_open()) : ?>
	<div id="respond">
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', 'your-theme'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>
	<?php else : ?>
		<?php comment_form(); ?>
		<?php endif; /* if ( get_option('comment_registration') && !$user_ID ) */ ?>
	</div><!-- #respond -->
<?php endif; /* if ( comments_open() ) */ ?>


</div><!-- #comments -->
