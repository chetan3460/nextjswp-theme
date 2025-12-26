<?php

/**
 * Resplast Theme Functions
 * 
 * @package Resplast
 * @since 1.0.0
 */

// Fix XML Sitemap whitespace from Yoast SEO
if (defined('ABSPATH') && preg_match('/sitemap.*\.xml/i', $_SERVER['REQUEST_URI'] ?? '')) {
    // Clear any buffered output before XML generation
    while (@ob_get_level() > 0) {
        @ob_end_clean();
    }
    // Create clean buffer
    ob_start(function ($buffer) {
        // Remove leading whitespace/newlines before XML declaration
        return ltrim($buffer);
    });
}

// Theme Constants
if (!defined('T_PREFIX')) {
    define('T_PREFIX', 'replast');
}

// Enable CORS for Next.js frontend
add_action('init', function () {
    if (
        strpos($_SERVER['REQUEST_URI'] ?? '', '/graphql') !== false ||
        strpos($_SERVER['REQUEST_URI'] ?? '', '/wp-json') !== false
    ) {

        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}, 1);

// ============================================================================
// CORE INCLUDES
// ============================================================================

// Core initialization
require_once __DIR__ . '/inc/core/init.php';

// Helper functions
require_once get_template_directory() . '/inc/product-helpers.php';
require_once get_template_directory() . '/inc/reports-sorting-helper.php';

// AJAX Handlers
require_once get_template_directory() . '/inc/ajax-handlers.php';

// Admin Customizations
require_once get_template_directory() . '/inc/admin-customizations.php';

// Product filtering AJAX
require_once get_template_directory() . '/inc/ajax/product-filter.php';

// ============================================================================
// ACF CONFIGURATION
// ============================================================================

// ACF JSON save and load paths
add_filter('acf/settings/save_json', function ($path) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

/**
 * Force all ACF Field Groups to be visible in REST API and GraphQL
 * for seamless headless integration.
 */
add_filter('acf/load_field_group', function ($field_group) {
    $field_group['show_in_rest'] = true;
    $field_group['show_in_graphql'] = true;
    return $field_group;
});

// ============================================================================
// BACKEND OPTIMIZATIONS
// ============================================================================

// ============================================================================
// WORDPRESS CORE OPTIMIZATIONS
// ============================================================================

// Disable Gutenberg (optional - remove if you use Gutenberg)
add_filter('use_block_editor_for_post', '__return_false');

// Limit WordPress heartbeat
function slow_heartbeat($settings)
{
    $settings['interval'] = 60;
    return $settings;
}
add_filter('heartbeat_settings', 'slow_heartbeat');

// Reduce revisions
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

// Increase memory limit
ini_set('memory_limit', '256M');

// Disable auto-updates
add_filter('automatic_updater_disabled', '__return_true');



// Optimize database queries
function limit_post_revisions($num, $post)
{
    return 3;
}
add_filter('wp_revisions_to_keep', 'limit_post_revisions', 10, 2);

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

/**
 * Calculate estimated reading time for a post
 */
function calculate_post_read_time($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Check cache
    $cached_time = get_post_meta($post_id, '_post_read_time', true);
    if (!empty($cached_time)) {
        return (int) $cached_time;
    }

    // Get and process content
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags(strip_shortcodes($content));
    $content = preg_replace('/\\s+/', ' ', trim($content));
    $word_count = str_word_count($content);

    // Calculate (200 words per minute)
    $reading_time = ceil($word_count / 200);
    $reading_time = max(1, min(30, $reading_time));

    // Cache result
    update_post_meta($post_id, '_post_read_time', $reading_time);

    return $reading_time;
}

/**
 * Clear read time cache when post is updated
 */
function clear_read_time_cache($post_id)
{
    delete_post_meta($post_id, '_post_read_time');
}
add_action('save_post', 'clear_read_time_cache');
add_action('post_updated', 'clear_read_time_cache');



// ============================================================================
// REPORTS CUSTOM POST TYPE
// ============================================================================

/**
 * Register Reports Custom Post Type
 */
function register_reports_post_type()
{
    $labels = array(
        'name'                  => _x('Reports', 'Post type general name', 'resplast'),
        'singular_name'         => _x('Report', 'Post type singular name', 'resplast'),
        'menu_name'             => _x('Reports', 'Admin Menu text', 'resplast'),
        'name_admin_bar'        => _x('Report', 'Add New on Toolbar', 'resplast'),
        'add_new'               => __('Add New', 'resplast'),
        'add_new_item'          => __('Add New Report', 'resplast'),
        'new_item'              => __('New Report', 'resplast'),
        'edit_item'             => __('Edit Report', 'resplast'),
        'view_item'             => __('View Report', 'resplast'),
        'all_items'             => __('All Reports', 'resplast'),
        'search_items'          => __('Search Reports', 'resplast'),
        'not_found'             => __('No reports found.', 'resplast'),
        'not_found_in_trash'    => __('No reports found in Trash.', 'resplast'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'reports'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('reports', $args);
}
add_action('init', 'register_reports_post_type');

/**
 * Register Report Taxonomies
 */
function register_report_taxonomies()
{
    // Report Categories
    register_taxonomy('report_category', array('reports'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Report Categories', 'Taxonomy General Name', 'resplast'),
            'singular_name' => _x('Report Category', 'Taxonomy Singular Name', 'resplast'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ));

    // Financial Years
    register_taxonomy('financial_year', array('reports'), array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x('Financial Years', 'Taxonomy General Name', 'resplast'),
            'singular_name' => _x('Financial Year', 'Taxonomy Singular Name', 'resplast'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ));

    // Quarters
    register_taxonomy('quarter', array('reports'), array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x('Quarters', 'Taxonomy General Name', 'resplast'),
            'singular_name' => _x('Quarter', 'Taxonomy Singular Name', 'resplast'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'register_report_taxonomies');

/**
 * Insert default taxonomy terms on theme activation
 */
function insert_default_report_terms()
{
    if (get_option('resplast_default_report_terms_inserted')) {
        return;
    }

    // Default categories
    $default_categories = array(
        'Annual Reports',
        'Quarterly Results',
        'Investor Presentations',
        'Financial Statements',
        'Corporate Governance',
        'Sustainability Reports',
        'Regulatory Filings',
        'Press Releases',
        'Notices & Disclosures',
        'Policies & Guidelines'
    );

    foreach ($default_categories as $category) {
        if (!term_exists($category, 'report_category')) {
            wp_insert_term($category, 'report_category');
        }
    }

    // Default financial years
    $current_year = date('Y');
    for ($i = -3; $i <= 2; $i++) {
        $year = $current_year + $i;
        $next_year = $year + 1;
        $fy_label = 'FY' . $year . '-' . substr($next_year, 2);
        if (!term_exists($fy_label, 'financial_year')) {
            wp_insert_term($fy_label, 'financial_year');
        }
    }

    // Default quarters
    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
        if (!term_exists($quarter, 'quarter')) {
            wp_insert_term($quarter, 'quarter');
        }
    }

    update_option('resplast_default_report_terms_inserted', true);
}
add_action('init', 'insert_default_report_terms');




/**
 * Add this code to the BOTTOM of your functions.php file
 * This enables GraphQL support for all your Custom Post Types and Taxonomies
 */
add_action('init', function () {
    // 1. Enable GraphQL for Custom Post Types
    $cpts = [
        'news' => ['singular' => 'News', 'plural' => 'News'],
        'team_member' => ['singular' => 'TeamMember', 'plural' => 'TeamMembers'],
        'product' => ['singular' => 'Product', 'plural' => 'Products'],
        'reports' => ['singular' => 'Report', 'plural' => 'Reports']
    ];
    foreach ($cpts as $post_type => $names) {
        $obj = get_post_type_object($post_type);
        if ($obj) {
            $obj->show_in_graphql = true;
            $obj->graphql_single_name = $names['singular'];
            $obj->graphql_plural_name = $names['plural'];
            register_post_type($post_type, (array) $obj);
        }
    }
    // 2. Enable GraphQL for Taxonomies
    $taxonomies = [
        'news_category' => ['singular' => 'NewsCategory', 'plural' => 'NewsCategories'],
        'team_category' => ['singular' => 'TeamCategory', 'plural' => 'TeamCategories'],
        'product_chemistry' => ['singular' => 'ProductChemistry', 'plural' => 'ProductChemistries'],
        'product_brand' => ['singular' => 'ProductBrand', 'plural' => 'ProductBrands'],
        'product_application' => ['singular' => 'ProductApplication', 'plural' => 'ProductApplications'],
        'report_category' => ['singular' => 'ReportCategory', 'plural' => 'ReportCategories'],
        'financial_year' => ['singular' => 'FinancialYear', 'plural' => 'FinancialYears'],
        'quarter' => ['singular' => 'Quarter', 'plural' => 'Quarters']
    ];
    foreach ($taxonomies as $tax_slug => $names) {
        global $wp_taxonomies;
        if (isset($wp_taxonomies[$tax_slug])) {
            $wp_taxonomies[$tax_slug]->show_in_graphql = true;
            $wp_taxonomies[$tax_slug]->graphql_single_name = $names['singular'];
            $wp_taxonomies[$tax_slug]->graphql_plural_name = $names['plural'];
        }
    }
}, 99);


/**
 * CUSTOM REST API ENDPOINTS FOR HEADLESS
 * Exposes Menus and Logo publicly to avoid Auth issues
 */
add_action('rest_api_init', function () {
    register_rest_route('nextjs/v1', '/header', [
        'methods' => 'GET',
        'callback' => 'nextjswp_get_header_data',
        'permission_callback' => '__return_true', // Public access
    ]);
});

function nextjswp_get_header_data()
{
    // 1. Get Logo
    $logo_id = get_theme_mod('custom_logo');
    $logo_url = null;
    if ($logo_id) {
        $logo_img = wp_get_attachment_image_src($logo_id, 'full');
        $logo_url = $logo_img ? $logo_img[0] : null;
    }

    // 2. Get Primary Menu
    $menu_items = [];
    $locations = get_nav_menu_locations();

    // Try 'primary' or 'main' location, or just the first available menu if none mapped
    $menu_id = $locations['primary'] ?? $locations['main'] ?? null;

    if (!$menu_id) {
        $menus = wp_get_nav_menus();
        if (!empty($menus)) {
            // Prefer a menu named "Main" or "Primary"
            foreach ($menus as $m) {
                if (stripos($m->name, 'main') !== false || stripos($m->name, 'primary') !== false) {
                    $menu_id = $m->term_id;
                    break;
                }
            }
            // Fallback to first menu
            if (!$menu_id) {
                $menu_id = $menus[0]->term_id;
            }
        }
    }

    if ($menu_id) {
        $wp_items = wp_get_nav_menu_items($menu_id);
        if ($wp_items) {
            foreach ($wp_items as $item) {
                // Normalize for frontend
                $menu_items[] = [
                    'id' => $item->ID,
                    'label' => $item->title,
                    'url' => str_replace(home_url(), '', $item->url), // Relative URL
                    'parentId' => (int) $item->menu_item_parent,
                    'cssClasses' => array_filter($item->classes),
                    'description' => $item->description,
                    'target' => $item->target,
                ];
            }
        }
    }

    return [
        'site_logo' => $logo_url,
        'menu_items' => $menu_items
    ];
}

// ============================================================================
// CUSTOM REST API ENDPOINTS
// ============================================================================

/**
 * Register REST API endpoint for Formidable Forms
 * Allows Next.js to fetch rendered form HTML
 */
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/formidable-form/(?P<id>[\w-]+)', [
        'methods' => 'GET',
        'callback' => 'get_formidable_form_html',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function ($param) {
                    return !empty($param);
                }
            ],
        ],
    ]);
});

