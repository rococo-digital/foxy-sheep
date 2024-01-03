<?php

add_theme_support('woocommerce');
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10);


add_filter('gettext',

    function ($translated_text, $text, $domain) {
        if ($domain == 'woocommerce') {
            switch ($translated_text) {
                case 'Cart totals':
                    $translated_text = __('Order summary', 'woocommerce');
                    break;
//                case 'Update cart':
//                    $translated_text = __('Update basket', 'woocommerce');
//                    break;
//                case 'Add to cart':
//                    $translated_text = __('Add to basket', 'woocommerce');
//                    break;
//                case 'View cart':
//                    $translated_text = __('View basket', 'woocommerce');
//                    break;
            }
        }
        return $translated_text;
    },
    20, 3);

/**
 * Removals
 */

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Change number of related products output
 */
function woo_related_products_limit() {
    global $product;

    $args['posts_per_page'] = 3;
    return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'woo_related_products_limit', 20 );

add_filter(  'gettext',  'wps_translate_words_array'  );
add_filter(  'ngettext',  'wps_translate_words_array'  );
function wps_translate_words_array( $translated ) {
    $words = array(
        // 'word to translate' = > 'translation'
        'Related Products' => 'You may also like',
    );
    $translated = str_ireplace(  array_keys($words),  $words,  $translated );
    return $translated;
}

// -------------
// 1. Show plus minus buttons

add_action( 'woocommerce_after_quantity_input_field', 'bbloomer_display_quantity_plus' );

function bbloomer_display_quantity_plus() {
    echo '<button type="button" class="plus">+</button>';
}

add_action( 'woocommerce_before_quantity_input_field', 'bbloomer_display_quantity_minus' );

function bbloomer_display_quantity_minus() {
    echo '<button type="button" class="minus">-</button>';
}

// -------------
// 2. Trigger update quantity script

add_action( 'wp_footer', 'bbloomer_add_cart_quantity_plus_minus' );

function bbloomer_add_cart_quantity_plus_minus() {

    if ( ! is_product() && ! is_cart() ) return;

    wc_enqueue_js( "   
           
      $(document).on( 'click', 'button.plus, button.minus', function() {
  
         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));
 
         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
            }
         }
 
      });
        
   " );
}

//Hide Price Range for WooCommerce Variable Products
add_filter( 'woocommerce_variable_sale_price_html', 'variable_product_price', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'variable_product_price', 10, 2 );

function variable_product_price( $v_price, $v_product ) {

    // Product Price
    $prod_prices = array( $v_product->get_variation_price( 'min', true ),
        $v_product->get_variation_price( 'max', true ) );
    $prod_price = $prod_prices[0]!==$prod_prices[1] ? sprintf(__('%1$s', 'woocommerce'),
        wc_price( $prod_prices[0] ) ) : wc_price( $prod_prices[0] );

    // Regular Price
    $regular_prices = array( $v_product->get_variation_regular_price( 'min', true ),
        $v_product->get_variation_regular_price( 'max', true ) );
    sort( $regular_prices );
    $regular_price = $regular_prices[0]!==$regular_prices[1] ? sprintf(__('%1$s','woocommerce')
        , wc_price( $regular_prices[0] ) ) : wc_price( $regular_prices[0] );

    if ( $prod_price !== $regular_price ) {
        $prod_price = '<del>'.$regular_price.$v_product->get_price_suffix() . '</del> <ins>' .
            $prod_price . $v_product->get_price_suffix() . '</ins>';
    }
    return $prod_price;
}

add_action( 'woocommerce_before_single_product', 'move_variations_single_price', 1 );

function move_variations_single_price(){
    global $product, $post;
    if ( $product->is_type( 'variable' ) ) {
        add_action( 'woocommerce_single_product_summary', 'replace_variation_single_price', 10 );
    }
}

