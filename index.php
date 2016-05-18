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
    add_menu_page('Infinite Blog Posts', 'Infinite Posts', 'manage_options', 'inf_posts', 'inf_posts_admin_page', '', 15);
//    add_submenu_page('menu1', 'My Custom Submenu Page', 'All blog types', 'manage_options', 'my-custom-submenu-page', 'getlist');
}

function inf_posts_admin_page() {

    global $wpdb;
    global $current_user;

    $table_name1 = $wpdb->prefix . 'options';
    if (isset($_POST['save_post_settings'])) {

        $enable_posts = $_POST['enable_posts'];
        $infiniteposts_unique_id = $_POST['infiniteposts_unique_id'];


        delete_option('enable_posts');
        delete_option('infiniteposts_unique_id');

        update_option('enable_posts', $enable_posts);
        update_option('infiniteposts_unique_id', $infiniteposts_unique_id);
    }
    if (isset($_POST['send_requests_email'])) {
        $to = get_option('admin_email');
        $subject = 'Infinte Post Requests';
        $body = get_site_url();
        $mail = wp_mail($to, $subject, $body);

        if ($mail) {
            echo "<h3 style='color:green'>Your mail has been Sent</h3>";
        }
    }

    $checked_posts == '';
    if ($enable_posts == 'on') {
        $checked_posts = "checked='checked'";
    }
    global $current_user;
    get_currentuserinfo();

    echo '<div class="infinite-posts">
	<h3>Infinite Blog Posts Settings</h3>
	 <form action="" method="post" >
				<p><label><b>Enable/ Disable: </b><input type="checkbox" name="enable_posts" ' . $checked_posts . ' ></label></p>
				<h4>If you do not want your header and footer to repeat please update this next field.</h4>
                <p><b>Unique container:</b> </p><textarea name="infiniteposts_unique_id" rows="4" cols="30" placeholder="e.g  .class { } or #id { }">' . get_option('infiniteposts_unique_id') . '</textarea>
                <p><input class="button button-primary button-large" type="submit" value="Save Changes" name="save_post_settings"></p>
				</form>
				<div class="send-requests-email">
				<h4>If you donot understand what to fill in this field, please give us a message</h4>
				<form action="" method="post" >
				<input type="submit" value="Send Requests" class="top-mar-min send Requests button button-primary button-large" name="send_requests_email">
				</form>
				</div>
	</div>';
}

// include script files
function inf_posts_resources() {
//    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
    wp_enqueue_script('infinite-blog-posts', plugins_url('js/infinite-blog-posts.js', __FILE__), array('jquery'), '1.0', false);
}

// include script files
function inf_posts_css() {
//    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

    wp_enqueue_style('main-css', plugins_url('css/infinte_posts_main.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'inf_posts_resources');
add_action('admin_enqueue_scripts', 'inf_posts_css');

$inf_data['inf_dir'] = plugin_dir_path(__FILE__);
$inf_data['inf_url'] = plugin_dir_url(__FILE__);
// include functions for frontend
require_once($inf_data['inf_dir'] . "/includes/frontend.php");
