<?php
if(empty($block['data'])){
    return;
}
$title = get_field('title');
$title_heading = get_field('title_heading');
$subtitle = get_field('subtitle');
$content = get_field('content');
$link = get_field('link');
$image = get_field('image');
$icons = get_field('icons');

?>

<section class="section bg--green400 text-image-icons">

 <div class="container">
     <div class="row">

         <div class="col-lg-6" data-aos="zoom-in" class="offset-image__wrapper">
             <div class="offset-image custom-aos" data-aos="custom-fade-light">
                 <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" >
             </div>
         </div>

         <div class="col-lg-6">
             <span class="subtitle section__subtitle text-image-icons__subtitle" data-aos="fade-up"><?php echo $subtitle;?></span>
             <h2 class="section__title text-image-icons__title" data-aos="fade-up" data-aos-delay="200"><?php echo $title;?></h2>
             <div class="section__content text-image-icons__content" data-aos="fade-up" data-aos-delay="300">
                 <?php echo $content;?>
             </div>
         </div>
     </div>

     <div class="row icon__row g-3 g-md-5 justify-content-center">
         <?php $i = 1; foreach ($icons as $icon){?>
                <?php
                    $icon_svg = $icon['icon']['url'];
                    $icon_alt = $icon['icon']['alt'];
                    $title = $icon['title'];
                    $content = $icon['content'];
                ?>

             <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo $i*100;?>">
                <div class="icon">
                    <div class="icon__wrapper">
                        <img src="<?php echo $icon_svg;?>" alt="<?php echo $icon_alt;?>">
                    </div>
                    <div class="icon__title">
                        <h3><?php echo $title;?></h3>
                    </div>
                    <div class="icon__content">
                        <?php echo $content;?>
                    </div>
                </div>
             </div>

         <?php $i++; } ?>
     </div>

 </div>

</section>
