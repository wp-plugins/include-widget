<?php
/**
* @package IncludeWidget
* @version 0.4
*/
/*
Plugin Name: Include Widget
Plugin URI: http://www.karrderized.com/wordpress-plugins/include-widget/
Description: Includes a file's contents in the widget.
Author: James Carppe
Version: 0.4
Author URI: http://www.karrderized.com/
*/

// Changelog:
// v0.4: improved widget config page formatting, added note to config page re pathing
// v0.3: added URL vs path checking
// v0.2: rewrote with wp2.8+ new widget interface code
// v0.1: initial release

add_action('widgets_init', 'include_load_widgets');

function include_load_widgets() {
  register_widget('IncludeWidget');
}

class IncludeWidget extends WP_Widget {
  /** constructor */
  function IncludeWidget() {
    $widget_ops = array('classname'=>'include', 'description'=>'Includes a file\'s contents in the widget.');
    $control_ops = array('width'=>250, 'height'=>150, 'id_base'=>'include-widget');
    $this->WP_Widget('include-widget', 'Include', $widget_ops, $control_ops);    
  }
  
  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $url = $instance['url'];
    
    echo $before_widget . $before_title . $title . $after_title;
    $components = parse_url($url);
    if (isset($components['scheme'])) {         // $url is a URL
      $includefile = $url;
    } else {                                    // $url is a path
      $includefile = ABSPATH . $url;
    }
		include($includefile);	
		echo $after_widget;
  }
  
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['url'] = strip_tags($new_instance['url']);
    
    return $instance;
  }
  
  function form($instance) {
    $defaults = array('title'=>'Stuff', 'url'=>'');
    $instance = wp_parse_args((array) $instance, $defaults ); ?>
    
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:<br /><input style="width: 200px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('url'); ?>">URL:<br /><input style="width: 200px;" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $instance['url']; ?>" /></label></p>
		<p>If including a file, the path is prepended with <strong><?php echo ABSPATH ; ?></strong></p>
		
		<?php
  }
  
}

?>