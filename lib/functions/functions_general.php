<?php
/**
 * Functions
 *
 * This file contains general functions
 *
 * @author    COLLIDA
 * @copyright 2020 COLLIDA
 * @version   1.0
 */

define( 'THEME_DIRECTORY', get_template_directory() );

/*
Body classes
- add more classes to the body to enable more specific targeting if needed
*/
function setup_body_class($classes) {$post_name_prefix = 'postname-';$page_name_prefix = 'pagename-';$single_term_prefix = 'single-';$single_parent_prefix = 'parent-';$category_parent_prefix = 'parent-category-';$term_parent_prefix = 'parent-term-';$site_prefix = 'site-';global $wp_query;if ( is_single() ) {$wp_query->post = $wp_query->posts[0];setup_postdata($wp_query->post);$classes[] = $post_name_prefix . $wp_query->post->post_name;$taxonomies = array_filter( get_post_taxonomies($wp_query->post->ID), "is_taxonomy_hierarchical" );foreach ( $taxonomies as $taxonomy ) {$tax_name = ( $taxonomy != 'category') ? $taxonomy . '-' : '';$terms = get_the_terms($wp_query->post->ID, $taxonomy);if ( $terms ) {foreach( $terms as $term ) {if ( !empty($term->slug ) )$classes[] = $single_term_prefix . $tax_name . sanitize_html_class($term->slug, $term->term_id);while ( $term->parent ) {$term = &get_term( (int) $term->parent, $taxonomy );if ( !empty( $term->slug ) )$classes[] = $single_parent_prefix . $tax_name . sanitize_html_class($term->slug, $term->term_id);}}}}} elseif ( is_archive() ) {if ( is_category() ) {$cat = $wp_query->get_queried_object();while ( $cat->parent ) {$cat = &get_category( (int) $cat->parent);if ( !empty( $cat->slug ) )$classes[] = $category_parent_prefix . sanitize_html_class($cat->slug, $cat->cat_ID);}} elseif ( is_tax() ) {$term = $wp_query->get_queried_object();while ( $term->parent ) {$term = &get_term( (int) $term->parent, $term->taxonomy );if ( !empty( $term->slug ) )$classes[] = $term_parent_prefix . sanitize_html_class($term->slug, $term->term_id);}}} elseif ( is_page() ) {$wp_query->post = $wp_query->posts[0];setup_postdata($wp_query->post);$classes[] = $page_name_prefix . $wp_query->post->post_name;}if ( is_multisite() ) {global $blog_id;$classes[] = $site_prefix . $blog_id;}return $classes;} add_filter('body_class', 'setup_body_class');

/*
Disable the theme editor
- stop clients from breaking their website
*/
define('DISALLOW_FILE_EDIT', true);

/*
Remove version info
- makes it that little bit harder for hackers
*/
function complete_version_removal() {
    return '';
}
add_filter('the_generator', 'complete_version_removal');

/*
Remove info from headers
- remove the stuff we don't need
*/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/*
Excerpt
- this theme supports excerpts
*/
add_post_type_support( 'page', 'excerpt' );

function new_excerpt_more($more) {
     global $post;
	 return '...';
}

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'...';
  } else {
	$excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

/*
Thumbnails
- this theme supports thumbnails
*/
add_theme_support( 'post-thumbnails' );
add_image_size ( 'full', 3000, 3000, true );
add_image_size ( 'shop-thumbnail', 636, 480, true );
add_image_size ( 'shop-full', 600, 610, true );
add_image_size ( 'blog-thumbnail', 450, 250, true );
add_image_size ( 'blog-thumbnail-big', 600, 350, true );
add_image_size ( 'uses-thumbnail-big', 745, 390, true );
add_image_size ( 'uses-thumbnail', 490, 390, true );
add_image_size ( 'card-thumbnail', 420, 470, true );
add_image_size ( 'benefits-thumbnail', 380, 380, true );
add_image_size ( 'side-image', 360, 333, true );


/*
Scripts & Styles
- include the scripts and stylesheets
*/
add_action( 'wp_enqueue_scripts', 'script_enqueues' );

add_filter('gform_init_scripts_footer', function() {
	return true;
});

function show_template() {
    if( is_super_admin() ){
        global $template;
        print_r($template);
    }
}
//add_action('wp_footer', 'show_template');

function script_enqueues() {

    global $wp_query;

    if ( wp_script_is( 'jquery', 'registered' ) ) {

		wp_deregister_script( 'jquery' );

	}

	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), '3.5.1', false );
	wp_enqueue_script( 'jquery-easing', '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js', array(), '1.4.1', false );

	wp_enqueue_script( 'custom', get_template_directory_uri() . '/build/js/main.js', array(), '1.0.0', false );
	wp_enqueue_style( 'style', get_template_directory_uri() . '/build/css/main.css', false, '1.0.0', 'all' );
    wp_localize_script( 'custom', 'loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ) );
}


