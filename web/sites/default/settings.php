<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

$settings['hash_salt'] = 'n2poBzyIBB-Dh_8QqZI3Lak4159yFw3uWxDcGqisZ9ik9Ptg-O-OoI68ngaYUpOsy4qphPmB_w';

$settings['config_sync_directory'] = '../config/default';

$_env_is_pantheon = TRUE;
// Local settings.
if (isset($_ENV['LANDO_INFO'])) {
    $_env_is_pantheon = FALSE;
    $lando_info = json_decode(getenv('LANDO_INFO'), TRUE);
    $databases['default']['default'] = [
      'database' => $lando_info['database']['creds']['database'],
      'username' => $lando_info['database']['creds']['user'],
      'password' => $lando_info['database']['creds']['password'],
      'host' => $lando_info['database']['internal_connection']['host'],
      'port' => $lando_info['database']['internal_connection']['port'],
      'driver' => 'mysql',
      'prefix' => '',
      'collation' => 'utf8mb4_general_ci',
    ];
    unset($lando_info);

  $settings['file_private_path'] = 'sites/default/private';
  $settings['skip_permissions_hardening'] = TRUE;

  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
  $config['system.logging']['error_level'] = 'some';

  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

  // Include local settings last so that they can override anything.
  if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
    include $app_root . '/' . $site_path . '/settings.local.php';
  }
}

