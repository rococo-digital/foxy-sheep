<?php
if (empty($block['data'])) {
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$products = get_field('products');
?>

<section class="section products-block g-padding bg--green200">
    <div class="container">
        <div class="row">
           <div class="col-md-8 text-center mx-auto" data-aos="fade-up">
               <?php if($subtitle){ ?>
                   <span class="section__subtitle subtitle"><?php echo $subtitle;?></span>
               <?php } ?>
               <div class="products-block__title section__title">
                   <h1><?php echo $title;?></h1>
               </div>

               <div class="products-block__content">
                   <?php echo $content;?>
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
        <div class="row g-4 <?php if($count > 4):?>slider-init-products justify-content-center<?php endif?>">

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

