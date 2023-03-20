const gravityFormsLabels = {

    init() {
        if(!$('.login-form')[0]){
            const form = $('.form-wrapper input[type=text]');
            const checkbox = $('.form-wrapper input[type=checkbox]');

            $('body').delegate('.form-wrapper input[type=text], .form-wrapper textarea','keydown', function(e){
                var parent = $(this).parent().parent();
                parent.find('label').css({"fontSize":"9px", "top": "15%"})
            })
            $('body').delegate('.form-wrapper input[type=text], .form-wrapper textarea', 'keyup', function(e){
                var input = $(this);
                var parent = $(this).parent().parent();
                if( input.val() == "" ) {
                    parent.find('label').css({"fontSize":"17px", "top": "50%"})
                }else{
                    parent.find('label').addClass('has-value')
                }
            })

            $('body').delegate('.form-wrapper input[type=checkbox]', 'click', function(e){
                var check = $(this);
                var parent = $(this).parent().parent();
                parent.toggleClass('checked');

            })

        }

        $('input#wc-stripe-new-payment-method, input#ship-to-different-address-checkbox').on('click', function(){
            var check = $(this);
            var parent = $(this).parent();
            parent.toggleClass('checked');
        });

    }

};

export { gravityFormsLabels }