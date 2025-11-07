wp.domReady( () => {

    wp.blocks.registerBlockStyle( 'core/paragraph', [ 
		{
			name: 'font-title',
			label: 'Playfair Display',
		},

        {
			name: 'font-semibold',
			label: 'Lato Semibold',
		},
        {
            name: 'font-light',
            label: 'Lato Light'
        },

        {
            name: 'font-heavy',
            label: 'Lato Heavy'
        }
	]);

    wp.blocks.registerBlockStyle( 'core/heading', [ 
        {
            name: 'font-title',
            label: 'Title',
        },
        {
			name: 'font-semibold',
			label: 'Lato Semibold',
		},
        {
			name: 'font-regular',
			label: 'Lato Regular',
		},
        {
            name: 'font-light',
            label: 'Lato Light'
        },   
        {
            name: 'subdued',
            label: 'Subdued'
        }
	]);

    wp.blocks.registerBlockStyle( 'core/spacer', [ 
        {
            name: 'wave',
            label: 'Wave'
        },
        {
            name: 'decorated',
            label: 'Decorated'
        }
	]);

    wp.blocks.registerBlockStyle( 'core/list', [
        {
            name: 'spiral',
            label: 'Spiral'
        }
    ] );

    wp.blocks.registerBlockStyle( 'core/image', [ 
        {
            name: 'half-width',
            label: 'Half Width'
        }
	]);

    wp.blocks.registerBlockStyle( 'sbtl/tabs', [
        {
            name: 'classic',
            label: 'Classic'
        }
    ]);
    
} );

