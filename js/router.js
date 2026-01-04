import { store, withSyncEvent } from '@wordpress/interactivity';

store( 'sbtl', {
	state: {},
	actions: {
		navigate: withSyncEvent( function* ( event ) {
			console.log( '[SBTL Router] Click detected', event );
			event.preventDefault();
			const url = event.currentTarget.href;
			console.log( '[SBTL Router] Navigating to:', url );

			try {
				const { actions: routerActions } = yield import(
					'@wordpress/interactivity-router'
				);

				console.log( '[SBTL Router] Router actions loaded:', routerActions );
				yield routerActions.navigate( url );
				console.log( '[SBTL Router] Navigation request sent' );
			} catch ( error ) {
				console.error( '[SBTL Router] Navigation failed:', error );
				// Fallback to native navigation if router fails
				window.location.href = url;
			}
		} ),
	},
	callbacks: {},
} );
