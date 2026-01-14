import { store, getContext, getElement } from '@wordpress/interactivity';

store( 'sbtl', {
	state: {
		search: {
			// isOpen now refers to the dropdown visibility, not the search bar
			isOpen: false,
			query: '',
			results: [],
			isLoading: false,
			selectedIndex: -1,
			get hasResults() {
				return this.results.length > 0;
			},
            get flatResults() {
                return this.results.flatMap(group => group.items);
            },
            get selectedId() {
                if ( this.selectedIndex === -1 ) return null;
                const selected = this.flatResults[this.selectedIndex];
                return selected ? selected.id : null;
            },
            get isResultActive() {
                const context = getContext();
                if (!context.item) return false;
                return context.item.id === this.selectedId;
            }
		},
	},
	actions: {
		search: {
			close: () => {
				const state = store( 'sbtl' ).state.search;
				state.isOpen = false;
				state.selectedIndex = -1;
			},
            clear: () => {
                const state = store( 'sbtl' ).state.search;
                state.query = '';
                state.results = [];
                state.isOpen = false;
                state.selectedIndex = -1;
                
                // Focus back on input
                const input = document.querySelector('.search-field');
                if (input) input.focus();
            },
            handleFocus: () => {
                const state = store( 'sbtl' ).state.search;
                if ( state.query.length >= 3 ) {
                    state.isOpen = true;
                }
            },
            handleBlur: () => {
                const state = store( 'sbtl' ).state.search;
                // Delay closing to allow clicks on results to register
                setTimeout(() => {
                    state.isOpen = false;
                }, 200);
            },
			handleInput: async ( event ) => {
				const state = store( 'sbtl' ).state.search;
				state.query = event.target.value;
				state.selectedIndex = -1;

				if ( state.query.length < 3 ) {
					state.results = [];
					state.isOpen = false;
					return;
				}

				state.isLoading = true;
				try {
					const response = await fetch( `/sbtl_search_index/${ state.query }` );
					if ( response.ok ) {
						const text = await response.text();
						try {
							const data = JSON.parse( text );
							if ( data.success ) {
								// Transform object to array for easier iteration
								state.results = Object.entries(data.data).map(([key, value]) => ({
									key: key.replace(/_/g, ' '), // Simple formatting
									items: value
								})).filter(group => {
                                    // Filter out empty groups and groups containing non-objects
                                    return Array.isArray(group.items) && group.items.length > 0 && typeof group.items[0] === 'object';
                                });
								
								// Open dropdown if we have results
								state.isOpen = true;
							}
						} catch ( jsonError ) {
							console.error( 'Search JSON Parse Error:', jsonError );
							state.results = [];
						}
					}
				} catch ( e ) {
					console.error( 'Search failed', e );
					state.results = [];
				} finally {
					state.isLoading = false;
				}
			},
			handleKeydown: ( event ) => {
				const state = store( 'sbtl' ).state.search;
				const { navigate, search } = store( 'sbtl' ).actions;
                
                // Flatten results for navigation
                const flatResults = state.results.flatMap(group => group.items);

				if ( ! state.isOpen || ! state.hasResults ) return;

				if ( event.key === 'ArrowDown' ) {
					event.preventDefault();
					state.selectedIndex = ( state.selectedIndex + 1 ) % flatResults.length;
                    search.scrollToSelected();
				} else if ( event.key === 'ArrowUp' ) {
					event.preventDefault();
					state.selectedIndex = state.selectedIndex === -1
                        ? flatResults.length - 1
                        : ( state.selectedIndex - 1 + flatResults.length ) % flatResults.length;
                    search.scrollToSelected();
				} else if ( event.key === 'Enter' ) {
					if ( state.selectedIndex >= 0 ) {
						event.preventDefault();
						const selected = flatResults[ state.selectedIndex ];
						if ( selected ) {
                            // Mock event for navigate/actions
							const mockEvent = {
								preventDefault: () => {},
								currentTarget: { href: selected.url },
                                target: event.target
							};
							search.processResult( mockEvent, selected );
						}
					}
				} else if ( event.key === 'Escape' ) {
					state.isOpen = false;
				}
			},
			selectResult: ( event ) => {
                const context = getContext();
                const { search } = store( 'sbtl' ).actions;
                search.processResult( event, context.item );
			},
            processResult: ( event, item ) => {
                const { navigate } = store( 'sbtl' ).actions;
                const action = item.action;
                
                store( 'sbtl' ).state.search.isOpen = false;

                // Explicit null -> Hard navigation (Default browser link behavior)
                if ( action === null ) {
                    if ( item.url ) {
                        window.location.href = item.url;
                    }
                    return;
                }

                // Custom Action
                if ( typeof action === 'string' ) {
                    // Window function
                    if ( action.startsWith( 'window.' ) ) {
                        event.preventDefault();
                        const funcName = action.replace( 'window.', '' );
                        if ( typeof window[ funcName ] === 'function' ) {
                            window[ funcName ]( event, item );
                        }
                        return;
                    }
                    
                    // Interactivity API Action
                    const parts = action.split( '.' );
                    if ( parts.length === 2 ) {
                        const [ namespace, actionName ] = parts;
                        try {
                            const storeObj = store( namespace );
                            if ( storeObj && storeObj.actions && typeof storeObj.actions[ actionName ] === 'function' ) {
                                event.preventDefault();
                                storeObj.actions[ actionName ]( event );
                                return;
                            }
                        } catch (e) {
                            console.warn( 'Failed to invoke action:', action );
                        }
                    }
                }

                // Default (SPA Navigation)
                if ( item.url ) {
                    navigate( event );
                }
            },
            scrollToSelected: () => {
                const state = store( 'sbtl' ).state.search;
                const { selectedId, selectedIndex } = state;
                
                if ( ! selectedId ) return;

                // Use setTimeout to ensure DOM is ready/layout is settled
                setTimeout(() => {
                    // If it's the first item, scroll the container to the top to show the group title
                    if ( selectedIndex === 0 ) {
                        const container = document.getElementById( 'search-results-list' );
                        if ( container ) {
                            container.scrollTop = 0;
                            return;
                        }
                    }

                    const element = document.getElementById( selectedId );
                    if ( element ) {
                        element.scrollIntoView( { block: 'nearest', inline: 'nearest' } );
                    }
                }, 0);
            }
		},
	},
    callbacks: {
        search: {
        }
    }
} );

// Vanilla JS for Search Bar Toggle
document.addEventListener('DOMContentLoaded', () => {
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.getElementById('search-form');
    const searchInput = document.querySelector('.search-field');

    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', (e) => {
            e.preventDefault();
            
            const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
            searchToggle.setAttribute('aria-expanded', !isExpanded);
            searchForm.setAttribute('aria-hidden', isExpanded);
            
            if (!isExpanded) {
                // Opening
                setTimeout(() => {
                    searchInput.focus();
                }, 500);
            }
        });
    }
});
