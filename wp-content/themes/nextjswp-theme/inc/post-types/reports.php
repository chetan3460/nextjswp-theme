<?php
/**
 * Reports Custom Post Type
 * Handles investor reports, annual reports, notices, etc.
 */

// Register Custom Post Type
function resplast_register_reports_cpt() {
    $labels = array(
        'name'                  => _x('Reports', 'Post Type General Name', 'resplast'),
        'singular_name'         => _x('Report', 'Post Type Singular Name', 'resplast'),
        'menu_name'             => __('Reports', 'resplast'),
        'name_admin_bar'        => __('Report', 'resplast'),
        'archives'              => __('Report Archives', 'resplast'),
        'attributes'            => __('Report Attributes', 'resplast'),
        'parent_item_colon'     => __('Parent Report:', 'resplast'),
        'all_items'             => __('All Reports', 'resplast'),
        'add_new_item'          => __('Add New Report', 'resplast'),
        'add_new'               => __('Add New', 'resplast'),
        'new_item'              => __('New Report', 'resplast'),
        'edit_item'             => __('Edit Report', 'resplast'),
        'update_item'           => __('Update Report', 'resplast'),
        'view_item'             => __('View Report', 'resplast'),
        'view_items'            => __('View Reports', 'resplast'),
        'search_items'          => __('Search Report', 'resplast'),
        'not_found'             => __('Not found', 'resplast'),
        'not_found_in_trash'    => __('Not found in Trash', 'resplast'),
        'featured_image'        => __('Featured Image', 'resplast'),
        'set_featured_image'    => __('Set featured image', 'resplast'),
        'remove_featured_image' => __('Remove featured image', 'resplast'),
        'use_featured_image'    => __('Use as featured image', 'resplast'),
        'insert_into_item'      => __('Insert into report', 'resplast'),
        'uploaded_to_this_item' => __('Uploaded to this report', 'resplast'),
        'items_list'            => __('Reports list', 'resplast'),
        'items_list_navigation' => __('Reports list navigation', 'resplast'),
        'filter_items_list'     => __('Filter reports list', 'resplast'),
    );
    
    $args = array(
        'label'                 => __('Report', 'resplast'),
        'description'           => __('Investor reports, annual reports, notices, and disclosures', 'resplast'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'taxonomies'            => array('report_category', 'financial_year', 'quarter'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-media-document',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => false,
    );
    
    register_post_type('reports', $args);
}
add_action('init', 'resplast_register_reports_cpt', 0);

// Register Taxonomies
function resplast_register_report_taxonomies() {
    
    // Report Categories
    register_taxonomy('report_category', 'reports', array(
        'labels' => array(
            'name'              => __('Report Categories', 'resplast'),
            'singular_name'     => __('Report Category', 'resplast'),
            'menu_name'         => __('Categories', 'resplast'),
            'all_items'         => __('All Categories', 'resplast'),
            'edit_item'         => __('Edit Category', 'resplast'),
            'view_item'         => __('View Category', 'resplast'),
            'update_item'       => __('Update Category', 'resplast'),
            'add_new_item'      => __('Add New Category', 'resplast'),
            'new_item_name'     => __('New Category Name', 'resplast'),
            'search_items'      => __('Search Categories', 'resplast'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
    ));
    
    // Financial Years
    register_taxonomy('financial_year', 'reports', array(
        'labels' => array(
            'name'              => __('Financial Years', 'resplast'),
            'singular_name'     => __('Financial Year', 'resplast'),
            'menu_name'         => __('Financial Years', 'resplast'),
            'all_items'         => __('All Years', 'resplast'),
            'edit_item'         => __('Edit Year', 'resplast'),
            'view_item'         => __('View Year', 'resplast'),
            'update_item'       => __('Update Year', 'resplast'),
            'add_new_item'      => __('Add New Year', 'resplast'),
            'new_item_name'     => __('New Year Name', 'resplast'),
        ),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
    ));
    
    // Quarters
    register_taxonomy('quarter', 'reports', array(
        'labels' => array(
            'name'              => __('Quarters', 'resplast'),
            'singular_name'     => __('Quarter', 'resplast'),
            'menu_name'         => __('Quarters', 'resplast'),
            'all_items'         => __('All Quarters', 'resplast'),
            'edit_item'         => __('Edit Quarter', 'resplast'),
            'view_item'         => __('View Quarter', 'resplast'),
            'update_item'       => __('Update Quarter', 'resplast'),
            'add_new_item'      => __('Add New Quarter', 'resplast'),
            'new_item_name'     => __('New Quarter Name', 'resplast'),
        ),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
    ));
}
add_action('init', 'resplast_register_report_taxonomies', 0);

// Add default taxonomy terms
function resplast_add_default_report_terms() {
    if (!term_exists('Annual Reports', 'report_category')) {
        wp_insert_term('Annual Reports', 'report_category');
        wp_insert_term('Notice Announcements', 'report_category');
        wp_insert_term('Policies', 'report_category');
        wp_insert_term('Unclaimed Dividend', 'report_category');
        wp_insert_term('AGM Transcripts & Board Meeting', 'report_category');
        wp_insert_term('Initiatives (Green Initiative & CSR)', 'report_category');
        wp_insert_term('Investor Grievances', 'report_category');
        wp_insert_term('Composition of Board & Committees', 'report_category');
    }
    
    if (!term_exists('Q1', 'quarter')) {
        wp_insert_term('Q1', 'quarter');
        wp_insert_term('Q2', 'quarter');
        wp_insert_term('Q3', 'quarter');
        wp_insert_term('Q4', 'quarter');
    }
    
    if (!term_exists('FY2023-24', 'financial_year')) {
        wp_insert_term('FY2023-24', 'financial_year');
        wp_insert_term('FY2022-23', 'financial_year');
        wp_insert_term('FY2021-22', 'financial_year');
        wp_insert_term('FY2020-21', 'financial_year');
    }
}
add_action('init', 'resplast_add_default_report_terms');

// Customize admin columns
function resplast_reports_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['report_category'] = __('Category', 'resplast');
    $new_columns['financial_year'] = __('Financial Year', 'resplast');
    $new_columns['quarter'] = __('Quarter', 'resplast');
    $new_columns['published_date'] = __('Published Date', 'resplast');
    $new_columns['document_file'] = __('Document', 'resplast');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_reports_posts_columns', 'resplast_reports_admin_columns');

// Populate admin columns
function resplast_reports_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'published_date':
            $published_date = get_post_meta($post_id, 'published_date', true);
            echo $published_date ? esc_html($published_date) : '—';
            break;
            
        case 'document_file':
            $document_file = get_post_meta($post_id, 'document_file', true);
            $download_link = get_post_meta($post_id, 'download_link', true);
            
            if ($document_file && is_array($document_file)) {
                echo '<a href="' . esc_url($document_file['url']) . '" target="_blank">📄 View File</a>';
            } elseif ($download_link) {
                echo '<a href="' . esc_url($download_link) . '" target="_blank">🔗 External Link</a>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_reports_posts_custom_column', 'resplast_reports_admin_columns_content', 10, 2);

// Add admin filters
function resplast_reports_admin_filters() {
    global $typenow;
    
    if ($typenow === 'reports') {
        // Category filter
        $selected_category = isset($_GET['report_category']) ? $_GET['report_category'] : '';
        $categories = get_terms('report_category');
        
        if (!empty($categories) && !is_wp_error($categories)) {
            echo '<select name="report_category">';
            echo '<option value="">All Categories</option>';
            foreach ($categories as $category) {
                echo '<option value="' . $category->slug . '"' . selected($selected_category, $category->slug, false) . '>' . $category->name . '</option>';
            }
            echo '</select>';
        }
        
        // Financial Year filter
        $selected_year = isset($_GET['financial_year']) ? $_GET['financial_year'] : '';
        $years = get_terms('financial_year');
        
        if (!empty($years) && !is_wp_error($years)) {
            echo '<select name="financial_year">';
            echo '<option value="">All Years</option>';
            foreach ($years as $year) {
                echo '<option value="' . $year->slug . '"' . selected($selected_year, $year->slug, false) . '>' . $year->name . '</option>';
            }
            echo '</select>';
        }
    }
}
add_action('restrict_manage_posts', 'resplast_reports_admin_filters');

// Apply admin filters
function resplast_reports_admin_filter_query($query) {
    global $typenow;
    
    if ($typenow === 'reports' && is_admin() && $query->is_main_query()) {
        if (!empty($_GET['report_category'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'report_category',
                    'field'    => 'slug',
                    'terms'    => $_GET['report_category']
                )
            ));
        }
        
        if (!empty($_GET['financial_year'])) {
            $tax_query = $query->get('tax_query') ?: array();
            $tax_query[] = array(
                'taxonomy' => 'financial_year',
                'field'    => 'slug',
                'terms'    => $_GET['financial_year']
            );
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'resplast_reports_admin_filter_query');

// Make admin columns sortable
function resplast_reports_sortable_columns($columns) {
    $columns['published_date'] = 'published_date';
    return $columns;
}
add_filter('manage_edit-reports_sortable_columns', 'resplast_reports_sortable_columns');

// Handle sorting
function resplast_reports_orderby($query) {
    if (!is_admin()) return;
    
    $orderby = $query->get('orderby');
    
    if ($orderby === 'published_date') {
        $query->set('meta_key', 'published_date');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'resplast_reports_orderby');
?>