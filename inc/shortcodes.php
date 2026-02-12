<?php 

/**
 * Theme Shortcodes
 * Custom shortcodes for displaying various content layouts throughout the site.
 * These shortcodes can be used in the WordPress editor or in template files.
 * 
 * @package sbtl
 */

/**
 * Article Card Carousel Shortcode
 * Displays a carousel or grid of post cards based on specified criteria.
 * 
 * Usage:
 * [article_card_carousel category="news" count="6" level="3"]
 * 
 * @param array  $atts    Shortcode attributes
 * @param string $content Shortcode content (not used)
 * @param string $tag     Shortcode tag name
 * @return string HTML output of the article carousel
 */
function sbtl_article_card_carousel($atts, $content = null, $tag = '') {

    ob_start();

    // Determine default meta display based on category filter
    if ($atts['category'] && isset($atts['category_relationship']) && 'is' === $atts['category_relationship']) {
        $default_meta = 'none'; // Hide category if filtering by specific category
    } else {
        $default_meta = 'category'; // Show category otherwise
    }

    /**
     * Shortcode attributes:
     * - level: Heading level for post titles (default: 3)
     * - category: Category slug to filter posts
     * - category_title: Custom title for the carousel section
     * - view_all: Text for "view all" link
     * - category_relationship: 'is' or 'not' - include or exclude category
     * - tag_relationship: 'is' or 'not' - include or exclude tag
     * - tag: Tag slug to filter posts
     * - count: Number of posts to display (default: 6)
     * - posts: Specific post IDs to display
     * - disable_carousel: Whether to disable carousel functionality
     * - meta: What metadata to show ('category', 'tag', 'none')
     * - featured: Whether to display as featured content
     */
    $atts = shortcode_atts(array(
        'level' => '3',
        'category' => '',
        'category_title' => '',
        'view_all' => __('View all', 'sbtl'),
        'category_relationship' => 'is',
        'tag_relationship' => 'is',
        'tag' => '',
        'count' => '6',
        'posts' => '',
        'disable_carousel' => false,
        'meta' => $default_meta,
        'featured' => false,
    ), $atts, $tag);

    // Build query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['count'],
        'orderby' => array(
            'date' => 'DESC',
        ),
        'order' => 'DESC',
    );

    // Filter by tag if specified
    if ($atts['tag'] && 'is' === $atts['tag_relationship'] ) {
        $args['tag'] = $atts['tag'];
    }

    // Exclude tag if "not" relationship
    if ( $atts['tag'] && 'not' === $atts['tag_relationship'] ) {
        $args['tag__not_in'] = array( get_term_ID($atts['tag'], 'post_tag') );
    }

    // Filter by category if specified
    if ($atts['category'] && 'is' === $atts['category_relationship']) {
        $args['category_name'] = $atts['category'];
    }

    if ($atts['category'] && 'not' === $atts['category_relationship']) {
        $cat = get_term_by('slug', $atts['category'], 'category');
        $args['category__not_in'] = array( $cat->term_id );
    }

    if ($atts['posts']) {
        $args['post__in'] = explode(',', $atts['posts']);
    }

    if ($atts['featured']) {
        $sticky_posts = get_option('sticky_posts');
        $args['post__in'] = $sticky_posts;
        $args['orderby'] = array (
            'menu_order' => 'ASC',
            'date' => 'ASC',
        );
    }

    if ($atts['disable_carousel']) {
       $grid_class = 'grid';
    } else {
        $grid_class = 'owl-carousel carousel';
    }

    $isHierarchicalPage = false;

    if (is_page() && (get_post_type() === 'page' || get_post_type() === 'post')) {
        $ancestors = get_post_ancestors(get_the_ID());
        $children = get_pages(array('child_of' => get_the_ID()));
    
        if (!empty($ancestors) || !empty($children)) {
            $isHierarchicalPage = true;
        }
    }

    if ($isHierarchicalPage) {
        $grid_class .= ' has-hierarchy';
    }

    $level = intval($atts['level']);

    $articles = new WP_Query($args);

    if ($articles->have_posts()) {

        if ($atts['disable_carousel'] === false) {
            $align = 'alignfull';
            wp_enqueue_script('sbtl-article-card-carousel', get_template_directory_uri() . '/js/card-carousel.js', array('sbtl-slides', 'jquery'), '1.0', true);
        } else {
            $align = 'alignwide';
        }


        $resource_page = get_option('sbtl_resources_page');

        if ( function_exists( 'pll_get_post' ) ) {
            $resource_page = pll_get_post($resource_page);
        }

        $view_all = false;

        if ( $resource_page ||( $atts['category'] && 'is' === $atts['category_relationship'] ) || ( $atts['tag'] && 'is' === $atts['tag_relationship'] ) ) {

            $view_all = true;

            if ($atts['category'] && 'is' === $atts['category_relationship'] ) {
                $view_all_link = get_term_link($atts['category'], 'category'); 
            } else if ($atts['tag'] && 'is' === $atts['tag_relationship'] ) {
                $view_all_link = get_term_link($atts['tag'], 'post_tag');
            } else {
                $view_all_link = get_permalink($resource_page);
            }
        }

        echo '<div class="article-cards service '.$align.'">';

        if ($atts['category'] && 'is' === $atts['category_relationship']) {
            
            $cat_link = get_term_link($atts['category'], 'category');
            $cat_term = get_term_by('slug', $atts['category'], 'category');
            $cat_icon = get_term_meta($cat_term->term_id, 'svg_icon', true);
            
            if ($atts['category_title']) {
                $cat_title = $atts['category_title'];
            } else {
                $cat_title = $cat_term->name;
            } ?>

            
            <a class="service__link cat-listing-title" aria-hidden="true"  tabindex="-1" aria-label="<?php echo $cat_title; ?>" href="<?php echo esc_url($cat_link); ?>">
                <?php echo $cat_icon; ?>
                <?php echo '<h' . $level . ' class="service__title">' . $cat_title . '</h' . $level . '>'  ?>
            </a>
            <?php 
            $level++;
            
        }

        echo '<div class="entry-listing '.$grid_class.'">';

        while ($articles->have_posts()) {
            $articles->the_post();

            get_template_part( 'template-parts/content', get_post_type(), array('level' => $atts['level'], 'meta' => $atts['meta']) );

        }

        echo '</div>';

        if ($view_all && $atts['view_all']) {
            echo '<a href="' . $view_all_link . '" class="sbtl-link service__link">' . $atts['view_all'] . sbtl_caret_arrow_svg() . '</a>';
        }

        echo '</div>';

    }

    wp_reset_postdata();

    $output = ob_get_clean();

    return $output;
}

