import { store, withSyncEvent } from '@wordpress/interactivity';

store( 'sbtl', {
	state: {},
	actions: {
		navigate: withSyncEvent( function* ( event ) {
			event.preventDefault();
			const url = event.currentTarget.href;

			try {
				const { actions: routerActions } = yield import(
					'@wordpress/interactivity-router'
				);

				yield routerActions.navigate( url );
                window.scrollTo({ top: 0, behavior: 'smooth' });
			} catch ( error ) {
				console.error( '[SBTL Router] Navigation failed:', error );
				// Fallback to native navigation if router fails
				window.location.href = url;
			}
		} ),
	},
	callbacks: {},
} );
