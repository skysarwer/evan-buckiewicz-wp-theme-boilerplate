document.addEventListener('DOMContentLoaded', function() {
    var iframe = document.querySelector('.gt-loading-iframe-wrapper iframe');
    var loadingAnimation = document.querySelector('.gt-loading-iframe-wrapper .gt-loading-container');

    iframe.addEventListener('load', function() {
        loadingAnimation.style.display = 'none';
        
        // Check if the iframe's data-label is not empty and if so, add it to the iframe's title attribute
        if (iframe.getAttribute('data-label') !== '') {
            iframe.setAttribute('aria-label', iframe.getAttribute('data-label'));
        } else {
            iframe.removeAttribute('aria-label');
        }
    });

    function loadIframe() {
        
        iframe.src = iframe.getAttribute('data-src');
    }
    
    loadIframe(); // Initial check
});