jQuery(document).ready(function($) {

    var owl_containers = document.querySelectorAll('.entry-listing.carousel');

    var owl = null;

    for (var i = 0; i < owl_containers.length; i++) {

        var hasHierarchy = false;    

        if (owl_containers[i].classList.contains('has-hierarchy')) {
            hasHierarchy = true;
        }
        

        var owlSelector = '.' + owl_containers[i].className;
        owlSelector = owlSelector.replaceAll(' ', '.');


        owl = $(owlSelector);

        //To do: add support for data dots : data-dot="<button role='button' class='owl-dot'></button>"

        owl.owlCarousel(
            {
                items: 3,
                center: true,
                margin: 20,
                smartSpeed: 1000,
                mouseDrag: true,
                stagePadding: 80,
                loop: true,
                dots: false,
                nav: true,
                onTranslated: function() {
                    // Set tabindex="-1" for all items
                    $(owlSelector + ' a').attr('tabindex', '-1');
    
                    // Set tabindex="0" for visible items
                    $(owlSelector + ' .owl-item.active a').attr('tabindex', '0');
                },
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 0,
                        stagePadding: 50,
                        center: true,
                    },
                    768: {
                        items: 2,
                        stagePadding: 50,
                        center: false,
                    },
                    1100: {
                        items: hasHierarchy ? 2 : 3,
                    },
                    1600: {
                        items: hasHierarchy ? 3 : 4,
                    },
                    3000: {
                        items: hasHierarchy ? 4 : 5,
                    }
                },
                navText: [
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="15 6 9 12 15 18"></polyline></svg><span class="screen-reader-text">Previous slide</span>',
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="9 6 15 12 9 18"></polyline></svg><span class="screen-reader-text">Next slide</span>'
                ],
                addClassActive : false, // visible items have class active
                onChanged: function (event) {

                    var activeSlide = event.currentTarget.children[0].children[0].children[event.item.index];
                    
                    if (activeSlide === undefined) {
                        activeSlide = event.currentTarget;
                    }
                   
                }
            }
        ).on('changed.owl.carousel', function(event) {
            // Set tabindex="-1" for all slides
            $('.owl-item a').attr('tabindex', '-1');
            
            // Get the indexes of the current slides
            var indexes = $(this).find('.owl-item.active').map(function() {
                return $(this).index();
            });
            
            // Set tabindex="0" for the current slides
            $.each(indexes, function(i, index) {
                $('.owl-item').eq(index).find('a').attr('tabindex', '0');
            });
        });

        // Set tabindex="-1" for all items initially, excluding those with class "active"
        $(owlSelector + ' .owl-item:not(.active) a').attr('tabindex', '-1');

        /*keyboard navigation*/
        $(document.documentElement).keyup(function(event) {    
            if (event.keyCode == 37) { /*left key*/
                owl.trigger('prev.owl.carousel', [700]);
            } else if (event.keyCode == 39) { /*right key*/
                owl.trigger('next.owl.carousel', [700]);
            }
        });
    }
});
