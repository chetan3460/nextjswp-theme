<?php

/**
 * Register "Case Studies" Custom Post Type
 */
function register_case_studies_post_type()
{
    $labels = array(
        'name'               => _x('Case Studies', 'post type general name', 'nextjswp'),
        'singular_name'      => _x('Case Study', 'post type singular name', 'nextjswp'),
        'menu_name'          => _x('Case Studies', 'admin menu', 'nextjswp'),
        'name_admin_bar'     => _x('Case Study', 'add new on admin bar', 'nextjswp'),
        'add_new'            => _x('Add New', 'case study', 'nextjswp'),
        'add_new_item'       => __('Add New Case Study', 'nextjswp'),
        'new_item'           => __('New Case Study', 'nextjswp'),
        'edit_item'          => __('Edit Case Study', 'nextjswp'),
        'view_item'          => __('View Case Study', 'nextjswp'),
        'all_items'          => __('All Case Studies', 'nextjswp'),
        'search_items'       => __('Search Case Studies', 'nextjswp'),
        'parent_item_colon'  => __('Parent Case Studies:', 'nextjswp'),
        'not_found'          => __('No case studies found.', 'nextjswp'),
        'not_found_in_trash' => __('No case studies found in Trash.', 'nextjswp'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'nextjswp'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'case-studies'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true, // Required for WP GraphQL and Gutenberg
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
    );

    register_post_type('case-studies', $args);
}
add_action('init', 'register_case_studies_post_type');
