<?php
add_action('init', 'create_custom_posts');
function create_custom_posts()
{
  // CPT News
  register_post_type('news', [
    'labels' => [
      'name' => __('News & Updates'),
      'singular_name' => __('News & Updates'),
      'menu_name' => __('News & Updates'),
      'all_items' => __('All News & Updates'),
      'add_new' => __('Add New'),
      'add_new_item' => __('Add New News'),
      'edit_item' => __('Edit News'),
      'new_item' => __('New News'),
      'view_item' => __('View News'),
      'search_items' => __('Search News & Updates'),
      'not_found' => __('No news found'),
      'not_found_in_trash' => __('No news found in trash'),
    ],
    'public' => true,
    'has_archive' => false,
    'rewrite' => ['slug' => 'news-updates'],
    'show_in_rest' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'publicly_queryable' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
  ]);

  // Custom taxonomy - Category for News
  $news_cat = [
    'name' => _x('Category', 'news category'),
    'singular_name' => _x('Category', 'news category'),
    'search_items' => __('Search Categories'),
    'all_items' => __('All Categories'),
    'parent_item' => __('Parent Category'),
    'parent_item_colon' => __('Parent Category:'),
    'edit_item' => __('Edit Category'),
    'update_item' => __('Update Category'),
    'add_new_item' => __('Add New Category'),
    'new_item_name' => __('New Category Name'),
    'menu_name' => __('Category'),
  ];

  register_taxonomy(
    'news_category',
    ['news'],
    [
      'hierarchical' => true,
      'labels' => $news_cat,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'news-category'],
    ]
  );

  // CPT Team Members
  register_post_type('team_member', [
    'labels' => [
      'name' => __('Team Members'),
      'singular_name' => __('Team Member'),
      'menu_name' => __('Team Members'),
      'add_new' => __('Add New Member'),
      'add_new_item' => __('Add New Team Member'),
      'edit_item' => __('Edit Team Member'),
      'new_item' => __('New Team Member'),
      'view_item' => __('View Team Member'),
      'search_items' => __('Search Team Members'),
      'not_found' => __('No team members found'),
      'not_found_in_trash' => __('No team members found in trash'),
    ],
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'team'],
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 25,
    'menu_icon' => 'dashicons-groups',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
    'show_in_rest' => true,
  ]);

  // Custom taxonomy - Team Categories
  $team_cat = [
    'name' => _x('Team Categories', 'team category'),
    'singular_name' => _x('Team Category', 'team category'),
    'search_items' => __('Search Categories'),
    'all_items' => __('All Categories'),
    'parent_item' => __('Parent Category'),
    'parent_item_colon' => __('Parent Category:'),
    'edit_item' => __('Edit Category'),
    'update_item' => __('Update Category'),
    'add_new_item' => __('Add New Category'),
    'new_item_name' => __('New Category Name'),
    'menu_name' => __('Categories'),
  ];

  register_taxonomy(
    'team_category',
    ['team_member'],
    [
      'hierarchical' => true,
      'labels' => $team_cat,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'team-category'],
    ]
  );

  // CPT Products
  register_post_type('product', [
    'labels' => [
      'name' => __('Products'),
      'singular_name' => __('Product'),
      'menu_name' => __('Products'),
      'all_items' => __('All Products'),
      'add_new' => __('Add New'),
      'add_new_item' => __('Add New Product'),
      'edit_item' => __('Edit Product'),
      'new_item' => __('New Product'),
      'view_item' => __('View Product'),
      'search_items' => __('Search Products'),
      'not_found' => __('No products found'),
      'not_found_in_trash' => __('No products found in trash'),
    ],
    'public' => true,
    'publicly_queryable' => true, // Enable single product pages
    'show_ui' => true,
    'show_in_menu' => true,
    'has_archive' => false,
    'rewrite' => ['slug' => 'product'], // Enable permalinks for single pages
    'show_in_rest' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-products',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
  ]);

  // Product Chemistry Taxonomy
  $chemistry_labels = [
    'name' => _x('Chemistry Types', 'product chemistry'),
    'singular_name' => _x('Chemistry Type', 'product chemistry'),
    'search_items' => __('Search Chemistry Types'),
    'all_items' => __('All Chemistry Types'),
    'parent_item' => __('Parent Chemistry'),
    'parent_item_colon' => __('Parent Chemistry:'),
    'edit_item' => __('Edit Chemistry Type'),
    'update_item' => __('Update Chemistry Type'),
    'add_new_item' => __('Add New Chemistry Type'),
    'new_item_name' => __('New Chemistry Type Name'),
    'menu_name' => __('Chemistry Types'),
  ];

  register_taxonomy(
    'product_chemistry',
    ['product'],
    [
      'hierarchical' => false,
      'labels' => $chemistry_labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_menu' => true,
      'show_tagcloud' => true,
      'show_in_quick_edit' => true,
      'meta_box_cb' => 'post_categories_meta_box',
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'chemistry'],
      'capabilities' => [
        'manage_terms' => 'manage_product_terms',
        'edit_terms' => 'manage_product_terms',
        'delete_terms' => 'manage_product_terms',
        'assign_terms' => 'edit_posts',
      ],
    ]
  );

  // Product Brand Taxonomy
  $brand_labels = [
    'name' => _x('Brand/Product Family', 'product brand'),
    'singular_name' => _x('Brand', 'product brand'),
    'search_items' => __('Search Brands'),
    'all_items' => __('All Brands'),
    'parent_item' => __('Parent Brand'),
    'parent_item_colon' => __('Parent Brand:'),
    'edit_item' => __('Edit Brand'),
    'update_item' => __('Update Brand'),
    'add_new_item' => __('Add New Brand'),
    'new_item_name' => __('New Brand Name'),
    'menu_name' => __('Brands'),
  ];

  register_taxonomy(
    'product_brand',
    ['product'],
    [
      'hierarchical' => false,
      'labels' => $brand_labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_menu' => true,
      'show_tagcloud' => true,
      'show_in_quick_edit' => true,
      'meta_box_cb' => 'post_categories_meta_box',
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'brand'],
      'capabilities' => [
        'manage_terms' => 'manage_product_terms',
        'edit_terms' => 'manage_product_terms',
        'delete_terms' => 'manage_product_terms',
        'assign_terms' => 'edit_posts',
      ],
    ]
  );

  // Product Application Taxonomy
  $application_labels = [
    'name' => _x('Applications', 'product application'),
    'singular_name' => _x('Application', 'product application'),
    'search_items' => __('Search Applications'),
    'all_items' => __('All Applications'),
    'parent_item' => __('Parent Application'),
    'parent_item_colon' => __('Parent Application:'),
    'edit_item' => __('Edit Application'),
    'update_item' => __('Update Application'),
    'add_new_item' => __('Add New Application'),
    'new_item_name' => __('New Application Name'),
    'menu_name' => __('Applications'),
  ];

  register_taxonomy(
    'product_application',
    ['product'],
    [
      'hierarchical' => false,
      'labels' => $application_labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_menu' => true,
      'show_tagcloud' => true,
      'show_in_quick_edit' => true,
      'meta_box_cb' => 'post_categories_meta_box',
      'query_var' => true,
      'show_in_rest' => true,
      'rewrite' => ['slug' => 'application'],
      'capabilities' => [
        'manage_terms' => 'manage_product_terms',
        'edit_terms' => 'manage_product_terms',
        'delete_terms' => 'manage_product_terms',
        'assign_terms' => 'edit_posts',
      ],
    ]
  );
}

