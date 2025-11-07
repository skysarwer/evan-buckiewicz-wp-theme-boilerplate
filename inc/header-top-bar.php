<?php

/**
 * Header Top Bar Settings
 * Provides an admin interface to configure the top bar displayed above the main header.
 * Supports multilingual content with separate fields for English and French.
 * 
 * @package sbtl
 */

/**
 * Register Header Top Bar admin menu page.
 * Creates a settings page in WordPress admin for configuring the top bar.
 */
function header_top_bar_menu() {
    add_menu_page(
        'Header Top Bar',          // Page title
        'Header Top Bar',          // Menu title
        'manage_options',          // Capability required
        'header-top-bar',          // Menu slug
        'header_top_bar_page_callback', // Callback function
        '',                        // Icon URL (empty = default)
        99                         // Menu position
    );

    add_action('admin_init', 'header_top_bar_settings');
}
add_action('admin_menu', 'header_top_bar_menu');

/**
 * Register settings and fields for Header Top Bar.
 * Creates input fields for text and link URL in both English and French.
 */
function header_top_bar_settings() {
    register_setting('header_top_bar_options', 'header_top_bar_options', 'header_top_bar_options_sanitize');

    add_settings_section(
        'header_top_bar_settings_section',
        'Header Top Bar Settings',
        '',
        'header-top-bar'
    );

    // English text field
    add_settings_field(
        'top_bar_text',
        'Top Bar Text',
        'header_top_bar_text_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    // English link URL field
    add_settings_field(
        'top_bar_link_url',
        'Top Bar Link URL',
        'header_top_bar_link_url_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    // French text field (for multilingual support)
    add_settings_field(
        'top_bar_text_fr',
        'Top Bar Text FR',
        'header_top_bar_text_fr_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    add_settings_field(
        'top_bar_link_url_fr',
        'Top Bar Link URL FR',
        'header_top_bar_link_url_fr_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    add_settings_field(
        'top_bar_bg_color',
        'Top Bar Background Color',
        'header_top_bar_bg_color_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    add_settings_field(
        'top_bar_text_color',
        'Top Bar Text Color',
        'header_top_bar_text_color_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    add_settings_field(
        'top_bar_bg_hover',
        'Top Bar Hover Background Color',
        'header_top_bar_bg_hover_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );

    add_settings_field(
        'top_bar_text_hover',
        'Top Bar Hover Text Color',
        'header_top_bar_text_hover_callback',
        'header-top-bar',
        'header_top_bar_settings_section'
    );
}

// Settings field callbacks
function header_top_bar_text_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_text]" value="' . esc_attr($options['top_bar_text'] ?? '') . '">';
}

function header_top_bar_link_url_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_link_url]" value="' . esc_attr($options['top_bar_link_url'] ?? '') . '">';
}

function header_top_bar_text_fr_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_text_fr]" value="' . esc_attr($options['top_bar_text_fr'] ?? '') . '">';
}

function header_top_bar_link_url_fr_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_link_url_fr]" value="' . esc_attr($options['top_bar_link_url_fr'] ?? '') . '">';
}

function header_top_bar_bg_color_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_bg_color]" value="' . esc_attr($options['top_bar_bg_color'] ?? '') . '">';
}

function header_top_bar_text_color_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_text_color]" value="' . esc_attr($options['top_bar_text_color'] ?? '') . '">';
}

function header_top_bar_bg_hover_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_bg_hover]" value="' . esc_attr($options['top_bar_bg_hover'] ?? '') . '">';
}

function header_top_bar_text_hover_callback() {
    $options = get_option('header_top_bar_options');
    echo '<input type="text" name="header_top_bar_options[top_bar_text_hover]" value="' . esc_attr($options['top_bar_text_hover'] ?? '') . '">';
}

// Menu page callback
function header_top_bar_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('header_top_bar_options');
            do_settings_sections('header-top-bar');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Sanitize callback
function header_top_bar_options_sanitize($input) {
    $sanitized_input = [];

    $sanitized_input['top_bar_text'] = isset($input['top_bar_text']) ? sanitize_text_field($input['top_bar_text']) : '';
    $sanitized_input['top_bar_link_url'] = isset($input['top_bar_link_url']) ? esc_url_raw($input['top_bar_link_url']) : '';
    $sanitized_input['top_bar_text_fr'] = isset($input['top_bar_text_fr']) ? sanitize_text_field($input['top_bar_text_fr']) : '';
    $sanitized_input['top_bar_link_url_fr'] = isset($input['top_bar_link_url_fr']) ? esc_url_raw($input['top_bar_link_url_fr']) : '';
    $sanitized_input['top_bar_bg_color'] = isset($input['top_bar_bg_color']) ? sanitize_text_field($input['top_bar_bg_color']) : '';
    $sanitized_input['top_bar_text_color'] = isset($input['top_bar_text_color']) ? sanitize_text_field($input['top_bar_text_color']) : '';
    $sanitized_input['top_bar_bg_hover'] = isset($input['top_bar_bg_hover']) ? sanitize_text_field($input['top_bar_bg_hover']) : '';
    $sanitized_input['top_bar_text_hover'] = isset($input['top_bar_text_hover']) ? sanitize_text_field($input['top_bar_text_hover']) : '';

    return $sanitized_input;
}

function header_top_bar_render() {
    $header_option = get_option('header_top_bar_options');
    $top_bar_text = $header_option['top_bar_text'] ?? '';
    $top_bar_link_url = $header_option['top_bar_link_url'] ?? '';
    $top_bar_bg_color = $header_option['top_bar_bg_color'] ?? '';
    $top_bar_text_color = $header_option['top_bar_text_color'] ?? '';
    $top_bar_bg_hover = $header_option['top_bar_bg_hover'] ?? '';
    $top_bar_text_hover = $header_option['top_bar_text_hover'] ?? '';

    if ( function_exists( 'pll_current_language' ) && 'fr' == pll_current_language() ) {
        $top_bar_text = $header_option['top_bar_text_fr'] ?? '';
        $top_bar_link_url = $header_option['top_bar_link_url_fr'] ?? '';
    }

    if ($top_bar_text) {
        ?>
        <a href="<?php echo $top_bar_link_url; ?>" class="header-top-bar content-wrap" style="background-color: <?php echo $top_bar_bg_color; ?>; color: <?php echo $top_bar_text_color; ?>; --header-top-bar-bg-hover: <?php echo $top_bar_bg_hover;?>; --header-top-bar-text-hover: <?php echo $top_bar_text_hover;?>">
            <?php echo $top_bar_text; ?>
        </a>
        <?php
    }
}