/**
 * Get rendered Formidable form HTML
 * 
 * @param WP_REST_Request $request
 * @return WP_REST_Response|WP_Error
 */
function get_formidable_form_html($request)
{
    $form_id = $request['id'];

    if (!$form_id) {
        return new WP_Error('no_form_id', 'Form ID is required', ['status' => 400]);
    }

    // Check if Formidable Forms is active
    if (!class_exists('FrmFormsController')) {
        return new WP_Error('formidable_not_active', 'Formidable Forms plugin is not active', ['status' => 500]);
    }

    // Build shortcode
    $attr = is_numeric($form_id)
        ? 'id="' . esc_attr($form_id) . '"'
        : 'key="' . esc_attr($form_id) . '"';

    $shortcode = '[formidable ' . $attr . ' title=true description=false]';

    // Process shortcode and capture output
    ob_start();
    echo do_shortcode($shortcode);
    $html = ob_get_clean();

    // Return response
    return new WP_REST_Response([
        'success' => true,
        'form_id' => $form_id,
        'html' => $html,
    ], 200);
}


/**
 * Register Gravity Forms REST API endpoints
 */
add_action('rest_api_init', function () {
    // Get Gravity Form HTML
    register_rest_route('custom/v1', '/gravity-form/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_gravity_form_html',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                }
            ],
        ],
    ]);

    // Submit Gravity Form entry
    register_rest_route('custom/v1', '/gravity-form/(?P<id>\d+)/submit', [
        'methods' => 'POST',
        'callback' => 'submit_gravity_form_entry',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                }
            ],
        ],
    ]);
});

