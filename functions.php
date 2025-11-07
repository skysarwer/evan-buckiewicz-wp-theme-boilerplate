<?php
/**
 * Subtle Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sbtl
 */

if ( ! defined( 'SBTL_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'SBTL_VERSION', '1.0.0' );
}

/**
 * Enable block template parts support for the theme.
 */
add_action( 'after_setup_theme', 'add_block_template_part_support' );

function add_block_template_part_support() {

	// Enable support for block template parts (reusable template sections)
    add_theme_support( 'block-template-parts' );

	// Disable the block-based widget editor, keeping classic widget management
	remove_theme_support( 'widgets-block-editor' );	
}

/**
 * Security: Remove template file editor from WordPress admin.
 * This prevents theme files from being edited directly in the admin panel.
 */
add_filter( 'theme_file_editor', '__return_false' );


/**
 * Prevent locking and unlocking in the editor UI.
 * This disables the ability to lock/unlock blocks in the block editor,
 * simplifying the editing experience for content creators.
 *
 * @param array $editor_settings Array of editor settings.
 * @param WP_Block_Editor_Context $editor_context The current block editor context.
 * @return array Modified editor settings.
 */
function sbtl_survey_lock_blocks($editor_settings, $editor_context) {

	// Only apply to post editing context, not site editor
    if ($editor_context->name === 'core/edit-post') {
        return $editor_settings;
    }

	// Disable block locking capability
    $editor_settings['canLockBlocks'] = false;

    return $editor_settings;
}
add_filter('block_editor_settings_all', 'sbtl_survey_lock_blocks', 10, 2);
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sbtl_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on sbtl, use a find and replace
		* to change 'sbtl' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'sbtl', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	//add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'sbtl' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	/*add_theme_support(
		'custom-background',
		apply_filters(
			'sbtl_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);*/

	// Add theme support for selective refresh for widgets.
	//add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	/*add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);*/
}
add_action( 'after_setup_theme', 'sbtl_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sbtl_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sbtl_content_width', 640 );
}
add_action( 'after_setup_theme', 'sbtl_content_width', 0 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * SVGS
 */
require get_template_directory() . '/inc/svg.php';

/**
 * Enqueue
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Navigation
 */
require get_template_directory() . '/inc/navigation.php';

/**
 * Model
 * Handles registration of Custom Post Types and their related functionality.
 */
require get_template_directory() . '/inc/model/index.php';

/**
 * Block Patterns
 */
require get_template_directory() . '/inc/block-patterns.php';

/**
 * Search
 */
require get_template_directory() . '/inc/search.php';

/**
 * Header Top Bar
 */
require get_template_directory() . '/inc/header-top-bar.php';

/**
 * Structured Data
 */
require get_template_directory() . '/inc/structured-data.php';

/**
 * Shortcodes
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Languages
 */
require get_template_directory() . '/inc/languages.php';

/**
 * WooCommerce
 */
//require get_template_directory() . '/inc/woocommerce.php';

/**
 * Add metabox to pages allowing the title to be hidden.
 */
function add_hide_title_metabox() {
    add_meta_box(
        'hide_title_metabox', // Metabox ID
        'Hide Title', // Metabox title displayed in admin
        'render_hide_title_metabox', // Callback function to render the metabox
        array( 'page' ), // Post types where this metabox appears (pages only)
        'side', // Context: where to display (normal, advanced, or side)
        'default' // Priority (high, core, default, or low)
    );
}
add_action( 'add_meta_boxes', 'add_hide_title_metabox' );

/**
 * Filter to remove terms marked as 'hidden' from being displayed.
 * This allows administrators to create tags/categories that are used
 * for organization but not shown to site visitors.
 *
 * @param array $terms Array of WP_Term objects.
 * @return array Filtered array with hidden terms removed.
 */
function filter_get_the_tags( $terms ) {
	// Remove terms that have 'hidden' term meta set to 1

	foreach ( $terms as $key => $term ) {
		$hidden = get_term_meta( $term->term_id, 'hidden', true );
		if ( $hidden == 1 ) {
			unset( $terms[$key] );
		}
	}

	return $terms;
}

add_filter( 'get_the_terms', 'filter_get_the_tags', 10, 3 );

/**
 * Render the hide title metabox content.
 * Displays a checkbox that allows editors to hide the page title.
 *
 * @param WP_Post $post The current post object.
 */
function render_hide_title_metabox( $post ) {
    $hide_title = get_post_meta( $post->ID, 'hide_title', true );

    ?>
    <label for="hide_title">
        <input type="checkbox" name="hide_title" id="hide_title" value="1" <?php checked( $hide_title, 1 ); ?>>
        Hide Title
    </label>
    <?php
}

/**
 * Save the hide title metabox data.
 * Updates or deletes the post meta based on checkbox state.
 *
 * @param int $post_id The ID of the post being saved.
 */
function save_hide_title_metabox( $post_id ) {

	// Prevent saving during quick edit mode to avoid data loss
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'inline-save' ) {
		return;
	}

	// Save or delete the meta value based on checkbox state
    if ( isset( $_POST['hide_title'] ) ) {
        update_post_meta( $post_id, 'hide_title', 1 );
    } else {
        delete_post_meta( $post_id, 'hide_title' );
    }
}
add_action( 'save_post', 'save_hide_title_metabox' );

/**
 * Remove WordPress default SVG filter rendering.
 * This prevents WordPress from adding SVG filters to the body tag,
 * which can interfere with custom styling.
 */
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

/**
 * Disable Gutenberg global styles in header (WordPress 5.9+).
 */
function sbtl_deregister_styles() {
    wp_dequeue_style( 'global-styles' );
}
//add_action( 'wp_enqueue_scripts', 'sbtl_deregister_styles', 100 );

/**
 * Remove all default WordPress block patterns.
 */
add_action('init', function() {
	remove_theme_support('core-block-patterns');
});
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/**
 * Register custom image sizes for the theme.
 * These sizes are used for responsive images and thumbnails throughout the site.
 */
add_image_size( 'medium_semilarge', 512, 512, false );

/**
 * Add custom image sizes to the media library size selector.
 * @param array $sizes Existing image size options.
 * @return array Modified array with custom sizes added.
 */
function sbtl_custom_image_sizes( $sizes ) {
return array_merge( $sizes, array(
	'medium_semilarge' => __( 'Medium Semilarge' ),
	'medium_large' => __('Medium Large'),
	) );
}
add_filter( 'image_size_names_choose','sbtl_custom_image_sizes' );