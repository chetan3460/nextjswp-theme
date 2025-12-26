<?php

/**
 * Security: Prevent direct access to this file
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper functions for product fields
 */

/**
 * Get sorted custom attributes for a product
 * 
 * @param int $post_id Optional post ID. Defaults to current post.
 * @return array Sorted array of custom attributes
 */
function get_sorted_custom_attributes($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Debug post ID
    if (isset($_GET['debug'])) {
        error_log('Getting custom attributes for post ID: ' . $post_id);
    }
    if (!function_exists('get_field')) {
        return [];
    }

    // Get custom attributes
    // Debug output
    if (isset($_GET['debug'])) {
        echo '<pre>';
        echo 'Raw custom_attributes field:';
        var_dump(get_field('custom_attributes', $post_id));
        echo '</pre>';
    }

    $attributes = get_field('custom_attributes', $post_id);

    // Debug raw attributes
    if (isset($_GET['debug'])) {
        error_log('Raw attributes: ' . print_r($attributes, true));
    }
    if (!$attributes || !is_array($attributes)) {
        return [];
    }

    // Sort attributes by order if set
    usort($attributes, function ($a, $b) {
        // Get order values, defaulting to 999 if not set
        $a_order = $a['order'] ?? 999;
        $b_order = $b['order'] ?? 999;

        // Convert to integers for comparison
        $a_order = is_numeric($a_order) ? intval($a_order) : 999;
        $b_order = is_numeric($b_order) ? intval($b_order) : 999;

        // Debug sorting
        if (isset($_GET['debug'])) {
            error_log(sprintf('Comparing orders: %d vs %d', $a_order, $b_order));
        }
        $order_a = isset($a['order']) && is_numeric($a['order']) ? (int)$a['order'] : 999;
        $order_b = isset($b['order']) && is_numeric($b['order']) ? (int)$b['order'] : 999;
        return $order_a - $order_b;
    });

    // Normalize keys for template compatibility
    $normalized = array_map(function ($attr) {
        $label = $attr['label'] ?? ($attr['attribute_label'] ?? '');
        $content = $attr['content'] ?? ($attr['attribute_content'] ?? '');
        $order = $attr['order'] ?? ($attr['display_order'] ?? null);
        return [
            'label' => $label,
            'content' => $content,
            'order' => $order,
            // Legacy keys expected by templates
            'attribute_label' => $label,
            'attribute_content' => $content,
        ];
    }, $attributes);

    return $normalized;
}

/**
 * Check if product image should be shown
 * 
 * @param int $post_id Optional post ID. Defaults to current post.
 * @return bool Whether to show the product image
 */
function should_show_product_image($post_id = null)
{
    if (!function_exists('get_field')) {
        return true;
    }

    $show_image = get_field('show_product_image', $post_id);
    // If field doesn't exist or is not set, default to true
    return $show_image !== false;
}
