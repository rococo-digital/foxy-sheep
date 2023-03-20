<?php
if (empty($block['data'])) {
    return;
}
$subtitle = get_field('subtitle');
$title = get_field('title');
$faqs = get_field('faqs');
?>

<section class="section faq-block">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <span class="subtitle faq-block__subtitle"><?php echo $subtitle;?></span>
                <h1 class="title faq-block__title section__title-big"><?php echo $title;?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="faq-block__list accordion" role="tablist">
                    <?php $i=0; foreach($faqs as $faq){  ?>
                        <div class="accordion__item faq-block__item" data-accordion="accordion-item-<?php echo $i;?>" data-aos="fade-up" data-aos-delay="<?php echo $i*100;?>">
                            <button href="#" class="no-style-button">
                                <h3 class="faq-block__item-title accordion__item-title"><?php echo $faq->post_title;?></h3>
                                <div class="accordion__icons">
                                    <i class="fas fa-plus"></i>
                                    <i class="fas fa-minus" style="display: none"></i>
                                </div>
                            </button>
                            <div class="accordion__content" style="display: none" data-index="accordion-item-<?php echo $i;?>">
                                <?php echo $faq->post_content;?>
                            </div>
                        </div>
                    <?php $i++; } ?>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
$faqArray = array();
//Schema
foreach ($faqs as $faq){

    //Build Json

    $faqArray[] = array(
        "@type" => "Question",
        "name" => $faq->post_title,
        "acceptedAnswer" => array(
            "@type" => "Answer",
            "text" =>  strip_tags($faq->post_content)
        )

    );


}

?>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity":<?php echo json_encode($faqArray, JSON_PRETTY_PRINT);?>

}

</script>
