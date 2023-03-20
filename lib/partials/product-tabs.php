<?php

$description = $args['description'];
$custom_tab = $args['custom_tab'];


?>

<div class="product-tabs">
    <div class="product-tabs__header">
        <?php if($description){?>
                <a href="#" class="product-tabs__link product-tabs__active" data-tab="description"><?php echo _e('Description', 'lanocare');?></a>
        <?php } ?>
        <?php if($custom_tab){?>
                <a href="#" class="product-tabs__link" data-tab="ingredients"><?php echo $args['custom_tab_title']?></a>
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
    </div>
</div>
