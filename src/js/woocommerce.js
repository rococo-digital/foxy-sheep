const variationsDropdown = {

    init() {

    }

};

export { variationsDropdown }

const addToCartAjax = {

    init() {
        $( document ).on( 'click', '.single_add_to_cart_button', function(e) {
            e.preventDefault();

            var $thisbutton = $(this),
                $form = $thisbutton.closest('form.cart'),
                id = $thisbutton.val(),
                product_qty = $form.find('input[name=quantity]').val() || 1,
                product_id = $form.find('input[name=product_id]').val() || id,
                variation_id = $form.find('input[name=variation_id]').val() || 0;

            var data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };

            console.log(data);

            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

            $.ajax({
                type: 'post',
                url: loadmore_params.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading');
                    $thisbutton.text('Added to cart...');
                    setTimeout(function(){
                        $thisbutton.text('Add to cart >');
                    }, 2000);
                },
                success: function (response) {

                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        var $qty = parseFloat($('.quantity input').val());
                        var $prevQty = parseFloat($('.cart-qty').text());
                        $('.cart-qty').text($qty + $prevQty);
                        $(window).scrollTop(0);
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });

            return false;

        });
    }
}

export { addToCartAjax }

const miniCart = {

    init() {
        const miniCartItem = $('#mini-cart-count');
        $('#mini-cart-count').on("click", function(e){
            e.preventDefault();
            $('.widget_shopping_cart_content').toggleClass('open');
        })
    }
}

export { miniCart }