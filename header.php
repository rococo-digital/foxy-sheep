<?php
    $logo = get_field('logo', 'options');
    $src = wp_get_attachment_image_src( $logo, $size = 'full' );
    $alt_text = get_post_meta($logo, '_wp_attachment_image_alt', true);
    $telephone = get_field('telephone', 'options');
    $items_count = WC()->cart->get_cart_contents_count();
?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
    <head>
        <meta HTTP-EQUIV="Content-type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=9;IE=10;IE=11;IE=Edge,chrome=1"/>
        <title><?php if(is_front_page()): bloginfo(); echo  ' | '; endif; ?><?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <script src="<?php bloginfo('template_directory'); ?>/modernizr-2.8.0.min.js"></script>
        <?php wp_head(); ?>
	</head>

    	<body <?php body_class(); ?>>

            <div class="top-bar bg--green400 text--light">
                <div class="container position-relative">
                    <div class="row">
                        <div class="col-6">
                            <?php
                                social_icons('top-bar__socials social-icons social-icons--flat');
                            ?>
                        </div>
                        <div class="col-6 mr-0 ml-auto">

                            <div class="top-bar__icons">
                                <div class="my-account">
                                    <a href="<?php echo wc_get_account_endpoint_url('dashboard');?>"><?php inline_svg('account');?> <span><?php _e('Account', 'lanocare');?></span></a>
                                </div>
                                <div id="mini-cart-count" class="cart-icon">
                                    <a href="<?php echo wc_get_cart_url();?>">
                                        <?php inline_svg('basket');?>
                                        <span>
                                            <span><?php _e('Basket', 'lanocare');?></span> (<span class="cart-qty"><?php echo $items_count ? $items_count : '0'; ?></span>)
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
<!--                    <div class="widget_shopping_cart_content">-->
<!--                        --><?php //woocommerce_mini_cart(); ?>
<!--                    </div>-->
                </div>
            </div>


            <header class="masthead__wrapper" id="masthead-wrapper">

               <div class="masthead" id="masthead">
                   <div class="container">
                       <div class="row align-items-center">


                           <div class="col-md-12">
                               <div class="masthead__columns">
                                   <div class="masthead__logo">
                                       <a href="<?php echo home_url('/');?>">
                                           <img src="<?php echo esc_attr($src[0]);?>" alt="<?php echo $alt_text;?>" width="272" height="83" class="masthead__logo">
                                       </a>
                                   </div>
                                   <div class="masthead__navigation d-none d-xl-flex">
                                       <?php wp_nav_menu(
                                           array(
                                               'theme_location' => 'header',
                                               'menu_class' => 'masthead__nav'
                                           )
                                       )?>
                                   </div>
                                   <div class="masthead__side me-0 ms-auto">
                                       <div class="masthead__misc">
                                           <div class="masthead__search">
                                               <button data-search class="no-style-button">
                                                   <?php inline_svg('search');?>
                                               </button>
                                           </div>
                                           <div class="masthead__tel">
                                               <a href="tel:<?php echo $telephone;?>">
                                                   <?php inline_svg('mobile');?>
                                                   <span class="d-none d-xl-block"><?php echo $telephone;?></span>
                                               </a>
                                           </div>
                                           <div class="masthead__burger d-xl-none">
                                               <?php inline_svg('burger');?>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>

                <div class="search-form" style="display: none;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <?php get_search_form();?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="masthead__sidenav">
                    <div class="masthead__sidenav-inner">
                        <?php wp_nav_menu(
                            array(
                                'theme_location' => 'header',
                                'menu_class' => 'masthead__nav'
                            )
                        )?>
                    </div>
                </div>

            </header>



            <main>