<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

get_header();

/**
 * Get the current taxonomy term ID.
 * This is used for category/tag archive pages.
 */
if ( !is_tax()) {
	$term_id = 0;
} else {
	$term_id = get_queried_object()->term_id;
}
?>

	<main id="primary" class="site-main" data-wp-interactive="sbtl" data-wp-router-region="main">
		<div class="content-wrap">

		<?php if ( have_posts() ) : 
			
			/**
			 * Get breadcrumb/back link configuration.
			 * Can link back to a parent page or the main resources listing page.
			 * Supports Polylang for multilingual sites.
			 */
			$parent_page = get_term_meta($term_id, 'parent_page', true);
			
			$resources_page = get_option('sbtl_resources_page');

			// Get translated version of resources page if Polylang is active
			if ( function_exists( 'pll_get_post' ) ) {
				$resources_page = pll_get_post($resources_page);
			}
			
			?>

			<header class="archive-header">
				<div>

				<?php if ( $parent_page || $resources_page ) : ?>
				<!-- Breadcrumb navigation back to parent/resources page -->
				<div class="sbtl-resources-link">
					<?php if ($parent_page) : ?>
						<a href="<?php echo get_permalink($parent_page); ?>" class="sbtl-link"><?php echo sbtl_caret_arrow_svg() . get_the_title($parent_page); ?></a>
					<?php endif; ?>

					<?php if ($resources_page) : ?>
						<a href="<?php echo get_permalink($resources_page); ?>" class="sbtl-link"><?php echo sbtl_caret_arrow_svg() . get_the_title($resources_page); ?></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Archive page title and description -->
				<h1 class="entry-title"><?php single_term_title('', true); ?></h1>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
				</div>
				<?php 
					// Display category SVG icon if available
					if ( get_term_meta($term_id, 'svg_icon', true) ) {
						echo get_term_meta($term_id, 'svg_icon', true);
					}
				?>
			</header><!-- .page-header -->
			
			<!-- Posts grid -->
			<div class="entry-listing grid">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					* Include the Post-Type-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Type name) and that will be used instead.
					*/
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;
				?>
			</div>
			<?php 
			/**
			 * Display pagination for archive pages.
			 * Shows "Older posts" and "Newer posts" links.
			 */
			the_posts_navigation();

		endif;

		/**
		 * Category Footer Content
		 * Display custom footer content associated with this category.
		 * Uses the 'cat-footer' custom post type linked to categories.
		 */
		if (is_tax() && get_queried_object()->taxonomy === 'category') :
			
			$cat_footers = new WP_Query (array(
				'post_type' => 'cat-footer',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => get_queried_object()->slug,
					)
					),
				'orderby' => array(
					'menu_order' => "ASC",
					'date' => 'ASC',
				),
			));


			if ($cat_footers->have_posts()) : ?>
				<footer class="entry-content">
					<?php while ($cat_footers->have_posts()) :
						$cat_footers->the_post();
						the_content();
					endwhile;
					wp_reset_postdata(); 
					?>
				</footer>
			<?php endif; ?>
		<?php endif; ?>

	</div>
	</main><!-- #main -->

<?php
get_footer();
