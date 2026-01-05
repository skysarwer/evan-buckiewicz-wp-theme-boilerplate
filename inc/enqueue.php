<?php

/**
 * Enqueue editor-specific styles and scripts for the block editor.
 */
function enqueue_editor_css() {

	/**
	 * Load custom editor JavaScript.
	 */
	wp_enqueue_script(
		'sbtl-editor', 
		get_stylesheet_directory_uri() . '/js/editor.js', 
		array( 'wp-blocks', 'wp-dom' ), 
		filemtime( get_stylesheet_directory() . '/js/editor.js' ),
		true
	);

	// Load Gutenberg editor styling
	wp_enqueue_style( 'gutenberg-css', get_stylesheet_directory_uri() . '/css/editor/gutenberg.css', array(), filemtime( get_stylesheet_directory() . '/css/editor/gutenberg.css' ));

	$sidebar_layout = false;

	/**
	 * Special handling for page post type editor
	 */
	if (get_current_screen()->post_type==='page') {

		$template = get_page_template_slug( get_the_ID() );

		/**
		 * Cover template: Add featured image as editor background.
		 * This gives editors a preview of how content will appear over the image.
		 */
		if ($template === 'tmpl-cover.php') {
			// Get the featured image URL
			$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id , 'large' );

			?>
			<style>
				/* Apply featured image as editor background */
				.editor-styles-wrapper {
					background-image: url(<?php echo $thumbnail_url[0]; ?>) !important;
					background-size: 100% auto !important;
					background-repeat: no-repeat !important;
					background-attachment: fixed !important;
				}

				.editor-styles-wrapper .edit-post-visual-editor__post-title-wrapper {
					background: var(--sbtl-bg);
					margin-top: 0 !important;
					padding: 0;
					border: 1px solid black;
					display: block !important;
					margin-bottom: 5em;
				}

				.editor-styles-wrapper .edit-post-visual-editor__post-title-wrapper h1 {
					margin: 0 1em !important;
					font-size: 3em !important;
					text-align: left !important;
				}
			</style>
			<?php
		}

		/**
		 * Check if page has parent or child pages.
		 * Pages with hierarchies require sidebar layout styling.
		 */
		$children = get_pages( array( 'child_of' => get_the_ID() ) );
		$parent = get_post_ancestors( get_the_ID() );

		if (count($children) > 0 || count($parent) > 0) {
			$sidebar_layout = true;
		}

	}

	/**
	 * Event post type also uses sidebar layout
	 */
	if ( (get_current_screen()->post_type ==='event')) {
		$sidebar_layout = true;
	}

	/**
	 * Disable wide/full alignments for sidebar layouts.
	 * This prevents content from breaking the sidebar layout.
	 */
	if ($sidebar_layout === true) {
		wp_enqueue_style( 'disable-alignments', get_stylesheet_directory_uri() . '/css/editor/disable-alignments.css' );
		add_filter('admin_body_class', function( $classes ) {
			$classes .= ' has-sidemenu';
			return $classes;
		} );
	}
}
add_action( 'enqueue_block_editor_assets', 'enqueue_editor_css' );


/**
 * Enqueue scripts and styles.
 */
function sbtl_scripts() {

	wp_dequeue_script('events-manager');
	
	wp_enqueue_style( 'sbtl-style', get_stylesheet_uri(), array(), SBTL_VERSION );

	wp_enqueue_style( 'sbtl-cover', get_template_directory_uri() . '/css/cover.css', array(), SBTL_VERSION );

	wp_enqueue_script( 'sbtl-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), SBTL_VERSION, true );

	//wp_enqueue_script('sbtl-typeahead', get_template_directory_uri() . '/js/vendor/jquery.typeahead.min.js', array('jquery'), SBTL_VERSION, true);

	//wp_enqueue_script('sbtl-search', get_template_directory_uri() . '/js/search.js', array('jquery', 'sbtl-typeahead'), SBTL_VERSION, true);

	wp_enqueue_script('sbtl-accordions', get_template_directory_uri() . '/js/accordions.js', array(), SBTL_VERSION, true);

	wp_enqueue_script( 'sbtl-group-css-vars', get_template_directory_uri() . '/js/addCssVarToGroups.js', array(), SBTL_VERSION, true );

	if ( function_exists( 'wp_enqueue_script_module' ) ) {
		wp_enqueue_script_module( 
			'sbtl-router', 
			get_template_directory_uri() . '/js/router.js', 
			array( '@wordpress/interactivity', '@wordpress/interactivity-router' ), 
			SBTL_VERSION 
		);

		wp_enqueue_script_module( 
			'sbtl-search-interactive', 
			get_template_directory_uri() . '/js/search-interactive.js', 
			array( '@wordpress/interactivity', 'sbtl-router' ), 
			SBTL_VERSION 
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sbtl_scripts' );

add_action( 'wp_enqueue_scripts', 'wpcf7_scripts_removal_contact_form_7', 999);

function wpcf7_scripts_removal_contact_form_7() {
  
  global $post;
  
  if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'contact-form-7') ) {
    
    wp_enqueue_script('contact-form-7');
    wp_enqueue_style('contact-form-7');

  } else {
    
    wp_dequeue_script( 'contact-form-7' );
    wp_dequeue_style( 'contact-form-7' );
    
  }
}