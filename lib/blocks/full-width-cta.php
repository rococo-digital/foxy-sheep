<?php
if (empty($block['data'])) {
    return;
}
$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$link = get_field('link');
$overlay_colour = get_field('background_overlay');
$bg = get_field('background_image');
?>

<section class="section full-width-cta text--light w-overlay" style="background-image: url(<?php echo $bg['url'];?>)">
    <div class="overlay" style="background: <?php echo $overlay_colour;?>; opacity: 0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center" data-aos="fade-up">
                <div class="subtitle"><?php echo $subtitle;?></div>
                <p><?php echo $title;?></p>
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
</section>
