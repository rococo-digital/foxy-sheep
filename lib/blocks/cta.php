<?php
if(empty($block['data'])){
    return;
}

$title = get_field('title');
$content = get_field('content');
$link = get_field('link');
$image = get_field('image');
$bg_image = get_field('background_image');

?>

<section class="section cta bg--dgrey">

     <div class="container">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <h2 class="cta__title" data-aos="fade-up" data-aos-delay="200"><?php echo $title;?></h2>
                 <div class="cta__content-wrapper">
                     <div class="cta__content" data-aos="fade-up" data-aos-delay="400">
                         <?php echo $content;?>
                     </div>
                 </div>
                 <div class="link-wrapper" data-aos="fade-up" data-aos-delay="600">
                    <?php
                    if( $link ):
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn btn--primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
                    <?php endif; ?>

                 </div>
             </div>

             <div class="col-md-6 pl-0" data-aos="fade-right" data-aos-delay="800">
                 <img src="<?php echo $image['url'];?>" alt="">
             </div>


         </div>

         <img class="cta__bg" src="<?php echo $bg_image['url'];?>" alt="<?php echo $bg_image['alt'];?>" data-aos="fade-left" data-aos-delay="1200">

     </div>


</section>
