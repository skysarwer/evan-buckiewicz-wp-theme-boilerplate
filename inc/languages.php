<?php

add_filter('pll_get_post_types', 'sbtl_add_cpt_to_pll', 10, 2);
function sbtl_add_cpt_to_pll($post_types) {
    $post_types['cat-footer'] = 'cat-footer';
    return $post_types;
}