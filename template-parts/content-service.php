<?php
/**
 * Template part for displaying services
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sbtl
 */

?>

<?php 

$title = get_the_title();
$id = get_the_ID();
$icon = get_post_meta( $id, 'svg_icon', true );
$link = get_post_meta( $id, 'button_link', true );
$content = get_the_content();

//check if is_pricing passed to args
if ( $args['is_pricing'] ) {
    $content = get_post_meta( $id, 'pricing', true );
}

if ( $args['level'] ) {
    $level = $args['level'];
} else {
    $level = '3';
}

if ( isset ( $args['class'] ) ) {
    $service_class = $args['class'];
} else {
    $service_class = '';
}

?>

<div class="service <?php echo $service_class;?>">

    <div class="service__title-wrap">
        <?php if ($link) { ?>

            <a class="service__link" aria-label="<?php echo get_the_title(); ?>" href="<?php echo esc_url($link['url']); ?>" target="<?php echo $link['target']; ?>">
        
        <?php } ?>
                <?php echo '<h' . $level . ' class="service__title">' . $title . '</h' . $level . '>'  ?>

        <?php if ($link) { ?>
            </a>
        <?php } ?>
    </div>

    <div class="service__icon-wrap">
        <?php if ($link) { ?>
            <a class="service__link" aria-hidden="true"  tabindex="-1" aria-label="<?php echo get_the_title(); ?>" href="<?php echo esc_url($link['url']); ?>" target="<?php echo $link['target']; ?>">
        <?php } ?>
                <div class="service__icon">
                    <?php echo $icon; ?>
                </div>

        <?php if ($link) { ?>
            </a>
        <?php } ?>

    </div>

    <div class="service__content">
        <div class="service__text">
            <?php echo $content; ?>
        </div>

        <?php if ($link) { ?>
            <a class="service__link button" aria-hidden="true" tabindex="-1" href="<?php echo esc_url($link['url']); ?>" target="<?php echo $link['target']; ?>">
                <?php echo $link['title']; ?>
            </a>
        <?php } ?>
    </div>
</div>