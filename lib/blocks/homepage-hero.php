<?php
if(empty($block['data'])){
    return;
}

$title = get_field('title');
$link = get_field('link');
$bg = get_field('background');

?>

<section class="section homepage-hero hero" style="background-image: url(<?php echo $bg['url'];?>)">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-6">
               <div class="hero__content" data-aos="fade-up">
                   <h1><?php echo $title;?></h1>
               </div>
                <div class="hero__link" data-aos="fade-up">
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
        </div>
        <div class="row">
            <div class="masthead__usp">
            <?php inline_svg('sustainable');?>
            <?php inline_svg('carbon-negative');?>
            <?php inline_svg('non-toxic');?>
            <?php inline_svg('bio-degradable');?>
            </div>
        </div>
    </div>
</section>
