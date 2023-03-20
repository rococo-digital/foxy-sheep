<?php
global $post;
get_header();
$img_url = get_the_post_thumbnail_url( $post, $size = 'full' ) ?: '';
$content = get_field('content', $post->ID) ?: get_field('content', 348);
$content_l = get_field('content_left', $post->ID) ?: get_field('content_left', 348);
$content_r = get_field('content_right', $post->ID) ?: get_field('content_right', 348);
?>

<section class="section uses-single__hero subpage-hero bg--green200 text--light">

    <div class="row h-100">
        <div class="col-md-12 col-lg-8 offset-lg-4 bg--cover order-md-2" style="background-image: url('<?php echo $img_url;?>')"></div>
    </div>

    <div class="container position-absolute">
        <div class="col-md-6 col-lg-4 center-div order-md-1">
            <div class="subpage-hero__content">
                <span class="archive__subtitle-big"><?php echo _e('Uses:');?></span>
                <h1><?php echo get_the_title($post);?></h1>
                <?php echo $content;?>
            </div>
        </div>
    </div>
</section>

<?php get_breadcrumbs();?>

<div class="uses-single__content">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6">
                <?php echo $content_l;?>
            </div>
            <div class="col-md-6">
                <?php echo $content_r;?>
            </div>
        </div>
    </div>
</div>

<!-- products block -->
<?php

$title = get_field('title') ?: 'Shop our key range';
$subtitle = get_field('subtitle') ?: 'Our products';
$products = get_field('products', $post->ID) ?: get_field('products', 348);

?>

    <section class="section bg--green200 brand-bg g-padding product-cta" style="background-image: url(<?php echo get_template_directory_uri();?>/lib/images/svg/key-features-bg.svg">
        <div class="container">
            <div class="row">
                <div class="col-md-8 text-center mx-auto" data-aos="fade-up">
                    <div class="section__header">
                        <span class="subtitle section__subtitle"><?php echo $subtitle;?></span>
                        <h2 class="section__title"><?php echo $title;?></h2>
                    </div>
                </div>
            </div>

            <div class="row">

                <?php if($products): $i=1; foreach ($products as $product){?>

                    <?php $product = wc_get_product($product->ID); ?>

                    <div class="col-md-6 col-lg-3 d-flex"
                            data-aos="fade-up"
                            data-aos-delay="<?php echo $i * 100;?>">

                        <?php get_template_part('lib/partials/product', 'card', $product); ?>

                    </div>

                    <?php $i++; }  endif; ?>
            </div>

        </div>
    </section>


<?php
get_footer();

