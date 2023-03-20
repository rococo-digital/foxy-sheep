<?php
if(empty($block['data'])){
    return;
}

global $post;

$title = get_field('title');
$content = get_field('content');
$link = get_field('link');
$image = get_field('image');

?>

<section class="hero section bg--lgrey hero-basic">


    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <div class="hero-basic__content-wrapper">

                    <h1 class="hero-basic__title text--red" data-aos="fade-up"><?php echo $title ?: $post->post_title;?></h1>

                   <div class="hero-basic__content lead" data-aos="fade-up" data-aos-delay="200">
                       <?php echo $content;?>
                   </div>

                    <div class="link-wrapper" data-aos="fade-up" data-aos-delay="400">
                        <?php
                        if( $link ):
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="button button--primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
                        <?php endif; ?>
                    </div>

                </div>

            </div>

            <div class="col-md-6" data-aos="fade-right" data-aos-delay="800">
                <img class="hero-basic__image" src="<?php echo $image['url'];?>" alt="<?php echo $image['alt'];?>">
            </div>

        </div>

        <img class="hero-basic__bg" src="<?php echo get_template_directory_uri();?>/lib/images/svg/hero-element-bg.svg" alt="" data-aos="fade-left" data-aos-delay="1200">


    </div>



</section>
