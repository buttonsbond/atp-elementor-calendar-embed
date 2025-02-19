<?php
/**
 * Plugin Name: ATP Elementor Calendar Embed
 * Description: Embed Google Calendar, Microsoft Calendar, and Calendly into Elementor Pro.
 * Version: 1.1
 * Author: Mark van Bellen, All Tech Plus, Rojales
 * GitHub Plugin URI: https://github.com/buttonsbond/atp-elementor-calendar-embed
 * Primary Branch: main
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register the widget after Elementor is loaded
add_action('elementor/widgets/register', function() {
    require_once __DIR__ . '/includes/elementor-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register(new \ATP_Embed_Calendar_Elementor_Widget());
});

// Enqueue styles and scripts
function elementor_calendar_embed_scripts() {
    // Plugin CSS
    wp_enqueue_style('elementor-calendar-embed-style', plugins_url('assets/css/style.css', __FILE__));

    // Plugin JS
    wp_enqueue_script('elementor-calendar-embed-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'elementor_calendar_embed_scripts');
// Include GitHub Updater

//require_once __DIR__ . '/github-updater.php';
