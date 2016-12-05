<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 11/30/2016
 * Time: 12:07 PM
 *
 * Plugin Name: Show Content
 * Plugin URI: http://codemonkeysllc.com
 * Description: Custom content display by shortcode
 * Version: 1.0.0
 * Author: Spencer Fraise
 * Author URI: http://codemonkeysllc.com
 * License: GPL2
 */

//* ENQUEUE SCRIPTS AND STYLES
function show_content_enqueue_scripts() {
    // ENQUEUE CSS
    wp_enqueue_style( 'showContentStyle', plugin_dir_url(__FILE__) . 'css/style.css' );

    // ENQUEUE SCRIPT
    wp_enqueue_script( 'showContentScript', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '2.0', true );

    // CHECK IF HOMEPAGE
    if(is_front_page()) {
        $frontpage = 1;
    } else {
        $frontpage = 0;
    }

    // PASS PHP DATA TO SCRIPT FILE
    wp_localize_script('showContentScript', 'showContentScript', array(
        'pluginUrl' => plugin_dir_url(__FILE__),
        'siteUrl' =>  get_site_url(),
        'frontPage' => $frontpage
    ));

    // LOCALIZE CUSTOM JS FILE TO USE WITH AJAX
    wp_localize_script( 'showContentScript', 'ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));

}
add_action( 'wp_enqueue_scripts', 'show_content_enqueue_scripts' );

//* REQUIRE AJAX FUNCTIONS
require(plugin_dir_path(__FILE__) . 'ajax-functions.php');

function createShowContentShortcode($atts) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-show-content.php';
    $postType = $atts['post_type'];
    $contentTypes = explode(',', $atts['content_types']);
    $categories = explode(',', $atts['categories']);
    $template = $atts['template'];
    $limit = $atts['limit'];
    $orderBy = $atts['orderby'];
    $paginate = $atts['paginate'];

    if($postType) {
        $getContent = new showContent;
        $content = $getContent->getContent($postType, $contentTypes, $categories, $template, $limit, $orderBy, $paginate, $page = 1);

        return $content;
    } else {
        return false;
    }
}

function add_show_content_shortcode() {
    add_shortcode('show_content', 'createShowContentShortcode');
}
add_action( 'get_header', 'add_show_content_shortcode' );