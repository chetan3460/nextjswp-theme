<?php

function render_blocks($field_name)
{
  if (have_rows($field_name)) {
    while (have_rows($field_name)) {
      the_row();
      $layout = get_row_layout();

      // Convert field name to folder name (e.g., 'service_panels' -> 'service_page_blocks')
      $field_folder = str_replace('_panels', '_page_blocks', $field_name);
      
      // Check multiple locations in order of priority:
      // 1. Nested folder based on field name (e.g., templates/blocks/service_page_blocks/service_test.php)
      $nested_template = "templates/blocks/{$field_folder}/{$layout}.php";
      // 2. Root blocks folder (e.g., templates/blocks/service_test.php)
      $local_template = "templates/blocks/{$layout}.php";
      // 3. Global shared fallback (e.g., templates/blocks/global/service_test.php)
      $global_template = "templates/blocks/global/{$layout}.php";

      if (locate_template($nested_template)) {
        get_template_part("templates/blocks/{$field_folder}/{$layout}");
      } elseif (locate_template($local_template)) {
        get_template_part("templates/blocks/{$layout}");
      } elseif (locate_template($global_template)) {
        get_template_part("templates/blocks/global/{$layout}");
      } else {
        error_log("[theme] Missing block: {$layout}");
      }
    }
  }
}

// Backward compatibility alias
function render_flex($field_name)
{
  render_blocks($field_name);
}

function get_vite_asset($filename)
{
  static $manifest = null;
  if (is_null($manifest)) {
    $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';
    if (file_exists($manifest_path)) {
      $manifest = json_decode(file_get_contents($manifest_path), true);
    } else {
      $manifest = [];
    }
  }

  if (isset($manifest[$filename])) {
    return get_template_directory_uri() . '/dist/' . $manifest[$filename]['file'];
  } else {
    // Fallback for dev mode or if asset not in manifest
    return get_template_directory_uri() . '/assets/' . $filename;
  }
}