add_shortcode('article_listing', 'sbtl_article_card_carousel');

/** Category Listing */

function sbtl_cat_query($level = '2', $filter_by = false, $class = '') {

    ob_start();

    $tax_query_args = array(
        'taxonomy' => 'category',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    );

    if ($filter_by) {
        //separate slugs from comma delimitted string
        $filter_by = explode(',', $filter_by);

        //trim whitespace from each slug
        $filter_by = array_map('trim', $filter_by);

        //pass slugs to query
        $tax_query_args['slug__in'] = $filter_by;
    }

    $tax_query = new WP_Term_Query($tax_query_args);

    // Get the terms from the query
    $terms = $tax_query->get_terms();

    // Sort the terms by term_order and name
    usort($terms, function($a, $b) {
        $a_order = (int) get_term_meta($a->term_id, 'menu_order', true);
        $b_order = (int) get_term_meta($b->term_id, 'menu_order', true);
    
        if ($a_order === $b_order) {
            return strcmp($a->name, $b->name);
        }
        return ($a_order < $b_order) ? -1 : 1;
    });

    echo '<div class="services-listing alignwide">';

    foreach ($terms as $term) {

        $title = $term->name;
        $id = $term->term_id;
        $icon = get_term_meta($id, 'svg_icon', true);
        $link = get_term_link($term);
        $content = get_term_meta($id, 'short_description', true);

        $term_posts = new WP_Query (array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $term->slug,
                )
            )
        ));

        if ( $level ) {
            $level = $level;
        } else {
            $level = '3';
        }

        if ( $class ) {
            $service_class = $class;
        } else {
            $service_class = '';
        }

        ?>

        <div class="service <?php echo $service_class;?>">

            <div class="service__title-wrap">
                <?php if ($link) { ?>

                    <a class="service__link" aria-label="<?php echo get_the_title(); ?>" href="<?php echo esc_url($link); ?>" >
                
                <?php } ?>
                        <?php echo '<h' . $level . ' class="service__title">' . $title . '</h' . $level . '>'  ?>

                <?php if ($link) { ?>
                    </a>
                <?php } ?>
            </div>

            <div class="service__icon-wrap">
                <?php if ($link) { ?>
                    <a class="service__link" aria-hidden="true"  tabindex="-1" aria-label="<?php echo $title; ?>" href="<?php echo esc_url($link); ?>">
                <?php } ?>
                        <div class="service__icon">
                            <?php echo $icon; ?>
                        </div>

                <?php if ($link) { ?>
                    </a>
                <?php } ?>

            </div>

            <div class="service__content">
                <div class="service__text post">
                    <?php if ($content) :
                        echo $content;
                    else : 
                        echo __('Latest publications', 'sbtl'); 
                    endif; ?>
                </div>
                <?php if ($term_posts->have_posts()) { 
                    
                    echo '<ul>';
                    
                    while ($term_posts->have_posts()) {
                        $term_posts->the_post();
                        echo '<li class="article-list-item"><a class="sbtl-link" href="' . get_permalink() . '">'.get_the_title(). sbtl_caret_arrow_svg() .' </a></li>';
                    }

                    wp_reset_postdata();

                    echo '</ul>';

                    echo '<a class="service__link sbtl-link" aria-hidden="true" href="'.$link.'">'. __('View all', 'sbtl') . sbtl_caret_arrow_svg() .'</a>';
                
                } ?>
            </div>
        </div>

        <?php

    }

    echo '</div>';

    $sbtl_service_query = ob_get_clean();

    return $sbtl_service_query;
}

