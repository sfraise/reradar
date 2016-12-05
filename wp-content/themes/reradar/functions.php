<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 2/23/2016
 * Time: 2:41 PM
 */

/**
 * Functions
 * @package reradar
 * @since reradar 1.0
 * @author Code Monkeys LLC <contact@codemonkeysllc.com>
 * @copyright Copyright (c) 2016, Code Monkeys LLC
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
//* Start the engine
include_once(get_template_directory() . '/lib/init.php');
/**
 * Theme Setup
 * This setup function attaches all of the site-wide functions
 * to the correct actions and filters. All the functions themselves
 * are defined below this setup function.
 *  WORDPRESS COMMON CODE
 * ADD PERMALINK SLUGS
 * <a href="[permalink id=49]">Basic Usage</a>
 * OR
 * [permalink id=49 text='providing text']
 * GET ABSOLUTE PATH TO ROOT WP INSTALL.
 * get_home_path()
 * GET THEME ROOT
 * get_stylesheet_directory_uri();
 * GET ABSOLUTE PATH TO CHILD THEME DIRECTORY
 * get_stylesheet_directory()
 * GET SITE URL
 * get_site_url()
 * GET UPLOAD DIRECTORY ROOT
 * wp_upload_dir()
 * USE SHORTCODE IN CODE
 * do_shortcode("[shortcode]")
 * //* REGISTER NEW WIDGET
 * genesis_register_sidebar( array(
 * 'id'      => 'header-sidebar',
 * 'name'    => __( 'Header Sidebar', 'nabm' ),
 * 'description'  => __( 'sidebar for header', 'nabm' ),
 * ));
 * add_action( 'init', 'create_custom_post_type' );
 * function create_custom_post_type() {
 * $labels = array(
 * 'name'               => __( 'Coupons' ),
 * 'singular_name'      => __( 'Coupon' ),
 * 'all_items'          => __( 'All Coupons' ),
 * 'add_new'            => _x( 'Add new Coupon', 'Coupons' ),
 * 'add_new_item'       => __( 'Add new Coupon' ),
 * 'edit_item'          => __( 'Edit Coupon' ),
 * 'new_item'           => __( 'New Coupon' ),
 * 'view_item'          => __( 'View Coupon' ),
 * 'search_items'       => __( 'Search in Coupons' ),
 * 'not_found'          => __( 'No Coupons found' ),
 * 'not_found_in_trash' => __( 'No Coupons found in trash' ),
 * 'parent_item_colon'  => ''
 * );
 * $args = array(
 * 'labels'             => $labels,
 * 'public'             => true,
 * 'has_archive'        => true,
 * 'menu_icon'          => 'dashicons-admin-users', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
 * 'rewrite'            => array( 'slug' => 'coupon' ),
 * 'taxonomies'         => array( 'category', 'post_tag', 'coupon' ),
 * 'query_var'          => true,
 * 'menu_position'      => 5,
 * 'supports'           => array( 'genesis-cpt-archives-settings', 'thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor')
 * );
 * register_post_type( 'coupons', $args);
 * }
 */


//* Add header image
//add_theme_support( 'genesis-custom-header');

//* Add HTML5 markup structure
add_theme_support('html5', array('search-form', 'comment-form', 'comment-list'));

//* Add viewport meta tag for mobile browsers
add_theme_support('genesis-responsive-viewport');

//* Add support for custom background
add_theme_support('custom-background');

//* Add support for 3-column footer widgets
add_theme_support('genesis-footer-widgets', 3);

//* Remove page titles from all single posts & pages (requires HTML5 theme support)
add_action('get_header', 'child_remove_titles');
function child_remove_titles()
{
    if (is_singular()) {
        remove_action('genesis_entry_header', 'genesis_do_post_title');
    }
}

