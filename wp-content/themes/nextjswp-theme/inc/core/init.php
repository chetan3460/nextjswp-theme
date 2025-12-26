<?php

/**
 * Core Theme Initialization
 * Autoload all PHP files in /core
 * Manually load feature files in /features
 */

// Auto-load all files in core/, except self
foreach (glob(__DIR__ . '/*.php') as $file) {
  if (basename($file) !== 'init.php') {
    require_once $file;
  }
}

// Load additional features (modular)
$features = ['filters.php', 'image-convert.php', 'custom-posts.php', 'walker-menu.php', 'acf-block-generator.php', 'global-block-helpers.php'];

foreach ($features as $feature) {
  $path = __DIR__ . '/../features/' . $feature;
  if (file_exists($path)) {
    require_once $path;
  }
}
