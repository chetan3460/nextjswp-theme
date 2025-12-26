<?php

/**
 * Security: Prevent direct access to this file
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Reports Category Sorting Helper
 * Provides functions to apply category-specific sorting to reports
 */

/**
 * Get sort configuration for a specific category
 * 
 * @param int $category_id The category term ID
 * @return array Sort configuration with 'sort_by' and 'custom_order' keys
 */
function resplast_get_category_sort_config($category_id)
{
    $default_config = array(
        'sort_by' => 'date_desc',
        'custom_order' => 0
    );

    // Get category sort order from block fields if available
    if (function_exists('get_field')) {
        $sort_configs = get_field('category_sort_order');

        if ($sort_configs && is_array($sort_configs)) {
            foreach ($sort_configs as $config) {
                $config_cat_id = isset($config['category']) ? $config['category'] : null;

                if ($config_cat_id == $category_id) {
                    return array(
                        'sort_by' => isset($config['sort_by']) ? $config['sort_by'] : 'date_desc',
                        'custom_order' => isset($config['custom_order']) ? $config['custom_order'] : 0
                    );
                }
            }
        }
    }

    return $default_config;
}

/**
 * Sort reports by category-specific settings
 * 
 * @param array $reports Array of WP_Post objects
 * @param int $category_id The category to filter and sort reports by
 * @return array Sorted reports filtered by category
 */
function resplast_sort_reports_by_category($reports, $category_id)
{
    // Filter reports by category
    $filtered_reports = array();

    foreach ($reports as $report) {
        $report_categories = get_the_terms($report->ID, 'report_category');

        if ($report_categories && !is_wp_error($report_categories)) {
            foreach ($report_categories as $report_cat) {
                if ($report_cat->term_id == $category_id) {
                    $filtered_reports[] = $report;
                    break;
                }
            }
        }
    }

    // Get sort configuration for this category
    $sort_config = resplast_get_category_sort_config($category_id);
    $sort_by = $sort_config['sort_by'];

    // Check if this is annual reports category
    $category = get_term($category_id, 'report_category');
    $is_annual_reports = $category && ($category->slug === 'annual-reports' || strpos(strtolower($category->name), 'annual') !== false);

    // Apply sorting based on configuration
    switch ($sort_by) {
        case 'date_asc':
            usort($filtered_reports, function ($a, $b) {
                return strtotime($a->post_date) - strtotime($b->post_date);
            });
            break;

        case 'title_asc':
            usort($filtered_reports, function ($a, $b) {
                return strcmp($a->post_title, $b->post_title);
            });
            break;

        case 'title_desc':
            usort($filtered_reports, function ($a, $b) {
                return strcmp($b->post_title, $a->post_title);
            });
            break;

        case 'year_desc':
            // Sort by financial year descending (latest year first)
            usort($filtered_reports, function ($a, $b) {
                $a_years = get_the_terms($a->ID, 'financial_year');
                $b_years = get_the_terms($b->ID, 'financial_year');

                $a_year = ($a_years && !is_wp_error($a_years)) ? $a_years[0]->name : '';
                $b_year = ($b_years && !is_wp_error($b_years)) ? $b_years[0]->name : '';

                // Extract numeric year from "FY2023-24" format
                preg_match('/\d{4}/', $a_year, $a_matches);
                preg_match('/\d{4}/', $b_year, $b_matches);

                $a_num = !empty($a_matches) ? intval($a_matches[0]) : 0;
                $b_num = !empty($b_matches) ? intval($b_matches[0]) : 0;

                return $b_num - $a_num; // Descending
            });
            break;

        case 'year_asc':
            // Sort by financial year ascending (oldest year first)
            usort($filtered_reports, function ($a, $b) {
                $a_years = get_the_terms($a->ID, 'financial_year');
                $b_years = get_the_terms($b->ID, 'financial_year');

                $a_year = ($a_years && !is_wp_error($a_years)) ? $a_years[0]->name : '';
                $b_year = ($b_years && !is_wp_error($b_years)) ? $b_years[0]->name : '';

                // Extract numeric year from "FY2023-24" format
                preg_match('/\d{4}/', $a_year, $a_matches);
                preg_match('/\d{4}/', $b_year, $b_matches);

                $a_num = !empty($a_matches) ? intval($a_matches[0]) : 0;
                $b_num = !empty($b_matches) ? intval($b_matches[0]) : 0;

                return $a_num - $b_num; // Ascending
            });
            break;

        case 'custom':
            // Custom order would need to be handled by data-attributes on individual items
            // This is primarily handled on the frontend with JavaScript
            break;

        case 'date_desc':
        default:
            usort($filtered_reports, function ($a, $b) {
                return strtotime($b->post_date) - strtotime($a->post_date);
            });
            break;
    }

    return $filtered_reports;
}

/**
 * Sort all reports and group by category with per-category sorting
 * 
 * @param array $reports Array of WP_Post objects
 * @param array $categories Array of WP_Term objects
 * @return array Grouped and sorted reports
 */
function resplast_sort_reports_by_all_categories($reports, $categories)
{
    $grouped_reports = array();

    if (empty($categories) || is_wp_error($categories)) {
        return $grouped_reports;
    }

    foreach ($categories as $category) {
        $sorted = resplast_sort_reports_by_category($reports, $category->term_id);

        if (!empty($sorted)) {
            $grouped_reports[$category->term_id] = array(
                'category' => $category,
                'reports' => $sorted,
                'sort_config' => resplast_get_category_sort_config($category->term_id)
            );
        }
    }

    return $grouped_reports;
}

/**
 * Get sort order HTML data attribute for frontend sorting
 * 
 * @param int $report_id The report post ID
 * @param int $category_id The category term ID
 * @return string HTML data attribute
 */
function resplast_get_report_sort_order_attr($report_id, $category_id)
{
    $sort_config = resplast_get_category_sort_config($category_id);

    if ($sort_config['sort_by'] === 'custom' && $sort_config['custom_order']) {
        return ' data-sort-order="' . absint($sort_config['custom_order']) . '"';
    }

    return '';
}

/**
 * Get sort config as JSON for JavaScript
 * 
 * @param array $categories Array of WP_Term objects
 * @return string JSON encoded sort configurations
 */
function resplast_get_sort_config_json($categories)
{
    if (empty($categories) || is_wp_error($categories)) {
        return '{}';
    }

    $config = array();

    foreach ($categories as $category) {
        $sort_config = resplast_get_category_sort_config($category->term_id);
        $config[$category->slug] = array(
            'sort_by' => $sort_config['sort_by'],
            'custom_order' => $sort_config['custom_order']
        );
    }

    return wp_json_encode($config);
}