//* REMOVE ANNOYING ADMIN BAR
function remove_admin_login_header()
{
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', 'remove_admin_login_header');

//* Remove primary/secondary navigation menus
remove_theme_support('genesis-menus');

//* LOAD GOOGLE FONTS
function google_fonts()
{
    $query_args = array(
        'family' => 'Noto+Sans:400,400i,700,700i',
        'subset' => 'latin,latin-ext'
    );
    wp_enqueue_style('google_fonts', add_query_arg($query_args, "//fonts.googleapis.com/css"), array(), null);
}

add_action('wp_enqueue_scripts', 'google_fonts');

//* Enqueues external font awesome stylesheet
function enqueue_our_required_stylesheets()
{
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('custom', get_stylesheet_directory_uri() . '/custom.css');
}

add_action('wp_enqueue_scripts', 'enqueue_our_required_stylesheets');

//* Enqueue Custom Admin CSS
function enqueue_admin_stylesheets()
{
    wp_enqueue_style('admin-style', get_stylesheet_directory_uri() . '/admin-style.css');
}

add_action('admin_enqueue_scripts', 'enqueue_admin_stylesheets');

//* ENQUEUE CUSTOM AJAX JAVASCRIPT
function ajax_enqueue_scripts()
{
    // ENQUEUE CUSTOM JS
    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '2.0', true);

    // CHECK IF HOMEPAGE
    if (is_front_page()) {
        $frontpage = 1;
    } else {
        $frontpage = 0;
    }

    // PASS PHP DATA TO SCRIPT FILE (use example: customScript.siteUrl)
    wp_localize_script('custom', 'customScript', array(
        'themeUrl' => get_stylesheet_directory_uri(),
        'siteUrl' => get_site_url(),
        'frontPage' => $frontpage
    ));

    // LOCALIZE CUSTOM JS FILE TO USE WITH AJAX
    wp_localize_script('custom', 'ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));

}

add_action('wp_enqueue_scripts', 'ajax_enqueue_scripts');

/*** ADD HEADER BOTTOM WIDGET ***/
genesis_register_sidebar(array(
    'id' => 'header-bottom',
    'name' => __('Header Bottom', 'nabm'),
    'description' => __('Bottom Header Position', 'nabm'),
));

/*** ADD HEADER BOTTOM WIDGET POSITION ***/
add_action('genesis_after_header', 'headerBottomPosition');
function headerBottomPosition()
{
    genesis_widget_area('header-bottom', array(
        'before' => '<div class="header-bottom widget-area">',
        'after' => '</div><div style="clear:both;"></div>'
    ));
}

//* Add custom menus
function register_my_menus()
{
    register_nav_menus(
        array(
            'footer-menu1' => __('Footer Menu1'),
            'footer-menu2' => __('Footer Menu2')
        )
    );
}

add_action('init', 'register_my_menus');

//* Remove the site footer
//remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action('genesis_footer', 'genesis_do_footer');
//remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

/** AND SHORTCODES TO WIDGETS */
add_filter('widget_text', 'do_shortcode');

/*** ADD PERMALINK SLUGS
 * Use: <a href="[permalink id=49]">Basic Usage</a>
 * OR
 * [permalink id=49 text='providing text']
 ***/
function do_permalink($atts)
{
    extract(shortcode_atts(array(
        'id' => 1,
        'text' => ""  // default value if none supplied
    ), $atts));

    if ($text) {
        $url = get_permalink($id);
        return "<a href='$url'>$text</a>";
    } else {
        return get_permalink($id);
    }
}

add_shortcode('permalink', 'do_permalink');

//* USE FONT AWESOME DASH ICONS
function fontawesome_dashboard()
{
    wp_enqueue_style('fontawesome', 'http:////netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css', '', '4.5.0', 'all');
}

add_action('admin_init', 'fontawesome_dashboard');

/*** CUSTOM POST TYPES ***/
require_once get_stylesheet_directory() . '/includes/custom-post-types.php';

/*** CONDITIONAL ACF STATEMENTS ***/
require_once get_stylesheet_directory() . '/includes/conditional-statements.php';

// ALLOW POST TYPE CHECK OUTSIDE OF LOOP
function is_post_type($type)
{
    global $wp_query;
    if ($type == get_post_type($wp_query->post->ID)) {
        return true;
    }
    return false;
}

/*
// FORCE LAYOUTS
function wpnj_cpt_layout() {
	if(is_home()) {
		return 'content-sidebar';
	} else {
		return 'full-width-content';
	}
}
add_filter( 'genesis_site_layout', 'wpnj_cpt_layout' );
*/

//* Remove Genesis Admin Dashboard Layout Settings
remove_theme_support('genesis-inpost-layouts');