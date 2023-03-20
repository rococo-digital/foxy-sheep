<?php

$title = get_field('title');
$content = get_field('content');

?>

<section class="section contact-header bg--green200 text--light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="section__title-big"><?php echo $title;?></h1>
                <div class="section__content">
                    <?php echo $content;?>
                </div>
            </div>
        </div>
    </div>
</section>
