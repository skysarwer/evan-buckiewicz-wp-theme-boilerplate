<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>" data-wp-interactive="sbtl">
    <div role="combobox" aria-haspopup="listbox" aria-owns="search-results-list" data-wp-bind--aria-expanded="state.search.isOpen">
        <label>
            <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
            <input 
                type="text" 
                autocomplete="off" 
                class="search-field" 
                placeholder="<?php echo esc_attr_x( 'What can we help you find?', 'rar' ) ?>" 
                value="<?php echo get_search_query() ?>" 
                name="s" 
                title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>"
                data-wp-bind--value="state.search.query"
                data-wp-on--input="actions.search.handleInput"
                data-wp-on--keydown="actions.search.handleKeydown"
                data-wp-on--focus="actions.search.handleFocus"
                data-wp-on--blur="actions.search.handleBlur"
                aria-autocomplete="list"
                aria-controls="search-results-list"
                data-wp-bind--aria-activedescendant="state.search.selectedId"
            />
        </label>
        <button 
            type="button" 
            class="search-clear-btn" 
            aria-label="<?php echo esc_attr_x( 'Clear search', 'label' ); ?>"
            data-wp-bind--hidden="!state.search.query"
            data-wp-on--click="actions.search.clear"
        >
            <?php echo sbtl_svg_close(); ?>
        </button>
        <button type="submit" class="search-btn" aria-label="Search"><?php echo sbtl_svg_search(); ?> </button>
    </div>

    <div class="search-loading" data-wp-bind--hidden="!state.search.isLoading">
        <?php esc_html_e( 'Loading...', 'sbtl' ); ?>
    </div>

    <div 
        id="search-results-list" 
        class="search-results-dropdown" 
        role="listbox" 
        hidden
        data-wp-bind--hidden="!state.search.isOpen"
    >
        <template data-wp-each--group="state.search.results">
            <div class="search-group">
                <h3 class="search-group-title" data-wp-text="context.group.key"></h3>
                <ul class="search-group-list">
                    <template data-wp-each--item="context.group.items">
                        <li role="option" data-wp-class--active="state.search.isResultActive" data-wp-bind--aria-selected="state.search.isResultActive" data-wp-bind--aria-label="context.item.name" data-wp-bind--id="context.item.id">
                            <a 
                                tabindex="-1"
                                data-wp-bind--href="context.item.url"
                                data-wp-text="context.item.name"
                                data-wp-on--click="actions.search.selectResult"
                            ></a>
                        </li>
                    </template>
                </ul>
            </div>
        </template>
        <div class="no-results" data-wp-bind--hidden="state.search.hasResults">
            <?php esc_html_e( 'No results found.', 'sbtl' ); ?>
        </div>
    </div>
</form>