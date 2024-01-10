<?php

/*
 *
 * Plugin Name: Simple Table Of Contents
 * Plugin URI: https://haait.net
 * Description: Generates Table of Contents
 * Version: 1.0
 * Author: Seriyyy95
 * Author URI: https://haait.net
 */

require __DIR__ . '/vendor/autoload.php';

add_filter('the_content', function ($content) {
    if (is_single()) {
        $storage = new \SimpleTOC\TOCStorage($content);

        return $storage->getFixedContent();
    }

    return $content;
});

add_action('init', function () {
    load_plugin_textdomain('simpletableofcontents', false, basename(plugin_dir_path(__FILE__)) . '/languages');
});

add_action('widgets_init', function () {
    register_widget(\SimpleTOC\TOCWidget::class);
});