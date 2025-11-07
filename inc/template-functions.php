<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package sbtl
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sbtl_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'sbtl_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sbtl_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'sbtl_pingback_header' );

function sbtl_share_widget() {

	global $post;

	// Get current page URL 
	$sbtlURL = urlencode(get_permalink());

	// Get current page title
	$sbtlTitle = str_replace( ' ', '%20', get_the_title());

	// Get Post Thumbnail for pinterest
	$sbtlThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

	if (empty($sbtlThumbnail)) {
		$sbtlThumbnail = array();
		$sbtlThumbnail[0] = '';
	}

	// Construct sharing URL without using any script
	$twitterURL = 'https://twitter.com/intent/tweet?text='.$sbtlTitle.'&amp;url='.$sbtlURL;
	$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sbtlURL;
	$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$sbtlURL.'&amp;title='.$sbtlTitle;
	$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sbtlURL.'&amp;media='.$sbtlThumbnail[0].'&amp;description='.$sbtlTitle;
	$tumblrURL = 'https://www.tumblr.com/widgets/share/tool?posttype=link&title='.$sbtlTitle.'&caption='.$sbtlTitle.'&content='.$sbtlURL.'&canonicalUrl='.$sbtlURL.'&shareSource=tumblr_share_button';
	$redditURL = 'https://reddit.com/submit?url='.$sbtlURL.'&amp;title='.$sbtlTitle;
	

    // Add sharing button at the end of page/page content
    $content = '<div class="sbtl-social-share flex">';
    $content .= '<a class="sbtl-link sbtl-facebook" href="javascript:void(0);" onclick="openShareWindow(\''.$facebookURL.'\')">'.sbtl_fb_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'Facebook').'</span></a>';
    $content .= '<a class="sbtl-link sbtl-twitter" href="javascript:void(0);" onclick="openShareWindow(\''.$twitterURL.'\')">'.sbtl_twitter_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'Twitter').'</span></a>';
    $content .= '<a class="sbtl-link sbtl-linkedin" href="javascript:void(0);" onclick="openShareWindow(\''.$linkedInURL.'\')">'.sbtl_linkedin_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'LinkedIn').'</span></a>';
    $content .= '<a class="sbtl-link sbtl-reddit" href="javascript:void(0);" onclick="openShareWindow(\''.$redditURL.'\')">'.sbtl_reddit_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'Reddit').'</span></a>';
    $content .= '<a class="sbtl-link sbtl-tumblr" href="javascript:void(0);" onclick="openShareWindow(\''.$tumblrURL.'\')">'.sbtl_tumblr_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'Tumblr').'</span></a>';
    $content .= '<a class="sbtl-link sbtl-pinterest" href="javascript:void(0);" onclick="openShareWindow(\''.$pinterestURL.'\')" data-pin-custom="true">'.sbtl_pinterest_svg().'<span class="screen-reader-text">'.sprintf(__('Share on %s', 'sbtl'), 'Pinterest').'</span></a>';
    $content .= '</div>';

    $content .= '
    <script>
    function openShareWindow(url) {
        window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
    }
    </script>
    ';

    return $content;
}

add_action('admin_menu', 'sbtl_add_resources_page');

function sbtl_add_resources_page() {
    add_posts_page('Category Listing Page', 'Category Listing Page', 'manage_options', 'sbtl-resources-page', 'sbtl_resources_page_callback');
}

function sbtl_resources_page_callback() {
    // Check if form is submitted and nonce is valid
    if(isset($_POST['sbtl_resources_nonce']) && wp_verify_nonce($_POST['sbtl_resources_nonce'], 'sbtl_resources_form')) {
        // Save selected page as site option
        update_option('sbtl_resources_page', $_POST['sbtl_resources_page']);
    }

    // Get saved page
    $saved_page = get_option('sbtl_resources_page');

    // Get all pages
    $pages = get_pages();

    echo '<h1>Category Listing Page</h1><form method="POST">';
    echo '<label for="sbtl_resources_page">Select a Page:</label>';
    echo '<select name="sbtl_resources_page" id="sbtl_resources_page">';
    foreach($pages as $page) {
        echo '<option value="' . $page->ID . '"' . selected($saved_page, $page->ID, false) . '>' . $page->post_title . '</option>';
    }
    echo '</select>';
    wp_nonce_field('sbtl_resources_form', 'sbtl_resources_nonce');
    echo '<input type="submit" value="Save">';
    echo '</form>';
}

function update_permalink_structure_and_category_base( $option_name, $old_value, $value ) {
    if ( 'sbtl_resources_page' === $option_name ) {
		global $wp_rewrite;
        $page_id = get_option('sbtl_resources_page');
        $page_slug = get_post_field( 'post_name', $page_id );
        $wp_rewrite->set_permalink_structure("/%category%/%postname%/");
        update_option('category_base', "/{$page_slug}");
        set_transient( 'update_permalinks', true );
    }
}
//add_action( 'updated_option', 'update_permalink_structure_and_category_base', 10, 3 );

function set_custom_permalink_structure() {
    if ( get_transient( 'update_permalinks' ) ) {
        flush_rewrite_rules();
        delete_transient( 'update_permalinks' );
    }
	
}
add_action('init', 'set_custom_permalink_structure');

function sbtl_schedule_thumbnail_generation($post_id) {
    if (!wp_is_post_revision($post_id) && !wp_is_post_autosave($post_id) && ( get_post_type($post_id) === 'post' || get_post_meta('structured_data_type', $post_id, true ) === 'article' ) ) {
        wp_schedule_single_event(time(), 'sbtl_generate_thumbnail_size_event', array($post_id));
    }
}
add_action('save_post', 'sbtl_schedule_thumbnail_generation');


function sbtl_generate_thumbnail_size($post_id) {
    if (has_post_thumbnail($post_id)) {
        $thumbnail_id = get_post_thumbnail_id($post_id);
        $sizes = array(array(1200, 900), array(1200, 675), array(800, 800));
        $file_path = get_attached_file($thumbnail_id);

        foreach ($sizes as $size) {
            $image = wp_get_image_editor($file_path);
            if (!is_wp_error($image)) {
                $meta = wp_get_attachment_metadata($thumbnail_id);
                if (!isset($meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]) || $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]['width'] != $size[0] || $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]['height'] != $size[1]) {
                    $image->resize($size[0], $size[1], true);
                    $saved = $image->save();
                    $base_name = wp_basename($saved['path'], '.' . $saved['extension']);
                    $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"] = array(
                        'file' => $base_name . "_sbtl_{$size[0]}x{$size[1]}." . $saved['extension'],
                        'width' => $size[0],
                        'height' => $size[1],
                        'mime-type' => $saved['mime-type'],
                    );
                    wp_update_attachment_metadata($thumbnail_id, $meta);
                }
            }
        }
    }
}
add_action('sbtl_generate_thumbnail_size_event', 'sbtl_generate_thumbnail_size');