/*
Admin Bar
- hide the admin bar
*/
add_filter('show_admin_bar', '__return_false');

/*
Menus
- enable WordPress Menus
*/
if (function_exists('register_nav_menus')){
	register_nav_menus(array(
		'header' => __( 'Main Nav' ),
		'footer' => __( 'Footer Nav' ),
		'top' => __( 'Top Nav' ),
		'social' => __( 'Social Nav' ),
		)
	);
}

/*
Menu Classes
- add first and last to menu items
*/
function wpdev_first_and_last_menu_class($items) {
	$items[1]->classes[] = 'first';
	$items[count($items)]->classes[] = 'last';
	return $items;
}
add_filter('wp_nav_menu_objects', 'wpdev_first_and_last_menu_class');

/*
Admin Menus
- hide unused menu items
*/
function remove_menus(){
  
  remove_menu_page( 'edit-comments.php' );
  
}
add_action( 'admin_menu', 'remove_menus' );

/**
 *  Pretty printer for data
 */
function print_data($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * Debug File
 */

function debug_file($filename, $data){
    $myfile = fopen(get_template_directory()."/_$filename.log", "w") or die("Unable to open file!");
    fwrite($myfile, "<pre>".print_r($data, true));
    fclose($myfile);
}

/**
 * Remove Gberg block library (not used)
 */
function remove_block_css(){
	wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );


// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
        return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];

}, 10, 4 );

function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

//function fix_svg() {
//    echo '<style type="text/css">
//        .attachment-266x266, .thumbnail img {
//             width: 100% !important;
//             height: auto !important;
//        }
//        </style>';
//}
//add_action( 'admin_head', 'fix_svg' );

add_post_type_support( 'page', 'excerpt' );

// Breadcrumbs
function custom_breadcrumbs() {

    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Homepage';

    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';

    // Get the query & post information
    global $post, $wp_query;

    // Do not display on the homepage
    if ( !is_front_page() ) {

        echo '<div class="container"><div class="row"><div class="col-12">';

        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';

        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

            echo '<li class="item-current item-archive"><span class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</span></li>';

        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><span class="bread-current bread-archive">' . $custom_tax_name . '</span></li>';

        } else if ( is_single() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            // Get post category info
            $category = get_the_category();

            if(!empty($category)) {

                // Get last category post is in
                $last_category = end(array_values($category));

                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }

            }

            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;

            }

            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';

                // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {

                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';

            } else {

                echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';

            }

        } else if ( is_category() ) {

            // Category page
            echo '<li class="item-current item-cat"><span class="bread-current bread-cat">' . single_cat_title('', false) . '</span></li>';

        } else if ( is_page() ) {

            // Standard page
            if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></li>';

            } else {

                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</span></li>';

            }

        } else if ( is_tag() ) {

            // Tag page

            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;

            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><span class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</span></li>';

        } elseif ( is_day() ) {

            // Day archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><span class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></li>';

        } else if ( is_month() ) {

            // Month Archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><span class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</span></li>';

        } else if ( is_year() ) {

            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><span class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</span></li>';

        } else if ( is_author() ) {

            // Auhor archive

            // Get the author information
            global $author;
            $userdata = get_userdata( $author );

            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><span class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</span></li>';

        } else if ( get_query_var('paged') ) {

            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><span class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</span></li>';

        } else if ( is_search() ) {

            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><span class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</span></li>';

        } elseif ( is_404() ) {

            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }

        echo '</ul>';

        echo '</div></div></div>';

    }

}

