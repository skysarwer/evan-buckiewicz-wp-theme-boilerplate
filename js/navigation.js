/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];		

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	//get all the a elements of the menu that have a href value of # or null
	const emptyLinks = menu.querySelectorAll('a[href="#"], a[href=""]');

	//remove the href attribute from all the empty links
	emptyLinks.forEach(link => link.removeAttribute('href'));

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
			siteNavigation.classList.add("untoggled");
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
			siteNavigation.classList.remove("untoggled");
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	const submenus = document.querySelectorAll('.sub-menu');
	// Get all the submenu toggle buttons within the menu.
	const submenuToggles = menu.querySelectorAll( '.menu-item-has-children .submenu-toggle, .page_item_has_children .submenu-toggle' );

	document.addEventListener('click', (event) => {
		if(!event.target.closest('.submenu-toggle') && !event.target.closest('.sub-menu')){
			submenus.forEach(submenu => submenu.classList.remove('toggled'));
			submenuToggles.forEach(toggleButton => {
				toggleButton.setAttribute('aria-expanded', 'false'); 
				//toggleButton.innerHTML = '+'
			});
		}
	});

	submenus.forEach(submenu => {
		submenu.classList.add('offscreen');
		submenu.style.setProperty('--submenu-height', 'calc(' + submenu.scrollHeight + 'px + 2em)');
		submenu.classList.remove('offscreen');
	});
	

	window.addEventListener('resize', () => {

		submenuToggles.forEach(toggleButton => {
			toggleButton.setAttribute('aria-expanded', 'false'); 
			//toggleButton.innerHTML = '+'
		});
		
		submenus.forEach(submenu => {
			submenu.classList.remove('toggled');
			submenu.classList.add('offscreen');
			submenu.style.setProperty('--submenu-height', 'calc(' + submenu.scrollHeight + 'px + 2em)');
			submenu.classList.remove('offscreen');
		});
	});

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children a, .page_item_has_children a' );

	for ( const toggle of submenuToggles ) {

		const submenu = document.getElementById( toggle.getAttribute('aria-controls') );

		// Toggle the the .toggled class and the aria-expanded value each time a .submenu-toggle button is clicked.
		toggle.addEventListener( 'click', function() {

			if ( toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				//toggle.innerHTML = '+'
				toggle.setAttribute( 'aria-expanded', 'false' );
				submenu.classList.remove( 'toggled' );
			} else {
				//toggle.innerHTML = '-'
				toggle.setAttribute( 'aria-expanded', 'true' );
				submenu.classList.add( 'toggled' );
			}
		} );
	}
})();