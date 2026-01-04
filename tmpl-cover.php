<?php
/**
 * Template Name: Cover
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

 $var_class= '';
 if (is_front_page()) {
     $var_class = 'homepage';
 } 

 $has_sidemenu = false;

//check if the page has parent or child pages
$children = get_pages( array( 'child_of' => get_the_ID() ) );
$parent = get_post_ancestors( get_the_ID() );

if (count($children) > 0 || count($parent) > 0) {
	$var_class .= ' has-sidemenu';

	$has_sidemenu = true;

	//Get top level ancestor page
	if (count($parent) > 0) {
		$top_parent = $parent[0];
	} else {
		$top_parent = get_the_ID();
	}
}
 
 get_header();
 ?>	
 
     <main id="primary" class="<?php echo $var_class;?>">
         <div class="content-wrap">
            <?php
            if ($has_sidemenu === true) {
                //get the top level ancestor page
                $top_parent = get_post($top_parent);

                //get the top level ancestor page title
                $top_parent_title = $top_parent->post_title;

                ?>

                <aside aria-label="<?php echo $top_parent_title; ?> navigation" class="sidemenu accordion-container">
                    <nav class="sidemenu__wrap" aria-label="<?php echo $top_parent_title; ?> navigation" >
                        <header class="sidemenu__title">
                            <?php if (count($parent) > 0) {
                                ?>
                                <a href="<?php echo get_permalink($top_parent->ID); ?>" class="sidemenu__link">
                                    <h2><?php echo sbtl_caret_arrow_svg();?><?php echo $top_parent_title; ?></h2>
                                </a>
                            <?php } else { ?>
                                <h2><?php echo $top_parent_title; ?></h2>
                            <?php } ?>
                        
                            <button class="accordion top-level" aria-expanded="false" data-open-text="<?php _e('Menu', 'sbtl');?>" data-close-text="<?php _e('Close', 'sbtl'); ?>">
                                <?php _e('Menu', 'sbtl'); ?>
                            </button>
                        </header>
                    

                        <ul class="sidemenu__list accordion-body">
							<?php
							wp_list_pages( array(
								'child_of' => $top_parent->ID,
								'title_li' => '',
								'depth' => 2,
								'walker' => new SBTL_Walker_Page_Sidemenu()
							));
							?>
						</ul>
                    </nav>
                </aside>
            <?php
            }
             
             while ( have_posts() ) :
                 the_post();
 
                 get_template_part( 'template-parts/content-single', 'page', array( 'template' => 'cover' ) );
 
                 // If comments are open or we have at least one comment, load up the comment template.
                 if ( comments_open() || get_comments_number() ) :
                     comments_template();
                 endif;
 
             endwhile; // End of the loop.
             ?>
         </div>
     </main><!-- #main -->
 <?php
 //get_sidebar();
 get_footer();