function get_breadcrumbs(){
    global $wp_query, $post, $product;

    $pre_text  = 'Home';
    echo '<div class="breadcrumbs">';
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<ul id="breadcrumblist" itemscope itemtype="http://schema.org/BreadcrumbList">';
    echo '<li class="pre-text"><a href="'.home_url('/').'">' . $pre_text . '</a>: </li>';
    if(is_single()){


        if(is_product()){
            $parent_page = home_url('/') . 'products/';
            $pagename = 'Products';
        }elseif(is_singular('post')){
            $parent_page = home_url('/') . 'blog/';
            $pagename = 'Blog';
        }else{
            $parent_page = home_url('/') . 'uses/';
            $pagename = 'Uses';
        }
        echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. $parent_page .'"><span itemprop="name">'. $pagename .'</span>/</a><meta itemprop="position" content="1" /></li>';
        echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" class="current-page">'. $post->post_title .'</span><meta itemprop="position" content="2" /></li>';


    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

}

/**
 * @param $classes
 */

function social_icons($classes) {

    $social_icons = get_field('social_links', 'options');

    echo '<ul class="' . esc_attr($classes) . '">';

        foreach ($social_icons as $icon){
            echo '<li>';
            echo '<a class="social-icons__icon" href="'. $icon['url'] .'" target="_blank" rel="noreferrer"><i class="fab fa-' . $icon['icon'] . '"></i></a>';
            echo '</li>';
        }

    echo '</ul>';

}

function inline_svg($svg){
    switch ($svg) {
        case 'account':
            echo ' <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <g id="Group_46" data-name="Group 46" transform="translate(-1161.778 -18.25)">
                        <path id="Path_42" data-name="Path 42" d="M1177.59,35.445a3.732,3.732,0,0,0-1.315-.509c-2.493-.477-2.5-1.25-2.5-1.75v-.3a4.56,4.56,0,0,0,1.3-2.2l.014,0c.762,0,.939-1.582.939-1.819s.02-1.182-.75-1.182c1.657-4.5-2.734-6.307-6.032-4-1.36,0-1.47,2-.97,4-.769,0-.739.946-.739,1.182s.177,1.819.939,1.819h0a4.562,4.562,0,0,0,1.3,2.192v.3c0,.5,0,1.3-2.5,1.75a3.679,3.679,0,0,0-1.322.5" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5"/>
                        <circle id="Ellipse_1" data-name="Ellipse 1" cx="9.25" cy="9.25" r="9.25" transform="translate(1162.528 19)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5"/>
                    </g>
                </svg>';
        break;
        case 'basket':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="21.5" height="18.476" viewBox="0 0 21.5 18.476">
                  <g id="Group_45" data-name="Group 45" transform="translate(-1300.25 -18.274)">
                    <path id="Path_41" data-name="Path 41" d="M1302.355,26l1.984,10h13.327l2-10" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <line id="Line_4" data-name="Line 4" x1="5.978" y2="6.667" transform="translate(1303.022 19.333)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5"/>
                    <line id="Line_5" data-name="Line 5" x2="6" y2="6.667" transform="translate(1313 19.333)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5"/>
                    <line id="Line_6" data-name="Line 6" x2="20" transform="translate(1301 26)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                  </g>
                </svg>';
        break;
        case 'mobile':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="14.866" height="25.037" viewBox="0 0 14.866 25.037">
                  <path id="Path_49" data-name="Path 49" d="M1234.568,104.472H1224.4a2.351,2.351,0,0,0-2.348,2.347v20.343a2.351,2.351,0,0,0,2.348,2.347h10.171a2.349,2.349,0,0,0,2.347-2.347V106.819A2.349,2.349,0,0,0,1234.568,104.472Zm-4.255,23.129a1.183,1.183,0,0,1-.825.343h-.01a1.172,1.172,0,0,1-.824-2,1.211,1.211,0,0,1,1.659,0,1.172,1.172,0,0,1,0,1.659Zm5.037-3.569h-11.736V107.6h11.736Z" transform="translate(-1222.049 -104.472)" fill="#90d5ac"/>
                </svg>';
        break;
        case 'search':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="25.622" height="25.621" viewBox="0 0 25.622 25.621">
                  <g id="Group_52" data-name="Group 52" transform="translate(-1124.398 -104.56)">
                    <g id="Group_51" data-name="Group 51">
                      <circle id="Ellipse_2" data-name="Ellipse 2" cx="8.899" cy="8.899" r="8.899" transform="translate(1125.898 106.06)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
                      <line id="Line_7" data-name="Line 7" x2="6.795" y2="6.795" transform="translate(1141.104 121.266)" fill="none" stroke="#90d5ac" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
                    </g>
                  </g>
                </svg>';
        break;
        case 'burger':
            echo ' <svg class="burger-btn" width="20" height="20" viewBox="0 0 40 26" xmlns="http://www.w3.org/2000/svg" fill="#185850">
                    <rect class="burger-btn--1" width="40" height="6" rx="3" ry="3" />
                    <rect class="burger-btn--2" width="40" height="6" y="10" rx="3" ry="3" />
                    <rect class="burger-btn--3" width="40" height="6" y="20" rx="3" ry="3" />
                </svg>';
        break;
        case 'email-big':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="36.428" height="37.07" viewBox="0 0 36.428 37.07">
                  <g id="Group_1336" data-name="Group 1336" transform="translate(-85.893 -2440.995)">
                    <path id="Path_1529" data-name="Path 1529" d="M85.893,2458.31v17.449l10.217-10.273Z" fill="#fff"/>
                    <path id="Path_1530" data-name="Path 1530" d="M112.114,2465.5l10.206,10.265v-17.28Z" fill="#fff"/>
                    <path id="Path_1531" data-name="Path 1531" d="M110.364,2463.187l11.093-7.624L104.107,2441l-17.234,14.47,10.984,7.715C100.748,2460.462,107.485,2460.465,110.364,2463.187Z" fill="#fff"/>
                    <path id="Path_1532" data-name="Path 1532" d="M99.457,2466.131l-11.87,11.934h33.034l-11.868-11.937C106.843,2464.219,101.37,2464.219,99.457,2466.131Z" fill="#fff"/>
                  </g>
                </svg>';
        break;
        case 'mobile-big':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="27.292" height="45.965" viewBox="0 0 27.292 45.965">
                  <path id="Path_1528" data-name="Path 1528" d="M110.121,2363.393H91.448a4.314,4.314,0,0,0-4.309,4.309v37.347a4.314,4.314,0,0,0,4.309,4.309h18.673a4.313,4.313,0,0,0,4.31-4.309V2367.7A4.313,4.313,0,0,0,110.121,2363.393Zm-7.812,42.461a2.17,2.17,0,0,1-1.513.632h-.02a2.152,2.152,0,0,1-1.512-3.676,2.219,2.219,0,0,1,3.045,0,2.149,2.149,0,0,1,0,3.044Zm9.249-6.551H90.011v-30.165h21.547Z" transform="translate(-87.139 -2363.393)" fill="#fff"/>
                </svg>';
        break;
        case 'calendar':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="29.129" height="28.924" viewBox="0 0 29.129 28.924">
                  <g id="Group_643" data-name="Group 643" transform="translate(-1075.137 -859.155)">
                    <g id="Group_628" data-name="Group 628">
                      <rect id="Rectangle_163" data-name="Rectangle 163" width="2.284" height="2.284" rx="0.38" transform="translate(1080.659 871.494)" fill="#4ca585"/>
                    </g>
                    <g id="Group_629" data-name="Group 629">
                      <rect id="Rectangle_164" data-name="Rectangle 164" width="2.284" height="2.284" rx="0.38" transform="translate(1085.926 871.494)" fill="#4ca585"/>
                    </g>
                    <g id="Group_630" data-name="Group 630">
                      <rect id="Rectangle_165" data-name="Rectangle 165" width="2.284" height="2.284" rx="0.38" transform="translate(1091.193 871.494)" fill="#4ca585"/>
                    </g>
                    <g id="Group_631" data-name="Group 631">
                      <rect id="Rectangle_166" data-name="Rectangle 166" width="2.284" height="2.284" rx="0.38" transform="translate(1096.459 871.494)" fill="#4ca585"/>
                    </g>
                    <g id="Group_632" data-name="Group 632">
                      <rect id="Rectangle_167" data-name="Rectangle 167" width="2.284" height="2.284" rx="0.38" transform="translate(1080.659 876.553)" fill="#4ca585"/>
                    </g>
                    <g id="Group_633" data-name="Group 633">
                      <rect id="Rectangle_168" data-name="Rectangle 168" width="2.284" height="2.284" rx="0.38" transform="translate(1085.926 876.553)" fill="#4ca585"/>
                    </g>
                    <g id="Group_634" data-name="Group 634">
                      <rect id="Rectangle_169" data-name="Rectangle 169" width="2.284" height="2.284" rx="0.38" transform="translate(1091.193 876.553)" fill="#4ca585"/>
                    </g>
                    <g id="Group_635" data-name="Group 635">
                      <rect id="Rectangle_170" data-name="Rectangle 170" width="2.284" height="2.284" rx="0.38" transform="translate(1096.459 876.553)" fill="#4ca585"/>
                    </g>
                    <g id="Group_636" data-name="Group 636">
                      <rect id="Rectangle_171" data-name="Rectangle 171" width="2.284" height="2.284" rx="0.38" transform="translate(1080.659 881.612)" fill="#4ca585"/>
                    </g>
                    <g id="Group_637" data-name="Group 637">
                      <rect id="Rectangle_172" data-name="Rectangle 172" width="2.284" height="2.284" rx="0.38" transform="translate(1085.926 881.612)" fill="#4ca585"/>
                    </g>
                    <g id="Group_638" data-name="Group 638">
                      <rect id="Rectangle_173" data-name="Rectangle 173" width="2.284" height="2.284" rx="0.38" transform="translate(1091.193 881.612)" fill="#4ca585"/>
                    </g>
                    <g id="Group_639" data-name="Group 639">
                      <rect id="Rectangle_174" data-name="Rectangle 174" width="9.945" height="1.705" transform="translate(1084.728 861.737)" fill="#4ca585"/>
                    </g>
                    <g id="Group_640" data-name="Group 640">
                      <path id="Path_961" data-name="Path 961" d="M1102.733,861.737h-3.427v1.705h3.254v4.075h-25.718v-4.075h3.255v-1.705h-3.428a1.533,1.533,0,0,0-1.532,1.532v23.278a1.533,1.533,0,0,0,1.532,1.532h26.064a1.534,1.534,0,0,0,1.533-1.532V863.269A1.534,1.534,0,0,0,1102.733,861.737Zm-25.891,24.637V869.223h25.718v17.151Z" fill="#4ca585"/>
                    </g>
                    <g id="Group_641" data-name="Group 641">
                      <path id="Path_962" data-name="Path 962" d="M1082.413,865.721a.989.989,0,0,0,.989-.989v-4.588a.99.99,0,0,0-1.979,0v4.588A.989.989,0,0,0,1082.413,865.721Z" fill="#4ca585"/>
                    </g>
                    <g id="Group_642" data-name="Group 642">
                      <path id="Path_963" data-name="Path 963" d="M1096.99,865.721a.989.989,0,0,0,.989-.989v-4.588a.99.99,0,0,0-1.979,0v4.588A.989.989,0,0,0,1096.99,865.721Z" fill="#4ca585"/>
                    </g>
                  </g>
                </svg>';
        break;
        case 'mobile-xxl':
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="27.292" height="45.966" viewBox="0 0 27.292 45.966">
                  <path id="Path_157" data-name="Path 157" d="M513.68,4553H495.006a4.315,4.315,0,0,0-4.309,4.309v37.347a4.315,4.315,0,0,0,4.309,4.31H513.68a4.313,4.313,0,0,0,4.309-4.31v-37.347A4.313,4.313,0,0,0,513.68,4553Zm-7.812,42.461a2.171,2.171,0,0,1-1.514.632h-.019a2.152,2.152,0,0,1-1.513-3.676,2.222,2.222,0,0,1,3.046,0,2.151,2.151,0,0,1,0,3.044Zm9.248-6.55H493.57v-30.165h21.546Z" transform="translate(-490.697 -4552.996)" fill="#90d5ac"/>
                </svg>';
        break;
    }
}

/**
 * Returns an image using wp_get_attachment_image().
 * Parameters accept an ACF image array, or an image ID.
 * Can also supply an array of classes:
 * e.g: ['class_1', 'class_2']
 */
function acf_image($image = null, $size = 'full', $classes = []) {
    if (!$image) return;

    if (!$size) $size = 'full';

    $attachment = false;

    if (is_numeric($image)) {
        $attachment = $image;
    }

    if (is_array($image) && isset($image['ID'])) {
        $attachment = $image['ID'];
    }

    if (!$attachment) return;

    return wp_get_attachment_image($attachment, $size, false, ['class' => implode(' ', $classes)]);
}

add_action('wp_ajax_loadmore_posts', 'loadmore_posts_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore_posts', 'loadmore_posts_ajax_handler'); // wp_ajax_nopriv_{action}

function loadmore_posts_ajax_handler(){


    $args = json_decode( stripslashes( $_POST['query'] ), true );
    $args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
    $args['post_status'] = 'publish';

    // it is always better to use WP_Query but not here
    query_posts( $args );

    if( have_posts() ) :
        echo '<div class="row">';
            // run the loop
            $i=1; while( have_posts() ): the_post(); ?>

                <?php if ($i == 1 || $i == 2 || $i == 6 || $i == 7 ) {
                            $size = '6';
                        }else{
                            $size = '4';
                        }
                        $args = array(
                            $size
                        )
                ?>
                    <div class="col-md-<?php echo $size;?>" <?php echo $i;?>>
                        <?php get_template_part('lib/partials/blog', 'post', $args);?>
                    </div>
                <?php
                    if($i == 8){
                        $i = 1;
                    }else{
                        $i++;
                    }

            endwhile;
        echo '</div>';

    endif;
    die; // here we exit the script and even no wp_reset_query() required!

    wp_die();
}

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );
function form_submit_button( $button, $form ) {
    return "<button class='button gform_button' id='gform_submit_button_{$form['id']}'><span>{$form['button']['text']}</span></button>";
}

