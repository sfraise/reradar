<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 8/5/2016
 * Time: 2:22 PM
 *
 * Plugin Name: Curation
 * Plugin URI: http://codemonkeysllc.com
 * Description: Custom content curation
 * Version: 1.0.0
 * Author: Spencer Fraise
 * Author URI: http://codemonkeysllc.com
 * License: GPL2
 */

//* ENQUEUE SCRIPTS AND STYLES
function curation_enqueue_scripts() {
    // ENQUEUE CSS
    wp_enqueue_style( 'style', plugin_dir_url(__FILE__) . '/css/style.css' );

    // ENQUEUE SCRIPT
    wp_enqueue_script( 'script', plugin_dir_url(__FILE__) . '/js/script.js', array('jquery'), '2.0', true );

    // CHECK IF HOMEPAGE
    if(is_front_page()) {
        $frontpage = 1;
    } else {
        $frontpage = 0;
    }

    // PASS PHP DATA TO SCRIPT FILE
    wp_localize_script('script', 'curationScript', array(
        'pluginUrl' => plugin_dir_url(__FILE__),
        'siteUrl' =>  get_site_url(),
        'frontPage' => $frontpage
    ));

    // LOCALIZE CUSTOM JS FILE TO USE WITH AJAX
    wp_localize_script( 'script', 'ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));

}
add_action( 'admin_enqueue_scripts', 'curation_enqueue_scripts' );

//* REQUIRE AJAX FUNCTIONS
require(plugin_dir_path(__FILE__) . '/ajax-functions.php');