// Ensure ACF fields are always visible in REST API for custom post types
add_action('rest_api_init', function () {
  $types = ['team_member', 'news', 'product'];
  foreach ($types as $type) {
    register_rest_field($type, 'acf', [
      'get_callback' => function ($post_arr) {
        if (function_exists('get_fields')) {
          return get_fields($post_arr['id']);
        }
        return [];
      },
      'update_callback' => null,
      'schema'          => null,
    ]);
  }
});

// Create default product taxonomy terms
// Disabled to allow manual term management
// add_action('init', 'create_default_product_terms', 999);
function create_default_product_terms()
{
  // Chemistry Types
  $chemistry_terms = [
    'Alkyd Resins' => 'alkyd-resins',
    'Acrylic Resins' => 'acrylic-resins',
    'Polyamide Resins' => 'polyamide-resins',
    'NR-Polyamide Resins' => 'nr-polyamide-resins',
    'Epoxy Resin' => 'epoxy-resin',
    'Phenolic Resins' => 'phenolic-resins',
    'UF Resins' => 'uf-resins',
    'MF Resins' => 'mf-resins',
    'Maleic Resins' => 'maleic-resins',
    'Ketonic Resins' => 'ketonic-resins',
    'Polylsocyanates' => 'polylsocyanates',
  ];

  foreach ($chemistry_terms as $name => $slug) {
    if (!term_exists($name, 'product_chemistry')) {
      wp_insert_term($name, 'product_chemistry', ['slug' => $slug]);
    }
  }

  // Brands/Product Family
  $brand_terms = [
    'Replakyd' => 'replakyd',
    'Replaknyl' => 'replaknyl',
    'Replaniela' => 'replaniela',
    'Replaster' => 'replaster',
    'Reploxy' => 'reploxy',
    'Replanoin' => 'replanoin',
    'Replacoal' => 'replacoal',
    'Replastic' => 'replastic',
    'Replacare' => 'replacare',
    'Replonic' => 'replonic',
  ];

  foreach ($brand_terms as $name => $slug) {
    if (!term_exists($name, 'product_brand')) {
      wp_insert_term($name, 'product_brand', ['slug' => $slug]);
    }
  }

  // Applications
  $application_terms = [
    'Automotive coatings' => 'automotive-coatings',
    'NC coatings' => 'nc-coatings',
    'Printing Inks' => 'printing-inks',
    'Can/Coil coatings' => 'can-coil-coatings',
    'Varnishes' => 'varnishes',
    'Primers' => 'primers',
    'Flooring & laminates' => 'flooring-laminates',
    'Adhesives & Sealants' => 'adhesives-sealants',
    'Epoxy Grouts' => 'epoxy-grouts',
    'Flooring & Floor Coatings' => 'flooring-floor-coatings',
    'Stationary Coatings' => 'stationary-coatings',
  ];

  foreach ($application_terms as $name => $slug) {
    if (!term_exists($name, 'product_application')) {
      wp_insert_term($name, 'product_application', ['slug' => $slug]);
    }
  }
}

