<?php

/*
  Plugin Name: Infinite Blog Posts
  Plugin URI: https://github.com/raj-uimatic/
  Description: Load next post at the end of the page and update the URLs along with it.
  Author: Raj
  Version: 1.0
  Author URI: http://uimatic.com
 */

global $inf_data;
$inf_data = array();

register_activation_hook(__FILE__, 'install_wp_inf_data');

// activation hook
function install_wp_inf_data() {
    // no data processing required on registration at this time
//    global $wpdb;
}

add_action('admin_menu', 'wp_inf_main_menu');

// add menu in WP dashboard
function wp_inf_main_menu() {
//    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    add_menu_page('Infinite Blog Posts', 'Infinite Posts', 'manage_options', 'inf_posts', 'inf_posts_admin_page', '', 6);
//    add_submenu_page('menu1', 'My Custom Submenu Page', 'All blog types', 'manage_options', 'my-custom-submenu-page', 'getlist');
}

function inf_posts_admin_page() {
    echo "Admin Page Test";
}

// include script files
function inf_posts_resources() {
//    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
    wp_enqueue_script('infinite-blog-posts', plugins_url('js/infinite-blog-posts.js', __FILE__), array('jquery'), '1.0', false);
}

add_action('wp_enqueue_scripts', 'inf_posts_resources');

$inf_data['inf_dir'] = plugin_dir_path(__FILE__);
$inf_data['inf_url'] = plugin_dir_url(__FILE__);
// include functions for frontend
require_once($inf_data['inf_dir'] . "/includes/frontend.php");
