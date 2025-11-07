<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sbtl
 */

?>

</div><!-- #page -->

<?php 

/**
 * Determine which footer template to use.
 * If Polylang plugin is active and current language is French,
 * use the French footer template. Otherwise, use the default footer.
 * This allows for language-specific footer content and layouts.
 */
$footer_template = 'footer';

// Check for Polylang and French language
if ( function_exists( 'pll_current_language' ) && 'fr' == pll_current_language() ) {
    $footer_template .= '-' . pll_current_language();
}

/**
 * Render the footer using block template parts.
 * Output is buffered to allow for shortcode processing.
 */
ob_start();

block_template_part( $footer_template );

$footer_content = shortcode_unautop( ob_get_clean() );

// Process shortcodes in footer content
echo do_shortcode( $footer_content ); 

?>



<?php 
/**
 * WordPress footer hook.
 * Outputs scripts and any content hooked into wp_footer.
 * Required for plugins and theme functionality.
 */
wp_footer(); 
?>

</body>
</html>
