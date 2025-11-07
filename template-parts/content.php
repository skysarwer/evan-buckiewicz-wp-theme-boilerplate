<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

?>

<?php 

/**
 * Determine heading level for the post title.
 * Can be customized via args when calling get_template_part().
 * Defaults to h2 for better semantic HTML structure.
 */
if (isset($args['level'])) {
	$level = $args['level'];
} else {
	$level = 2;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- Clickable overlay link covering the entire card for better UX -->
	<a href="<?php echo esc_url( get_permalink() ) ;?>" rel="bookmark" aria-label="<?php echo get_the_title();?>">	</a>
	<div class="post__card-body">
		<div>
			<header>
				<?php
				// Display the post title with customizable heading level
				the_title( '<h'.$level.' class="card-title">', '</h'.$level.'>' ); ?>
			</header><!-- .entry-header -->

			<div class="content">
				<?php
				// Display the post excerpt
				the_excerpt();
				?>
			</div><!-- .entry-content -->
		</div>

		<?php
		
		if ( 'post' === get_post_type() ) :
			
			?>

		<?php 

		/**
		 * Display post metadata based on args.
		 * Can show categories, tags, or other taxonomy terms.
		 * This allows for flexible card displays in different contexts.
		 */
		if ( isset($args['meta']) && $args['meta'] !== 'tag') {
			if ($args['meta'] === 'category') {

				// Display the first category of the post
				$categories = get_the_category();
				
				if ( $categories ) {
					$category = $categories[0];
					echo '<div class="entry-tags category">';
					echo '<a class="entry-tag category" href="'. get_category_link($category) . '">' . sbtl_folder_svg() . $category->name . '</a>';
					echo '</div>';
				}

			}

			if ($args['meta'] === 'author') {

				$author_title = sprintf( __( 'by %s', 'sbtl' ), get_the_author() );

				$picture = get_the_author_meta('profile_picture');
				$author_img = wp_get_attachment_image_url($picture, 'thumbnail');
				
				echo '<div class="entry-tags category">';
				echo '<a class="entry-tag category" href="'. esc_url( get_permalink() ) .'#article-author"><img src="' . $author_img . '">' . $author_title . '</a>';
				echo '</div>';
			}
		} else {
			$tags = get_the_tags();
			if ( $tags ) :
				?>
				<div class="entry-tags">
					<?php
					foreach ( $tags as $tag ) {
						echo '<a class="entry-tag" href="' . get_term_link($tag) . '">' . $tag->name . '</a>';
					}
					?>
				</div>
			<?php endif; 
		} ?>
			
			<footer class="entry-meta">
				<?php
				sbtl_posted_on();
				//sbtl_posted_by();

				echo sbtl_caret_arrow_svg();
				?>
			</footer><!-- .entry-meta -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
