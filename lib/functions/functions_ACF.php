<?php

if( function_exists('acf_add_options_sub_page') ) {

    $parent = acf_add_options_page(array(
        'page_title' 	=> 'Theme General Settings',
        'menu_title'	=> 'Theme Settings',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));

    acf_add_options_sub_page(array(
        'page_title'  => __('Header Options'),
        'menu_title'  => __('Header Options'),
        'parent_slug' => $parent['menu_slug'],
    ));

    acf_add_options_sub_page(array(
        'page_title'  => __('Footer Options'),
        'menu_title'  => __('Footer Options'),
        'parent_slug' => $parent['menu_slug'],
    ));

    acf_add_options_sub_page(array(
        'page_title'  => __('Global Options'),
        'menu_title'  => __('Global Options'),
        'parent_slug' => $parent['menu_slug'],
    ));

    acf_add_options_sub_page(array(
        'page_title'  => __('Archive Options'),
        'menu_title'  => __('Archive Options'),
        'parent_slug' => $parent['menu_slug'],
    ));

}

/**
 * ACF save json file
 */

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

function my_acf_json_save_point( $path ) {

    // update path
    $path = get_template_directory() . '/lib/acf-json';

    // return
    return $path;

}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

/**
 * ACF json load point
 * @param $paths
 * @return mixed
 */

function my_acf_json_load_point( $paths ) {

    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = get_template_directory() . '/lib/acf-json';


    // return
    return $paths;

}

/**
 * ACF blocks init
 *
 */


add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'Homepage Hero',
            'title'             => __('Homepage Hero'),
            'description'       => __('Homepage Hero'),
            'render_template'   => 'lib/blocks/homepage-hero.php',
            'category'          => 'formatting',
            'keywords'          => 'hero',
        ));

        acf_register_block_type(array(
            'name'              => 'Products CTA',
            'title'             => __('Products CTA'),
            'description'       => __('Products CTA'),
            'render_template'   => 'lib/blocks/products-cta.php',
            'category'          => 'formatting',
            'keywords'          => 'cta',
        ));

        acf_register_block_type(array(
            'name'              => 'Text Image',
            'title'             => __('Text Image'),
            'description'       => __('Text Image'),
            'render_template'   => 'lib/blocks/text-image.php',
            'category'          => 'formatting',
            'keywords'          => 'text',
        ));

        acf_register_block_type(array(
            'name'              => 'Text Image Icons',
            'title'             => __('Text Image Icons'),
            'description'       => __('Text Image Icons'),
            'render_template'   => 'lib/blocks/text-image-icons.php',
            'category'          => 'formatting',
            'keywords'          => 'text',
        ));

        acf_register_block_type(array(
            'name'              => 'Full Width CTA',
            'title'             => __('Full width cta'),
            'description'       => __('Full Width CTA'),
            'render_template'   => 'lib/blocks/full-width-cta.php',
            'category'          => 'formatting',
            'keywords'          => 'cta',
        ));

        acf_register_block_type(array(
            'name'              => 'Blog Slider',
            'title'              => 'Blog Slider',
            'render_template'   => 'lib/blocks/blog-slider.php',
            'category'          => 'formatting',
            'keywords'          => 'blogs',
        ));

        acf_register_block_type(array(
            'name'              => 'Footer CTA',
            'title'              => 'Footer CTA',
            'render_template'   => 'lib/blocks/footer-cta.php',
            'category'          => 'formatting',
            'keywords'          => 'cta',
        ));

        acf_register_block_type(array(
            'name'              => 'Subpage Hero',
            'title'              => 'Subpage Hero',
            'render_template'   => 'lib/blocks/subpage-hero.php',
            'category'          => 'formatting',
            'keywords'          => 'cta',
        ));

        acf_register_block_type(array(
            'name'              => 'Card CTA',
            'title'              => 'Card CTA',
            'render_template'   => 'lib/blocks/card-cta.php',
            'category'          => 'formatting',
            'keywords'          => 'cta',
        ));

        acf_register_block_type(array(
            'name'              => 'Form',
            'title'              => 'Form',
            'render_template'   => 'lib/blocks/form-block.php',
            'category'          => 'formatting',
            'keywords'          => 'form',
        ));

        acf_register_block_type(array(
            'name'              => 'Products Block',
            'title'              => 'Products Block',
            'render_template'   => 'lib/blocks/products-block.php',
            'category'          => 'formatting',
            'keywords'          => 'product',
        ));

        acf_register_block_type(array(
            'name'              => 'Icon Grid',
            'title'              => 'Icon Grid',
            'render_template'   => 'lib/blocks/icon-grid.php',
            'category'          => 'formatting',
            'keywords'          => 'icon',
        ));

        acf_register_block_type(array(
            'name'              => 'USP Grid',
            'title'              => 'USP Grid',
            'render_template'   => 'lib/blocks/usp-grid.php',
            'category'          => 'formatting',
            'keywords'          => 'usp',
        ));

        acf_register_block_type(array(
            'name'              => 'FAQ Block',
            'title'              => 'FAQ Block',
            'render_template'   => 'lib/blocks/faq-block.php',
            'category'          => 'formatting',
            'keywords'          => 'faq',
        ));

        acf_register_block_type(array(
            'name'              => 'Form CTA',
            'title'              => 'Form CTA',
            'render_template'   => 'lib/blocks/form-cta.php',
            'category'          => 'formatting',
            'keywords'          => 'form',
        ));

        acf_register_block_type(array(
            'name'              => 'Archive Header',
            'title'              => 'Archive Header',
            'render_template'   => 'lib/blocks/archive-header.php',
            'category'          => 'formatting',
            'keywords'          => 'archive',
        ));

        acf_register_block_type(array(
            'name'              => 'Benefits',
            'title'              => 'Benefits',
            'render_template'   => 'lib/blocks/benefits.php',
            'category'          => 'formatting',
            'keywords'          => 'benefits',
        ));

        acf_register_block_type(array(
            'name'              => 'Contact Header',
            'title'              => 'Contact Header',
            'render_template'   => 'lib/blocks/contact-header.php',
            'category'          => 'formatting',
            'keywords'          => 'contact',
        ));

        acf_register_block_type(array(
            'name'              => 'Basic Content',
            'title'              => 'Basic Content',
            'render_template'   => 'lib/blocks/basic-content.php',
            'category'          => 'formatting',
            'keywords'          => 'basic content',
        ));

    }
}