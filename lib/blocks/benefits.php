<?php
$subtitle = get_field('subtitle');
$title = get_field('title');
$content = get_field('content');
$benefits = get_field('benefits');
?>

<section class="section benefits bg--green200">

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center" data-aos="fade-up">
                <span class="subtitle section__subtitle benefits__subtitle"><?php echo $subtitle;?></span>
                <h1 class="section__title-big benefits__title"><?php echo $title;?></h1>
                <div class="section__content benefits__content">
                    <?php echo $content;?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <?php foreach ($benefits as $benefit){ ?>


                    <?php if($benefit['text_position'] == 'left'){
                        $justify = 'justify-content-start';
                        ?>

                        <div class="benefits-item__content <?php echo $justify;?> text-left" data-aos="fade-up">
                            <div class="benefits-item__text">
                                <h3><?php echo $benefit['title'];?></h3>
                                <?php echo $benefit['content'];?>
                                <?php
                                $link = $benefit['link'];
                                if( $link ):
                                    $link_url = $link['url'];
                                    $link_title = $link['title'];
                                    $link_target = $link['target'] ? $link['target'] : '_self';
                                    ?>
                                    <a class="button button--primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?> ></a>
                                <?php endif; ?>
                            </div>
                            <div class="benefits-item__image" >
                                <img src="<?php echo $benefit['image']['sizes']['benefits-thumbnail'];?>" alt="">
                            </div>
                        </div>

                    <?php

                    }else{
                        $justify = 'justify-content-end';?>

                        <div class="benefits-item__content <?php echo $justify;?> text-right" data-aos="fade-up">
                            <div class="benefits-item__image">
                                <img src="<?php echo $benefit['image']['sizes']['benefits-thumbnail'];?>" alt="">
                            </div>
                            <div class="benefits-item__text">
                                <h3><?php echo $benefit['title'];?></h3>
                                <?php echo $benefit['content'];?>
                                <?php
                                $link = $benefit['link'];
                                if( $link ):
                                    $link_url = $link['url'];
                                    $link_title = $link['title'];
                                    $link_target = $link['target'] ? $link['target'] : '_self';
                                    ?>
                                    <a class="button button--primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?> ></a>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php
                    }?>


            <?php } ?>
            </div>
        </div>

    </div>

</section>
