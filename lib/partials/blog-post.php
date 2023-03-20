<?php
global $post;

if(current($args) == 4){
    $img_url = get_the_post_thumbnail_url( $post, $size = 'blog-thumbnail' );
}else{
    $img_url = get_the_post_thumbnail_url( $post, $size = 'blog-thumbnail-big' );
}
if(isset($args['text-align'])){
    $text_position = 'text-start';
}else{
    $text_position = 'text-center';
}
?>

<article <?php echo post_class($text_position . ' position-relative blog-post');?>>
    <a href="<?php echo get_permalink($post->ID);?>" class="overlay-click"></a>
    <div class="blog-post__featured-image">
        <img src="<?php echo $img_url;?>" alt="">
    </div>
    <span class="the-date"><?php echo get_the_date('j M, y');?></span>
    <h3 class="blog-post__title"><?php echo get_the_title();?></h3>
</article>
