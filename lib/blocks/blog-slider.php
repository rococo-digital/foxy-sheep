<?php
if (empty($block['data'])) {
    return;
}
$title = get_field('title');
$articles = get_field('articles');
$all_posts_link = get_field('all_posts_link');

?>

<section class="blog-slider section">


    <div class="container">

        <div class="row align-items-center title-row">
            <div class="col-8 col-md-10">
                <h2><?php echo $title;?></h2>
            </div>
            <div class="col-4 col-md-2">
               <div class="slick-arrows">
                   <button class="slide-arrow prev-arrow no-style-button"><i class="fas fa-chevron-left"></i></button>
                   <button class="slide-arrow next-arrow no-style-button"><i class="fas fa-chevron-right"></i></button>
               </div>
            </div>
        </div>

        <div class="row">

           <div class="col-md-12">

               <div class="slider-init"  data-slick='{"autoplay": true}'>
                   <?php foreach($articles as $article){ ?>
                       <?php get_template_part('lib/partials/blog', 'card', $article);?>
                   <?php } ?>
               </div>

             <div class="slider-init-nav__wrapper d-none d-md-flex">
                 <div class="slider-init-nav" data-slick='{"slidesToShow": <?php echo count($articles);?>}'>
                     <?php foreach($articles as $article){ ?>
                         <div class="scroll-bar"></div>
                     <?php } ?>
                 </div>

                 <?php if($all_posts_link){
                     $link = $all_posts_link;
                     $link_url = $link['url'];
                     $link_title = $link['title'];
                     $link_target = $link['target'] ? $link['target'] : '_self';
                     ?>
                     <a class="inline-link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?> <i class="fas fa-caret-right"></i></a>

                 <?php } ?>

             </div>

           </div>

        </div>

    </div>

</section>
