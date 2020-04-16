<?php

/**
 * Plugin Name: Books
 * Plugin URI: https://github.com/Bullport/springer
 * Description: Springer Coding Challenge
 * Author: Michael Zippe
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Books
 */

namespace Books;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    die ("Do not access this plugin directly");
}

//Starting point
$books = Books::getInstance();

class Books
{
    static $instance = false;

    //Singleton
    public static function getInstance()
    {
        if (!self::$instance) self::$instance = new self;
        return self::$instance;
    }

    public function __construct()
    {
        add_action('init', array($this, 'registerBookPostType'));
        add_action("admin_init", array($this, "admin_init"));
        add_action('wp_insert_post', 'save_upload', 10, 2);
    }

    public function registerBookPostType()
    {
        $labels = array(
            'name' => __('Books'),
            'singular_name' => __('Books'),
            'add_new_item' => __('Import'),
            'add_new' => __('Import'),
            'new_item' => __('New Book'),
            'edit_item' => __('Edit Book'),
            'view_item' => __('View Book'),
            'view_items' => __('View Books'),
            'not_found' => __('No book found'),
            'items_list' => __( 'Books list'),
        );

        $args = array(
            'labels' => $labels,
            'supports' => false,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'book', 'with_front' => FALSE)
	    );

        register_post_type('book', $args);

    }

    public function admin_init()
    {
        add_meta_box("json-meta", "Upload File", array($this, "json_upload"), "post");
    }

    function json_upload()
    {
        echo '<input type="file" name="json_data" />';
    }

    function save_upload($post_ID, $post)
    {
        if (!empty($_FILES['json-meta']['name'])) {
            $upload = wp_handle_upload($_FILES['json-meta']);
            if (!isset($upload['error'])) {
                die ($upload);
            }
        }
    }

}