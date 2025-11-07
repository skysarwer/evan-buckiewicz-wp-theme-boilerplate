<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package sbtl
 */

get_header();

	/**
	 * Determine which 404 template to use based on language.
	 * If Polylang is active and current language is French,
	 * use the French 404 template. Otherwise use default.
	 * Templates are stored in the /parts/ directory as HTML files.
	 */
	$not_found_template = 'page-not-found';

	// Check for Polylang and French language
	if ( function_exists( 'pll_current_language' ) && 'fr' == pll_current_language() ) {
	    $not_found_template .= '-' . pll_current_language();
	}

	// Render the 404 page using block template part
	block_template_part( $not_found_template );

get_footer();
