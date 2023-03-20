const searchBar = {

    init() {
        const button = $('button[data-search]');
        const searchForm = $('.search-form');

        button.on('click', function(){
            searchForm.slideToggle();
        })
    }

};

export { searchBar }