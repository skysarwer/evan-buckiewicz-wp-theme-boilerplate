<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php 
		/**
		 * Display post title as clickable link.
		 * Uses h2 for proper heading hierarchy on search results page.
		 */
		the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); 
		?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<!-- Display post metadata (date, author) for blog posts -->
		<div class="entry-meta">
			<?php
			sbtl_posted_on(); // Display publish date
			sbtl_posted_by(); // Display author
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php 
	/**
	 * Display post thumbnail if available.
	 * Helps users identify content visually in search results.
	 */
	sbtl_post_thumbnail(); 
	?>

	<div class="entry-summary">
		<?php 
		/**
		 * Display post excerpt.
		 * Provides a brief preview of the content in search results.
		 */
		the_excerpt(); 
		?>
	</div><!-- .entry-summary -->

</article><!-- #post-<?php the_ID(); ?> -->
