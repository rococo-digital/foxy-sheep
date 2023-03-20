const loadMorePosts = {

    init() {
        const button = $('a[data-loadmore]');
        const results = $('#results');
        button.on('click', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: loadmore_params.ajaxurl,
                data: {
                    'action': 'loadmore_posts',
                    'query': loadmore_params.posts, // that's how we get params from wp_localize_script() function
                    'page' : loadmore_params.current_page
                },
                beforeSend: function( xhr ) {
                    button.addClass('loading');
                    button.find('.text').html('Loading');
                },
                success: function(data) {
                    button.removeClass('loading');
                    results.append(data);
                    if( data ) {
                        button.find('.text').html('Older posts >');

                        loadmore_params.current_page++;

                        if ( loadmore_params.current_page == loadmore_params.max_page )
                            button.remove(); // if last page, remove the button
                    } else {
                        button.remove(); // if no data, remove the button as well
                    }

                },
                error: function(data) {

                }

            });

        })
    }

};

export { loadMorePosts }