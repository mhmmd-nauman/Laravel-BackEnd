<?php
/*
 * Template Name: Home
 * Description: The main landing page for the site.
 */
	get_header();
	$args = array( 
		'post_type' => 'post', 
		'posts_per_page' => 8,
		'order'     => 'ASC',
        'meta_key' => 'popularity',
        'orderby'   => 'meta_value',
		);
	$magazine_query = new WP_Query( $args );

	$args = array( 
		'post_type' => 'promoitem', 
		'posts_per_page' => 3,
		);
	$promo_query = new WP_Query( $args );
	
?>
<?php while(have_posts()): the_post();
	global $post;
	$value_props = (get_post_meta($post->ID, "value_proposition"));
	$description_heading = get_post_meta($post->ID, "description_heading")[0];
	$description_content = get_post_meta($post->ID, "description_content")[0];
	$description_more = get_post_meta($post->ID, "description_more")[0];

	$magazine_title = get_post_meta($post->ID, "magazine_title")[0];
	$magazine_description = get_post_meta($post->ID, "magazine_description")[0];

	$hotel_promos = (get_post_meta($post->ID, "hotel_promo"));
	$hotels_promo_title = get_post_meta($post->ID, "hotels_promo_title")[0];
?>
<div id="main-banner" class="home" style="background-image: url('<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full')[0]; ?>')">
	<div class="container">
		<h1><?php the_title();?></h1>
		<h2><?php echo strip_tags(get_the_content());?></h2>
		<a class="btn btn-outline btn-lg" id="call-to-action-1" href="<?php echo get_bloginfo('url')."/spahotell" ?>">Börja söka spahotell</a>
	</div>
	
</div>
<?php endwhile; ?>

<div class="container">
	<div class="row">
		<div class="spason-description"> 
			<h2 class="text-center"><?php echo $description_heading; ?></h2>
			<h3 class="text-center"><?php echo $description_content; ?></h3>
			<p id="description-more" class="text-center"><?php echo $description_more; ?></p>
			<p class="text-center"><button id="toggle-description-more" class="btn btn-default">Visa mer</button></p>
		</div>
	</div>
	<div class="why-spason row">
			<?php if(is_array($value_props) && count($value_props) > 0){ ?>
		    <ul class="reasons list-unstyled">
		    	<?php 
		    	foreach($value_props as $value_prop){ 
		    		$value_prop = explode(',', $value_prop);
		    		$prop_title = $value_prop[0];
		    		$prop_desc = $value_prop[1];
		    	?>
		        <li class="reason col-md-6">
		            <span class="note"><span class="elegant-icon">&#x52;</span><?php echo $prop_title; ?></span> - <?php echo $prop_desc; ?>
		        </li>
		        <?php } ?>
		    </ul>
		    <?php } ?>
	</div>
</div>
	<div id="promos" class="hidden-xs">
		<div class="container">
			<div class="row">
			<?php 
				//hack to make pagination work:
				$temp = $wp_query;
				$wp_query = null; 
				$wp_query = $promo_query;
				while($wp_query->have_posts()): 
					$wp_query->the_post();
					echo get_template_part('promotion');
				endwhile;
				//reset query
				$wp_query = null;
				$wp_query = $temp;
			?>
			</div>
		</div>
	</div>

	<?php if(count($hotel_promos)>0) {?>
	<div class="container">
		<div class="hotel-promo row">
		<hgroup>
			<h3><?php echo $hotels_promo_title; ?></h3>
		</hgroup>
			<?php 
				foreach($hotel_promos as $hotel_promo){
					$hotel_promo = explode(';', $hotel_promo);
					$title = trim($hotel_promo[0]);
					$desc = trim($hotel_promo[1]);
					$img = trim($hotel_promo[2]);
					$url = trim($hotel_promo[3]);
			?>
			<div class="col-md-4 col-lg-3 col-sm-6">
				<a href="<?php echo $url ?>" class="post-link">
				<div class="media-box" >
					<div class="image-container" style="background-image: url('<?php echo $img ?>');">
					</div>
					<div class="info">
						<h2><?php echo $title ?></h2>
						<p><?php echo $desc ?></p>
					</div>
				</div>
				</a>
			</div>
		<?php
			}
		 ?>
		 </div>
	</div>
	<?php }//if count($hotel_promos)>0?>
</div>
<div class="magazine-promo">
	<div class="container">
	<hgroup class="text-center">
	<h2 class="big"><?php echo $magazine_title; ?></h2>
	<h3><?php echo $magazine_description; ?></h3>
	</hgroup>
<center style="margin-bottom: 20px;">
	<a href="<?php echo get_bloginfo('url')."/magasin/" ?>" class="btn btn-primary">Till Magasinet</a>
	</center>
	<div id="post-grid" class="clearfix">
		<?php 
		//hack to make pagination work:
		$temp = $wp_query;
		$wp_query = null; 
		$wp_query = $magazine_query;
		while($wp_query->have_posts()): $wp_query->the_post();
			if(in_category(array('quotes','citat'))) continue;
			echo get_template_part('content', 'postgrid-item');
		endwhile;
		//reset query
		$wp_query = null;
		$wp_query = $temp;
	?>
		</div>
</div>
</div>

<?php 
	get_footer();
?>