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

        if ($storage->getCount() > 2) {
            include __DIR__ . "/../templates/toc-style.php";

            echo <<<STYLE
    <style>
        .widget_simple_toc_widget > div > ul::-webkit-scrollbar {
            width: 4px;
            background-color: $scrollbarBg;
        }

        .widget_simple_toc_widget > div > ul::-webkit-scrollbar-thumb {
            background-color: $scrollbarThumb;
        } 
    </style>
STYLE;

            echo <<<SCRIPT
<script>

(function(){
    let overrideActiveItem = null
    let items = [];
    
    const getOffsetTop = element => {
        let offsetTop = 0;
        while(element) {
            offsetTop += element.offsetTop;
            element = element.offsetParent;
        }
        return offsetTop;
    } 
    const getTOCOffsetTop = element => {
        let offsetTop = 0;
        while(element.parentNode !== undefined && element.parentNode.tagName !== 'DIV') {
            offsetTop += element.offsetTop;
            element = element.offsetParent;
        }
        return offsetTop;
    } 
    const scrollWidgetTOC = function(element) {
       const container = document.querySelector("#simple-toc-widget ul")
       const offset = getTOCOffsetTop(element) - (container.offsetHeight / 2)
       container.scrollTo({top: offset, behavior: 'smooth'})
    }
    
    const updateTOCWidget = function(){
            document.querySelectorAll("#simple-toc-widget li > a").forEach(function(element, index){element.style.color = "#cc7a44"})
            
            const offset = window.scrollY + (window.innerHeight / 2);
            for (let i=1; i < items.length; i++){
                if(items[i-1].position < offset && items[i].position > offset){
                    document.querySelector('#simple-toc-widget li[data-toc-section="' + items[i-1].id + '"] > a').style.color = "#ccced1"
                    scrollWidgetTOC(document.querySelector('#simple-toc-widget li[data-toc-section="' + items[i-1].id + '"]'))
                    
                    break;
                }else if(i === items.length-1 && items[i].position < offset){
                    document.querySelector('#simple-toc-widget li[data-toc-section="' + items[i].id + '"] > a').style.color = "#ccced1"
                    
                    break;
                } 
            }
    }
    
    document.addEventListener("DOMContentLoaded", function(){
        document.querySelectorAll("#simple-toc-widget li").forEach(function(element, index){
            const id = element.getAttribute("data-toc-section")
            const heading = document.getElementById(id)

            items.push({
                id: id,
                position: getOffsetTop(heading),
            })
            
            const link = element.querySelector("a");
            link.addEventListener("click", function(){
               overrideActiveItem = true
               document.querySelectorAll("#simple-toc-widget li > a").forEach(function(element, index){element.style.color = "#cc7a44"})
               document.querySelector('#simple-toc-widget li[data-toc-section="' + id + '"] > a').style.color = "#ccced1"
            })
        });       
        
        updateTOCWidget()
        
    }, false);

    
    function observerCallback(entries, observer) {
        if(overrideActiveItem !== null){
            overrideActiveItem = null
              
            return
        }
 
        entries.forEach(entry => {
            updateTOCWidget()
        });
    }
    
    let marginObserver = new IntersectionObserver(observerCallback, {
        rootMargin: '-50% 0% -50% 0%',
        threshold: 0
    });
    let centerObserver = new IntersectionObserver(observerCallback, {
        rootMargin: '0px',
        threshold: 1
    });
 
    let target = '[data-toc-heading="true"]';
    document.querySelectorAll(target).forEach((i) => {
        if (i) {
            marginObserver.observe(i);
            centerObserver.observe(i);
        }
    });
})();
</script>
SCRIPT;


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