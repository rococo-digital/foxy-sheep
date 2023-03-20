const stickyHeader = {
    init() {
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {sticker()};

        // Get the header
        var header = document.getElementById("masthead");
        var headerWrapper = document.getElementById("masthead-wrapper");

        // Get the offset position of the navbar
        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function sticker() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
                var height = header.offsetHeight;
                headerWrapper.style.height = height + 'px';
            } else {
                header.classList.remove("sticky");
                headerWrapper.style.height = 'auto';
            }
        }
    }
}

export {stickyHeader}