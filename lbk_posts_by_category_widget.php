<?php
/**
 * LBK Widget Category Post
 * 
 * @package LBK Widget Category Post
 * @author LBK
 * @copyright 2021 LBK
 * @license GPL-2.0-or-later
 * @category plugin
 * @version 1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name:       LBK Widget Category Post
 * Plugin URI:        https://lbk.vn/
 * Description:       Plugin Name always appear on the website
 * Version:           1.0.0
 * Requires at least: 1.0.0
 * Requires PHP:      7.4
 * Author:            LBK
 * Author             URI: https://www.facebook.com/profile.php?id=100008413214141
 * Text Domain:       plugin-menu-slug
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain path:       /languages/
 * 
 * LBK Widget Category Post is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *  
 * LBK Widget Category Post is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with LBK Widget Category Post. If not, see <http://www.gnu.org/licenses/>.
*/

// Die if accessed directly
if ( !defined('ABSPATH') ) die( 'What are you doing here? You silly human!' );

if ( !class_exists('LBKCategoryPosts') ) {
	class LBKCategoryPosts extends WP_Widget{
	    function __construct()
	    {
	        parent::__construct(
	            'product_viewed',
	            'LBK Widget Category Post',
	            array(
	            'description' => 'Display LBK Widget Category Post'
	        ));

	        LBKCategoryPosts::product_viewed_enqueue_scipt();
	    }
	    public function widget($args, $instance)
	    {	        
			extract( $args );
	        $title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			echo $before_title.esc_attr($instance['title']).$after_title;
			
			if (isset($instance['title'])) {
	            $title = $instance['title'];
	        } else {
	            $title = 'LBK Widget Category Post';
	        }
			
			if (isset($instance['count'])) {
	            $count = $instance['count'];
	        } else {
	            $count = 5;
	        }
			
			if (isset($instance['orderby'])) {
	            $orderby = $instance['orderby'];
	        } else {
	            $orderby = 'date';
	        }

	        if (isset($instance['category'])) {
	            $category = $instance['category'];
	        } else {
	            $category = -1;
	        }
			
			if (isset($instance['image_type'])) {
	            $image_type = $instance['image_type'];
	        } else {
	            $image_type = 'post-thumbnail';
	        }
			
			if (isset($instance['image_width'])) {
	            $image_width = $instance['image_width'];
	        } else {
	            $image_width = '90';
	        }
			
			if (isset($instance['image_height'])) {
	            $image_height = $instance['image_height'];
	        } else {
	            $image_height = '90';
	        }
			
			if (isset($instance['font_size'])) {
	            $font_size = $instance['font_size'];
	        } else {
	            $font_size = '';
	        }

	        if (isset($instance['title_lines'])) {
	            $title_lines = $instance['title_lines'];
	        } else {
	            $title_lines = '2';
	        }

			if (isset($instance['round'])) {
	            $round = $instance['round'];
	        } else {
	            $round = '';
	        }

	        if (isset($instance['fix_to_square'])) {
	            $fix_to_square = $instance['fix_to_square'];
	        } else {
	            $fix_to_square = '';
	        }

	        if (isset($instance['show_excerpt'])) {
	            $show_excerpt = $instance['show_excerpt'];
	        } else {
	            $show_excerpt = 'yes';
	        }

	        if (isset($instance['excerpt_lines'])) {
	            $excerpt_lines = $instance['excerpt_lines'];
	        } else {
	            $excerpt_lines = 3;
	        }

	        if (isset($instance['post_date'])) {
	            $post_date = $instance['post_date'];
	        } else {
	            $post_date = '';
	        }

			$query_args = array(
				'posts_per_page' => $count, // Hiển thị số lượng sản phẩm đã xem
				'post_status'    => 'publish', 
				'post_type'      => 'post', 
				'orderby'        => $orderby,
				'cat'        => $category
			);
			$posts = new WP_Query( $query_args );

			// The Loop
			if ( $posts->have_posts() ) :
			echo '<div class = "posts-widget">';
			while ( $posts->have_posts() ) : $posts->the_post();
			 	?>
				
				<div class = " post-item" >
					<div class = "post-image"  style="width:<?php echo esc_attr($image_width); ?>px; height: <?php echo esc_attr($image_height); ?>px;">
						<a href = "<?php echo get_the_permalink(); ?>"><img class = "<?php if($round !=='') { echo $round; }?> <?php if($fix_to_square !=='') { echo "square"; }?>" src="<?php  echo get_the_post_thumbnail_url(get_the_ID(), $image_type); ?>"/></a>
					</div>
					<div class = "post-box-text">
						<h5 class ="post-title <?php echo 'fs-'.$font_size; ?> mb-0" style="-webkit-line-clamp: <?php echo esc_attr($title_lines);?>;">
							<a class = "plain" href = "<?php echo get_the_permalink(); ?>"> <?php  echo get_the_title();?> </a>
						</h5>
						<?php
							if($post_date == 'yes') {
								?>
									<span class ="is-small"> <?php echo get_the_date();?> </span>
								<?php
							}
						?>
						<?php
							if($show_excerpt == 'yes') {
								?>
									<p class="post-excerpt fs-<?php echo $font_size;?>" style="-webkit-line-clamp: <?php echo esc_attr($excerpt_lines);?>;"> <?php echo get_the_excerpt();?> </p>
								<?php
							}
						?>

					</div>
				</div>
				<?php
			endwhile;
			echo '</div>';
			endif;

			// Reset Post Data
			wp_reset_postdata();
			
			echo $args['after_widget'];

	    }
	    // Widget Backend
	    public function form($instance)
	    {
	        if (isset($instance['title'])) {
	            $title = $instance['title'];
	        } else {
	            $title = 'LBK Widget Category Post';
	        }
			
			 if (isset($instance['count'])) {
	            $count = $instance['count'];
	        } else {
	            $count = 5;
	        }
			
			if (isset($instance['orderby'])) {
	            $orderby = $instance['orderby'];
	        } else {
	            $orderby = 'date';
	        }
	        if (isset($instance['category'])) {
	            $category = $instance['category'];
	        } else {
	            $category = 'date';
	        }
			
			if (isset($instance['image_type'])) {
	            $image_type = $instance['image_type'];
	        } else {
	            $image_type = 'post-thumbnail';
	        }
			
			if (isset($instance['image_width'])) {
	            $image_width = $instance['image_width'];
	        } else {
	            $image_width = '90';
	        }
			
			if (isset($instance['image_height'])) {
	            $image_height = $instance['image_height'];
	        } else {
	            $image_height = '90';
	        }
			
			if (isset($instance['font_size'])) {
	            $font_size = $instance['font_size'];
	        } else {
	            $font_size = '';
	        }

	        if (isset($instance['title_lines'])) {
	            $title_lines = $instance['title_lines'];
	        } else {
	            $title_lines = '2';
	        }

	        if (isset($instance['round'])) {
	            $round = $instance['round'];
	        } else {
	            $round = '';
	        }

	        if (isset($instance['fix_to_square'])) {
	            $fix_to_square = $instance['fix_to_square'];
	        } else {
	            $fix_to_square = '';
	        }

			if (isset($instance['show_excerpt'])) {
	            $show_excerpt = $instance['show_excerpt'];
	        } else {
	            $show_excerpt = 'yes';
	        }

	        if (isset($instance['excerpt_lines'])) {
	            $excerpt_lines = $instance['excerpt_lines'];
	        } else {
	            $excerpt_lines = 3;
	        }

	         if (isset($instance['post_date'])) {
	            $post_date = $instance['post_date'];
	        } else {
	            $post_date = '';
	        }

	?>
			<p><label for="<?php echo esc_attr($this->get_field_id('title'));?>"><?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'));?>" name="<?php echo esc_attr($this->get_field_name('title'));?>" type="text" value="<?php echo esc_attr($title);?>" /></label></p>

			<p><b>Query</b></p>
				
			<p><label for="<?php echo esc_attr($this->get_field_id('count'));?>"><?php _e('Number of products:'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('count'));?>" name="<?php echo esc_attr($this->get_field_name('count'));?>" type="number" value="<?php echo esc_attr($count);?>" /></label></p>

			<p><label for="<?php echo esc_attr($this->get_field_id('category'));?>"><?php _e('Category'); ?>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('category'));?>" name="<?php echo esc_attr($this->get_field_name('category'));?>">

				<option  value="-1"> Tất cả </option>
				<?php
					$categories = get_categories();
					foreach($categories as $key => $value) {
						?>
						<option <?php if($category == $value->cat_id) echo 'selected'; ?> value="<?php echo esc_attr($value->cat_id) ?>"><?php  _e( $value->cat_name ) ?></option>
						<?php
					}
				?>
	        </select></label></p>
			
			<p><label for="<?php echo esc_attr($this->get_field_id('orderby')) ;?>"><?php _e('Chooise sort by:'); ?>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby'));?>" name="<?php echo esc_attr($this->get_field_name('orderby'));?>">
				<option  value="">Chooise</option>
				<option <?php if($orderby == 'rand') echo 'selected'; ?> value="rand">Random</option>
				<option <?php if($orderby == 'date') echo 'selected';?> value="date">Date</option>
	        </select></label></p>
				
	        <p><b>Image</b></p>
				
			<p><label for="<?php echo esc_attr($this->get_field_id('image_width'));?>"><?php _e('Image Width:'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image_width'));?>" name="<?php echo esc_attr($this->get_field_name('image_width'));?>" type="number" value="<?php echo esc_attr($image_width);?>" /></label></p>
				
			<p><label for="<?php echo esc_attr($this->get_field_id('image_height'));?>"><?php _e('Image Height:'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image_height'));?>" name="<?php echo esc_attr($this->get_field_name('image_height'));?>" type="number" value="<?php echo esc_attr($image_height);?>" /></label></p>

			<p><label for="<?php echo esc_attr($this->get_field_id('image_type'));?>"><?php _e('Chooise image type:'); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('image_type'));?>" name="<?php echo esc_attr($this->get_field_name('image_type'));?>">
				<option  value="">Chooise size</option>
				<option <?php if($image_type == 'post-thumbnail') echo 'selected'; ?> value="post-thumbnail">Thumbnail</option>
				<option <?php if($image_type == 'post-medium') echo 'selected';?> value="post-medium">Mediumn</option>
				<option <?php if($image_type == 'post-large') echo 'selected';?> value="post-large">Large</option>
				<option <?php if($image_type == 'post-origin') echo 'selected';?> value="post-origin">Origin</option>
	        </select></p>

	        <p><label for="<?php echo esc_attr($this->get_field_id('round'));?>"><?php _e('Chooise round type:'); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('round'));?>" name="<?php echo esc_attr($this->get_field_name('round'));?>">
				<option  value="">Chooise size</option>
				<option <?php if($round == 'round-3') echo 'selected'; ?> value="round-3">S</option>
				<option <?php if($round == 'round-5') echo 'selected';?> value="round-5">M</option>
				<option <?php if($round == 'round-10') echo 'selected';?> value="round-10">L</option>
				<option <?php if($round == 'round-full') echo 'selected';?> value="round-full">Full</option>
	        </select></p>

	        

	        <p><label for="<?php echo esc_attr($this->get_field_id('fix_to_square'));?>"><?php _e('Fix image to square:'); ?> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('fix_to_square'));?>" name="<?php echo esc_attr($this->get_field_name('fix_to_square'));?>" type="checkbox" value="yes"  <?php if($fix_to_square == 'yes') echo 'checked'; ?> />
	    	</label></p>


			<p><b>Text</b></p>

			<p><label for="<?php echo esc_attr($this->get_field_id('title_lines'));?>"><?php _e('Title lines:'); ?>
			<input class="widefat" id="<?php esc_attr(echo $this->get_field_id('title_lines'));?>" name="<?php echo esc_attr($this->get_field_name('title_lines'));?>" type="number" value="<?php echo esc_attr($title_lines);?>" /></label></p>
				
			<p><label for="<?php echo esc_attr($this->get_field_id('font_size'));?>"><?php _e('Chooise title size:'); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('font_size'));?>" name="<?php echo esc_attr($this->get_field_name('font_size'));?>">
				<option  value="">Chooise size</option>
				<option <?php if($font_size == 'small') echo 'selected'; ?> value="small">S</option>
				<option <?php if($font_size == 'medium') echo 'selected';?> value="medium">M</option>
				<option <?php if($font_size == 'large') echo 'selected';?> value="large">L</option>
	        </select></p>

	        <p><label for="<?php echo esc_attr($this->get_field_id('post_date'));?>"><?php _e('Show date:'); ?> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_date'));?>" name="<?php echo esc_attr($this->get_field_name('post_date'));?>" type="checkbox" value="yes"  <?php if($post_date == 'yes') echo 'checked'; ?> />
	    	</label></p>

	        <p><label for="<?php echo esc_attr($this->get_field_id('show_excerpt'));?>"><?php _e('Show excerpt:'); ?> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('show_excerpt'));?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt'));?>" type="checkbox" value="yes"  <?php if($show_excerpt == 'yes') echo 'checked'; ?> />
	    	</label></p>


			<p class="description">Mọi ý kiến đóng góp về Plugin xin gửi email về <b>sheensilvers@gmail.com</b> hoặc <b>service.lbk.vn@gmail.com</b></p>

			<?php
	    }
	    // Updating widget replacing old instances with new
	    public function update($new_instance, $old_instance) {
	        $instance          = array();
	        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '' ;
			$instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '' ;
			$instance['image_width'] = (!empty($new_instance['image_width'])) ? strip_tags($new_instance['image_width']) : '' ;
			$instance['image_height'] = (!empty($new_instance['image_height'])) ? strip_tags($new_instance['image_height']) : '' ;
			$instance['font_size'] = (!empty($new_instance['font_size'])) ? strip_tags($new_instance['font_size']) : '' ;
			$instance['orderby'] = (!empty($new_instance['orderby'])) ? strip_tags($new_instance['orderby']) : 'date' ;
			$instance['image_type'] = (!empty($new_instance['image_type'])) ? strip_tags($new_instance['image_type']) : 'post-thumbnail' ;
			$instance['title_lines'] = (!empty($new_instance['title_lines'])) ? strip_tags($new_instance['title_lines']) : '2' ;
			$instance['round'] = (!empty($new_instance['round'])) ? strip_tags($new_instance['round']) : '' ;
			$instance['fix_to_square'] = (!empty($new_instance['fix_to_square'])) ? strip_tags($new_instance['fix_to_square']) : '' ;
			$instance['category'] = (!empty($new_instance['category'])) ? strip_tags($new_instance['category']) : -1 ;
			$instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? strip_tags($new_instance['show_excerpt']) : -1 ;
			$instance['excerpt_lines'] = (!empty($new_instance['excerpt_lines'])) ? strip_tags($new_instance['excerpt_lines']) : 3 ;
			$instance['post_date'] = (!empty($new_instance['post_date'])) ? strip_tags($new_instance['post_date']) : '' ;


			return $instance;
			
		}
		static function product_viewed_enqueue_scipt() {
			wp_enqueue_style('lbk_widget_post_by_category_script', plugin_dir_url(__FILE__) . 'assets/css/frontend.css', array(), 'all');
		}

	} 
// Class LBKCategoryPosts ends here
// Register and load the widget
	if(!function_exists('create_category_posts_widget')) {
		function create_category_posts_widget() {
	     register_widget('LBKCategoryPosts');
		}
	}

	add_action('widgets_init', 'create_category_posts_widget');
}
