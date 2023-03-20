const sliders = {

    init() {
        const sliderInit =  $('.slider-init');
        const sliderInitNav =  $('.slider-init-nav');
        const sliderInitProducts =  $('.slider-init-products');

        var $options = {
            infinite: false,
            slidesToShow: 2.7,
            slidesToScroll: 1,
            nextArrow: $('.prod-slide-arrow .next-arrow'),
            prevArrow: $('.prod-slide-arrow .prev-arrow'),
            asNavFor: '.slider-init-nav',
            responsive: [

                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1.2,
                        slidesToScroll: 1
                    }
                }
        ]

        }

        var $optionsNav = {
            slidesToScroll: 1,
            asNavFor: '.slider-init',
            dots: false,
            centerMode: false,
            arrows: false,
            focusOnSelect: true,
            infinite: false
        }

        sliderInit.slick($options)
        sliderInitNav.slick($optionsNav)

        if(sliderInitProducts[0]){
            sliderInitProducts.slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                dots: true,
                nextArrow: $('.next-arrow'),
                prevArrow: $('.prev-arrow'),
                responsive: [


                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },

                    {
                        breakpoint: 800,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },

                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]

            })
        }

    }

};

export { sliders }