/**
 * Get Gravity Form structure (fields, not HTML)
 */
function get_gravity_form_html($request)
{
    $form_id = $request['id'];

    if (!class_exists('GFAPI')) {
        return new WP_Error('gravity_not_active', 'Gravity Forms plugin is not active', ['status' => 500]);
    }

    $form = GFAPI::get_form($form_id);

    if (!$form) {
        return new WP_Error('form_not_found', 'Form not found', ['status' => 404]);
    }

    // Return form structure with fields
    return new WP_REST_Response([
        'success' => true,
        'form_id' => $form_id,
        'title' => $form['title'],
        'description' => $form['description'],
        'fields' => $form['fields'],
        'button' => $form['button'],
    ], 200);
}

/**
 * Submit Gravity Form entry via REST API
 */
function submit_gravity_form_entry($request)
{
    $form_id = $request['id'];
    $params = $request->get_json_params();

    if (!class_exists('GFAPI')) {
        return new WP_Error('gravity_not_active', 'Gravity Forms plugin is not active', ['status' => 500]);
    }

    $form = GFAPI::get_form($form_id);

    if (!$form) {
        return new WP_Error('form_not_found', 'Form not found', ['status' => 404]);
    }

    // Prepare entry data
    $entry = [
        'form_id' => $form_id,
    ];

    foreach ($form['fields'] as $field) {
        $field_id = $field->id;
        $input_name = 'input_' . $field_id;

        if (isset($params[$input_name])) {
            $entry[$field_id] = sanitize_text_field($params[$input_name]);
        }
    }

    // Add entry
    $result = GFAPI::add_entry($entry);

    if (is_wp_error($result)) {
        return new WP_Error('submission_failed', $result->get_error_message(), ['status' => 400]);
    }

    return new WP_REST_Response([
        'success' => true,
        'entry_id' => $result,
        'message' => 'Form submitted successfully',
    ], 200);
}

