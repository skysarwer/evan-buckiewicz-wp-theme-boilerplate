<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sbtl
 */

get_header();

/**
 * WooCommerce Product Support
 * If WooCommerce is active and this is a product page,
 * use WooCommerce default template instead of standard single post layout.
 */
if ( class_exists( 'WooCommerce' ) && is_product() ) {
	?>
	<main id="primary" class="site-main" data-wp-interactive="sbtl" data-wp-router-region="main">
		<div class="content-wrap">
			<?php get_template_part( 'template-parts/content-single', 'page',  array( 'template' => 'woocommerce' )  ); ?>
		</div>
	</main>
	<?php
	return;
}

/**
 * Custom Post Type Handling
 * For post types other than 'post', use custom templates if available.
 * Falls back to default page template if custom template doesn't exist.
 */
$post_type = get_post_type();
if ( $post_type !== 'post' ) { ?>
	<main id="primary" class="site-main" data-wp-interactive="sbtl" data-wp-router-region="main">
		<div class="content-wrap">
			<?php
			// Check for post-type-specific template (e.g., content-single-event.php)
			$template_part = 'template-parts/content-single-' . $post_type;
			if ( locate_template( $template_part . '.php' ) ) {
				get_template_part( $template_part, null, array( ) );
			} else {
				// Fall back to default page template
				get_template_part( 'template-parts/content-single', 'page', array( 'template' => 'default' ) );
			} ?>
		</div>
	</main><!-- #main -->
	<?php
	get_footer(); 
	return;
}

/**
 * Enqueue back-to-top button script for standard blog posts.
 * This provides a smooth scroll-to-top functionality on long articles.
 */
wp_enqueue_script( 
	'sbtl-back-to-top', 
	get_template_directory_uri() . '/js/back-to-top.js', 
	array( ), 
	'1.0.0', 
	true 
);

?>

	<main id="primary" class="site-main has-sidemenu" data-wp-interactive="sbtl" data-wp-router-region="main">
		<div class="content-wrap">
			<?php
			/**
			 * Build category-based sidebar navigation.
			 * Shows all posts in the same category as the current post,
			 * allowing easy navigation between related content.
			 */
			$categories_list = get_the_terms( get_the_ID(), 'category' );
			$first_category = $categories_list[0];
			
			$cat_title = $first_category->name;
			$cat_link = get_category_link( $first_category->term_id );
			$cat_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'cat' => $first_category->term_id,
				'order' => 'DESC',
				'orderby' => 'date'
			) );
			?>

			<!-- Category sidebar navigation -->
			<aside aria-label="<?php echo sprintf( __('%1$s navigation', 'sbtl'), $cat_title);?>" class="sidemenu accordion-container close post-cat">
				<nav class="sidemenu__wrap" aria-label="<?php echo sprintf( __('%1$s navigation', 'sbtl'), $cat_title);?>" >
					<header class="sidemenu__title">
						
						<a href="<?php echo $cat_link; ?>" class="sidemenu__link">
							<?php 
							// Display category SVG icon if available
							if (get_term_meta($first_category->term_id, 'svg_icon', true) ) {	?>
								<div class="sidemenu__icon">
									<?php echo get_term_meta($first_category->term_id, 'svg_icon', true); ?>
								</div>
							<?php } ?>
							<h2><?php echo sbtl_caret_arrow_svg() . $cat_title; ?></h2>
						</a>
					
						<!-- Mobile accordion toggle -->
						<button class="accordion top-level" aria-expanded="false" data-open-text="<?php _e('Menu', 'sbtl');?>" data-close-text="<?php _e('Close', 'sbtl'); ?>">
							<?php _e('Menu', 'sbtl'); ?>
						</button>
					</header>
					
					<?php 
					if ( $cat_query->have_posts() ) : 
							
						$active_id = get_the_id();	?>

						<!-- List of posts in this category -->
						<ul class="sidemenu__list accordion-body">
							<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); 
							
								$is_active = ''; 
								
								// Highlight the current post
								if ( $active_id === get_the_id() ) {
									$is_active = 'is-active';
								} 

								?>
								<li class="sidemenu__item <?php echo $is_active;?>">
									<a href="<?php the_permalink(); ?>" class="sidemenu__link" data-wp-on--click="actions.navigate">
										<?php echo get_the_title(); ?>
									</a>
								</li>
							<?php endwhile; ?> 
						</ul>
						<?php 
						wp_reset_postdata();
					endif; ?>

				</nav>

			</aside>

			<?php
			while ( have_posts() ) :
				the_post(); ?>

				<div class="content-wrap-inner">

					<div class="entry-content">
						<?php get_template_part( 'template-parts/content-single', get_post_type() ); ?>
					</div>

				</div>

				<?php
		

			endwhile; // End of the loop.
			?>
		</div>
		
	</main><!-- #main -->

<?php
get_footer();
