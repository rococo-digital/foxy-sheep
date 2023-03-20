const accordion = {

    init() {
        const button = $('[data-accordion] button');

        button.on('click', function(){
            var item = $(this);
            var parent = item.parent().data('accordion');
            $('[data-index]').slideUp();
            if( $('[data-index="'+ parent +'"]').hasClass('open')){
                $('[data-index="'+ parent +'"]').slideUp();
                $('[data-index="'+ parent +'"]').removeClass('open');
                item.parent().removeClass('open');
                item.parent().find('.fa-plus').show();
                item.parent().find('.fa-minus').hide();
            }else{
                $('[data-index="'+ parent +'"]').slideDown();
                $('[data-index="'+ parent +'"]').addClass('open');
                item.parent().addClass('open');
                item.parent().find('.fa-minus').show();
                item.parent().find('.fa-plus').hide();
            }

        })
    }

};

export { accordion }