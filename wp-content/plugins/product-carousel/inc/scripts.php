<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Scripts and styles
 */
class WPE_Product_Carousel_Scripts{

	/**
	 * Script version number
	 */
	protected $version;

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$this->version = '20170411';

		add_action( 'wp_enqueue_scripts', array( $this, 'wpe_product_carousel_front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpe_product_carousel_admin_scripts' ) );
	}

	/**
	 * Front Scripts
	 */
	public function wpe_product_carousel_front_scripts() {
		// CSS Files
		wp_enqueue_style( 'slick', WPE_PRODUCT_CAROUSEL_URL . 'assets/css/slick.css', false, $this->version );
		wp_enqueue_style( 'font-awesome', WPE_PRODUCT_CAROUSEL_URL . 'assets/css/font-awesome.min.css', false, $this->version );
		wp_enqueue_style( 'product-carousel-style', WPE_PRODUCT_CAROUSEL_URL . 'assets/css/style.css', false, $this->version );

		//JS Files
		wp_enqueue_script( 'slick-min-js', WPE_PRODUCT_CAROUSEL_URL . 'assets/js/slick.min.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Admin Scripts
	 */
	public function wpe_product_carousel_admin_scripts() {
		// CSS Files
		wp_enqueue_style( 'product-carousel-meta-box', WPE_PRODUCT_CAROUSEL_URL . 'inc/admin/meta-box/assets/css/meta-box.css', false, $this->version );
		wp_enqueue_style( 'wp-color-picker', false, $this->version );

		//JS Files
		wp_enqueue_script( 'wp-color-picker', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'product-carousel-meta-box-js', WPE_PRODUCT_CAROUSEL_URL . 'inc/admin/meta-box/assets/js/meta-box.js', array( 'jquery' ), $this->version, false );
	}

}
new WPE_Product_Carousel_Scripts();