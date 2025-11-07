<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package evn
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="content-wrap">

		<?php if ( have_posts() ) : ?>

			<!-- Display search results header with the search query -->
			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'evn' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<!-- Search results grid -->
			<div class="entry-listing grid">

			<?php
			/* Start the Loop - display each search result */
			while ( have_posts() ) :
				the_post();

				/**
				 * Display search result content using template part.
				 * Uses template-parts/content-search.php for custom search result formatting.
				 * If you want to override this in a child theme, include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			// Pagination for search results
			the_posts_navigation();

		else :

			// No search results found - display "nothing found" message
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		</div>
	</div>
	</main><!-- #main -->

<?php
get_footer();
