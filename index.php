<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

get_header();
?>

	<main id="primary" class="site-main" data-wp-interactive="sbtl" data-wp-router-region="main"> <div class="content-wrap">

		<?php
		if ( have_posts() ) :

			/* Display page title for blog page (not front page) */
			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif; ?>

			<!-- Posts are displayed in a grid layout -->
			<div class="entry-listing grid">

			<?php 
			/* Start the Loop - iterate through all posts */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 * 
				 * For example: content-post.php, content-page.php, content-event.php
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			?> </div> <?php

			/**
			 * Display pagination links for navigating between pages of posts.
			 * Shows "Older posts" and "Newer posts" links.
			 */
			the_posts_navigation();

		else :

			// No posts found - display "nothing found" message
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</div></main><!-- #main -->

<?php
get_footer();
