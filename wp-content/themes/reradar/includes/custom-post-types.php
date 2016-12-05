<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 11/5/2016
 * Time: 1:39 PM
 */

//* CREATE CUSTOM AGENT PROFILE POST TYPE
function agent_profile_post_type() {
    $labels = array(
        'name'               => __( 'Agent Profile' ),
        'singular_name'      => __( 'Agent Profile' ),
        'all_items'          => __( 'All Agent Profiles' ),
        'add_new'            => _x( 'Add Agent Profile', 'Agent Profile' ),
        'add_new_item'       => __( 'Add Agent Profile' ),
        'edit_item'          => __( 'Edit Agent Profile' ),
        'new_item'           => __( 'New Agent Profile' ),
        'view_item'          => __( 'View Agent Profile' ),
        'search_items'       => __( 'Search Agent Profiles' ),
        'not_found'          => __( 'No Agent Profiles found' ),
        'not_found_in_trash' => __( 'No Agent Profiles found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'agent-profile-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'agent-profile', $args);
}
add_action( 'init', 'agent_profile_post_type' );

//* CREATE CUSTOM BROKER PROFILE POST TYPE
function broker_profile_post_type() {
    $labels = array(
        'name'               => __( 'Broker Profile' ),
        'singular_name'      => __( 'Broker Profile' ),
        'all_items'          => __( 'All Broker Profiles' ),
        'add_new'            => _x( 'Add Broker Profile', 'Broker Profile' ),
        'add_new_item'       => __( 'Add Broker Profile' ),
        'edit_item'          => __( 'Edit Broker Profile' ),
        'new_item'           => __( 'New Broker Profile' ),
        'view_item'          => __( 'View Broker Profile' ),
        'search_items'       => __( 'Search Broker Profiles' ),
        'not_found'          => __( 'No Broker Profiles found' ),
        'not_found_in_trash' => __( 'No Broker Profiles found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'broker-profile-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'broker-profile', $args);
}
add_action( 'init', 'broker_profile_post_type' );

//* CREATE CUSTOM DIRECTORY POST TYPE
function directory_post_type() {
    $labels = array(
        'name'               => __( 'Directory' ),
        'singular_name'      => __( 'Directory' ),
        'all_items'          => __( 'All Directory Items' ),
        'add_new'            => _x( 'Add Directory', 'Directory' ),
        'add_new_item'       => __( 'Add Directory Item' ),
        'edit_item'          => __( 'Edit Directory Item' ),
        'new_item'           => __( 'New Directory Item' ),
        'view_item'          => __( 'View Directory Item' ),
        'search_items'       => __( 'Search Directory' ),
        'not_found'          => __( 'No Directory Items found' ),
        'not_found_in_trash' => __( 'No Directory Items found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'directory-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'directory', $args);
}
add_action( 'init', 'directory_post_type' );

//* CREATE CUSTOM EXPERT POST TYPE
function expert_post_type() {
    $labels = array(
        'name'               => __( 'Expert' ),
        'singular_name'      => __( 'Expert' ),
        'all_items'          => __( 'All Experts' ),
        'add_new'            => _x( 'Add Expert', 'Expert' ),
        'add_new_item'       => __( 'Add Expert' ),
        'edit_item'          => __( 'Edit Expert' ),
        'new_item'           => __( 'New Expert' ),
        'view_item'          => __( 'View Expert' ),
        'search_items'       => __( 'Search Experts' ),
        'not_found'          => __( 'No Experts found' ),
        'not_found_in_trash' => __( 'No Experts found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'expert-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'expert', $args);
}
add_action( 'init', 'expert_post_type' );

//* CREATE CUSTOM NEWS POST TYPE
function news_post_type() {
    $labels = array(
        'name'               => __( 'News' ),
        'singular_name'      => __( 'News' ),
        'all_items'          => __( 'All News' ),
        'add_new'            => _x( 'Add News', 'News' ),
        'add_new_item'       => __( 'Add News' ),
        'edit_item'          => __( 'Edit News' ),
        'new_item'           => __( 'New News' ),
        'view_item'          => __( 'View News' ),
        'search_items'       => __( 'Search News' ),
        'not_found'          => __( 'No News found' ),
        'not_found_in_trash' => __( 'No News found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'news-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'categories', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'news', $args);
}
add_action( 'init', 'news_post_type' );

//* CREATE CUSTOM PRODUCT POST TYPE
function products_post_type() {
    $labels = array(
        'name'               => __( 'Products' ),
        'singular_name'      => __( 'Product' ),
        'all_items'          => __( 'All Products' ),
        'add_new'            => _x( 'Add Product', 'Products' ),
        'add_new_item'       => __( 'Add Product' ),
        'edit_item'          => __( 'Edit Product' ),
        'new_item'           => __( 'New Product' ),
        'view_item'          => __( 'View Product' ),
        'search_items'       => __( 'Search Products' ),
        'not_found'          => __( 'No Products found' ),
        'not_found_in_trash' => __( 'No Products found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'product-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'products', $args);
}
add_action( 'init', 'products_post_type' );

//* CREATE CUSTOM RESOURCES POST TYPE
function resources_post_type() {
    $labels = array(
        'name'               => __( 'Resources' ),
        'singular_name'      => __( 'Resource' ),
        'all_items'          => __( 'All Resources' ),
        'add_new'            => _x( 'Add Resource', 'Resources' ),
        'add_new_item'       => __( 'Add Resource' ),
        'edit_item'          => __( 'Edit Resource' ),
        'new_item'           => __( 'New Resource' ),
        'view_item'          => __( 'View Resource' ),
        'search_items'       => __( 'Search Resources' ),
        'not_found'          => __( 'No Resources found' ),
        'not_found_in_trash' => __( 'No Resources found in trash' ),
        'parent_item_colon'  => ''
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-widgets-menus', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
        'rewrite'            => array( 'slug' => 'resource-items' ),
        'taxonomies'         => array( 'category' ),
        'query_var'          => true,
        'menu_position'      => 5,
        'supports'           => array('thumbnail' , 'custom-fields', 'excerpt', 'comments', 'title', 'editor', 'genesis-seo', 'genesis-layouts', 'genesis-cpt-archives-settings')
    );
    register_post_type( 'resources', $args);
}
add_action( 'init', 'resources_post_type' );

//* ADD CUSTOM TAXONOMIES FOR CUSTOM POST TYPES
function build_region_taxonomy() {
    register_taxonomy(
        'region',
        array(
            'agent-profile',
            'broker-profile',
            'directory',
            'expert',
            'news',
            'products',
            'resources'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Region',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_region_taxonomy', 0 );

function build_content_type_taxonomy() {
    register_taxonomy(
        'content_type',
        array(
            'expert',
            'news',
            'resources'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Content Type',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_content_type_taxonomy', 0 );

function build_agent_profiles_categories() {
    register_taxonomy(
        'agent_profiles_categories',
        array(
            'agent-profiles'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Agent Profiles Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_agent_profiles_categories', 0 );

function build_broker_profiles_categories() {
    register_taxonomy(
        'broker_profiles_categories',
        array(
            'broker-profiles'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Broker Profiles Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_broker_profiles_categories', 0 );

function build_directory_categories() {
    register_taxonomy(
        'directory_categories',
        array(
            'directory'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Directory Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_directory_categories', 0 );

function build_products_categories() {
    register_taxonomy(
        'products_categories',
        array(
            'products'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Products Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_products_categories', 0 );

function build_resources_categories() {
    register_taxonomy(
        'resources_categories',
        array(
            'resources'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Resources Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_resources_categories', 0 );

function build_expert_categories() {
    register_taxonomy(
        'expert_categories',
        array(
            'expert'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'Expert Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_expert_categories', 0 );

function build_news_categories() {
    register_taxonomy(
        'news_categories',
        array(
            'news'
        ), // this is the custom post type(s) I want to use this taxonomy for
        array(
            'hierarchical' => false,
            'label' => 'News Categories',
            'query_var' => true,
            'rewrite' => true
        )
    );
}
add_action( 'init', 'build_news_categories', 0 );