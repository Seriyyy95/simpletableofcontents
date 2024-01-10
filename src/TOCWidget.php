<?php

namespace SimpleTOC;

use WP_Widget;

class TOCWidget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'simple_toc_widget',
            __('Simple TOC', 'simple_toc'),
            ['description' => __('Displays Table Of Contents', 'simple_toc')]
        );
    }

    public function widget($args, $instance)
    {
        $beforeWidget = $args['before_widget'];
        $beforeTitle = $args['before_title'];
        $afterTitle = $args['after_title'];
        $afterWidget = $args['after_widget'];

        if (false === is_single()) {
            return;
        }

        $storage = TOCStorage::getInstance();

        $title = $instance["title"];
        $scrollbarBg = $instance['scrollbar_background'];
        $scrollbarThumb = $instance['scrollbar_thumb'];

        echo $beforeWidget;

        include __DIR__ . "/../templates/toc-style.php";

        echo <<<STYLE
    <style>
        .widget_simple_toc_widget > ul::-webkit-scrollbar {
            width: 4px;
            background-color: $scrollbarBg;
        }

        .widget_simple_toc_widget > ul::-webkit-scrollbar-thumb {
            background-color: $scrollbarThumb;
        } 
    </style>
STYLE;


        if ($storage->getCount() > 2) {
            $tableOfContents = $storage->getTableOfContents();

            include __DIR__ . "/../templates/toc-template.php";
        }

        echo $afterWidget;
    }

    public function form($instance)
    {
        if (isset($instance['scrollbar_background'])) {
            $scrollbarBg = $instance['scrollbar_background'];
        } else {
            $scrollbarBg = "#ccced1";
        }

        if (isset($instance['scrollbar_thumb'])) {
            $scrollbarThumb = $instance['scrollbar_thumb'];
        } else {
            $scrollbarThumb = "#171716";
        }

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __("Table of Contents", "simpletableofcontents");
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo "Title" ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('scrollbar_background'); ?>"><?php echo "Scrollbar Background Color" ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('scrollbar_background'); ?>"
                   name="<?php echo $this->get_field_name('scrollbar_background'); ?>" type="text"
                   value="<?php echo esc_attr($scrollbarBg); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('scrollbar_thumb'); ?>"><?php echo "Scrollbar Thumb Color" ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('scrollbar_thumb'); ?>"
                   name="<?php echo $this->get_field_name('scrollbar_thumb'); ?>" type="text"
                   value="<?php echo esc_attr($scrollbarThumb); ?>"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['scrollbar_background'] = (!empty($new_instance['scrollbar_background'])) ? strip_tags($new_instance['scrollbar_background']) : '';
        $instance['scrollbar_thumb'] = (!empty($new_instance['scrollbar_thumb'])) ? strip_tags($new_instance['scrollbar_thumb']) : '';

        return $instance;
    }
}