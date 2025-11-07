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
//Get the Events Manager Events Archive page 
$events_page = get_option('dbem_events_page');

//if Events page exists
if($events_page){
    //Get the Events Manager Events Archive page URL
    $events_page_url = get_permalink($events_page);

    echo '<a href="' . $events_page_url . '" class="sbtl-link">&#x2190; <span class="cta">&nbsp;'.__('All Events', 'srp').'</span></a>';
}

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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

</article><!-- #post-<?php the_ID(); ?> -->
