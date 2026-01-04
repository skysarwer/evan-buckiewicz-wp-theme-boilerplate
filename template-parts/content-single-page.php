<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	//check if page has thumbnail
	if ( $args['template'] == 'cover' && has_post_thumbnail() ) {
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
			<img srcset="<?php echo esc_attr( $srcset ); ?>" sizes="100vw" alt="<?php echo esc_attr( $alt ); ?>" class="sbtl-cover-img">
		</div>
	<?php
	}
	?>
	
	<?php if(get_post_meta(get_the_id(), 'hide_title' , true) != 1 && $args['template'] != 'cover'): ?> 
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content <?php echo $args['template'];?>">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sbtl' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_post_meta( get_the_id(), 'structured_data_type', true ) === 'article' ) : 
		
		//get the date last modified 
		$modified_date = get_the_modified_date( 'F j, Y' );

		$author_type = get_the_author_meta( 'structured_data_type' );

		if ( $author_type === 'person' ) {
			$author_title = sprintf( __( 'Written by %s', 'sbtl' ), get_the_author() );
		} else if ( $author_type === 'business' ) {
			$author_title = sprintf( __( 'Brought to you by %s', 'sbtl' ), get_the_author() );
		} else {
			$author_title = sprintf( __( 'Written by %s', 'sbtl' ), get_the_author() );
		}
		?>
		
		<footer class="page-article-footer entry-content">
			<div id="article-author" class="alignfull content-wrap">
				<p class="small date is-style-font-light"><em><?php echo __('Last updated:', 'sbtl') . ' ' . $modified_date;?></em></p>
				<div class="author-box">
					<div class="author-avatar">
						<?php 
						$picture = get_the_author_meta('profile_picture');
						$thumbnail_medium = wp_get_attachment_image_url($picture, 'thumbnail');
						$thumbnail_large = wp_get_attachment_image_url($picture, 'large');
						$srcset = wp_get_attachment_image_srcset($picture, 'large');

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
							echo '<a class="author-link" href="' . $author_link['url'] . '" target="' . $author_link['target'] . '">' . $author_link['title'] . sbtl_caret_arrow_svg() .'</a>';
						}
						?>
					</div>
				</div>
				<hr class="margin-bottom-0">
				<p><?php _e('If you’re enjoying our content, please consider sharing this page with your communities. <br/> It helps us out a lot.', 'sbtl');?></p>
				<?php echo sbtl_share_widget(); ?>
			</div>
		</footer>

	<?php endif; ?>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'sbtl' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
