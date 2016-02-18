<?php

//load defaults for first time on page load
function load_inf_bg() {

    //should not run unless post
    if (!is_single())
        return;

    // load information of current post
    $inf_post_id = get_the_ID();
    $inf_post_url = get_permalink($inf_post_id);

    //scrollMargin determines how much difference should be covered by scroll before updating the URL

    $tmp = "<script>";
    $tmp .= "inf_settings = {"
//    . "'container':'#single-post',"
//    . "'scrollMargin':50,"    
            . "'parent_ID':'$inf_post_id',"
            . "'parent_URL':'$inf_post_url'"
            . "};";
    $tmp .= "</script>";
    echo $tmp;
}

add_action('wp_head', 'load_inf_bg');
add_filter('the_content', 'inf_next_post');

//append details of next post inside content
function inf_next_post($content) {
    if (!is_single())
        return $content;

    $next_post = get_next_post();
    if (!$next_post) {
        $next_ID = $next_URL = '';
    } else {
        $next_ID = $next_post->ID;
        $next_URL = get_permalink($next_post->ID);
    }

    $inf_content = "<input type='hidden' class='inf-next-post' data-id='" . $next_ID . "' data-url='" . $next_URL . "'/>";
    $inf_content .= $content;

    return $inf_content;
}

?>