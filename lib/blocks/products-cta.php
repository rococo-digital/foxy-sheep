<?php
if(empty($block['data'])){
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$products = get_field('products');
$bg = get_field('background');

?>

<section class="section bg--green200 brand-bg g-padding product-cta" style="background-image: url(<?php echo $bg['url'];?>)">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center mx-auto" data-aos="fade-up">
                <div class="">
                    <span class="subtitle section__subtitle"><?php echo $subtitle;?></span>
                    <h2 class="section__title"><?php echo $title;?></h2>
                </div>
            </div>
        </div>
        <?php $count = count($products);?>
        <?php if($count > 4):?>
            <div class="slick-arrows">
                <button class="prod-slide-arrow prev-arrow no-style-button"><i class="fas fa-chevron-left"></i></button>
                <button class="prod-slide-arrow next-arrow no-style-button"><i class="fas fa-chevron-right"></i></button>
            </div>
        <?php endif;?>
        <div class="row <?php if($count > 4):?>slider-init-products justify-content-center<?php endif?>">

            <?php $i=1; foreach ($products as $product){?>

                <?php $product = wc_get_product($product->ID); ?>

                <div class="col-md-6 col-lg-3 d-flex"
                        data-aos="fade-up"
                        data-aos-delay="<?php echo $i * 100;?>">

                    <?php get_template_part('lib/partials/product', 'card', $product); ?>

                </div>

            <?php $i++; } ?>
        </div>

    </div>
</section>
