<?php
/*
Plugin Name: Up Sell Product Display For Woocommerce
Plugin URI: http://codextent.com
description: Woocommerce Up Sell product display using Widgets and Shortcode.
Version: 1.0
Author: Codextent
Author URI: http://codextent.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Required Contant
define( 'CE_CDXWOOFREEUP_PLUGIN_PATH',			dirname(__FILE__).'/' );
define( 'CE_CDXWOOFREEUP_PLUGIN_URL',			 untrailingslashit( plugins_url( '/', __FILE__ )) );
define( 'CE_CDXWOOFREEUP_PLUGIN_BASENAME',  		plugin_basename( __FILE__ ));
define( 'CE_CDXWOOFREEUP_VERSION',  				'1.0');

//Templates
require_once(CE_CDXWOOFREEUP_PLUGIN_PATH.'templates.php');
//Shortcode
require_once(CE_CDXWOOFREEUP_PLUGIN_PATH.'shortcode.php');

class CodextentWooUpSells extends WP_Widget {

	public function __construct() {
		// Instantiate the parent object
		parent::__construct(
			'codextent-woo-up-sells', // Base ID
			__('Woocommerce Up Sells Product Display', 'codextent-woo-up-sells'), // Name
			array( 'description' => __( 'Woocommerce Up Sells products display. Widget will work on product details page.', 'codextent-woo-up-sells' ), ) // Args
		);
		
		add_action('wp_enqueue_scripts', array(__CLASS__, 'cdxfreewooup_register_script_style'));	
		
	}
	
	/**
	 * Scripts for widgets resut HTML.
	 */
	public static function cdxfreewooup_register_script_style(){
		wp_register_style('cdx-html-lib',  CE_CDXWOOFREEUP_PLUGIN_URL. '/assets/css/ce.css');
		wp_register_style('cdxup-front',  CE_CDXWOOFREEUP_PLUGIN_URL. '/assets/css/codextent-woo-up-sell.css');
		wp_enqueue_style('cdx-html-lib');	
		wp_enqueue_style('cdxup-front');	
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		if ( !session_id() )
		add_action( 'init', 'session_start' );	
				
		extract( $args );
		$widget_title = apply_filters('widget_title', $instance['codextent_woo_up_sells_title']);
				
		if(isset($before_widget)){echo $before_widget;}
		if ( $widget_title ) {echo $before_title . $widget_title . $after_title; }
		
		//Widget content Start
		$theme = $instance['codextent_woo_up_sells_layout'];
		
		switch($theme){
		
			case 'theme-list-view':
				$html = cdxfreewooup_theme_list_view();
			break;	
			case 'theme-hover':
				$html = cdxfreewooup_theme_hover();
			break;
			default:
				$html = cdxfreewooup_theme_list_view();
			break;	
			
		}
		echo $html;
		//Widget content End
		
		if(isset($after_widgets)){echo $after_widgets;}
		
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// Output admin widget options form
		
		if ( isset( $instance[ 'codextent_woo_up_sells_title' ] ) ) { $codextent_woo_up_sells_title = $instance[ 'codextent_woo_up_sells_title' ]; } 
		else { $codextent_woo_up_sells_title = __( '', 'codextent-woo-up-sells' ); }
		
		
		if ( isset( $instance[ 'codextent_woo_up_sells_layout' ] ) ) { $codextent_woo_up_sells_layout = $instance[ 'codextent_woo_up_sells_layout' ]; } 
		else { $codextent_woo_up_sells_layout = __( 'theme-list-view', 'codextent-woo-up-sells' ); }
		?>
        
		<p>
		<label for="<?php echo $this->get_field_id( 'codextent_woo_up_sells_title' ); ?>"><?php _e( 'Title' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'codextent_woo_up_sells_title' ); ?>" 
        name="<?php echo $this->get_field_name( 'codextent_woo_up_sells_title' ); ?>" type="text" value="<?php echo esc_attr( $codextent_woo_up_sells_title ); ?>">
		</p>
                
        <p>
		<label for="<?php echo $this->get_field_id( 'codextent_woo_up_sells_layout' ); ?>"><?php _e( 'Layout' ); ?></label> 
		
        <select class="widefat" id="<?php echo $this->get_field_id( 'codextent_woo_up_sells_layout' ); ?>" name="<?php echo $this->get_field_name( 'codextent_woo_up_sells_layout' ); ?>" >
        	<option <?php if($codextent_woo_up_sells_layout=='theme-list-view'){echo 'selected="selected"';} ?> value="theme-list-view">Theme - List View</option>
            <option <?php if($codextent_woo_up_sells_layout=='theme-hover'){echo 'selected="selected"';} ?> value="theme-hover">Theme - Hover</option>
        </select>
        
		</p>
		<?php 
		
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		// Save widget options
		
		$instance = array();
		$instance['codextent_woo_up_sells_title'] = ( ! empty( $new_instance['codextent_woo_up_sells_title'] ) ) ? 
		strip_tags( $new_instance['codextent_woo_up_sells_title'] ) : '';
		
		$instance['codextent_woo_up_sells_layout'] = ( ! empty( $new_instance['codextent_woo_up_sells_layout'] ) ) ? 
		strip_tags( $new_instance['codextent_woo_up_sells_layout'] ) : '';
		
		return $instance;
	}
	
}

function codextent_woo_up_sells() {
	register_widget( 'CodextentWooUpSells' );
}

add_action( 'widgets_init', 'codextent_woo_up_sells' );