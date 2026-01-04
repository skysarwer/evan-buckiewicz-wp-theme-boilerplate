<?php

/**
 * Navigation Walkers
 * Custom WordPress walkers for enhanced menu and page navigation functionality.
 * Provides accordion-style navigation with improved accessibility and user experience.
 */

/**
 * Custom Page Walker for Sidebar Menu
 * Creates an accordion-style navigation for page hierarchies in sidebars.
 * Automatically expands to show the current page and its ancestors.
 *
 * @extends Walker_Page
 */
class SBTL_Walker_Page_Sidemenu extends Walker_Page {
    /**
     * Start outputting a menu item element.
     *
     * @param string  $output       The output string (passed by reference).
     * @param WP_Post $page         The page object.
     * @param int     $depth        Depth of page in hierarchy.
     * @param array   $args         Arguments passed to wp_list_pages().
     * @param int     $current_page Current page ID.
     */
    function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
        if ( $depth ) {
            $indent = str_repeat("\t", $depth);
        } else {
            $indent = '';
        }

        $this->item_id = $page->ID;

        extract($args, EXTR_SKIP);
        $css_class = array('sidemenu__item', 'sidemenu__item--depth-' . $depth );

        /**
         * Variables for submenu accordion state.
         * Determines if submenu should be open or closed by default.
         */
        $accordion_cont = 'closed';
        $accordion_expanded = 'false';

        // Check if this page or its ancestors are the current page
        if ( !empty($current_page) ) {
            $_current_page = get_post( $current_page );
            // Expand if this page is an ancestor of the current page
            if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
                $css_class[] = 'current_page_ancestor';
                $accordion_cont = 'open';
                $accordion_expanded = 'true';
            }
            // Expand and highlight if this is the current page
            if ( $page->ID == $current_page ) {
                $css_class[] = 'is-active';
                $accordion_cont = 'open';
                $accordion_expanded = 'true';
            }
        } 

        $dropdown = '';
        
        // Check if this page has children
        $children = get_pages( array( 'child_of' => $page->ID ) );

        // Add accordion functionality if the page has children
        if ( !empty($children) ) {
            $css_class[] = 'has_children accordion-container ' . $accordion_cont;
            $dropdown = '<button class="page-submenu-toggle accordion" aria-expanded="'.$accordion_expanded.'" aria-label="'.sprintf(__('%1$s sub-menu', 'sbtl'), $page->post_title ).'" aria-controls="submenu-'.$page->ID.'">'.sbtl_caret_svg().'</button>';
        } 
        $css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
        
        /**
         * Handle empty parent pages (pages that shouldn't be clickable).
         * If marked as empty parent, render as button instead of link.
         */
        if ( get_post_meta( $page->ID, 'empty_parent_page', true ) && !empty($children) ) {
            $output .= $indent . '<li class="' . $css_class . '"><span class="sidemenu__subwrap"><button class="sidemenu__link accordion" aria-expanded="'.$accordion_expanded.'" aria-label="'.sprintf(__('%1$s sub-menu', 'sbtl'), $page->post_title ).'" aria-controls="submenu-'.$page->ID.'">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '<span class="caret">' .  sbtl_caret_svg() . '</span>' . '</button></span>';
        }
        // Page with children - render as link with dropdown toggle
        else if ( !empty($children) ) {
            $output .= '<li class="' . $css_class . '"><span class="sidemenu__subwrap"><a class="sidemenu__link" data-wp-on--click="actions.navigate" href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>' . $dropdown . '</span>';
        } 
        // Page without children - simple link
        else {
            $output .= '<li class="' . $css_class . '"><a class="sidemenu__link" data-wp-on--click="actions.navigate" href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';
        }
    }

    /**
     * Start outputting a submenu level.
     *
     * @param string $output The output string (passed by reference).
     * @param int    $depth  Depth of page in hierarchy.
     * @param array  $args   Arguments passed to wp_list_pages().
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        
        // Create accordion-enabled submenu
        $output .= "\n$indent<ul class=\"children accordion-body\" id=\"submenu-".$this->item_id."\">\n";
    }


}

/**
 * Custom Menu Walker for Primary Navigation
 * Extends WordPress nav menu walker to add custom classes and accordion functionality.
 *
 * @extends Walker_Nav_Menu
 */
