<?php

/**
 * Plugin Name:       ChartLocal
 * Description:       Enables the ChartLocal Tracking Code on all your site pages.
 * Version:           1.0.0
 * Author:            ChartLocal
 * Author URI:        https://www.chartlocal.com/
 * License:           MIT license
 * License URI:       https://opensource.org/licenses/MIT
 */

if (!defined('WPINC')) {
  die;
}

if (!defined('CLT_DEFAULT_CODE')) {
  define('CLT_DEFAULT_CODE', '00000000-0000-0000-0000-000000000000');
}

/**
 * Async load script
 */
function clt_async_scripts($url)
{
    if ( strpos( $url, '#asyncload') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#asyncload', '', $url );
    else
  return str_replace( '#asyncload', '', $url )."' async='async"; 
}
add_filter( 'clean_url', 'clt_async_scripts', 11, 1 );

function clt_tracking_plugin() {
  $clt_tracking_id = get_option('clt_tracking_code_id' );
  if (strlen($clt_tracking_id) == strlen(constant('CLT_DEFAULT_CODE')) && $clt_tracking_id != CLT_DEFAULT_CODE) {
    wp_enqueue_script( 'clt_tracking_script', clt_reachedge_code_snippet_src($clt_tracking_id));
  }
}

if (is_admin()) {
  require_once(plugin_dir_path(__FILE__) . 'chartlocal-tracking-settings.php');
} else {
  add_action('wp_enqueue_scripts', 'clt_tracking_plugin', 5);
}

/**
 * Convert site_id from 'fc62c28f-3f38-4812-85c3-b3fe1329dba8' to '555/6e6/569/cfc4c23ac7e7ab663b58748.js';
 * Return '//cdn.rlets.com/capture_configs/fc6/2c2/8f3/f38481285c3b3fe1329dba8.js#asyncload'
 */
function clt_reachedge_code_snippet_src($reachlocal_tracking_id) {
  $site_id = array();
  array_push($site_id, (substr($reachlocal_tracking_id, 0, 8)));
  array_push($site_id, (substr($reachlocal_tracking_id, 9, 4)));
  array_push($site_id, (substr($reachlocal_tracking_id, 14, 4)));
  array_push($site_id, (substr($reachlocal_tracking_id, 19, 4)));
  array_push($site_id, (substr($reachlocal_tracking_id, 24, 12)));
  $flattened_site_id = implode("",$site_id);
  $snippet_src = array();
  array_push($snippet_src, '//cdn.rlets.com/capture_configs/');
  array_push($snippet_src, (substr($flattened_site_id, 0, 3)));
  array_push($snippet_src, '/');
  array_push($snippet_src, (substr($flattened_site_id, 3, 3)));
  array_push($snippet_src, '/');
  array_push($snippet_src, (substr($flattened_site_id, 6, 3)));
  array_push($snippet_src, '/');
  array_push($snippet_src, (substr($flattened_site_id, 9, 23)));
  array_push($snippet_src, '.js#asyncload');
  return implode('', $snippet_src);
}