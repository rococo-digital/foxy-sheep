<?php

//footer
$pre_footer_content = get_field('pre_footer_content', 'options');
$pre_footer_form = get_field('pre_footer_form', 'options');
$footer_cta_title = $pre_footer_content['title'];
$include_global_number = $pre_footer_content['include_global_number'];
$include_global_email = $pre_footer_content['include_global_email'];
$telephone = get_field('telephone', 'options');
$email = get_field('email', 'options');

$footer_cta_form_title = $pre_footer_form['title'];
$form_id = $pre_footer_form['form_id'];
?>

<section class="section archive-footer footer-form-cta bg--green000">

    <div class="container">
        <div class="row g-4">
            <div class="col-md-12 col-lg-5">
                <h2 class="footer-form-cta__title"><?php echo $footer_cta_title;?></h2>
                <?php if($include_global_email && $include_global_number){?>
                    <div class="footer-form-cta__details">
                        <?php if($include_global_number){?>
                            <a href="tel:<?php echo $telephone;?>">
                                <?php inline_svg('mobile-big');?>
                                <?php echo $telephone;?>
                            </a>
                        <?php } ?>
                        <?php if($include_global_email){?>
                            <a href="mailto:<?php echo $email;?>">
                                <?php inline_svg('email-big');?>
                                <?php echo $email;?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-12 col-lg-6 offset-lg-1">
                <h2 class="footer-form-cta__form-title"><?php echo $footer_cta_form_title;?></h2>
                <div class="form-wrapper">
                </div>
            </div>
        </div>
    </div>

</section>