// Create default team categories
add_action('init', 'create_default_team_categories', 999);
function create_default_team_categories()
{
  // Only create if they don't exist
  if (!term_exists('Board Members', 'team_category')) {
    wp_insert_term('Board Members', 'team_category', [
      'description' => 'Board of Directors and key leadership',
      'slug' => 'board-members'
    ]);

    // Flush rewrite rules on theme activation to enable product permalinks
    add_action('after_switch_theme', 'flush_rewrite_rules');
  }

  if (!term_exists('Management Team', 'team_category')) {
    wp_insert_term('Management Team', 'team_category', [
      'description' => 'Executive management and senior staff',
      'slug' => 'management-team'
    ]);
  }

  if (!term_exists('Advisory Board', 'team_category')) {
    wp_insert_term('Advisory Board', 'team_category', [
      'description' => 'Independent advisors and consultants',
      'slug' => 'advisory-board'
    ]);
  }
}

function modify_nav_menu_meta_box_object($object)
{
  if ($object->name === 'news_category') {
    $object->labels->name = __('News Category', T_PREFIX);
  }
  if ($object->name === 'team_category') {
    $object->labels->name = __('Team Category', T_PREFIX);
  }
  if ($object->name === 'product_chemistry') {
    $object->labels->name = __('Product Chemistry', T_PREFIX);
  }
  if ($object->name === 'product_brand') {
    $object->labels->name = __('Product Brand', T_PREFIX);
  }
  if ($object->name === 'product_application') {
    $object->labels->name = __('Product Application', T_PREFIX);
  }
  return $object;
}

add_filter('nav_menu_meta_box_object', 'modify_nav_menu_meta_box_object');

// Grant product taxonomy capabilities to administrators and editors
add_action('admin_init', 'grant_product_taxonomy_capabilities');
function grant_product_taxonomy_capabilities()
{
  $roles = ['administrator', 'editor'];

  foreach ($roles as $role_name) {
    $role = get_role($role_name);
    if ($role) {
      $role->add_cap('manage_product_terms');
    }
  }
}

// Force show taxonomy metaboxes for products
add_action('add_meta_boxes', 'ensure_product_taxonomy_metaboxes');
function ensure_product_taxonomy_metaboxes()
{
  $taxonomies = ['product_chemistry', 'product_brand', 'product_application'];

  foreach ($taxonomies as $taxonomy) {
    if (taxonomy_exists($taxonomy)) {
      add_meta_box(
        'tagsdiv-' . $taxonomy,
        get_taxonomy($taxonomy)->labels->name,
        'post_categories_meta_box',
        'product',
        'side',
        'core',
        ['taxonomy' => $taxonomy]
      );
    }
  }
}
