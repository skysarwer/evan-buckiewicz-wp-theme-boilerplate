<?php
/**
 * Template part for displaying FAQs
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

?>

<?php 

$title = get_the_title();
$id = get_the_ID();
$content = get_the_content();

if ( $args['level'] ) {
    $level = $args['level'];
} else {
    $level = 2;
}
?>

<?php if ( $args['template'] === 'grid' ) : ?>

    <div class="faq__item">

        <?php //get thumbnails 

        if ( has_post_thumbnail() ) {
            //get the thumbnail url
            $thumbnail_large = get_the_post_thumbnail_url( null, 'large' );
            $thumbnail_medium = get_the_post_thumbnail_url( null, 'medium' );        
            //get the srcset
            $srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id(), 'large' );

            //get the alt text
            $alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>

            <figure class="faq__thumbnail">
                <img data-srcset="<?php echo esc_attr( $srcset ); ?>" data-lowsrc="<?php echo esc_url( $thumbnail_medium ); ?>" sizes="100vw" alt="<?php echo esc_attr( $alt ); ?>" class="lazyload">
            </figure>
        <?php
        }
        
        
        ?>



        <?php echo '<h' . $level . ' class="faq__title">' . $title . '</h' . $level . '>' ?>

        <div class="faq__content">
            <?php echo $content; ?>
        </div>
    </div>
    
<?php else : ?>

    <div class="faq accordion-container">
        <button class="faq__toggle accordion" aria-expanded="false">

            <?php echo '<h' . $level . ' class="faq__title">' . $title . '</h' . $level . '>'  . sbtl_caret_svg(); ?>
        </button>
        <div class="accordion-body">
            <div class="faq__content">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
   
<?php endif; ?>

