<?php
/*
Plugin Name: Hide Post Dates
Plugin URI: https://originalsolutions.com.au/hide-post-dates
Description: This plugin hides the post dates on all posts.
Version: 0.2
Author: Tom Kaczocha
Author URI: https://originalsolutions.com.au
*/

// Hook for adding admin menus
add_action('admin_menu', 'hpd_add_box');
add_action('wp_enqueue_scripts', 'hpd_enqueue_styles');
add_action('wp_head', 'hpd_custom_styles');
add_action('save_post', 'hpd_save_postdata');

function hpd_enqueue_styles()
{
    wp_add_inline_style('wp-block-library', '.post-date, .entry-date { display: none !important; }');
}

// Adds a meta box to the post editing screen
function hpd_add_box()
{
    add_meta_box('hpd_sectionid', 'Show Post Date', 'hpd_meta_box_callback', 'post', 'side');
}

// Meta box content
function hpd_meta_box_callback($post)
{
    wp_nonce_field(plugin_basename(__FILE__), 'hpd_noncename');
    $value = get_post_meta($post->ID, 'show_date', true);
    echo '<label for="hpd_field">Show Date:</label> ';
    echo '<input type="checkbox" id="hpd_field" name="hpd_field" value="yes" ' . checked($value, 'yes', false) . '/>';
}

// Saves the custom meta input
function hpd_save_postdata($post_id)
{
    // Check if our nonce is set.
    if (!isset($_POST['hpd_noncename']) || !wp_verify_nonce($_POST['hpd_noncename'], plugin_basename(__FILE__))) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }
    // Update the meta field in the database.
    $show_date = (isset($_POST['hpd_field']) && $_POST['hpd_field'] == 'yes') ? 'yes' : 'no';
    update_post_meta($post_id, 'show_date', $show_date);
}

// Adds CSS to show dates based on post meta
function hpd_custom_styles()
{
    if (is_single()) {
        global $post;

        if (get_post_meta($post->ID, 'show_date', true) === 'yes') {
            echo '<style>.post-date, .entry-date { display: inline-block !important; }</style>';
        }
    }
}