class SBTL_Menu_Walker extends Walker_Nav_Menu {
    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );

        // Default class.
        $classes = array( 'sub-menu' );

        if ( isset( $args->lang_container ) ) {
            $classes = array ( );
        }


        // ! Get parent item ID:
        $id = isset( $args->item_id ) ? ' id="submenu-' . absint( $args->item_id ) . '"' : '';

        $id = isset( $args->lang_container ) ? ' id="lang-switcher"' : $id;

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @since 4.8.0
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args    An object of `wp_nav_menu()` arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        // ! Insert ID:
        $output .= "{$n}{$indent}<ul{$class_names}{$id}>{$n}";
    }
}

add_filter(
    'walker_nav_menu_start_el',
    function ($item_output, $menu_item, $depth, $args) {

        if ($menu_item->lang) {
    
            $languages = pll_the_languages(array('raw'=>1));

            //find the entry in languages array with the same locale as the menu item
            $lang = array_filter($languages, function($v) use ($menu_item) {
                return $v['locale'] == $menu_item->lang;
            });

            $lang = $lang[array_key_first($lang)];

            $lang_output = '<a href="'.$lang['url'].'" hreflang="'.$lang['locale'].'" lang="'.$lang['locale'].'" aria-label="'.$lang['name'].'"><span aria-hidden="true">'.strtoupper($lang['slug']).'</span><span class="screen-reader-text">'.$lang['name'].'</span></a>';

            // Get the first language
            $first_language = reset($languages);

            if ($args->lang != $first_language['locale']) {
               $lang_output = '<span class="separator" aria-hidden="true"> / </span>' . $lang_output;
            }        

            return $lang_output;
        }

        if ( $menu_item->url == '#pll_switcher') {
            return;
        }

        return $item_output;
    }, 10, 4
);

add_filter(
    'nav_menu_item_args',
    function( $args, $item, $depth ) {

        if ( $item->url == '#pll_switcher') {
            $args->lang_container = true;
        }

        if ( $item->lang ) {
            $args->lang = $item->lang;
        }

        if (is_array($args)) {
            $args = (object) $args;
        }
        $args->item_id = $item->ID;
        return $args;
    },
    10,
    3
);


//Add custom dropdown filter to Primary Menu
add_filter( 'walker_nav_menu_start_el', 'sbtl_parent_menu_dropdown', 10, 4 );
function sbtl_parent_menu_dropdown( $item_output, $item, $depth, $args ) {


    if ( ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) && '#pll_switcher' !== $item->url ) {
        $stripped_item_output = preg_replace('/<a[^>]*>(.*)<\/a>/', '$1', $item_output);
        return '<span class="flex">'.$item_output . '<button class="submenu-toggle outline" aria-expanded="false"  aria-label="'.$stripped_item_output.' sub-menu" aria-controls="submenu-'.$args->item_id.'">'.sbtl_caret_svg().'</button></span>';
    }

    return $item_output;
}


function sbtl_check_nav_for_lang_switcher() {
    $menu_locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object( $menu_locations['menu-1'] );
    if ($menu) {
        $menu_items = wp_get_nav_menu_items($menu->term_id);
    } else {
        // Handle the case where $menu is not valid
        $menu_items = [];
        // Optionally, log an error or display a message
        error_log('Menu not found or invalid.');
    }
    foreach ( $menu_items as $menu_item ) {
        if ( $menu_item->url == '#pll_switcher') {
            return 'has-lang-switcher';
        }
    }

    return '';
}