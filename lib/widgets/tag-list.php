<?php
/**
 * Tag list widget - taken from Twenty Links by Stephanie Leary
 *
 * @since   1.0.0
 * @link    http://sillybean.net/code/themes/twenty-links-a-delicious-inspired-child-theme-for-wordpress/
 * @package Tasty
 */

/**
 * Register Tag List widget
 *
 * @since 1.0.0
 */
function tag_list_widget_setup() {
	register_widget( 'Tag_List_Widget' );
}
add_action( 'widgets_init', 'tag_list_widget_setup' );

/**
 * Tag list widget
 *
 * @since 1.0.0
 * @package Tasty
 */
class Tag_List_Widget extends WP_Widget {

	function Tag_List_Widget() {
		$widget_ops  = array( 'description' => 'Lists all tags' );
		$control_ops = array( 'width' => 400,  'height' => 200  );
		$this->WP_Widget( 'tag_list', 'Tag List', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args );
		echo $before_widget; 
		
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Top Tags' ) : $instance['title']);
		echo $before_title . $title . $after_title;
		?>
	    <ul id="tag-list-widget">
		<?php
		$tags = get_terms('post_tag', array(
			'fields' => 'all', 
		    'orderby' => 'count',
			'order' => 'DESC',
			'number' => $instance['num']  ));
		foreach ($tags as $tag) {
			echo '<li><a href="/tag/'.$tag->slug.'">'.$tag->name.'<span class="count">'.$tag->count.'</span></a></li>';
		}
		?>
	    </ul>
	   	<?php
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num'] = (int)$new_instance['num'];
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'Top Tags',
			'num'   => 45,
		));	
	?>  
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of links to show:'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" type="text" value="<?php echo $instance['num']; ?>" /></p>
		<?php
	}
}