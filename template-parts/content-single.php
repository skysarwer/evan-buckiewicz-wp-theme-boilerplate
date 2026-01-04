<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

$author_type = get_the_author_meta( 'structured_data_type' );

if ( $author_type === 'person' ) {
	$author_title = sprintf( __( 'Written by %s', 'sbtl' ), get_the_author() );
} else if ( $author_type === 'business' ) {
	$author_title = sprintf( __( 'Brought to you by %s', 'sbtl' ), get_the_author() );
} else {
	$author_title = sprintf( __( 'Written by %s', 'sbtl' ), get_the_author() );
}

$categories_list = get_the_terms( get_the_ID(), 'category' );
$first_category = $categories_list[0];

$has_thumbnail = '';
$content_wrap = 'article-body';

if ( has_post_thumbnail() ) {
	$has_thumbnail = ' has-thumbnail';
	$content_wrap = "alignfull content-wrap has-thumbnail article-body";
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="article-header<?php echo $has_thumbnail;?>">
		
		<?php

		if ($has_thumbnail) :

			//get the thumbnail url
			$thumbnail_large = get_the_post_thumbnail_url( null, 'large' );
			//get the srcset
			$srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id(), 'large' );

			//get the alt text
			$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); 

			$cover_class = 'sbtl-cover';

			if (get_post_meta( get_the_id(), 'with_overlay' , true) == 1) {
				$cover_class .= ' with-overlay';
			}
			?>
			<div class="<?php echo $cover_class; ?>">
				<img srcset="<?php echo esc_attr( $srcset ); ?>" sizes="100vw" alt="<?php echo esc_attr( $alt ); ?>" class="sbtl-cover-img lazyload">
			</div>

		<?php endif; ?>

		<?php
		
		the_title( '<h1><span>', '</span></h1>' );
		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				sbtl_posted_on();

				echo ', ' . sprintf( __('by %s', 'sbtl'), '<a href="#article-author">' . get_the_author() . '</a>');
				?>
			</div><!-- .entry-meta -->
		<?php endif; 
		?>
		<hr>
	</header><!-- .entry-header -->

		<?php
		$tags = get_the_tags();
		if ( $tags ) :
			?>
			<div class="entry-tags single">
				<?php
				foreach ( $tags as $tag ) {
					echo '<a class="entry-tag" href="' . get_term_link($tag) . '">' . $tag->name . '</a>';
				}
				?>
			</div>
		<?php endif; ?>

		<div class="<?php echo $content_wrap;?>">

		<div>
			<?php
			
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sbtl' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sbtl' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->

		<footer id="article-author" class="entry-footer">
		
			<div class="author-box">
				<div class="author-avatar">
					<?php 
					$picture = get_the_author_meta('profile_picture');
					$thumbnail_medium = wp_get_attachment_image_url($picture, 'thumbnail');
					$thumbnail_large = wp_get_attachment_image_url($picture, 'large');
					$srcset = wp_get_attachment_image_srcset($picture, 'large');
					$alt = get_post_meta( $picture, '_wp_attachment_image_alt', true );

					if ($picture) {
						$thumbnail_image = wp_get_attachment_image( $picture, 'thumbnail' );

						?>
						<figure class="author_image">
							<img data-src="<?php echo $thumbnail_large;?>" data-srcset="<?php echo esc_attr( $srcset ); ?>" data-lowsrc="<?php echo esc_url( $thumbnail_medium ); ?>" data-sizes="auto" alt="<?php echo esc_attr( $alt ); ?>" class="lazyload">
						</figure>
						<?php 
					} 
					?>
				</div>
				<div class="author-info">
					<h2><?php echo $author_title;?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>

					<?php 
					$author_link = get_the_author_meta('cta_link');

					if ( function_exists( 'pll_current_language') && 'en' !== pll_current_language() ) {
						$author_link = get_the_author_meta('cta_link_' . pll_current_language());
					}

					if ( $author_link ) {
						echo '<a class="sbtl-link" href="' . $author_link['url'] . '" target="' . $author_link['target'] . '">' . $author_link['title'] . sbtl_caret_arrow_svg() .'</a>';
					}
					?>
				</div>
			</div>


			<hr class="margin-bottom-0">
			<p><?php _e('If you’re enjoying our content, please consider sharing this page with your communities. 
			<br/> It helps us out a lot.', 'sbtl');?></p>
			<?php echo sbtl_share_widget(); ?>
			
			<?php 
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . __( 'Previous', 'sbtl' ) . '</span><br/> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . __( 'Next', 'sbtl' ) . '</span><br/> <span class="nav-title">%title</span>',
					'in_same_term' => true,
					'taxonomy' => 'category',
					'order' => 'DESC',
				)
			);
			?>
		</footer><!-- .entry-footer -->
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
