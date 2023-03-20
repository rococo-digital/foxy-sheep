<?php
if(empty($block['data'])){
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$link = get_field('link');
$image_front = get_field('image_front');
$image_back = get_field('image_back');
$remove_bottom_padding = get_field('remove_bottom_padding');
?>

<section class="bg--white section text-image <?php if($remove_bottom_padding):?> pb-lg-0 <?php endif;?>">

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <span class="subtitle section__subtitle" data-aos="fade-up"><?php echo $subtitle;?></span>
                <h2 class="section__title text-image__title" data-aos="fade-up" data-aos-delay="200"><?php echo $title;?></h2>
                <div class="section__content text-image__content" data-aos="fade-up" data-aos-delay="300">
                    <?php echo $content;?>
                </div>
                <div class="section__link" data-aos="fade-up" data-aos-delay="400">
                    <?php
                    if( $link ):
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="button button--primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?> ></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6" data-aos="zoom-in">
                <div class="text-image__back custom-aos" data-aos="custom-fade-light">
                    <img src="<?php echo $image_back['url'];?>" alt="<?php echo $image_back['alt'];?>">
                </div>
                <div class="text-image__front custom-aos" data-aos="custom-fade-dark">
                    <img src="<?php echo $image_front['sizes']['side-image'];?>" alt="<?php echo $image_front['alt'];?>">
                </div>
            </div>
        </div>
    </div>

</section>
