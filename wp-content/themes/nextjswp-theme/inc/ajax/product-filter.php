<?php

/**
 * Security: Prevent direct access to this file
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for product filtering
 */
function resplast_handle_product_filter_ajax()
{
    check_ajax_referer('resplast_products_nonce', 'nonce');

    // Get parameters with proper sanitization
    $ppp = isset($_POST['posts_per_page']) ? max(1, min(50, absint($_POST['posts_per_page']))) : 12;
    $offset = isset($_POST['offset']) ? max(0, absint($_POST['offset'])) : 0;
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'name';
    $view_mode = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'grid';

    // Get filter arrays
    $chemistry_filters = !empty($_POST['chemistry']) ? array_filter(explode(',', sanitize_text_field($_POST['chemistry']))) : [];
    $brand_filters = !empty($_POST['brand']) ? array_filter(explode(',', sanitize_text_field($_POST['brand']))) : [];
    $application_filters = !empty($_POST['applications']) ? array_filter(explode(',', sanitize_text_field($_POST['applications']))) : [];

    // Build query args with stable ordering
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $ppp,
        'offset' => $offset,
        'no_found_rows' => false,
        's' => $search,
        'ignore_sticky_posts' => true,
        'orderby' => ['menu_order' => 'ASC', 'ID' => 'ASC']
    ];

    // Add filter queries
    if (!empty($chemistry_filters) || !empty($brand_filters) || !empty($application_filters)) {
        $tax_query = ['relation' => 'AND'];

        if (!empty($chemistry_filters)) {
            $tax_query[] = [
                'taxonomy' => 'product_chemistry',
                'field' => 'slug',
                'terms' => $chemistry_filters,
                'operator' => 'IN'
            ];
        }

        if (!empty($brand_filters)) {
            $tax_query[] = [
                'taxonomy' => 'product_brand',
                'field' => 'slug',
                'terms' => $brand_filters,
                'operator' => 'IN'
            ];
        }

        if (!empty($application_filters)) {
            $tax_query[] = [
                'taxonomy' => 'product_application',
                'field' => 'slug',
                'terms' => $application_filters,
                'operator' => 'IN'
            ];
        }

        $args['tax_query'] = $tax_query;
    }

    // Add sorting with deterministic ordering
    switch ($sort) {
        case 'newest':
            $args['orderby'] = [
                'date' => 'DESC',
                'menu_order' => 'ASC',
                'ID' => 'ASC'
            ];
            break;
        case 'oldest':
            $args['orderby'] = [
                'date' => 'ASC',
                'menu_order' => 'ASC',
                'ID' => 'ASC'
            ];
            break;
        case 'name':
        default:
            $args['orderby'] = [
                'title' => 'ASC',
                'menu_order' => 'ASC',
                'ID' => 'ASC'
            ];
            break;
    }

    error_log(sprintf(
        'Product Filter Request: offset=%d, ppp=%d, sort=%s',
        $offset,
        $ppp,
        $sort
    ));

    // Execute query
    $products_query = new WP_Query($args);
    $total = (int) $products_query->found_posts;
    $returned = (int) $products_query->post_count;
    $has_more = ($offset + $returned) < $total;

    // Generate HTML
    ob_start();
    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            get_template_part('templates/products/card-' . $view_mode);
        }
        wp_reset_postdata();
    }
    $html = trim(ob_get_clean());

    error_log(sprintf(
        'Product Filter Result: total=%d, returned=%d, offset=%d, has_more=%d',
        $total,
        $returned,
        $offset,
        $has_more ? 1 : 0
    ));

    wp_send_json_success([
        'html' => $html,
        'total' => $total,
        'returned' => $returned,
        'has_more' => $has_more,
        'debug' => [
            'offset' => $offset,
            'returned' => $returned,
            'next_offset' => $offset + $returned
        ]
    ]);
}

add_action('wp_ajax_resplast_filter_products', 'resplast_handle_product_filter_ajax');
add_action('wp_ajax_nopriv_resplast_filter_products', 'resplast_handle_product_filter_ajax');
