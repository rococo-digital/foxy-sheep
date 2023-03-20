<?php
get_header();
$subtitle = get_field('archive_subtitle', 'options');
$title = get_field('archive_title', 'options');
$content = get_field('archive_content', 'options');
?>

<section class="archive-header section bg--green200 text--light archive-uses">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center mx-auto">
                <span class="subtitle archive-header__subtitle"><?php echo $subtitle;?></span>
                <h1 class="archive-header__title"><?php echo $title;?></h1>
               <div class="archive-header__content">
                   <?php echo $content;?>
               </div>
            </div>
        </div>
    </div>
</section>

<div class="archive-posts uses-posts">
    <div class="row">
        <?php if (have_posts()) : ?>
            <?php $i=1; $x=1; while (have_posts()): the_post(); ?>
                <?php
                if ($i == 3 || $i == 4 || $i == 5 ) {
                    $size = '4';
                }else{
                    $size = '6';
                }
                $args = array(
                    $size
                )
                ?>
                <div class="col-md-<?php echo $size;?>" data-aos="fade-up" data-aos-delay="<?php echo $x*100;?>">
                    <?php get_template_part('lib/partials/uses', 'post', $args);?>
                </div>
                <?php
                if($i == 7){
                    $i = 1;
                }else{
                    $i++;
                }
                $x++;
            endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();