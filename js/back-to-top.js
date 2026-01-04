document.addEventListener("DOMContentLoaded", function() {
    var header = document.querySelector('.sidemenu__wrap');
    var backToTop = document.querySelector('.back-to-top');
    var footer = document.querySelector('.site-footer');
    var headerHeight = header.offsetHeight; // Get the height of the header
    var footerOffset = footer.offsetTop + 10; // Distance from the top of the page to the footer

    window.addEventListener('scroll', function() {
        
        if (!backToTop) return;
        
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop; // Current scroll position

        if (scrollTop > headerHeight - 100 && scrollTop < footerOffset - window.innerHeight) { // If the user has scrolled past the header minus 100px and has not yet reached the footer minus the window's inner height
            backToTop.classList.add('active'); // Show the back-to-top button
        } else {
            backToTop.classList.remove('active'); // Hide the back-to-top button
        }
    });

    
    document.querySelector('a[href="#article-author"]').addEventListener('click', function(e) {
        e.preventDefault();
        var authorTop = document.querySelector('#article-author').offsetTop;
        var colophonTop = document.querySelector('#colophon').offsetTop;
        var scrollDistance = Math.min(authorTop, colophonTop - window.innerHeight);
        window.scrollTo({ top: scrollDistance, behavior: 'smooth' });
    });
});
