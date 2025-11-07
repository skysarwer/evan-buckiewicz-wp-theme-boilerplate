<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
        <input type="search" autocomplete="off" class="search-field" placeholder="<?php echo esc_attr_x( 'What can we help you find?', 'rar' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
    </label>
    <button type="submit" class="search-btn" aria-label="Search"><?php echo sbtl_svg_search(); ?> </button>
</form>