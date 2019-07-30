<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//This file is for adding facebook commentbox on product single page.
	if (get_option('sharepro_acctoken')) {
		if(get_option('wsp_gen_settings')['fb_combox'] == "on"){
			add_action('woocommerce_after_single_product_summary','ced_pas_add_fb_commentbox');
	
		}
	}
	
	/**
	  * This is for adding commentbox from frontend.
	  * @name Add_fb_commentbox()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	function ced_pas_add_fb_commentbox() {
			global $post;			
			?>
			<div id="fb-root"></div>
			<div class="fb-comments" data-href="<?php the_permalink() ?>" data-numposts="5"></div>	
			<?php 
			
	}	