<?php

/**
 * Security: Prevent direct access to this file
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Handlers
 * Handles AJAX requests for news filtering and product filtering
 */

/**
 * AJAX: filter/sort/paginate news
 */
if (!function_exists('resplast_handle_news_ajax')) {
    function resplast_handle_news_ajax()
    {
        check_ajax_referer('resplast_news_nonce', 'nonce');

        $paged  = max(1, absint($_POST['paged'] ?? 1)); // kept for backward compatibility
        $ppp    = isset($_POST['posts_per_page']) ? max(1, min(24, absint($_POST['posts_per_page']))) : 6;
        $offset = isset($_POST['offset']) ? max(0, absint($_POST['offset'])) : 0;

        $sort = isset($_POST['sort']) ? sanitize_text_field(wp_unslash($_POST['sort'])) : 'newest';
        $order = (in_array(strtolower($sort), ['asc', 'oldest'], true)) ? 'ASC' : 'DESC';

        // Use news_category taxonomy specifically for news posts
        $taxonomy = 'news_category';
        if (!empty($_POST['taxonomy'])) {
            $tx = sanitize_text_field(wp_unslash($_POST['taxonomy']));
            if (taxonomy_exists($tx)) {
                $taxonomy = $tx;
            }
        }

        $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
        $search   = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';

        $args = [
            'post_type'      => 'news',
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => $order,
            'posts_per_page' => $ppp,
            'offset'        => $offset,
            'no_found_rows'  => false,
            's'              => $search,
        ];

        if (!empty($category) && $category !== 'all') {
            $term = null;
            if (is_numeric($category)) {
                $term = get_term_by('id', (int) $category, $taxonomy);
            }
            if (!$term) {
                $term = get_term_by('slug', $category, $taxonomy);
            }
            if ($term) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => (int) $term->term_id,
                    ],
                ];
            }
        }

        $q = new WP_Query($args);

        ob_start();
        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();
                // Use the same card template as listing.php for consistency
                get_template_part('templates/parts/news_card_new');
            }
            wp_reset_postdata();
        }
        $html = trim(ob_get_clean());
        $returned = (int) $q->post_count;
        $total    = (int) $q->found_posts;
        $has_more = ($offset + $returned) < $total;

        // Do not inject a "No news found" placeholder here to avoid duplicates on load-more.
        // Frontend decides when to show an empty-state message (only on full refresh/search results).

        wp_send_json_success([
            'html'         => $html,
            'found_posts'  => $total,
            'returned'     => $returned,
            'has_more'     => $has_more,
            'current'      => $paged,
            'empty'        => ($html === ''),
        ]);
    }
    add_action('wp_ajax_resplast_news_query', 'resplast_handle_news_ajax');
    add_action('wp_ajax_nopriv_resplast_news_query', 'resplast_handle_news_ajax');
}

// Product filtering AJAX handler moved to inc/ajax/product-filter.php
// to avoid duplication with existing specialized implementation
