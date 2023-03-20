<?php
if (empty($block['data'])) {
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$icon_cards = get_field('icon_cards');
?>

<section class="section icon-grid">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <span class="subtitle icon-grid__subtitle section__subtitle"><?php echo $subtitle;?></span>
                <h2 class="icon-grid__title section__title"><?php echo $title;?></h2>
                <div class="section__content icon-grid__content">
                    <?php echo $content;?>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            <?php $i=1; foreach($icon_cards as $icon_card){?>
                <div class="col-md-4 col-xxl-3 text-center" data-aos="fade-up" data-aos-delay="<?php echo $i * 100;?>">
                   <div class="icon-grid__item">
                       <img src="<?php echo $icon_card['icon']['url'];?>" alt="<?php echo $icon_card['icon']['alt'];?>">
                       <h3><?php echo $icon_card['title'];?></h3>
                      <div data-mh="icon__content">
                          <?php echo $icon_card['content'];?>
                      </div>
                   </div>
                </div>
            <?php $i++; } ?>
        </div>
    </div>
</section>
