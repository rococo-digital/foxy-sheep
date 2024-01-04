const productTabs = {

    init() {
        
        const button = $('a[data-tab]');

        //Detect if #reviews param is in the url and open the tab
        let anchor = $(location).attr('hash');
        if(anchor == '#reviews'){
            button.removeClass('product-tabs__active');
            $('#reviews').addClass('product-tabs__active');
            $('[data-content]').hide();
            $('[data-content="reviews"]').show();
        }

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