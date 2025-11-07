<?php

function register_cat_footer_post_type() {
    register_post_type('cat-footer', 
    array(
        'labels' => array(
            'name' => __('Category Footers'),
            'singular_name' => __('Category Footer'),
            'plural_name' => __('Category Footers'),
            'add_new' => 'Add New Category Footer',
            'add_new_item' => 'Add New Category Footer',
        ),
        'public' => false,
        'show_ui' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'show_in_nav_menus' => false,
        'show_in_menu' => 'edit.php',
        'show_in_rest' => true,
        'rewrite' => false,
        'supports' => array('editor', 'page-attributes'),
        'menu_icon' => 'dashicons-category',
        'taxonomies' => array('category'),
        'template' => array(
            array('core/group', 
                array(
                    'align' => 'wide',
                    'layout' => array('type' => 'default'),
                ),
                array(
                    array('core/paragraph', array()),
                )
            ),
        ),
    ));
}
add_action('init', 'register_cat_footer_post_type', 200);

function modify_cat_footer_columns($columns) {
    unset($columns['date']); // Remove the date column
    $columns['menu_order'] = 'Order'; // Add an order column
    $columns['date'] = 'Date'; // Add a date column
    return $columns;
}
add_filter('manage_edit-cat-footer_columns', 'modify_cat_footer_columns');

// Function to output the content of the custom columns
function custom_cat_footer_column_content($column, $post_id) {
    if ($column == 'menu_order') {
        echo get_post_field('menu_order', $post_id); // Output the order of the post
    }
}
add_action('manage_cat-footer_posts_custom_column', 'custom_cat_footer_column_content', 10, 2);


// Function to set a default title for 'cat-footer' posts
function set_default_title($data, $postarr) {
    if ($data['post_type'] == 'cat-footer' && $data['post_title'] == 'Auto Draft') {
        $data['post_title'] = 'Category Footer'; // Set your default title here
    }
    return $data;
}
add_filter('wp_insert_post_data', 'set_default_title', 10, 2);