function replace_variation_single_price() {
    ?>
    <style>
        .woocommerce-variation-price {
            display: none;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            var priceselector = '.product p.price';
            var originalprice = $(priceselector).html();

            $( document ).on('show_variation', function() {
                $(priceselector).html($('.single_variation .woocommerce-variation-price').html());
            });
            $( document ).on('hide_variation', function() {
                $(priceselector).html(originalprice);
            });
        });
    </script>
    <?php
}

function product_short_desc(){
    global $post;

    echo $post->post_excerpt;

}

add_action('woocommerce_template_single_excerpt', 'product_short_desc', 10);




/**
 * Additions
 */


function single_custom_tabs(){
    global $post;
    $id = $post->ID;

    $tabs_desc = get_field('description', $id) ?: get_field('description', 71);
    $custom_tab = get_field('custom_tab', $id);
    $custom_tab_title = get_field('custom_tab_title', $id);
    $faq_tab = get_field('faq_tab', $id);

   echo '<div class="container"><div class="row"><div class="col-lg-12">';
    get_template_part('lib/partials/product', 'tabs', array(
        'description' => $tabs_desc,
        'custom_tab' => $custom_tab,
        'custom_tab_title' => $custom_tab_title,
        'faq_tab' => $faq_tab
    ));
    echo '</div></div></div>';

}

add_action('woocommerce_after_single_product_summary', 'single_custom_tabs', 10);

function single_custom_icons(){
    global $post;
    $id = $post->ID;
    $icons = get_field('icons', $id) ?: get_field('icons', 71) ;

    if(!$icons){
        return;
    }

    get_template_part('lib/partials/product', 'icons', $icons);


}

add_action('woocommerce_single_product_summary', 'single_custom_icons', 50);


//add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'filter_dropdown_args', 10 );
//
//function filter_dropdown_args( $args ) {
//    $args['show_option_none'] = apply_filters( 'the_title', 'Choose Size:' );
//    print_data($args);
//    return $args;
//}

//remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');

function woocommerce_ajax_add_to_cart() {
   
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();


    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
            'cart_count' => $quantity
        );

        echo wp_send_json($data);
    }

    wp_die();
}


/**
 * remove notice
 */

add_filter( 'wc_add_to_cart_message_html', '__return_false' );
add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999 );

function custom_remove_downloads_my_account( $items ) {
    unset($items['downloads']);
    return $items;
}
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
function ShowOneError( $fields, $errors ){

    // if any validation errors

    if( !empty( $errors->get_error_codes() ) ) {

        // remove all of Error msg

        foreach( $errors->get_error_codes() as $code ) {

            $errors->remove( $code );

        }

        // our custom Error msg

        $errors->add('validation','There is an error in filed data.');

    }

}

//add_action('woocommerce_after_checkout_validation','ShowOneError',999,2);

//add_action( 'woocommerce_after_checkout_validation', 'misha_validate_fname_lname', 10, 2);


add_action( 'wp_footer', 'misha_checkout_js' );
function misha_checkout_js(){

    // we need it only on our checkout page
    if( !is_checkout() ) return;

    ?>
    <script>
        jQuery(function($){
            console.log('loaded')
            $('.validate-required .woocommerce-input-wrapper input').addClass('invalid)')
        });
    </script>
    <?php
}
add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){

//    unset( $menu_links['edit-address'] ); // Addresses


    //unset( $menu_links['dashboard'] ); // Remove Dashboard
    //unset( $menu_links['payment-methods'] ); // Remove Payment Methods
    //unset( $menu_links['orders'] ); // Remove Orders
    //unset( $menu_links['downloads'] ); // Disable Downloads
    //unset( $menu_links['edit-account'] ); // Remove Account details tab
    //unset( $menu_links['customer-logout'] ); // Remove Logout link

    return $menu_links;

}
/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available( $rates ) {
    $free = array();
   
    foreach ( $rates as $rate_id => $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free[ $rate_id ] = $rate;
            break;
        }
    }
    return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );
//eof