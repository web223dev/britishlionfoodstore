<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the cart list product item quantity.
 *
 * This template can be overridden by copying it to yourtheme/woo-floating-cart/parts/cart/list/product/quantity.php.
 *
 * Available global vars: $product, $cart_item, $cart_item_key
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @link       http://xplodedthemes.com
 * @since      1.0.8
 *
 * @package    XT_Woo_Floating_Cart
 * @subpackage XT_Woo_Floating_Cart/public/templates/parts
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 
?>

<span class="xt_woofc-quantity">

	<?php
	if ( $product->is_sold_individually() ) {

		echo sprintf( '<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
		
	} else {

	    $min_value = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
	    $max_value = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );

		$min_value = ($min_value < 0) ? 0 : $min_value;
		$max_value = ($max_value < 0) ? 99999 : $max_value;

		echo sprintf( 
			'<input type="number" name="cart[%s][qty]" value="%s" step="1" min="%s" max="%s" />',
			$cart_item_key, 
			xt_woofc_item_qty($cart_item, $cart_item_key),
			$min_value,
			$max_value
		);
		
		echo '	
	  	<span class="xt_woofc-quantity-changer">
	    	<span class="xt_woofc-quantity-button xt_woofc-quantity-up"><i class="xt_woofcicon-flat-plus"></i></span>
			<span class="xt_woofc-quantity-button xt_woofc-quantity-down"><i class="xt_woofcicon-flat-minus"></i></span>  
		</span>';
	
	}
	?>

</span>
