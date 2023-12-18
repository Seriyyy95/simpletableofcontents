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

        echo $beforeWidget;

        include __DIR__ . "/../templates/toc-style.php";

        if ($storage->getCount() > 2) {
            $tableOfContents = $storage->getTableOfContents();

            include __DIR__ . "/../templates/toc-template.php";
        }

        echo $afterWidget;
    }
}