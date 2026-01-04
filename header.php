<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sbtl
 */

?>

<?php

/**
 * Search bar visibility configuration.
 * By default, the search bar is hidden and collapsed.
 * On search results pages, automatically show and expand the search bar.
 */
$hide_search_bar = 'true';
$expand_search_toggle = 'false';

if (is_search()) {
	$hide_search_bar = 'false';
	$expand_search_toggle = 'true';
}

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<title><?php wp_title();?></title>
	<?php wp_head(); ?>
</head>

<body>

<?php 
/**
 * Get the current post type to add as a class to the page container.
 * This allows for post-type-specific styling.
 */
$post_type = get_post_type(); 
?>

<div id="page" class="site type-<?php echo $post_type; ?>">
	<!-- Accessibility: Skip to main content link for keyboard navigation -->
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'sbtl' ); ?></a>
	
	<?php 
	/**
	 * Render the top header bar (if enabled).
	 */
	header_top_bar_render(); 
	?>
	
	<header id="masthead" class="site-header content-wrap">
		<!-- Site Logo/Branding -->
		<div class="logo">
		<a href="<?php echo function_exists('pll_home_url') ? pll_home_url() : site_url();?>" aria-label="<?php echo get_bloginfo('name');?>">
			<h1><?php echo get_bloginfo('name');?></h1>
		</a>
		</div>

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'sbtl' ); ?></span><span class="nav-bar"></span><span class="nav-bar"></span><span class="nav-bar"></span></button>
			<?php
			/**
			 * Display the primary navigation menu.
			 * Uses a custom walker for enhanced menu a11y.
			 * Container class is checked for language switcher integration.
			 */
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'container_class' => sbtl_check_nav_for_lang_switcher(),
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'nav-menu',
					'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>' ,
					'walker' => new SBTL_Menu_Walker()
				)
			);
			?>
			<!-- Search Toggle Button -->
			<button class="search-toggle search-btn" aria-controls="search-form" aria-expanded="<?php echo $expand_search_toggle;?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'sbtl' ); ?></span>
				<?php echo sbtl_svg_search();?>
				<?php echo sbtl_svg_close();?>
			</button>
			<!-- Overlay for mobile navigation -->
			<span class="nav-overlay"></span>
		</nav><!-- #site-navigation -->
		
		<!-- Search Form Container (toggleable) -->
		<div id="search-form" class="content-wrap" aria-hidden="<?php echo $hide_search_bar;?>">
			<div class="entry-content">
			<div class="search-container">
				<div class="search-form-wrap">
				
					<?php get_search_form();?>
				
				</div>
				<div class="search-loading">
				</div>	
			</div>
			</div>
		</div>
	</header><!-- #masthead -->
	
	
