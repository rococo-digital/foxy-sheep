<?php
if (empty($block['data'])) {
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$icon_cards = get_field('icon_cards');
?>

<section class="section usp-grid bg--green400">
    <div class="container">
        <div class="row justify-content-center">
            <?php $i=1; foreach($icon_cards as $icon_card){?>
                <div class="col-md-4 col-xxl-3 text-center" data-aos="fade-up" data-aos-delay="<?php echo $i * 100;?>">
                   <div class="usp-grid__item">
                       <img src="<?php echo $icon_card['icon']['url'];?>" alt="<?php echo $icon_card['icon']['alt'];?>">
                   </div>
                </div>
            <?php $i++; } ?>
        </div>
    </div>
</section>
