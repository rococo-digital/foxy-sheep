const productTabs = {

    init() {
        const button = $('a[data-tab]');

        button.on('click', function(e){
            e.preventDefault();
            var content = $(this).data('tab');

            if($(this).hasClass('product-tabs__active')){
                return;
            }else{
                button.removeClass('product-tabs__active');
                $(this).addClass('product-tabs__active');
                $('[data-content]').hide();
                $('[data-content="'+ content +'"]').show();
            }
        });
    }

};

export { productTabs }