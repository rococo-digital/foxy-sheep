<?php

global $product;
global $post;
$id = $post->ID;

$description = $args['description'];
$custom_tab = $args['custom_tab'];
$faq_tab = $args['faq_tab'];
$review_tab = $product->get_review_count();

?>

<div class="product-tabs">
    <div class="product-tabs__header">
        <?php if($description){?>
                <a href="#" class="product-tabs__link product-tabs__active" data-tab="description"><?php echo _e('Description', 'lanocare');?></a>
        <?php } ?>
        <?php if($custom_tab){?>
                <a href="#" class="product-tabs__link" data-tab="ingredients"><?php echo $args['custom_tab_title']?></a>
        <?php } ?>
        <?php if($faq_tab){?>
                <a href="#" class="product-tabs__link" data-tab="faqs">FAQ's</a>
        <?php } ?>
        <?php if($review_tab){?>
                <a href="#" class="product-tabs__link" data-tab="reviews">Reviews</a>
        <?php } ?>
    </div>
    <div class="product-tabs__content">
        <?php if($description){?>
            <div data-content="description">
                <?php echo $description;?>
            </div>
        <?php } ?>
        <?php if($custom_tab){?>
            <div data-content="ingredients" style="display: none">
                <?php echo $custom_tab;?>
            </div>
        <?php } ?>
        <?php if($faq_tab){?>
            <div data-content="faqs" style="display: none">
                <div class="col-md-12">
                    <div class="faq-block__list accordion" role="tablist">
                        <?php $i=0; foreach($faq_tab as $faq){  ?>
                            <div class="accordion__item faq-block__item" data-accordion="accordion-item-<?php echo $i;?>" data-aos="fade-up" data-aos-delay="<?php echo $i*100;?>">
                                <button href="#" class="no-style-button">
                                    <h3 class="faq-block__item-title accordion__item-title"><?php echo $faq->post_title;?></h3>
                                    <div class="accordion__icons">
                                        <i class="fas fa-plus"></i>
                                        <i class="fas fa-minus" style="display: none"></i>
                                    </div>
                                </button>
                                <div class="accordion__content" style="display: none" data-index="accordion-item-<?php echo $i;?>">
                                    <?php echo $faq->post_content;?>
                                </div>
                            </div>
                        <?php $i++; } ?>
                    </div>
                </div>
           
            </div>
        <?php } ?>
        <?php if($review_tab){?>
            <div data-content="reviews" style="display: none">
                <?php
                    comments_template('/single-product-reviews.php' ); 

                    
                ?>


            </div>
        <?php } ?>
    </div>
</div>
