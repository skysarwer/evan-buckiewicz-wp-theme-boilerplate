/**
 * Accordion Functionality
 * Handles expandable/collapsible accordion sections throughout the theme.
 * Supports nested accordions with automatic height calculations.
 */

/**
 * Find and update heights of parent accordion containers.
 * When a nested accordion expands/collapses, all parent accordions
 * need their heights adjusted to accommodate the change.
 *
 * @param {HTMLElement} element - The accordion button element
 * @param {string} expanded - "true" if accordion is being expanded, "false" if collapsed
 */
function findAccordionBodyParents(element, expanded) {
    
    elementControls = element.getAttribute('aria-controls');

    if ( !elementControls ) {
        return;
    }

    elementControls = document.getElementById(elementControls);

    // Get the current height of this accordion body
    var elementHeight = elementControls.style.getPropertyValue('--accordion-body-height').replace('calc(', '').replace('px )', '');
        
    elementHeight = parseInt(elementHeight);

    // Find all parent accordion bodies (for nested accordions)
    var accordionBodyParents = [];
    while (element) {
        element = element.parentElement;
        if (element && element.classList.contains('accordion-body')) {
            accordionBodyParents.push(element);
        }
    }
    
    // Update heights of all parent accordions
    for (var i = 0; i < accordionBodyParents.length; i++) {
        accordionBody = accordionBodyParents[i];
        accordionScrollHeight = accordionBody.style.getPropertyValue('--accordion-body-height').replace('calc(', '').replace('px )', '');
        accordionScrollHeight = parseInt(accordionScrollHeight);

        // Add or subtract height based on expansion/collapse
        if (expanded === "true") {
            accordionBody.style.setProperty('--accordion-body-height', 'calc(' + (accordionScrollHeight + elementHeight) + 'px )');
        } else {
            accordionBody.style.setProperty('--accordion-body-height', 'calc(' + (accordionScrollHeight - elementHeight) + 'px )');
        }
    }
}

/**
 * Initialize all accordion elements on the page.
 * Adds click handlers and manages expand/collapse behavior.
 */
var accordions = document.querySelectorAll('.accordion');
var accordionBodies = document.querySelectorAll('.accordion-body');
accordions.forEach(accordion => {
    accordion.addEventListener('click', (event) => {
        // Get the parent container
        var accordionCont = accordion.closest('.accordion-container');

       // Toggle accordion state
       if (accordion.getAttribute('aria-expanded') === "true") {
            // Collapse the accordion
            accordion.setAttribute('aria-expanded', 'false');
            accordionCont.classList.remove('open');
            accordionCont.classList.add('closed');

            // Update button text for sidemenu accordions
            if (accordionCont.classList.contains('sidemenu')) {
                accordion.innerHTML = accordion.getAttribute('data-open-text');
            }

            findAccordionBodyParents(accordion, "false");

       } else {
            accordion.setAttribute('aria-expanded', 'true');
            accordionCont.classList.add('open');
            accordionCont.classList.remove('closed');

            //if accordion has class of "sidemenu toggle"
            if (accordionCont.classList.contains('sidemenu')) {
                accordion.innerHTML = accordion.getAttribute('data-close-text');
            }

            findAccordionBodyParents(accordion, "true");
        }
        

    });
});

accordionBodies.forEach(accordionBody => {
    accordionBody.style.setProperty('--accordion-body-height', 'calc(' + accordionBody.scrollHeight + 'px )');
});


window.addEventListener('resize', () => {
    
    accordionBodies.forEach(accordionBody => {
        accordionBody.classList.remove('toggled');
        accordionBody.style.setProperty('--accordion-body-height', 'calc(' + accordionBody.scrollHeight + 'px )');
    });
});