// ============================================================================
// FOOTER DATA REST API ENDPOINT
// ============================================================================

/**
 * Register custom REST API endpoint for footer data
 */
add_action('rest_api_init', function () {
    register_rest_route('nextjs/v1', '/footer', [
        'methods' => 'GET',
        'callback' => 'get_footer_data',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * Get footer data from ACF options
 */
function get_footer_data()
{
    // Get footer logo - ACF might return URL or ID
    $footer_logo_value = get_field('footer_logo', 'option');
    $footer_logo = null;

    if ($footer_logo_value) {
        // Try to get the image ID from the URL
        $attachment_id = attachment_url_to_postid($footer_logo_value);

        if ($attachment_id) {
            // We have an ID, get full image data
            $footer_logo = [
                'url' => wp_get_attachment_url($attachment_id),
                'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
                'title' => get_the_title($attachment_id)
            ];
        } else if (is_numeric($footer_logo_value)) {
            // It's already an ID
            $footer_logo = [
                'url' => wp_get_attachment_url($footer_logo_value),
                'alt' => get_post_meta($footer_logo_value, '_wp_attachment_image_alt', true),
                'title' => get_the_title($footer_logo_value)
            ];
        } else {
            // It's just a URL, return with empty alt/title
            $footer_logo = [
                'url' => $footer_logo_value,
                'alt' => '',
                'title' => ''
            ];
        }
    }

    $footer_data = [
        'footer_logo' => $footer_logo,
        'contact_title' => get_field('contact_title', 'option'),
        'phone_no' => get_field('phone_no', 'option'),
        'email' => get_field('email', 'option'),
        'page_links' => get_field('page_links', 'option'),
        'follow_us' => get_field('follow_us', 'option'),
        'policy' => get_field('policy', 'option'),
    ];

    return new WP_REST_Response($footer_data, 200);
}
