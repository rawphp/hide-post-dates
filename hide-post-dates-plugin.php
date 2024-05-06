<?php
/*
Plugin Name: Hide Post Dates
Plugin URI: https://originalsolutions.com.au/hide-post-dates
Description: This plugin hides the post dates on all posts.
Version: 0.1
Author: Tom Kaczocha
Author URI: https://originalsolutions.com.au
*/

function hide_post_dates()
{
    echo '<style> .post-date, .entry-date { display: none !important; } </style>';
}

add_action('wp_head', 'hide_post_dates');
