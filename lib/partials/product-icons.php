<?php
$icons = $args;
?>

<div class="product-icons">
    <?php $i=1;foreach ($icons as $icon){ ?>

        <div class="product-icons__icon" data-aos="zoom-in" data-aos-delay="<?php echo $i * 100;?>">
            <img src="<?php echo $icon['icon']['url'];?>" alt="<?php echo $icon['icon']['alt'] ?: 'Icon';?>" width="78" height="65">
        </div>

    <?php $i++;}?>
</div>
