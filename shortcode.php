<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CodextentWooUpSellsShortcode {
	
	static $add_script;
	var $wpvepdf_attr = false;

	static function init() {
		
		add_filter('widget_text', 'do_shortcode');
		add_shortcode('cdxwoous', array(new CodextentWooUpSellsShortcode(), 'cdxfreewooup_shortcode_fun'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'register_script_style'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'print_style'));
		
	}

	public function cdxfreewooup_shortcode_fun($attr) {
						
		$product_id = (isset($attr['pid']))?(int)$attr['pid']:0;
		$theme      = (isset($attr['theme']))?$attr['theme']:'theme-list-view';
		
		//initial value
		$products = false;
		
		//If product ID supplied
		if($product_id!='0'){ $products = cdxfreewooup_get_up_sell_products($product_id);}
		
		switch($theme){
		
			case 'theme-list-view':
				$html     = cdxfreewooup_theme_list_view($products);
			break;	
			case 'theme-hover':
				$html 	= cdxfreewooup_theme_hover($products);
			break;
			default:
				$html    = cdxfreewooup_theme_list_view($products);
			break;	
			
		}
			
		return $html;
						
	}

	static function register_script_style() {
		wp_register_style('cdx-html-lib',  CE_CDXWOOFREEUP_PLUGIN_URL. '/assets/css/ce.css');
		wp_register_style('cdxup-front',  CE_CDXWOOFREEUP_PLUGIN_URL. '/assets/css/codextent-woo-up-sell.css');
	}

	static function print_style(){
		
		wp_enqueue_style('cdx-html-lib');	
		wp_enqueue_style('cdxup-front');	
	}
	
}

CodextentWooUpSellsShortcode::init();
?>