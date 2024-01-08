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
	wp_enqueue_style( 'style', get_template_directory_uri() . '/build/css/main.css', false, '1.0.1', 'all' );
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
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 25.622 25.621">
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
        case 'carbon-negative':
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 175.52 191.88"><defs><style>.cls-1{fill:#4ca586;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="HEADER"><path class="cls-1" d="M3,96.69A84.31,84.31,0,0,1,19.91,46l-1.86-2.5a87.58,87.58,0,0,0-6.93,96l2.37-1.93A84.19,84.19,0,0,1,3,96.69Z"/><path class="cls-1" d="M172.52,96.69a84.23,84.23,0,0,1-11.06,41.78l2.29,2.07a87.57,87.57,0,0,0-8.08-99.37l-2.08,2.21A84.38,84.38,0,0,1,172.52,96.69Z"/><path class="cls-1" d="M27.6,32.85a12,12,0,0,0,4,3.54,8.17,8.17,0,0,0,4.62.93,9.26,9.26,0,0,0,4.7-2A8.42,8.42,0,0,0,44,25.19L41.3,26.3A6.09,6.09,0,0,1,41.62,30a5.82,5.82,0,0,1-2.28,3.18,5.94,5.94,0,0,1-3.29,1.33,5.54,5.54,0,0,1-3.21-.81A9.86,9.86,0,0,1,30,31.06a10,10,0,0,1-1.79-3.5,5.64,5.64,0,0,1,.07-3.31,5.93,5.93,0,0,1,2.17-2.8,5.7,5.7,0,0,1,3.67-1.34,6,6,0,0,1,3.52,1.32l1.8-2.33a8.73,8.73,0,0,0-5.18-1.78,8.83,8.83,0,0,0-5.43,2,9.16,9.16,0,0,0-3.16,4,8.16,8.16,0,0,0-.35,4.7A12,12,0,0,0,27.6,32.85Z"/><path class="cls-1" d="M50.33,28.31,53,27.2l-.34-4.57,7.34-3,3,3.49,2.7-1.1L52.56,6.52,48.82,8.05Zm7.89-10.79-5.75,2.36-.75-10Z"/><path class="cls-1" d="M70.13,21l2.78-.38L72,13.78l5.08-.68,4.25,6.43,3.18-.43-4.62-7a4.79,4.79,0,0,0,1.87-1.92,6.68,6.68,0,0,0,.65-4.1,7.33,7.33,0,0,0-.91-2.74A5.13,5.13,0,0,0,78,.9,6.92,6.92,0,0,0,76.79.76a5.39,5.39,0,0,0-.8,0L75.3.85,75,.88l-7.47,1Zm9-15.89a4.59,4.59,0,0,1,.41,1.37,4.36,4.36,0,0,1,0,1.44A3.25,3.25,0,0,1,79,9.3a2.78,2.78,0,0,1-1.27.92,3.62,3.62,0,0,1-.61.17,5.26,5.26,0,0,1-.58.11l-4.86.65-.94-7,4.85-.65.6,0a3.27,3.27,0,0,1,.63,0A2.68,2.68,0,0,1,78.24,4,3.21,3.21,0,0,1,79.15,5.14Z"/><path class="cls-1" d="M89.58,19.19l8.27.94a6.56,6.56,0,0,0,3.19-.35,4.84,4.84,0,0,0,2.18-1.73,6.14,6.14,0,0,0,1-2.87,4.93,4.93,0,0,0-.64-3.17,4.5,4.5,0,0,0-1.8-1.71,3.92,3.92,0,0,0,1.4-1,4.68,4.68,0,0,0,1.09-2.63,5.37,5.37,0,0,0-.34-2.56A5,5,0,0,0,102.28,2a5.23,5.23,0,0,0-1.92-.89A8,8,0,0,0,99.3.85L91.75,0l-.11,1Zm11.59-6a3.23,3.23,0,0,1,.21,1.61,2.92,2.92,0,0,1-.62,1.56,2.79,2.79,0,0,1-1.3.9,4,4,0,0,1-1.74.17l-5-.57.69-6.08,5.18.59a3.39,3.39,0,0,1,1.63.59A2.87,2.87,0,0,1,101.17,13.19Zm.25-6.85a2.78,2.78,0,0,1-.53,1.4,2.46,2.46,0,0,1-1.09.79,3.21,3.21,0,0,1-1.43.16l-4.68-.53L94.28,3,99,3.47a2.8,2.8,0,0,1,1.92.95A2.49,2.49,0,0,1,101.42,6.34Z"/><path class="cls-1" d="M109.84,17.78a8.27,8.27,0,0,0,1.6,4.43,10.23,10.23,0,0,0,9.17,3.64,8.22,8.22,0,0,0,4.2-2.14,12.1,12.1,0,0,0,2.94-4.5,12.33,12.33,0,0,0,.94-5.28,8.19,8.19,0,0,0-1.6-4.43,9.2,9.2,0,0,0-4.12-3,9.21,9.21,0,0,0-5-.64A8.22,8.22,0,0,0,113.72,8a12,12,0,0,0-2.94,4.49A12.1,12.1,0,0,0,109.84,17.78Zm3.7-4.19a10.63,10.63,0,0,1,2.08-3.36,5.66,5.66,0,0,1,2.86-1.67A5.81,5.81,0,0,1,122,9a6.13,6.13,0,0,1,2.83,2.13,5.71,5.71,0,0,1,1,3.16,10.11,10.11,0,0,1-.79,3.85,10.1,10.1,0,0,1-2.07,3.36,5.63,5.63,0,0,1-2.86,1.66,5.69,5.69,0,0,1-3.5-.41,6,6,0,0,1-2.84-2.14,5.57,5.57,0,0,1-1-3.16A9.91,9.91,0,0,1,113.54,13.59Z"/><polygon class="cls-1" points="128.2 30.72 130.46 32.45 139.2 21.05 138.01 38.23 140.26 39.96 152.01 24.63 149.76 22.9 141.03 34.29 142.21 17.12 139.96 15.39 128.2 30.72"/><polygon class="cls-1" points="40.68 156.44 38.63 154.47 28.67 164.79 31.79 147.86 29.75 145.89 16.33 159.79 18.38 161.76 28.36 151.42 25.22 168.36 27.27 170.34 40.68 156.44"/><polygon class="cls-1" points="43.35 167.78 46.06 163.08 54.32 167.84 55.64 165.55 44.95 159.39 35.31 176.13 46 182.29 47.32 180 39.06 175.24 42.04 170.07 48.91 174.02 50.23 171.73 43.35 167.78"/><path class="cls-1" d="M72.31,181.89c0-.07.06-.24.13-.49s.11-.47.13-.63l-6.46-1.87-.63,2.17,3.61,1a7.88,7.88,0,0,1-1.71,2.66l-.19.17a4.64,4.64,0,0,1-2.14,1.11,5.72,5.72,0,0,1-2.89-.21,6.65,6.65,0,0,1-2.32-1.16,5.87,5.87,0,0,1-.72-.69,5.67,5.67,0,0,1-1.25-3.05A9.77,9.77,0,0,1,58.3,177a10.33,10.33,0,0,1,1.75-3.53,5.55,5.55,0,0,1,2.68-1.93,5.89,5.89,0,0,1,3.54.08,5.18,5.18,0,0,1,2.87,1.92,5.8,5.8,0,0,1,1.07,3.18l2.87.3a7.94,7.94,0,0,0-1.45-5,8.57,8.57,0,0,0-4.78-3,8.91,8.91,0,0,0-4.82-.13,8.32,8.32,0,0,0-4,2.44,11.71,11.71,0,0,0-2.59,4.85,12.56,12.56,0,0,0-.54,4.25,9.14,9.14,0,0,0,.93,3.57c.09.18.19.34.29.51A7.89,7.89,0,0,0,58,186.72a9.31,9.31,0,0,0,3.25,1.64,9,9,0,0,0,4.92.18A7.92,7.92,0,0,0,70,186.2a9.68,9.68,0,0,0,.87-1.13A11.46,11.46,0,0,0,72.31,181.89Z"/><path class="cls-1" d="M87.66,172.4l-4-.11L79,185.36l-2.16,6.07,2.91.08,1.53-4.32,7.93.21,1.31,4.4,2.91.08-1.79-6.08Zm-2.12,2.74,2.86,9.61-6.21-.17Z"/><polygon class="cls-1" points="104.31 173.28 110.61 172.04 110.11 169.45 94.75 172.46 95.25 175.06 101.56 173.82 103.99 186.24 104.77 190.18 107.52 189.64 106.87 186.34 104.31 173.28"/><rect class="cls-1" x="117.5" y="166.52" width="2.8" height="19.31" transform="translate(-56.1 55.55) rotate(-21.41)"/><polygon class="cls-1" points="136.19 178.46 139.62 176.32 134.74 156.61 132.26 158.15 136.38 175.01 123.12 163.84 120.65 165.38 136.19 178.46"/><polygon class="cls-1" points="151.03 167.8 159.72 159.04 157.84 157.18 151.13 163.95 146.89 159.75 152.47 154.12 150.6 152.26 145.01 157.88 141.17 154.07 147.89 147.3 146.01 145.44 137.32 154.19 151.03 167.8"/><path class="cls-1" d="M89.25,81.1a16.1,16.1,0,0,1,11.46,4.74l2.12-2.12a19.21,19.21,0,1,0,0,27.15l-2.12-2.12A16.2,16.2,0,1,1,89.25,81.1Z"/><path class="cls-1" d="M41.57,71.48,44.19,73a50,50,0,0,1,83.84-5l2.42-1.78a53,53,0,0,0-88.88,5.32Z"/><path class="cls-1" d="M131.65,121.17a50,50,0,0,1-43.89,26.08A50.13,50.13,0,0,1,48.2,127.68c-.23-.3-.48-.6-.71-.91l-1.23.9-1.19.87a52.94,52.94,0,0,0,89.21-5.93Z"/><path class="cls-1" d="M116.34,128.19a42,42,0,0,0,13.54-30.78v-1.5h-5.81L133.8,82l9.73,13.87h-5.82v1.5a49.39,49.39,0,0,1-4,19.55l2.76,1.18a52.46,52.46,0,0,0,4.24-19.23h5.67l1.5-.07v-2l-14.06-20L120,96.47l-.27.47v2h7.11A39.11,39.11,0,0,1,63.28,127.8a39.7,39.7,0,0,1-9.06-10.44l-2.57,1.55a42.76,42.76,0,0,0,7.05,8.85,42,42,0,0,0,57.64.43Z"/><path class="cls-1" d="M87.76,55.29a42.19,42.19,0,0,0-42.12,42V98.8h5.82l-9.73,13.87L32,98.8H37.8V97.3a49.52,49.52,0,0,1,4-19.55l-2.75-1.19A52.56,52.56,0,0,0,34.83,95.8H29.15l-1.5.07v2l14.07,20L55.51,98.24l.27-.47v-2H48.67A39.09,39.09,0,0,1,121.3,77.35l2.57-1.55A41.8,41.8,0,0,0,87.76,55.29Z"/></g></g></svg>';
        break;
        case 'sustainable':
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 179.11 192.28"><defs><style>.cls-1{fill:#4ca586;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="HEADER"><path class="cls-1" d="M4,109.5A84.56,84.56,0,0,1,30.78,34l-1.33-2.83a87.7,87.7,0,0,0-28.27,79.9"/><path class="cls-1" d="M174.78,108.06a87.64,87.64,0,0,0-28.1-76.35l-1.45,2.76a84.54,84.54,0,0,1,26.65,72.59Z"/><polygon class="cls-1" points="49.89 28.98 52.42 27.79 48.89 20.33 55.25 17.32 54.04 14.78 47.69 17.79 45.36 12.86 53.17 9.17 51.97 6.63 41.63 11.53 49.89 28.98"/><path class="cls-1" d="M63.12,15.52a7.88,7.88,0,0,0,1.75,3.7,6.77,6.77,0,0,0,3.18,2,9.06,9.06,0,0,0,7.85-1.59A6.7,6.7,0,0,0,78,16.55a7.74,7.74,0,0,0,.18-4.08L75.8.51,73,1.07,75.4,12.89a5.46,5.46,0,0,1,0,2.2,4.54,4.54,0,0,1-.78,1.76,5,5,0,0,1-1.34,1.25,4.93,4.93,0,0,1-1.67.67,5.18,5.18,0,0,1-1.8,0,5,5,0,0,1-1.72-.63,4.61,4.61,0,0,1-1.4-1.32,5.32,5.32,0,0,1-.84-2L63.49,3l-2.78.57Z"/><polygon class="cls-1" points="85.81 19.3 97.35 19.77 97.46 17.13 88.72 16.77 89.41 0.12 86.6 0 85.81 19.3"/><polygon class="cls-1" points="102.09 20.66 113.28 23.54 113.94 20.98 105.47 18.8 109.63 2.65 106.92 1.95 102.09 20.66"/><polygon class="cls-1" points="122.74 19.06 119.31 26.27 121.88 27.48 125.31 20.28 136.07 12.85 133.12 11.44 125.41 16.76 124.66 7.42 121.71 6.02 122.74 19.06"/><path class="cls-1" d="M106.73,51.64c-.35-.47-.68-.92-1-1.39-4.38-5.84-10.5-9.09-16.81-8.91C81.81,41.54,75,46,69.72,53.91a1.5,1.5,0,0,0,2.5,1.67C77,48.4,82.8,44.51,89,44.34c5.3-.14,10.54,2.66,14.33,7.71l1,1.27c-3.54,4-7.59,13,0,22,4.11,4.9,8.49,6.14,12.36,7.24a21.24,21.24,0,0,1,7.56,3.27l1.75,1.32.6-2.1c.18-.63,4.38-15.62-1.9-25.54C118.56,49.94,108.09,51.4,106.73,51.64Zm17.4,30.6a28.8,28.8,0,0,0-6.69-2.53c-3.78-1.07-7.35-2.08-10.88-6.28-6.74-8-2.36-15.2-.46-17.62,4.81,6.5,8.4,11.78,10.39,16.69a1.49,1.49,0,0,0,1.39.94,1.55,1.55,0,0,0,.56-.11,1.5,1.5,0,0,0,.83-2c-2.07-5.11-5.67-10.46-10.48-17a14.3,14.3,0,0,1,13.31,6.74C126.4,68,125,78,124.13,82.24Z"/><path class="cls-1" d="M135.75,98.34A1.5,1.5,0,0,0,133,99.59c3,6.47,3.8,12.17,2.5,16.7a13,13,0,0,1-1.26,3c-2.41,4.15-7.18,6.76-13.43,7.33l-1.59.15a16.83,16.83,0,0,0-9-10.45,17.5,17.5,0,0,0-9.74-1.11,20.65,20.65,0,0,0-4.37,1.11,24.85,24.85,0,0,0-8.28,5.6,21.23,21.23,0,0,1-6.76,4.72l-2,.79L80.5,129c.44.49,10.87,11.8,22.53,11.8h.21c11.36-.13,15.61-9.79,16.13-11.08l1.73-.16c7.25-.66,12.85-3.8,15.76-8.82a16.28,16.28,0,0,0,1.75-4.47C139.59,112.1,139.36,106.23,135.75,98.34ZM103.2,137.82c-8,.09-15.85-6.38-19-9.36a28.39,28.39,0,0,0,5.66-4.36c2.9-2.65,5.64-5.16,11.07-5.95,10.39-1.52,14.18,6,15.24,8.86-8.07.68-14.45,1-19.67.07a1.5,1.5,0,0,0-.5,3,49.69,49.69,0,0,0,8.21.6c3.49,0,7.37-.24,11.71-.6A14.42,14.42,0,0,1,103.2,137.82Z"/><path class="cls-1" d="M44.49,105.1c.23-.5.44-1,.65-1.46a16.41,16.41,0,0,0,3.63.39c5.3,0,12.24-2.34,15.58-11.17,2.27-6,1.22-10.41.28-14.32a21.32,21.32,0,0,1-.84-8.2l.3-2.17-2.13.5c-.64.15-15.77,3.82-21.35,14.15-5.41,10,1,18.39,1.84,19.48l-.7,1.59a21.07,21.07,0,0,0-1.8,12.4,15.47,15.47,0,0,0,1.9,5.07c2.42,4,8,9.08,20.68,10.08h.12a1.5,1.5,0,0,0,.11-3c-9-.71-15.38-3.69-18.33-8.63a12.29,12.29,0,0,1-1.4-3.53A17.83,17.83,0,0,1,44.49,105.1ZM43.24,84.24c3.83-7.07,13.28-10.76,17.42-12.07a28.69,28.69,0,0,0,1.05,7.07c.92,3.82,1.78,7.43-.16,12.56-3.72,9.81-12.11,9.49-15.15,9,3.33-7.38,6.18-13.09,9.49-17.23a1.5,1.5,0,0,0-2.34-1.87c-3.45,4.3-6.35,10-9.68,17.42A14.27,14.27,0,0,1,43.24,84.24Z"/><path class="cls-1" d="M2.59,137.22a9.4,9.4,0,0,1-.91-3.71,7.06,7.06,0,0,1,.71-3.36,6.76,6.76,0,0,1,2.3-2.59L6.31,130a4.6,4.6,0,0,0-1.73,2.83,5.65,5.65,0,0,0,.48,3.4,5.93,5.93,0,0,0,1.23,1.79,3.87,3.87,0,0,0,1.62,1,2.45,2.45,0,0,0,1.76-.15,2.16,2.16,0,0,0,1.19-1.29,3,3,0,0,0,.16-.92,6.4,6.4,0,0,0-.06-1.05l-.74-5.11A11.24,11.24,0,0,1,10.1,129a5.33,5.33,0,0,1,.25-1.63,4.29,4.29,0,0,1,.83-1.5,4.7,4.7,0,0,1,1.66-1.19A5,5,0,0,1,16,124.2a5.81,5.81,0,0,1,2.75,1.48,9.23,9.23,0,0,1,2,2.93,8.92,8.92,0,0,1,.83,3.47,6.72,6.72,0,0,1-.67,3.15,7.06,7.06,0,0,1-2.17,2.55L17,135.27a4.09,4.09,0,0,0,1.35-1.56,4.45,4.45,0,0,0,.42-2,5.23,5.23,0,0,0-.47-2.06A4.89,4.89,0,0,0,17.22,128a3.47,3.47,0,0,0-1.48-.88,2.15,2.15,0,0,0-1.54.12,1.81,1.81,0,0,0-1,1,3.45,3.45,0,0,0-.23,1.38,11.29,11.29,0,0,0,.15,1.45l.57,3.66a15.23,15.23,0,0,1,.18,1.6,6.94,6.94,0,0,1-.11,1.86A4.5,4.5,0,0,1,13,140a4.8,4.8,0,0,1-1.91,1.45,5.21,5.21,0,0,1-4.94-.16,7.5,7.5,0,0,1-2-1.68A10.72,10.72,0,0,1,2.59,137.22Z"/><path class="cls-1" d="M15.48,157.71A8.34,8.34,0,0,1,13.65,154a6.87,6.87,0,0,1,.3-3.76A7.62,7.62,0,0,1,16.39,147l9.42-7.75,1.81,2.19-9.31,7.66a5.31,5.31,0,0,0-1.42,1.7,4.7,4.7,0,0,0-.5,1.86,4.82,4.82,0,0,0,.26,1.81,5.13,5.13,0,0,0,.87,1.57,4.85,4.85,0,0,0,1.38,1.16,4.75,4.75,0,0,0,1.73.6,4.69,4.69,0,0,0,1.92-.14,5.45,5.45,0,0,0,1.93-1.06l9.31-7.66,1.81,2.2-9.43,7.75a7.78,7.78,0,0,1-3.68,1.76,6.78,6.78,0,0,1-3.75-.43A8.3,8.3,0,0,1,15.48,157.71Z"/><path class="cls-1" d="M32.76,174.08a9.23,9.23,0,0,1-2.59-2.8,7.06,7.06,0,0,1-1-3.28,6.87,6.87,0,0,1,.75-3.39L32.55,166a4.66,4.66,0,0,0-.15,3.32,5.57,5.57,0,0,0,2.06,2.75,6,6,0,0,0,1.94,1,3.75,3.75,0,0,0,1.9.09,2.42,2.42,0,0,0,1.46-1,2.3,2.3,0,0,0,.41-.85,2.26,2.26,0,0,0,0-.86,2.9,2.9,0,0,0-.31-.88,6.84,6.84,0,0,0-.56-.89l-3.11-4.12a10.73,10.73,0,0,1-.86-1.32,5.29,5.29,0,0,1-.57-1.54,4.28,4.28,0,0,1,0-1.71,4.52,4.52,0,0,1,.88-1.85,4.93,4.93,0,0,1,2.53-1.88,5.6,5.6,0,0,1,3.12,0,9.27,9.27,0,0,1,3.2,1.58,9.07,9.07,0,0,1,2.41,2.65,6.85,6.85,0,0,1,.93,3.08,7.13,7.13,0,0,1-.67,3.28l-2.72-1.38a4,4,0,0,0,.42-2,4.29,4.29,0,0,0-.58-1.93,5.24,5.24,0,0,0-1.4-1.58,5,5,0,0,0-1.77-.91,3.48,3.48,0,0,0-1.72,0,2.12,2.12,0,0,0-1.29.85,1.83,1.83,0,0,0-.4,1.32,3.39,3.39,0,0,0,.47,1.32,12,12,0,0,0,.82,1.2l2.27,2.93c.29.37.6.8.93,1.31a7.11,7.11,0,0,1,.8,1.69,4.39,4.39,0,0,1,.18,2,4.87,4.87,0,0,1-1,2.19,5.39,5.39,0,0,1-2,1.71,5.33,5.33,0,0,1-2.4.53,7.36,7.36,0,0,1-2.56-.5A10,10,0,0,1,32.76,174.08Z"/><path class="cls-1" d="M51.25,184.29l6.44-15.37-5.93-2.49,1-2.43,14.43,6-1,2.44L60.28,170l-6.44,15.38Z"/><path class="cls-1" d="M65.85,189.65l9.26-18.08,4,.64,3.14,20.07-2.87-.46L76.44,173.5l.72.11-8.44,16.5Zm3.91-3.79.41-2.59,10,1.59-.41,2.6Z"/><path class="cls-1" d="M90.33,192.17l-.5-19.31,2.8-.07.51,19.31Z"/><path class="cls-1" d="M102.71,191.52l-4.24-18.84,2.78-.62L113.67,184l-3.14-14,2.77-.62,4.24,18.84-2.78.63-12.43-11.94,3.15,14Z"/><path class="cls-1" d="M125.77,185.27l-3.38-20,3.58-1.87,14.51,14.22-2.58,1.34L124.62,166l.64-.34,3.09,18.27Zm.87-5.37-1.21-2.33,8.94-4.67,1.21,2.33Z"/><path class="cls-1" d="M147.4,172.55,134.57,158.1l5.68-5a5.81,5.81,0,0,1,2.8-1.48,5,5,0,0,1,2.69.2,5.29,5.29,0,0,1,2.14,1.45,4.81,4.81,0,0,1,1.23,2.57,3.91,3.91,0,0,1-.55,2.56l-.46-.47a4.89,4.89,0,0,1,3.31-.56A5,5,0,0,1,154.2,159a6.21,6.21,0,0,1,1.46,2.67,4.81,4.81,0,0,1-.17,2.78,6.4,6.4,0,0,1-1.88,2.6Zm-5.47-10.44,3.52-3.12a3.11,3.11,0,0,0,.84-1.17,2.36,2.36,0,0,0,.14-1.34,2.71,2.71,0,0,0-.68-1.32,2.45,2.45,0,0,0-1.79-.89,2.77,2.77,0,0,0-2,.8l-3.52,3.12Zm5.82,6.55,3.77-3.35a3.91,3.91,0,0,0,1-1.4,2.85,2.85,0,0,0,.2-1.57,2.89,2.89,0,0,0-.75-1.5,3.26,3.26,0,0,0-1.33-.93,2.84,2.84,0,0,0-1.56-.1,3.46,3.46,0,0,0-1.53.81l-3.9,3.47Z"/><path class="cls-1" d="M163.62,156.44l-15.7-11.26,1.63-2.27,13.55,9.71,5.1-7.11,2.14,1.54Z"/><path class="cls-1" d="M174.25,140l-17.76-7.61L161.35,121l2.43,1L160,130.83l5,2.13,3.12-7.28,2.43,1L167.44,134l5.48,2.35,3.76-8.76,2.43,1Z"/></g></g></svg>';
        break;
        case 'non-toxic':
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 175.52 192.26"><defs><style>.cls-1{fill:#4ca586;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="HEADER"><path class="cls-1" d="M3,97A84.7,84.7,0,0,1,37,29.11l-1.3-2.76a87.65,87.65,0,0,0-6.25,136.18l1.44-2.74A84.54,84.54,0,0,1,3,97Z"/><polygon class="cls-1" points="58.89 24.69 61.63 23.92 57.72 10.09 70.78 21.33 73.51 20.55 68.26 1.97 65.52 2.74 69.43 16.55 56.37 5.33 53.63 6.1 58.89 24.69"/><path class="cls-1" d="M79.53,4.67a12,12,0,0,0-1.17,5.24,12.23,12.23,0,0,0,1,5.28,8.26,8.26,0,0,0,3.07,3.58,9.1,9.1,0,0,0,4.91,1.35,9.23,9.23,0,0,0,5-1.19,8.13,8.13,0,0,0,3.18-3.48,12.29,12.29,0,0,0,1.15-5.24,12.09,12.09,0,0,0-1-5.28,8.26,8.26,0,0,0-3.07-3.58A8.06,8.06,0,0,0,91.85,1a9.69,9.69,0,0,0-4.19-1,9.49,9.49,0,0,0-4.51,1c-.15.08-.31.14-.45.23A8.17,8.17,0,0,0,79.53,4.67ZM93,6.28a10.1,10.1,0,0,1,.63,3.88A10.38,10.38,0,0,1,92.89,14,5.63,5.63,0,0,1,90.8,16.6a5.75,5.75,0,0,1-3.43.85,6,6,0,0,1-3.41-1,5.56,5.56,0,0,1-2-2.61A9.92,9.92,0,0,1,81.34,10a10.27,10.27,0,0,1,.76-3.88,5.63,5.63,0,0,1,2.09-2.57,5.79,5.79,0,0,1,3.42-.84,6,6,0,0,1,3.4,1A5.62,5.62,0,0,1,93,6.28Z"/><polygon class="cls-1" points="103.81 21.34 107.73 7.52 112.96 23.94 115.7 24.71 120.97 6.13 118.23 5.35 114.32 19.16 109.08 2.76 106.35 1.98 101.08 20.57 103.81 21.34"/><polygon class="cls-1" points="54.88 168.11 60.66 170.91 61.82 168.54 47.73 161.7 46.58 164.08 52.36 166.89 45.08 181.89 47.61 183.11 54.88 168.11"/><path class="cls-1" d="M80.11,182.47a12.26,12.26,0,0,0,0-5.37A8.16,8.16,0,0,0,77.69,173a9.14,9.14,0,0,0-4.59-2.19,9,9,0,0,0-5.08.29A8.2,8.2,0,0,0,64.27,174a13.39,13.39,0,0,0-2,10.32,8.18,8.18,0,0,0,2.37,4.07,10.23,10.23,0,0,0,9.68,1.91A8.22,8.22,0,0,0,78,187.42,12.23,12.23,0,0,0,80.11,182.47Zm-6.86,5.26a5.8,5.8,0,0,1-3.52.23,6.08,6.08,0,0,1-3.18-1.59A5.56,5.56,0,0,1,65,183.44a9.68,9.68,0,0,1,.09-3.92,10.42,10.42,0,0,1,1.43-3.69,5.69,5.69,0,0,1,2.51-2.15,5.76,5.76,0,0,1,3.53-.22A6,6,0,0,1,75.75,175,5.51,5.51,0,0,1,77.26,178a11.21,11.21,0,0,1-1.5,7.6A5.59,5.59,0,0,1,73.25,187.73Z"/><polygon class="cls-1" points="93.66 182.01 99.6 172.18 96.16 172.35 91.83 179.72 86.78 172.8 83.35 172.96 90.22 182.18 84.11 192.26 87.55 192.1 92.06 184.48 97.26 191.63 100.69 191.47 93.66 182.01"/><rect class="cls-1" x="106.09" y="170.37" width="2.8" height="19.31" transform="translate(-38.57 29.59) rotate(-13.31)"/><path class="cls-1" d="M130.12,174.81a6,6,0,0,1-.5,3.72,5.7,5.7,0,0,1-2.9,2.62,6,6,0,0,1-3.5.6,5.62,5.62,0,0,1-3-1.49A9.9,9.9,0,0,1,118,177a10.1,10.1,0,0,1-1-3.81,5.69,5.69,0,0,1,.79-3.21,6,6,0,0,1,2.71-2.28,5.85,5.85,0,0,1,3.88-.52,6,6,0,0,1,3.15,2l2.26-1.89a8.73,8.73,0,0,0-4.67-2.85,8.85,8.85,0,0,0-5.74.79,9.17,9.17,0,0,0-4,3.22,8.22,8.22,0,0,0-1.35,4.52,12,12,0,0,0,1.23,5.22,12.22,12.22,0,0,0,3.18,4.32,8.19,8.19,0,0,0,4.32,1.9,9.13,9.13,0,0,0,5-.92,8.41,8.41,0,0,0,5.18-9.26Z"/><path class="cls-1" d="M139.17,25.9l-1.41,2.69a84.62,84.62,0,0,1,7.52,130.56l1.35,2.86A87.65,87.65,0,0,0,139.17,25.9Z"/><path class="cls-1" d="M100,54.34l-.81,2.89a43.43,43.43,0,0,1,17.8,10L98.32,85.68l-3.56-5.05V53.94h.33a3.75,3.75,0,0,0,3.75-3.75V46.33a3.75,3.75,0,0,0-3.75-3.74H79.62a3.75,3.75,0,0,0-3.75,3.74v3.86a3.75,3.75,0,0,0,3.75,3.75h.16V80.63l-20.37,28.8a10.63,10.63,0,0,0-2,11.58,10.88,10.88,0,0,0,2.08,3.18l-4,4A42.77,42.77,0,0,1,43.72,98.76a43.36,43.36,0,0,1,31.7-41.54l-.81-2.89A46.38,46.38,0,0,0,40.72,98.76c0,25.45,20.88,46.15,46.55,46.15s46.54-20.7,46.54-46.15A46.37,46.37,0,0,0,100,54.34Zm12.77,56.91a2.09,2.09,0,0,0,.14.17,7.67,7.67,0,0,1,1.51,8.41,7.82,7.82,0,0,1-7.28,4.76H67.43a8.16,8.16,0,0,1-3.36-.72L97.92,90.31Zm-51.06.17.13-.17L82.5,82a1.53,1.53,0,0,0,.28-.87V52.48a1.53,1.53,0,0,0-.19-.78,1.5,1.5,0,0,0-1.44-.76H79.62a.75.75,0,0,1-.75-.75V46.33a.74.74,0,0,1,.75-.74H95.09a.75.75,0,0,1,.75.74v3.86a.76.76,0,0,1-.75.75H93.38a1.51,1.51,0,0,0-1.62,1.49V81.11A1.52,1.52,0,0,0,92,82l4.14,5.84L61.62,122.07a8,8,0,0,1-1.46-2.24A7.64,7.64,0,0,1,61.67,111.42Zm25.6,30.49a43.58,43.58,0,0,1-29.68-11.62l4.24-4.21a11,11,0,0,0,5.6,1.51H107.1a10.75,10.75,0,0,0,10-6.58,10.63,10.63,0,0,0-2-11.58l-15-21.27,19-18.82a43,43,0,0,1-31.8,72.57Z"/></g></g></svg>';
        break;
        case 'bio-degradable':
            echo '<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 190 193" xmlns="http://www.w3.org/2000/svg"><path d="m92.84 114.37c.623.944 1.761 1.419 2.87 1.2.544-.077 1.06-.29 1.5-.62.419-.328.74-.763.93-1.26.195-.555.243-1.151.14-1.73l-.81-5.15-6 1 .78 5c.081.557.282 1.089.59 1.56z" fill="none"/><path d="m79.94 7.5c-.138-.679-.463-1.307-.94-1.81-.439-.49-.992-.865-1.61-1.09-.645-.213-1.335-.251-2-.11-.674.128-1.301.44-1.81.9-.501.433-.884.987-1.11 1.61-.126.357-.194.732-.2 1.11h7.81z" fill="none"/><path d="m72.42 186.31c.793-.182 1.527-.564 2.13-1.11l-7.37-.27c.693.546 1.489.947 2.34 1.18.934.314 1.932.383 2.9.2z" fill="none"/><path d="m102.48 6.71c.045-.695-.108-1.388-.44-2-.31-.593-.768-1.096-1.33-1.46-.576-.372-1.244-.58-1.93-.6-.693-.052-1.386.094-2 .42-.593.31-1.096.768-1.46 1.33-.379.575-.593 1.242-.62 1.93l-.06 1.97h7.76z" fill="none"/><path d="m122 7.65c-.077-.413-.26-.799-.53-1.12-.258-.327-.606-.574-1-.71-.395-.139-.819-.171-1.23-.09-.408.091-.791.272-1.12.53-.325.26-.571.607-.71 1-.134.378-.165.786-.09 1.18h4.7c.042-.262.035-.53-.02-.79z" fill="none"/><path d="m13 112.38c.269.372.636.663 1.06.84.47.186.983.234 1.48.14.684-.08 1.305-.443 1.71-1 .413-.616.558-1.375.4-2.1l-.73-4.65-5.18.81.73 4.65c.072.472.254.921.53 1.31z" fill="none"/><path d="m22.17 134.09c-1.311.232-2.567.711-3.7 1.41-1.163.646-2.184 1.52-3 2.57-.683.892-1.084 1.968-1.15 3.09-.005.09-.005.18 0 .27l9.1-7.39c-.417-.03-.836-.013-1.25.05z" fill="none"/><path d="m172.81 143.9c-.236-.518-.618-.956-1.1-1.26-.241-.165-.503-.297-.78-.39l1.9 1.71s-.01-.04-.02-.06z" fill="none"/><g fill="#4ca586"><path d="m7.91 86 3 .37c2.593-20.992 12.98-40.263 29.09-53.97l-1.72-2.4c-16.804 14.176-27.654 34.184-30.37 56z" fill-rule="nonzero"/><path d="m150.56 32.87c15.225 13.206 25.25 31.421 28.26 51.35l3-.44c-3.116-20.635-13.495-39.496-29.26-53.17-.93-.81-1.88-1.6-2.84-2.36l-2 2.22c.93.78 1.89 1.58 2.84 2.4z" fill-rule="nonzero"/><path d="m2.48 116.56c.569.759 1.353 1.33 2.25 1.64.966.321 1.998.393 3 .21 1.103-.133 2.13-.629 2.92-1.41.581-.61.986-1.368 1.17-2.19.364.46.83.829 1.36 1.08.882.404 1.866.526 2.82.35.864-.132 1.681-.476 2.38-1 .737-.582 1.298-1.358 1.62-2.24.372-1.01.462-2.103.26-3.16l-1.18-7.5-19.08 3.02 1.29 8.22c.143 1.077.552 2.101 1.19 2.98zm14.43-11 .73 4.65c.158.725.013 1.484-.4 2.1-.405.557-1.026.92-1.71 1-.497.094-1.01.046-1.48-.14-.424-.177-.791-.468-1.06-.84-.28-.398-.462-.858-.53-1.34l-.73-4.65zm-7.79 1.23.81 5.21c.108.579.06 1.176-.14 1.73-.181.504-.504.945-.93 1.27-.437.328-.949.541-1.49.62-1.113.224-2.256-.252-2.88-1.2-.332-.488-.547-1.046-.63-1.63l-.78-5z" fill-rule="nonzero"/><path d="m4.71 123.93h19.31v2.8h-19.31z" transform="matrix(.942816 -.333313 .333313 .942816 -40.95 11.95)"/><path d="m16.65 149.53c1.426.734 3.037 1.029 4.63.85 1.8-.207 3.53-.819 5.06-1.79 1.588-.891 2.955-2.128 4-3.62.902-1.329 1.396-2.894 1.42-4.5-.015-1.73-.518-3.422-1.45-4.88-.86-1.519-2.133-2.764-3.67-3.59-.15-.08-.31-.13-.46-.2-1.313-.577-2.753-.805-4.18-.66-1.805.231-3.535.867-5.06 1.86-1.594.888-2.962 2.13-4 3.63-.907 1.323-1.401 2.886-1.42 4.49-.013.799.099 1.595.33 2.36.273.886.659 1.733 1.15 2.52.861 1.496 2.126 2.72 3.65 3.53zm-2.3-8.37c.066-1.122.467-2.198 1.15-3.09.816-1.05 1.837-1.924 3-2.57 1.133-.699 2.389-1.178 3.7-1.41.427-.066.86-.083 1.29-.05.691.022 1.371.182 2 .47 1.076.548 1.953 1.419 2.51 2.49.652 1.015.983 2.204.95 3.41-.072 1.119-.476 2.192-1.16 3.08-.82 1.049-1.84 1.926-3 2.58-1.123.706-2.373 1.185-3.68 1.41-1.113.199-2.26.056-3.29-.41-1.079-.537-1.963-1.397-2.53-2.46-.614-.939-.96-2.029-1-3.15.014-.101.034-.201.06-.3z" fill-rule="nonzero"/><path d="m45 156.3c-.149-1.406-.721-2.735-1.64-3.81-.293-.352-.606-.686-.94-1l-.71-.7-4.38-4.18-13.33 13.94 4.38 4.18.73.68c.346.317.71.614 1.09.89 1.096.848 2.418 1.356 3.8 1.46 1.382.058 2.758-.231 4-.84 2.78-1.384 4.988-3.699 6.24-6.54.592-1.274.854-2.678.76-4.08zm-3.49 3.24c-.483.954-1.11 1.828-1.86 2.59-.713.784-1.539 1.458-2.45 2-.815.488-1.733.78-2.68.85-.925.053-1.843-.2-2.61-.72-.305-.215-.599-.445-.88-.69-.32-.28-.59-.52-.81-.73l-2.3-2.2 9.64-10.11 2.31 2.19c.21.2.46.46.76.76.271.265.518.553.74.86.551.732.846 1.624.84 2.54-.037.932-.29 1.843-.74 2.66z" fill-rule="nonzero"/><path d="m42.66 176.49 10.71 6.12 1.31-2.29-8.28-4.73 2.96-5.18 6.89 3.93 1.31-2.3-6.89-3.93 2.69-4.7 8.28 4.73 1.31-2.3-10.71-6.12z" fill-rule="nonzero"/><path d="m79.79 181.64c.06-.26.11-.47.13-.63l-6.47-1.85-.62 2.17 3.61 1c-.38.996-.958 1.904-1.7 2.67l-.19.17c-.603.546-1.337.928-2.13 1.11-.968.183-1.966.114-2.9-.2-.851-.233-1.647-.634-2.34-1.18-.251-.197-.483-.418-.69-.66-.725-.863-1.169-1.928-1.27-3.05-.127-1.315.016-2.642.42-3.9.344-1.285.935-2.491 1.74-3.55.678-.899 1.612-1.572 2.68-1.93 1.155-.339 2.387-.319 3.53.06 1.15.294 2.166.971 2.88 1.92.672.937 1.045 2.057 1.07 3.21l2.88.29c.089-1.783-.425-3.545-1.46-5-1.216-1.512-2.908-2.57-4.8-3-1.564-.487-3.234-.529-4.82-.12-1.541.416-2.93 1.266-4 2.45-1.231 1.394-2.115 3.059-2.58 4.86-.417 1.38-.593 2.821-.52 4.26.069 1.238.389 2.449.94 3.56.08.16.18.32.28.48.494.846 1.142 1.592 1.91 2.2.966.753 2.07 1.311 3.25 1.64 1.594.503 3.296.558 4.92.16 1.469-.401 2.79-1.221 3.8-2.36.313-.352.601-.726.86-1.12.649-.983 1.139-2.063 1.45-3.2z" fill-rule="nonzero"/><path d="m99.26 182.83c.828-1.168 1.266-2.568 1.25-4 .017-.968-.156-1.929-.51-2.83-.327-.844-.868-1.589-1.57-2.16-.776-.607-1.696-1.003-2.67-1.15-.261-.053-.525-.09-.79-.11h-.7l-7.8-.15-.26 13.19-.12 6.12 2.81.05.11-6.07v-.86l5.13.1.44 1 2.79 6 3.2.06-2.78-6-.73-1.58c.873-.314 1.637-.873 2.2-1.61zm-10-7.71 4.9.09h.59c.21.018.418.058.62.12.523.114 1.001.382 1.37.77.333.361.583.791.73 1.26.14.46.207.939.2 1.42-.013.484-.101.963-.26 1.42-.16.466-.423.89-.77 1.24-.388.368-.874.614-1.4.71-.204.046-.411.076-.62.09h-.62l-4.9-.09z" fill-rule="nonzero"/><path d="m114.1 170.13-3.93.92-1.31 15.36-.42 4.87 2.84-.66.34-4.11v-.45l7.72-1.81 1.57 2.59.81 1.33 2.84-.66-.34-.55zm-2.21 13.17.85-10 5.2 8.57z" fill-rule="nonzero"/><path d="m138.07 181.51.87-.47c.413-.227.811-.48 1.19-.76 1.177-.781 2.091-1.9 2.62-3.21.504-1.301.669-2.708.48-4.09-.201-1.532-.682-3.013-1.42-4.37-.705-1.367-1.658-2.591-2.81-3.61-1.039-.935-2.299-1.592-3.66-1.91-1.38-.303-2.82-.181-4.13.35-.442.157-.873.344-1.29.56l-.89.46-5.35 2.83 9 17.07zm-7.79-14.71 1-.48c.322-.167.657-.311 1-.43.87-.29 1.81-.29 2.68 0 .886.326 1.689.845 2.35 1.52 1.476 1.537 2.495 3.456 2.94 5.54.222.923.222 1.887 0 2.81-.258.896-.803 1.683-1.55 2.24-.297.223-.608.426-.93.61l-1 .54-2.77 1.49-6.54-12.35z" fill-rule="nonzero"/><path d="m154.62 170.92-1.88-4.17 5.87-5.33 4 2.27 2.16-1.95-17.62-10.1-3 2.71 8.32 18.53zm-3-6.7-4.1-9.13 8.71 5z" fill-rule="nonzero"/><path d="m160.71 135.93c-.963.483-1.773 1.224-2.34 2.14l-4.23 6.3 16 10.76 4.63-6.9c.361-.526.65-1.098.86-1.7.156-.419.256-.856.3-1.3.101-.942-.084-1.894-.53-2.73-.5-.897-1.215-1.657-2.08-2.21-.907-.637-2.003-.951-3.11-.89-.659.041-1.3.225-1.88.54-.162.08-.319.17-.47.27.02-.236.02-.474 0-.71-.04-.343-.124-.679-.25-1-.371-.895-1.01-1.654-1.83-2.17-.718-.486-1.547-.786-2.41-.87-.913-.099-1.836.064-2.66.47zm-2.79 7.55 2.62-3.91c.382-.632 1.002-1.086 1.72-1.26.693-.16 1.422-.014 2 .4.43.269.777.654 1 1.11.194.418.266.883.21 1.34-.068.481-.246.939-.52 1.34l-2.62 3.91zm10.73-1.11c.476-.238 1.01-.338 1.54-.29.253.022.502.079.74.17.277.093.539.225.78.39.482.304.864.742 1.1 1.26s0 0 0 .06c.195.478.257 1 .18 1.51-.093.585-.315 1.141-.65 1.63l-2.81 4.18-5.08-3.4 2.91-4.34c.32-.497.765-.9 1.29-1.17z" fill-rule="nonzero"/><path d="m164.8 125.92-1 2.62 18.03 6.91 4.14-10.78-2.47-.95-3.13 8.16z" fill-rule="nonzero"/><path d="m181 104.94-2.61-.39-1.17 7.84-5.36-.8 1.41-9.43-2.62-.39-1.81 12.2 19.1 2.85 1.82-12.21-2.61-.39-1.41 9.44-5.9-.88z" fill-rule="nonzero"/><path d="m52.45 12.17 1.27 2.86 2.43-3.49 6.57 14.84 2.61-1.16-7.61-17.19-.21-.47-1.06.47-1.55.68z" fill-rule="nonzero"/><path d="m70.92 16.34c.222 1.198.757 2.316 1.55 3.24.75.864 1.722 1.507 2.81 1.86 2.372.683 4.936.143 6.83-1.44.867-.737 1.52-1.694 1.89-2.77.361-1.163.413-2.401.15-3.59l-1.15-5.43-.22-1.09c-.218-1.199-.753-2.318-1.55-3.24-.744-.871-1.712-1.521-2.8-1.88-2.369-.696-4.935-.171-6.84 1.4-.859.753-1.498 1.724-1.85 2.81-.2.61-.305 1.248-.31 1.89-.009.571.044 1.141.16 1.7zm1.55-9.34c.216-.631.592-1.196 1.09-1.64.509-.46 1.136-.772 1.81-.9.665-.141 1.355-.103 2 .11.628.231 1.189.616 1.63 1.12.462.508.774 1.135.9 1.81l.14.69 1.27 6.19c.146.665.105 1.357-.12 2-.214.635-.59 1.204-1.09 1.65-.514.453-1.138.763-1.81.9-.665.14-1.356.099-2-.12-.636-.212-1.208-.584-1.66-1.08-.462-.512-.774-1.142-.9-1.82l-1.38-6.86c-.066-.299-.093-.604-.08-.91.003-.388.071-.774.2-1.14z" fill-rule="nonzero"/><path d="m91.56 13.07c-.077 1.214.165 2.428.7 3.52.515 1.022 1.3 1.883 2.27 2.49 2.131 1.27 4.762 1.39 7 .32 1.014-.514 1.868-1.296 2.47-2.26.628-1.043.972-2.233 1-3.45l.25-5.32.06-1.32c.082-1.218-.16-2.436-.7-3.53-.513-1.028-1.294-1.898-2.26-2.52-2.135-1.256-4.759-1.376-7-.32-1.012.536-1.86 1.338-2.45 2.32-.634 1.037-.979 2.225-1 3.44l-.09 1.86zm3.17-6.71c.027-.688.241-1.355.62-1.93.364-.562.867-1.02 1.46-1.33.614-.326 1.307-.472 2-.42.686.02 1.354.228 1.93.6.536.373.969.875 1.26 1.46.332.612.485 1.305.44 2l-.08 1.64-.25 5.38c-.022.69-.233 1.361-.61 1.94-.36.546-.852.993-1.43 1.3-.614.324-1.307.473-2 .43-.686-.029-1.352-.24-1.93-.61-.558-.368-1.016-.87-1.33-1.46-.328-.613-.477-1.306-.43-2l.23-5.08z" fill-rule="nonzero"/><path d="m123.18 24.84c.545.654 1.262 1.142 2.07 1.41.799.294 1.664.363 2.5.2.827-.158 1.601-.523 2.25-1.06 1.326-1.129 1.946-2.889 1.62-4.6-.161-.819-.53-1.584-1.07-2.22-.548-.652-1.264-1.143-2.07-1.42-.796-.28-1.656-.332-2.48-.15-.826.145-1.605.488-2.27 1-.657.537-1.153 1.247-1.43 2.05-.295.806-.361 1.679-.19 2.52.162.834.53 1.615 1.07 2.27zm1.42-3.93c.141-.392.386-.738.71-1 .325-.259.704-.44 1.11-.53.839-.165 1.703.143 2.25.8.269.319.451.701.53 1.11.089.413.061.842-.08 1.24-.146.389-.39.734-.71 1-.322.269-.708.451-1.12.53-.413.088-.842.06-1.24-.08-.391-.143-.736-.388-1-.71-.265-.325-.447-.709-.53-1.12-.086-.413-.058-.841.08-1.24z" fill-rule="nonzero"/><path d="m116 11.22c1.129 1.314 2.878 1.928 4.58 1.61.819-.164 1.585-.528 2.23-1.06.654-.553 1.148-1.271 1.43-2.08.133-.382.22-.778.26-1.18.042-.448.018-.899-.07-1.34-.171-.799-.535-1.544-1.06-2.17-.548-.652-1.264-1.143-2.07-1.42-.792-.284-1.646-.346-2.47-.18-.829.168-1.605.535-2.26 1.07-.658.541-1.153 1.254-1.43 2.06-.232.64-.324 1.322-.27 2 0 .18 0 .37.07.55.179.787.542 1.521 1.06 2.14zm1.42-3.94c.139-.393.385-.74.71-1 .329-.258.712-.439 1.12-.53.411-.081.835-.049 1.23.09.394.136.742.383 1 .71.264.317.443.695.52 1.1.055.277.055.563 0 .84-.017.132-.044.262-.08.39-.143.391-.388.736-.71 1-.322.269-.708.451-1.12.53-.413.089-.842.061-1.24-.08-.393-.138-.74-.384-1-.71-.265-.325-.447-.709-.53-1.12s0 0 0-.06c-.065-.39-.027-.79.11-1.16z" fill-rule="nonzero"/><path d="m111.57 19.01 1.15 2.4 22.25-10.63-1.05-2.21-.09-.2-.42.2z" fill-rule="nonzero"/></g><path d="m111.15 87c.38.281.604.726.604 1.198 0 .326-.107.643-.304.902-4.906 6.756-8.697 14.254-11.23 22.21 3.5.51 14.7 1.05 19.17-11.94 2.25-6.52 1-11-.26-15.77-.94-2.954-1.472-6.022-1.58-9.12-4.83 1.66-17.1 6.65-21.77 16-3.142 6.237-2.461 13.749 1.75 19.32 2.637-8.057 6.525-15.65 11.52-22.5.283-.377.728-.6 1.2-.6.324 0 .64.105.9.3z" fill="none"/><path d="m85.65 142.33c.116.015.234.015.35 0 .691-.005 1.293-.487 1.45-1.16.029-.117.044-.236.044-.356 0-.69-.482-1.292-1.154-1.444-23.697-5.705-38.654-29.702-33.34-53.49l1.78 2.86c.268.445.751.718 1.27.72.279-.004.552-.083.79-.23.694-.435.913-1.359.49-2.06l-3.61-5.84-.06-.06-.09-.13-.11-.1c-.032-.038-.069-.072-.11-.1l-.12-.08-.12-.07-.15-.07h-1.05l-.12.05-.15.07-5.83 3.61c-.436.274-.702.755-.702 1.27 0 .82.672 1.496 1.492 1.5.278 0 .552-.076.79-.22l2.61-1.63c-5.705 25.396 10.327 51.022 35.66 57z" fill="#4ca586" fill-rule="nonzero"/><path d="m112 135.82c.137-.232.209-.496.209-.765 0-.825-.679-1.504-1.504-1.504-.531 0-1.025.282-1.295.739l-3.49 5.91c-.004.03-.004.06 0 .09-.029.051-.053.104-.07.16-.027.093-.053.186-.08.28v.15c-.005.047-.005.093 0 .14-.005.06-.005.12 0 .18-.004.033-.004.067 0 .1.031.149.089.291.17.42v.06c.08.12.178.228.29.32h.07l.12.09 5.9 3.49c.228.141.492.214.76.21h.005c.822 0 1.5-.677 1.5-1.5 0-.529-.28-1.02-.735-1.29l-2.85-1.62c24.89-7.731 39.136-34.43 31.7-59.41-.153-.682-.764-1.172-1.464-1.172-.822 0-1.5.678-1.5 1.5 0 .178.032.355.094.522 7.021 23.33-6.224 48.328-29.47 55.62z" fill="#4ca586" fill-rule="nonzero"/><path d="m122.39 63.72c-.001.024-.002.048-.002.072 0 .797.636 1.462 1.432 1.498l6.85.31h.07c.178-.002.354-.036.52-.1.169-.069.325-.167.46-.29.141-.135.256-.294.34-.47.075-.167.119-.347.13-.53l.31-6.85c.001-.022.001-.043.001-.065 0-.824-.677-1.501-1.501-1.501-.799 0-1.465.638-1.5 1.436l-.16 3.48c-8.839-8.245-20.473-12.854-32.56-12.9-13.163-.045-25.769 5.385-34.78 14.98-.277.281-.432.659-.432 1.053 0 .823.677 1.5 1.5 1.5.424 0 .828-.179 1.112-.493 8.457-8.98 20.275-14.053 32.61-14 11.119.023 21.845 4.161 30.1 11.61l-2.89-.17c-.037-.003-.074-.004-.111-.004-.798 0-1.464.637-1.499 1.434z" fill="#4ca586" fill-rule="nonzero"/><path d="m76.51 80.72c4.237 1.745 8.141 4.208 11.54 7.28.276.25.635.389 1.008.389.823 0 1.5-.677 1.5-1.5 0-.392-.154-.769-.428-1.049-4.248-3.835-9.209-6.798-14.6-8.72-.468-.157-.984-.074-1.38.22-.395.299-.617.775-.59 1.27.001 2.744-.536 5.462-1.58 8-1.27 3.75-2.71 8-1 14.11 2.75 9.94 10.55 12.47 15.92 12.47.888.005 1.775-.068 2.65-.22.099-.017.196-.044.29-.08l1.25-.49c.175-.072.335-.178.47-.31.265-.249.515-.513.75-.79.243-.276.378-.632.378-1 0-.83-.683-1.513-1.513-1.513-.434 0-.848.187-1.135.513l-.35.38-.81.32c-1.38.21-11.82 1.4-15-10.13-1.44-5.2-.27-8.67 1-12.34.82-2.195 1.368-4.482 1.63-6.81z" fill="#4ca586" fill-rule="nonzero"/><path d="m93.66 135.93h.1c.785-.003 1.443-.617 1.5-1.4 0-.08.19-2.51.82-6.37.78-4.71 1.859-9.366 3.23-13.94 1.224.221 2.466.332 3.71.33 6.52 0 15.34-2.94 19.21-14.19 2.55-7.4 1.15-12.55-.21-17.54-.91-3.34-1.77-6.5-1.37-10.25.056-.491-.135-.978-.51-1.3-.378-.322-.891-.438-1.37-.31-.78.21-19.21 5.28-25.67 18.15-6.62 13.19 3 23.68 3.1 23.78.082.072.169.139.26.2-1.489 4.915-2.638 9.927-3.44 15-.56 3.58-.73 5.87-.76 6.19-.005.048-.007.096-.007.144 0 .789.619 1.452 1.407 1.506zm2.12-45.48c4.67-9.31 16.94-14.3 21.77-16 .108 3.098.64 6.166 1.58 9.12 1.29 4.76 2.51 9.25.26 15.77-4.47 13-15.67 12.45-19.17 11.94 2.533-7.956 6.324-15.454 11.23-22.21.197-.261.303-.578.303-.905 0-.825-.678-1.503-1.503-1.503-.471 0-.916.222-1.2.598-4.993 6.841-8.88 14.423-11.52 22.47-4.195-5.562-4.875-13.053-1.75-19.28z" fill="#4ca586" fill-rule="nonzero"/></svg>';
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

