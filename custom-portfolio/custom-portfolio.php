<?php
/**
 * Plugin Name: Custom Portfolio Plugin
 * Description: A custom portfolio plugin to display projects dynamically.
 * Author: W.A. Oliveira Azevedo - TheTechDodo
 * Author URI: https://thetechdodo.com/
 * Author URI Staging: https://staging-eab2-thetechdodo.wpcomstaging.com/portfolio/
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'post-type.php';
include_once plugin_dir_path(__FILE__) . 'meta-fields.php';
include_once plugin_dir_path(__FILE__) . 'shortcodes.php';
include_once plugin_dir_path(__FILE__) . 'ajax.php';

// Load Portfolio CSS
function custom_portfolio_enqueue_assets() {
    $plugin_url = plugins_url('custom-portfolio/assets/');

    wp_enqueue_style(
        'portfolio-style',
        $plugin_url . 'css/portfolio.css',
        array(),
        time(), // Add a timestamp as version number
        'all'
    );

    wp_enqueue_script(
        'portfolio-script',
        $plugin_url . 'js/portfolio.js',
        array('jquery'),
        time(), // Also refresh JS
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_portfolio_enqueue_assets');


function custom_portfolio_template($single_template) {
    global $post;

    if ($post->post_type == 'portfolio') {
        $template_path = plugin_dir_path(__FILE__) . 'templates/single-portfolio.php';
        if (file_exists($template_path)) {
            return $template_path;
        }
    }

    return $single_template;
}
add_filter('single_template', 'custom_portfolio_template');