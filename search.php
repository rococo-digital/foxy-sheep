<?php
/**
 * Search Page
 */
global $wp_query;

$page_id = get_queried_object_id();
$post = get_post($page_id);

//header

$subtitle = 'Search results for';
$title = $_GET['s'];


get_header(); ?>

    <section class="section archive-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8 text-center mx-auto">
                    <span class="subtitle archive-header__subtitle"><?php echo $subtitle;?></span>
                    <h1 class="archive-header__title"><?php echo $title;?></h1>
                </div>
            </div>
        </div>
    </section>


    <div class="archive-blog">
        <div class="container">
            <div class="row">
                <?php if (have_posts()) : ?>
                    <?php $i=1; while (have_posts()): the_post(); ?>

                        <div class="col-md-4">
                            <?php get_template_part('lib/partials/search', 'post');?>
                        </div>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <div id="results"></div>

            <?php if($wp_query->found_posts > 8){ ?>

                <div class="row loadmore__wrapper">
                    <div class="col-md-12 text-center">
                        <a href="#" class="loadmore button button--primary" data-loadmore><span class="text"><?php echo _e('Older Posts >');?></span> <span class="d-none loading__icon"><img src="<?php echo get_template_directory_uri();?>/lib/images/rolling.gif" alt=""></span></a>
                    </div>
                </div>

            <?php } ?>

        </div>
    </div>

<?php get_footer(); ?>