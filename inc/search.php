<?php 

function sbtl_search_ajax_url( ) {

    add_rewrite_tag('%search_key%', '([^&]+)');
    add_rewrite_rule('sbtl_search_index/([^&]+)/?', 'index.php?search_key=$matches[1]', 'top');
}
add_action('init', 'sbtl_search_ajax_url');

function sbtl_search_index() {
    global $wp_query;
    $search_param = $wp_query->get('search_key');

    // Fallback: Extract from URL if rewrite rule failed
    if (empty($search_param) && strpos($_SERVER['REQUEST_URI'], 'sbtl_search_index') !== false) {
        $path_parts = explode('sbtl_search_index/', $_SERVER['REQUEST_URI']);
        if (isset($path_parts[1])) {
            $search_param = sanitize_text_field(urldecode(trim($path_parts[1], '/')));
        }
    }

    $index_count = 0;

    if (!empty($search_param)) {

        //remove_filter( 'posts_request', 'relevanssi_prevent_default_request' );
        //remove_filter( 'the_posts', 'relevanssi_query', 99 );

        $autocomplete_index = array (
            'Pages' => array(),
            'Upcoming Events' => array(),
            'Past Events' => array(),
            'Categories' => array(),
        );

        $queried_cat = get_terms(
            array(
                'taxonomy' => 'category',
                'hide_empty' => true
            )
        );

        if (!empty($queried_cat)) {
            foreach ($queried_cat as $cat) {

                $autocomplete_index[$cat->name] = array ();

                $cat_posts = new WP_Query( array(
                    'post_type' => 'post',
                    'posts_per_page' => '5',
                    'category_name' => $cat->slug,
                    's' => $search_param,
                ) );

                if ($cat_posts->have_posts()) :

                    while ($cat_posts->have_posts()) : $cat_posts->the_post();

                        $autocomplete_index[$cat->name][] = array (
                            'name' => get_the_title(),
                            'url' => get_the_permalink(),
                            'id' => 'post-' . get_the_ID() . '-cat-' . $cat->slug,
                            'action' => '', // Default to SPA navigation
                        );

                        $index_count++;

                    endwhile;

                endif;
            }
        }

        $indexed_events = new WP_Query(array(
            'post_type' => 'event',
            'posts_per_page' => '-1',
            's' => $search_param,
        ));

        $indexed_upcoming_events = 0;
        $indexed_past_events = 0;

        if ($indexed_events->have_posts()) :

            while ($indexed_events->have_posts()) : $indexed_events->the_post();

                $event_date = get_post_meta(get_the_id(), '_event_start_date', true);

                if (strtotime($event_date) > strtotime('now')) {

                    $indexed_upcoming_events++;

                    if ($indexed_upcoming_events < 5) {
                        $autocomplete_index['Upcoming Events'][] = array (
                            'name' => get_the_title(),
                            'url' => get_the_permalink(),
                            'id' => 'event-' . get_the_ID() . '-upcoming',
                            'action' => '',
                        );

                        $index_count++;
                    }

                } else {
                    $indexed_past_events++;

                    if ($indexed_past_events < 5) {
                        $autocomplete_index['Past Events'][] = array (
                            'name' => get_the_title(),
                            'url' => get_the_permalink(),
                            'id' => 'event-' . get_the_ID() . '-past',
                            'action' => '',
                        );

                        $index_count++;
                    }
                  
                }

                /*if ($index_count >= 9) {
                    break;
                }*/

            endwhile;

        endif;

        $indexed_pages = new WP_Query( array(
            'post_type' => 'page',
            'posts_per_page' => '5',
            's' => $search_param,
        ) );

        if ($indexed_pages->have_posts()) :

            while ($indexed_pages->have_posts()) : $indexed_pages->the_post();

                $autocomplete_index['Pages'][] = array (
                    'name' => get_the_title(),
                    'url' => get_the_permalink(),
                    'id' => 'page-' . get_the_ID(),
                    'action' => '',
                );

                $index_count++;

                /*if ($index_count >= 9) {
                    break;
                }*/

            endwhile;

        endif;

        /*if ($index_count >= 9) {
            wp_send_json_success($autocomplete_index);
        }*/

        $indexed_cat = get_terms( array (
                'taxonomy' => 'category',
                'hide_empty' => true,
                'search' => $search_param,
                'number' => 5,
            ));

        foreach($indexed_cat as $term) {
            $autocomplete_index['Categories'][] = array(
                'name' => $term->name,
                'url' => get_term_link($term->term_id, $term->taxonomy),
                'id' => 'term-' . $term->term_id,
                'action' => '',
            );

            $index_count++;

            if ($index_count >= 9) {
                break;
            }
        }

        /*if ($index_count >= 9) {
            wp_send_json_success($autocomplete_index);
        }*/
    
        wp_send_json_success($autocomplete_index);

        remove_filter( 'posts_where', 'sbtl_title_filter' );
        exit;
    }
}

add_action('template_redirect', 'sbtl_search_index');

function sbtl_title_filter( $where, $wp_query ) {
    global $wpdb;
    
    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like(  $wp_query->get( 'search_post_title') ) . '%\'';
 
    return $where;
 }