function sbtl_cat_query_handler($atts, $content = null, $tag = '') {
    $atts = shortcode_atts(array(
        'level' => '3',
        'filter_by' => false,
        'class' => ''
    ), $atts, $tag);

    return sbtl_cat_query($atts['level'], $atts['filter_by'], $atts['class']);
}

add_shortcode('category_listing', 'sbtl_cat_query_handler');

/** Service Listing and Service Pricing */


function sbtl_service_query($is_pricing = false, $level = '2', $filter_by = false, $class = '') {

    ob_start();

    $args = array(
        'post_type' => 'services',
        'posts_per_page' => -1,
        'orderby' => array(
            'menu_order' => "ASC",
            'date' => 'ASC',
        ),
        'order' => 'ASC',
    );

    if ($filter_by) {
        //separate ids from comma delimitted string
        $filter_by = explode(',', $filter_by);

        //trim whitespace from each id
        $filter_by = array_map('trim', $filter_by);

       //pass ids to query
        $args['post__in'] = $filter_by;
        
    }

    $services = new WP_Query($args);

    echo '<div class="services-listing alignwide">';

    while ($services->have_posts()) {
        $services->the_post();

        get_template_part('template-parts/content', 'service', array('is_pricing' => $is_pricing, 'level' => $level, 'class' => $class ));

    }

    echo '</div>';

    $sbtl_service_query = ob_get_clean();

    wp_reset_postdata();

    return $sbtl_service_query;
}

function sbtl_service_query_handler($atts, $content = null, $tag = '') {
    $atts = shortcode_atts(array(
        'is_pricing' => false,
        'level' => '3',
        'filter_by' => false,
        'class' => ''
    ), $atts, $tag);

    return sbtl_service_query($atts['is_pricing'], $atts['level'], $atts['filter_by'], $atts['class']);
}

function sbtl_service_pricing() {
    return sbtl_service_query(true);
}

add_shortcode('service_listing', 'sbtl_service_query_handler');
add_shortcode('service_pricing', 'sbtl_service_pricing');
