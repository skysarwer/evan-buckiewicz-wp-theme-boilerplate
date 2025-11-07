/**
 * Search Functionality
 * Handles search bar toggle and implements typeahead autocomplete functionality.
 * Integrates with WordPress REST API for dynamic search suggestions.
 */

// Get the search toggle button
var searchToggle = document.querySelector('.search-toggle');
// Get the search form container
var searchForm = document.querySelector('#search-form');

/**
 * Toggle search form visibility when search button is clicked.
 * Automatically focuses the search input field when opened.
 */
searchToggle.addEventListener('click', function () {

    // Check if the search form is currently hidden
    if (searchForm.getAttribute('aria-hidden') === 'true') {

        // Show the search form
        searchToggle.setAttribute('aria-expanded', 'true');
        searchForm.setAttribute('aria-hidden', 'false');

        // Focus the search input after animation completes (0.5 seconds)
        setTimeout(function () {
            searchForm.querySelector('.search-field').focus();
        }, 500);

    } else {

        // Hide the search form
        searchToggle.setAttribute('aria-expanded', 'false');
        searchForm.setAttribute('aria-hidden', 'true');
    }
});

/**
 * Typeahead Autocomplete Setup
 * Provides search suggestions from posts and categories via WordPress REST API.
 */
jQuery(document).ready(function ($) {

    // Array to store categories for filtering
    var categories = [];

    /**
     * Fetch all categories from WordPress REST API.
     * Used to provide category-based search suggestions.
     */
    $.ajax({
        url: '/wp-json/wp/v2/categories',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                categories.push({
                    name: value.name,
                    slug: value.slug
                });
            });

            var source = {};

            //loop through categories with foreach
        
            
            for (var i = 0; i < categories.length; i++) {
                
                //Add category to source object
                source[categories[i].name] = {
                    display: 'name',
                    ajax: {
                        url: '/sbtl_search_index/{{query}}',
                        path: 'data.' + categories[i].slug,
        
                    }
                }
            }

            source["Upcoming Events"] = {
                display: 'name',
                ajax: {
                    url: '/sbtl_search_index/{{query}}',
                    path: 'data.upcoming_events',
                }
            }

            source["Past Events"] = {
                display: 'name',
                ajax: {
                    url: '/sbtl_search_index/{{query}}',
                    path: 'data.past_events',
                }
            }

            source.Pages = {
                display: 'name',
                ajax: {
                    url: '/sbtl_search_index/{{query}}',
                    path: 'data.pages',
                }
            }

            source.Writing = {
                display: 'name',
                ajax: {
                    url: '/sbtl_search_index/{{query}}',
                    path: 'data.categories',
                }
            }
            
            $.typeahead({
                input: '.search-field',
                maxItem: 10,
                order: "asc",
                group: {
                    template: "{{group}}"
                },
                asyncResult: false,
                emptyTemplate: 'No result for "{{query}}"',
                loadingAnimation: true,
                dynamic: true,
                delay: 50,
                cancelButton: false,  
                maxItemPerGroup: 5,
                source: source,
                selector: {
                    container: "search-container",
                    query: "search-container",
                    button: "search-container",
                },
                callback: {
                    onClick: function (node, item, event) {
        
                        window.location.href = event.url;
                        
                    },
                    onSearch (node, query) {

                        //get the search container that is a parent of the node
                        var searchContainer = node.closest('.search-container')[0];

                        //if query is empty or a single letter
                        if (query.length < 2) {
                            //hide the .typeahead__result closest to node 
                           $(searchContainer.querySelector('.typeahead__result')).addClass('hidden');

                            //remove loading class from searchContainer
                            searchContainer.classList.remove('loading');
                        } else {
                            //show the results
                           $(searchContainer.querySelector('.typeahead__result')).removeClass('hidden');
                        }
                    }
                },
            });
        }

    });

} )( jQuery );