add_action( 'admin_init', 'bbloomer_generate_coupons_admin' );
 
function bbloomer_generate_coupons_admin() { 
   if ( isset( $_REQUEST['bb-gen-coupons'] ) ) {      
      if ( ! current_user_can( 'manage_woocommerce' ) ) {
         wp_die( 'You do not have permission to bulk generate coupons' );
      }     
      $number_of_coupons = 100; // DEFINE BULK QUANTITY     
      for ( $i = 1; $i <= $number_of_coupons; $i++ ) {
         $coupon = new WC_Coupon();
         $random_code = "LC10" . bin2hex( random_bytes( 2 ) ); // 16 CHARS PHP 7+ ONLY
         if ( wc_get_coupon_id_by_code( $random_code ) ) continue; // SKIP IF CODE EXISTS 
         $coupon->set_code( $random_code );
         $coupon->set_description( 'Coupon generated programmatically (' . $i . '/' . $number_of_coupons . ')' );
         $coupon->set_discount_type( 'percent' );
         $coupon->set_amount( 10 );
         $coupon->set_minimum_amount( 1 );
         $coupon->set_individual_use( true );
         $coupon->set_usage_limit(1);
        //  $coupon->set_product_categories( array( 54, 55 ) );
        //  $coupon->set_usage_limit_per_user( 1 );
         $coupon->save();        
      }  
   }
}