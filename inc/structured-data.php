<?php

function sbtl_post_structured_data() {

    // @todo: fix later
    return;

    global $post;

    if($post->post_type == 'post' || get_post_meta( $post->ID, 'structured_data_type', true ) == 'article') {
        $post_id = $post->ID;
        $post_title = get_the_title($post_id);
        $post_excerpt = get_the_excerpt($post_id);
        $post_permalink = get_permalink($post_id);
        //$post_thumbnail = get_the_post_thumbnail_url($post_id);

        $author_data_type = get_the_author_meta('structured_data_type', $post->post_author) ?? 'person';
        $author_description = get_the_author_meta('description', $post->post_author);
        $author_cta_link = get_the_author_meta('cta_link', $post->post_author);

        if ( function_exists('pll_current_language') && pll_current_language() == 'fr' ) {
            $author_cta_link = get_the_author_meta('cta_link_fr', $post->post_author);
        }

        $author_type = "Person";

        if($author_data_type == 'business') {
            $author_type = "Organization";
        }


        $structured_data = array(
            "@context" => "https://schema.org",
            "@type" => "BlogPosting",
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id" => $post_permalink
            ),
            "headline" => $post_title,
            "description" => $post_excerpt,
            //"image" => $post_thumbnail, 
            "author" => array(
                "@type" => $author_type,
                "name" => get_the_author_meta('display_name', $post->post_author),
            ),
            "publisher" => array(
                "@type" => "Organization",
                "name" => get_bloginfo('name'),
                "url" => get_bloginfo('url'),
                /*"logo" => array(
                    "@type" => "ImageObject",
                    "url" => "URL_TO_YOUR_LOGO_IMAGE"
                )*/
            ),
            "datePublished" => get_the_time('c', $post_id),
            "dateModified" => get_the_modified_time('c', $post_id),
        );

        if($author_cta_link) {
            $structured_data['author']['url'] = $author_cta_link['url'];
        }

        if($author_description) {
            $structured_data['author']['description'] = get_the_author_meta('description', $post->post_author);
        }

        if (has_post_thumbnail($post_id)) {

            $thumbnail_id = get_post_thumbnail_id($post_id);

            $meta = wp_get_attachment_metadata($thumbnail_id);
            
            $structured_data['image'] = array();

            $sizes = array(array(1200, 900), array(1200, 675), array(800, 800));

            foreach ($sizes as $size) {
                if (isset($meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]) && 
                    (null !== $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"] || 
                    $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]['width'] != $size[0] || 
                    $meta['sizes']["sbtl_{$size[0]}x{$size[1]}"]['height'] != $size[1])) {                    $structured_data['image'][] = get_the_post_thumbnail_url($post_id, "sbtl_{$size[0]}x{$size[1]}");
                }
            }

            if ( empty($structured_data['image']) ) {
                $structured_data['image'][] = get_the_post_thumbnail_url($post_id, 'large');
            }

        }

        echo '<script type="application/ld+json">' . json_encode($structured_data) . '</script>';
    }

    if ( get_post_meta( $post->ID, 'structured_data', true ) !== '' && get_post_meta( $post->ID, 'structured_data', true ) !== 'article') {
        $json_ld_data = get_post_meta( $post->ID, 'structured_data', true );
        $structured_data = json_decode($json_ld_data, true);

        if($structured_data) {
            echo '<script type="application/ld+json">' . json_encode($structured_data) . '</script>';
        }
    }

    if ( is_category( )) {
        $cat_id = get_queried_object_id();

        $structured_data = get_term_meta($cat_id, 'structured_data', true);

        if ($structured_data) {
            $structured_data = json_decode($structured_data, true);
            echo '<script type="application/ld+json">' . json_encode($structured_data) . '</script>';
        }
    }
}
add_action('wp_head', 'sbtl_post_structured_data');