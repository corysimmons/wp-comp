<?php
/**
 * Plugin Name: WP Comp
 * Plugin URI: https://github.com/corysimmons/wp-comp
 * Plugin Description: Component architecture in WP. Cleans up WP_Query loops while making content reusable.
 * Author: Cory Simmons
 * Author URI: https://corysimmons.com
 * Version: 0.0.1
 * License: MIT
 */

// Init arr to collect used components (so we don't load duplicate CSS/JS)
$wpcomp_loaded_components = [];

function component($params = [
  'component_filepath' => null,
  'wp_query_args' => null,
  'context' => null,
  'css_filepath' => null,
  'js_filepath' => null,
  'error_filepath' => null
]) {
  global $wpcomp_loaded_components;

  ob_start();

  // https://jakearchibald.com/2016/link-in-body/
  // If component CSS hasn't been declared yet, load it before the component markup.
  if (!in_array($params['component_filepath'], $wpcomp_loaded_components) && $params['css_filepath']) {
    echo '<link rel="stylesheet" href="/wp-content/themes/' . wp_get_theme() . '/' . $params['css_filepath'] . '">';
  }

  // Custom WP_Query loop
  $c = new WP_Query($params['wp_query_args']); if ($c->have_posts() && $params['component_filepath']) {
    require get_template_directory() . '/' . $params['component_filepath'];
    wp_reset_postdata();
  } else {
    // Throw component-specific error view if loop fails.
    require get_template_directory() . '/' . $params['error_filepath'];
  }

  // If component JS hasn't been declared yet, load it after the component markup.
  if (!in_array($params['component_filepath'], $wpcomp_loaded_components) && $params['js_filepath']) {
    echo '<script src="/wp-content/themes/' . wp_get_theme() . '/' . $params['js_filepath'] . '"></script>';
  }

  echo ob_get_clean();

  // Push component to arr to ensure we don't load duplicate CSS/JS.
  $wpcomp_loaded_components[] = $params['component_filepath'];
}