add_filter( 'gform_required_legend', __return_false(), 10, 2 );

function get_social_share(){
    global $post;
    $title = str_replace( ' ', '%20', $post->post_title);
    $url = urlencode(get_permalink($post->ID));

    $twitterURL = 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.get_permalink($post->ID).'&amp;via=wpvkp';
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$url;
    $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$url.'&amp;title='.$title;


    ?>

    <ul class="social-share">
        <li class="social-share__title">Share</li>
        <li><a href="<?php echo $facebookURL;?>"><i class="fab fa-facebook-f"></i></a></li>
        <li><a href="<?php echo $twitterURL;?>"><i class="fab fa-twitter"></i></a></li>
        <li><a href="<?php echo $linkedInURL;?>"><i class="fab fa-linkedin-in"></i></a></li>
        <li><a href="mailto:?subject=&amp;body=:%20"><i class="fas fa-envelope"></i></a></li>
    </ul>

    <?php


}

function faqs_ld_json(){
    global $post;
    if(is_page('faqs')){



        ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "sf",
                        "acceptedAnswer": { "@type": "Answer", "text": "dsfdsg" }
                    },
                    {
                        "@type": "Question",
                        "name": "dsgds",
                        "acceptedAnswer": { "@type": "Answer", "text": "sdgfsg" }
                    }
                ]
            }
        </script>
    <?php
    }
}

//add_action('wp_head', 'faqs_ld_json');

function disable_gutenberg($is_enabled, $post_type) {

    if ($post_type === 'uses') return false; // change book to your post type

    return $is_enabled;

}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg', 10, 2);
