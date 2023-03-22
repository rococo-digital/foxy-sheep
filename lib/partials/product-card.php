<?php

$product = $args;

$product_id = $product->get_id();
$product_name = $product->get_name();
// $short_description = $product->post->post_excerpt;
$image = get_field('image', $product_id);

if(!$image['url']){
    $image = array('url'=> wc_placeholder_img_src('shop-thumbnail'),'alt' => $product_name);
}
?>



<div class="product-card position-relative">
    <a class="overlay-click" href="<?php echo get_permalink($product_id);?>"></a>
    <div class="product-card__image">
        <img src="<?php  echo $image['sizes']['shop-thumbnail']; ?>" data-id="<?php echo $product_id; ?>" alt="<?php echo $image['alt'] ?: $product_name . ' Product Image';?>">
    </div>
    <div class="product-card__body bg--white" data-mh="card-body">
        <h3 class="text-center"><?php echo $product_name;?></h3>
        <div class="product-card__link">
            <a class="button button--primary d-block" href="<?php echo get_permalink($product_id);?>"><?php echo _e('Buy >');?></a>
        </div>
    </div>


</div>
