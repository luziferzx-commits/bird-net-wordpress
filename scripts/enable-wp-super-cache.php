<?php
// Enables WP Super Cache the same way the "Caching On" button in its admin
// page does, using the plugin's own helper functions so wp-config.php and
// wp-content/advanced-cache.php stay consistent with what the plugin expects.
global $wp_cache_config_file;
wp_set_current_user(1);
wp_cache_create_advanced_cache();
wp_cache_replace_line('^ *\$cache_enabled', '$cache_enabled = true;', $wp_cache_config_file);
wp_cache_replace_line('^ *\$super_cache_enabled', '$super_cache_enabled = true;', $wp_cache_config_file);
echo "WP Super Cache enabled\n";
