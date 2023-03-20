<?php
global $post;

if(current($args) == 4){
    $img_url = get_the_post_thumbnail_url( $post, $size = 'uses-thumbnail' );

}else{
    $img_url = get_the_post_thumbnail_url( $post, $size = 'uses-thumbnail-big' );
}

$alt = get_post_meta ( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );



?>

<article <?php echo post_class( 'position-relative uses-post');?> style="background-image: url('<?php echo $img_url;?>');">
    <a href="<?php echo get_the_permalink($post);?>" class="overlay-click"></a>
        <h3><?php echo get_the_title($post);?></h3>
        <div class="uses-post__overlay text-center">
            <h3><?php echo get_the_title($post);?></h3>
            <div class="uses-post__excerpt">
                <p><?php echo get_the_excerpt($post->ID);?></p>
            </div>
            <a href="<?php echo get_the_permalink($post);?>"><?php echo _e('View More', 'lanocare');?></a>
        </div>

</article>
