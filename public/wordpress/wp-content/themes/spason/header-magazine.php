<?php get_header(); ?>
<?php 
	$args = array( 
		'post_type' => 'page',
        'post_status' => 'publish',
        'pagename' => 'magasin',
        'posts_per_page' => 1
    );
	$magazine_page = new WP_Query( $args );
		while($magazine_page->have_posts()) { $magazine_page->the_post();
	?>	
<div id="main-banner" style="background-image: url('<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full')[0]; ?>')">

		<div class="site-heading">
			<h1 class="big"><?php echo get_the_title($post->ID);?></h1>
			<h2><?php echo get_the_content($post->ID);?></h2>
		</div>
		
</div>
<?php }
	wp_reset_query();
?>
	<header id="secondary-header">
			<div class="container">
				<div class="hidden-sm hidden-xs col-md-6">
					<div class="nav-breadcrumb">
						<?php the_breadcrumb(); ?>
					</div>
				</div>
				<div class="col-sm-12 col-md-6">
					<nav class="filters">
						<div class="filter <?php if(is_home()) echo "active";?>">
					  		<a href="<?php echo esc_url( home_url( '/magasin' ))?>">Alla</a>
				  		</div>
				  		<div class="dropdown filter hidden-xs <?php if(!empty($cat)) echo "active";?>">
							<a data-toggle="dropdown" href="#">
								<?php 
								$cat = single_cat_title('', false); 
								if(!empty($cat)) {
									echo "Kategori: " . $cat;
								}else{
									echo "Kategori";
								}
								?>
							</a>
							<ul class="dropdown-menu" role="menu">
							<?php wp_list_categories(array(
									'exclude'            => '',
									'hierarchical'       => 1,
									'title_li'           => "",
							)); ?> 
							</ul>
					  	</div>
						<form role="search" method="get" class="filter search form-inline" action="<?php echo home_url( '/' ); ?>">
					  		<div class="input-group input-search">
							  <input type="text" class="form-control" value="<?php echo get_search_query() ?>" name="s" id="s" placeholder="sök">
							</div>
					  	</form>
					  	
					</nav>
				</div>
			</div>
		</header>