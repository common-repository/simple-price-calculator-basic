<?php

/*

Plugin Name: Simple Price Calculator(Basic)
Plugin URI: http://www.premiumbizthemes.com
Description: Simple Price Calculator is a WordPress plugin that allows you to create your own price calculation form. You can use it to provide instant price quotes or estimates on products and services for your visitors
Version: 1.3
Author: Premiumbizthemes
Author URI: http://www.premiumbizthemes.com

*/

class SimplePriceCalculator
{ 				
	public function __construct() {			
			
		//Admin functions

		$this->spc_admin();
			
		add_action('init', array($this,'register_scripts'));	
		add_shortcode('spc-form',array($this,'spc_shortcode'));		
							
	}

	private function spc_admin(){
	if(is_admin())
		require_once('spcadmin.php');
	}

	//Simple Price Calculator Shortcode for displaying form on frontend

	function spc_shortcode ($atts) {
		
		extract(shortcode_atts( array (

		'id' => '',		

		),$atts));

		
		$currpost= get_post($id);		
							
		wp_enqueue_script('spc-number');
		wp_enqueue_script('spc-pricecalc');
		wp_enqueue_style('spc-css');		
		$spccontent = '
		<script>
		jQuery(document).ready( function ($) {
			$("#spcquoteform").SimplePriceCalc();
		});
		</script>
		';				
		
		$spccontent .= '<form id="spcquoteform">';   		
			
		$spccontent .= $currpost->post_content;				
	    
		$spccontent .= '</form> ';		
		
		return $spccontent;
		
	}
	
	function register_scripts() {
		wp_register_script('spc-number',plugins_url('jquery.number.min.js',__FILE__));
		wp_register_script('spc-pricecalc',plugins_url('simplepricecalc.min.js',__FILE__));
		wp_register_style('spc-css',plugins_url('spcstyle.css',__FILE__));
	}
	

 }  // End of Simple Price Calculator class

$simplepricecalc = new SimplePriceCalculator; 
 
 
?>