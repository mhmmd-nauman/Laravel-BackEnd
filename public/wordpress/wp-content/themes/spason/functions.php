<?php

add_theme_support( 'post-thumbnails' ); 
show_admin_bar(false);

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
        return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }
add_filter('language_attributes', 'add_opengraph_doctype');

function create_post_type() {
    register_post_type( 'newsitem',
        array(
            'labels' => array(
                'name' => __( 'Nyheter' ),
                'singular_name' => __( 'Nyhet' )
            ),
        'public' => true,
        'has_archive' => true,
        )
    );
    register_post_type( 'promoitem',
        array(
            'labels' => array(
                'name' => __( 'Promotions' ),
                'singular_name' => __( 'Promotion' )
            ),
        'supports' => array('title', 'thumbnail' ),
        'public' => false,
        'show_ui' => true,
        'has_archive' => false,
        )
    );
}
add_action( 'init', 'create_post_type' );


/**
* Registers the sidebar(s).
*/

register_sidebar(
	array(
		'name'			=>	'Single Post',
		'id'			=>	'sidebar-single',
		'before_widget'	=>	'<div class="widget">',
		'after_widget'	=>	'</div>'
	)
);


/**
* Registers the primary menu.
*/
function register_menus() {
  register_nav_menu('main-menu',__( 'Huvudmeny' ));
  register_nav_menu('footer-menu',__( 'Sidfot' ));
  register_nav_menu('more-menu',__( 'Mer' ));
  register_nav_menu('home-secondary-menu',__('Undermeny - Hem'));
}
add_action( 'init', 'register_menus' );


function the_post_next_prev() {
    global $post;
    echo "<div class='post-next-prev hidden-xs'>";
    echo '<div class="pull-left">';
	previous_post('<span class="elegant-icon">&#x34;</span> %', '', 'yes'); 
	echo '</div><div class="pull-right">';
	next_post('% <span class="elegant-icon">&#x35;</span>', '', 'yes'); 
	echo "</div></div>";
}

function the_breadcrumb() {
    global $post;
    echo '<ul>';
    echo "<li><a href='http://spason.se' class='elegant-icon'>&#xe009;</a></li>";
    echo '<li class="separator"> / </li>';
    if(is_home()){
    	echo '<li><strong>Magasin</strong></li>';
    }elseif (is_page()) {
        if($post->post_parent){
            $anc = get_post_ancestors( $post->ID );
            $title = get_the_title();
            foreach ( $anc as $ancestor ) {
                $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">/</li>';
            }
            echo $output;
            echo '<strong title="'.$title.'"> '.$title.'</strong>';
        } else {
            echo '<li><strong> '.get_the_title().'</strong></li>';
        }
    }
    elseif (is_category()) {
        echo '<li><a href="'.get_option('home').'">Magasin</a></li><li class="separator"> / </li>';
        echo "<li><strong>".single_cat_title('', false)."</strong></li>";
    }
    else {
        echo '<li><a href="';
        echo get_bloginfo('url')."/magasin";
        echo '">';
        echo 'Magasin';
        echo '</a></li>';
        if (is_single()) {
            echo '<li class="separator"> / </li>';
            echo '<li>';
            the_category(', ');
            echo '</li><li class="separator"> / </li><li><strong>';
            $title = get_the_title();
            $max_length = 25;
            echo (strlen($title) > $max_length) ? substr($title, 0, $max_length) . "..." : $title;
            echo '</strong></li>';
        }
    }
    echo '</ul>';   
}

function facebook_count($url){
    // Query in FQL
    $fql  = "SELECT share_count, like_count, comment_count ";
    $fql .= " FROM link_stat WHERE url = '$url'";
    $fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);
    // Facebook Response is in JSON
    $response = file_get_contents($fqlURL);
    return json_decode($response);
}

function update_popularity_messures() {
    global $post;
    $currentViews = ( (int)get_post_meta( $post->ID, 'views', true ) + 1 );
    update_post_meta( $post->ID, 'views', $currentViews );

    //fb messures
    $fb = facebook_count(get_permalink()); 
    $like_count = $fb[0]->like_count > 0 ? $fb[0]->like_count : 0;
    $share_count = $fb[0]->share_count > 0 ? $fb[0]->share_count : 0;
    $comment_count = $fb[0]->comment_count > 0 ? $fb[0]->comment_count : 0;

    update_post_meta( $post->ID, 'fb_likes', $like_count );
    update_post_meta( $post->ID, 'fb_shares', $share_count );
    update_post_meta( $post->ID, 'fb_comments', $comment_count );
    $popularity = ($like_count + $share_count + $comment_count)*3 + $currentViews;
    update_post_meta( $post->ID, 'popularity', $popularity );
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 120, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

?>