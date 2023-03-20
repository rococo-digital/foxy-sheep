<?php
$left_title = get_field('left_side_header');
$right_title = get_field('form_header');
$form_id = get_field('form_id');

$telephone = get_field('telephone', 'options');
$email = get_field('email', 'options');
$address = get_field('address', 'options');
$google_maps_link = get_field('google_maps_link', 'options');

?>

<section class="form-block section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="section__title form-block__title"><?php echo $left_title;?></h2>
                <div class="form-block__contact">
                    <div><span>T:</span><a href="tel:<?php echo $telephone;?>"><?php echo $telephone;?></a></div>
                    <div><span>E:</span><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></div>
                    <div><span class="d-block">Address:</span><p><?php echo $address;?></p></div>
                    <div><a class="maps-link" href="<?php echo $google_maps_link['url'];?>" target="_blank"><?php echo $google_maps_link['title']; ?></a></div>

                    <div class="social-icons__wrapper">
                        <?php echo social_icons('inline__icons');?>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-wrapper">
                    <h3 class="section__title form-wrapper__title text-center"><?php echo $right_title?></h3>
                    <?php echo do_shortcode('[gravityforms id="'. $form_id .'" title="false" description="false" ajax="true"]');?>
                </div>
            </div>
        </div>
    </div>
</section>
