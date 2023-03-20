<?php

$title = get_field('title');
$content = get_field('content');
$image = get_field('image');
$img_url = $image['url'];
?>

<section class="section uses-single__hero subpage-hero bg--green200 text--light">

    <div class="row h-100">
        <div class="col-md-12 col-lg-8 offset-lg-4 bg--cover order-md-2" style="background-image: url('<?php echo $img_url;?>')"></div>

    </div>

    <div class="container position-absolute">
        <div class="col-md-6 col-lg-4 center-div order-md-1">
            <div class="subpage-hero__content">
                <h1 class="subpage-hero__title"><?php echo $title;?></h1>
                <?php echo $content;?>
            </div>
        </div>
    </div>
</section>
