<?php
get_header();
global $post;
$image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
$page_id = get_option( 'page_for_posts' );

?>

<section class="blog-single__header" style="background-image: url( <?php echo $image;?> )">
</section>

<?php get_breadcrumbs();?>

<div class="blog-single">
    <div class="container">
        <div class="row">
            <div class="col-md-8 border-right">
                <article>
                    <h1 data-aos="fade-up"><?php echo get_the_title();?></h1>
                    <div data-aos="fade-up">
                        <?php echo the_content();?>
                    </div>
                    <div data-aos="fade-up">
                        <?php echo get_social_share();?>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <aside class="blog-single__sidebar" data-aos="fade-up">
                    <span class="posted-on"><?php inline_svg('calendar');?> <?php echo _e('Posted on') . ' ' . get_the_date('j F y');?></span>

                    <?php

                        $args = array(
                                'post_type' => 'post',
                                'post_not__in' => array($post->ID),
                                'posts_per_page' => 3,
                                'post_status' => 'publish'
                        );

                        $query = new WP_Query($args);

                        if($query->have_posts()):
                            while($query->have_posts()) : $query->the_post();
                                get_template_part('lib/partials/blog', 'post', array('4', 'text-align'=>'text-start'));
                            endwhile;
                        else:
                            echo '<p>Sorry, no posts found</p>';

                        endif;
                    ?>

                </aside>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();