<?php

/**
 * Functions
 */
class WPE_Product_Carousel_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'wpe_product_carousel_load_text_domain' ) );
		add_action( 'init', array( $this, 'wpe_product_carousel_shortcode_generator' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Load Text Domain
	 */
	public function wpe_product_carousel_load_text_domain() {
		load_plugin_textdomain( 'product-carousel', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * ShortCode Generator
	 */
	public function wpe_product_carousel_shortcode_generator() {

		register_post_type( 'wpe_product_carousel', array(
			'label'           => esc_html__( 'Shortcode Generator', 'product-carousel' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'hierarchical'    => false,
			'query_var'       => false,
			'supports'        => array( 'title' ),
			'menu_icon'       => WPE_PRODUCT_CAROUSEL_URL . 'assets/images/icon.png',
			'capability_type' => 'post',
			'labels'          => array(
				'name'               => esc_html__( 'Shortcode Generator', 'product-carousel' ),
				'singular_name'      => esc_html__( 'Generate Shortcode', 'product-carousel' ),
				'menu_name'          => esc_html__( 'Product Carousel', 'product-carousel' ),
				'add_new'            => esc_html__( 'Add New Shortcode', 'product-carousel' ),
				'add_new_item'       => esc_html__( 'Generate New Shortcode', 'product-carousel' ),
				'edit'               => esc_html__( 'Edit', 'product-carousel' ),
				'edit_item'          => esc_html__( 'Edit Shortcode', 'product-carousel' ),
				'new_item'           => esc_html__( 'New Shortcode', 'product-carousel' ),
				'view'               => esc_html__( 'View Shortcode', 'product-carousel' ),
				'view_item'          => esc_html__( 'View Shortcode', 'product-carousel' ),
				'search_items'       => esc_html__( 'Search Shortcode', 'product-carousel' ),
				'not_found'          => esc_html__( 'No Shortcode Found', 'product-carousel' ),
				'not_found_in_trash' => esc_html__( 'No Shortcode Found in Trash', 'product-carousel' ),
				'parent'             => esc_html__( 'Parent Shortcode', 'product-carousel' ),
			),
		) );
	}

	/**
	 * Admin Menu
	 */
	function admin_menu() {
		add_submenu_page( 'edit.php?post_type=wpe_product_carousel', esc_html__( 'Support', 'product-carousel' ), esc_html__( 'Support', 'product-carousel' ), 'manage_options', 'wpe_support_page', 'wpe_support_page_callback' );
	}


}

new WPE_Product_Carousel_Functions();

// Add the Shortcode
function wpe_product_carousel_shortcode_metaboxes() {
	add_meta_box( 'wpe_product_carousel_shortcode_content_metabox', 'Shortcode', 'wpe_product_carousel_shortcode_content_metabox', 'wpe_product_carousel', 'side',
		'default' );
}

add_action( 'add_meta_boxes', 'wpe_product_carousel_shortcode_metaboxes' );

// The Shortcode
function wpe_product_carousel_shortcode_content_metabox() {
	global $post;
	echo '<p>Copy and paste this shortcode into your post, page or custom post editor:</p>';
	echo '<input type="text" name="sp-wpsp-shortcode" id="sp-wpsp-shortcode" value="[product-carousel id=&#x00022;' . $post->ID . '&#x00022;]" class="the-shortcode" size="30" readonly="readonly" onClick="this.select();" style="width: 100%;"/>';
	echo '<p>Copy and paste this code in your template files:</p>';
	echo '<input type="text" name="sp-wpsp-shortcode" id="sp-wpsp-shortcode" value="<?php  echo do_shortcode(\'[product-carousel id=&#x00022;' . $post->ID . '&#x00022; ]\'); ?>" class="the-shortcode" size="30" readonly="readonly" onClick="this.select();" style="width: 100%;"/>';
}

/**
 * Support page callback
 */
function wpe_support_page_callback(){ ?>
	<div class="wrap wpe-support-page">
		<h2>Support</h2>
		<div class="postbox">
			<div class="wpe-support-section">
                <h3>Video Tutorial</h3>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/5rJiy4TttpU" frameborder="0" allowfullscreen></iframe>
            </div>
			<div class="wpe-support-section">
                <h3>Support</h3>
                <p>If you need any help regarding the plugin, please don't hesitate to <a href="mailto:info@wpexpand.com?Subject=Product%20Carousel">contact us</a>.</p>
            </div>
            <div class="wpe-support-section">
                <h3>Submit a Review</h3>
                <p>If you like this plugin, please <a href="https://wordpress.org/support/plugin/product-carousel/reviews/#new-post" target="_blank">give us 5 <i class="dashicons-before dashicons-star-filled"></i><i class="dashicons-before dashicons-star-filled"></i><i class="dashicons-before dashicons-star-filled"></i><i class="dashicons-before dashicons-star-filled"></i><i class="dashicons-before dashicons-star-filled"></i> star</a> to encourage for future improvement of this plugin. If you face any issue with the plugin, please <a href="mailto:info@wpexpand.com?Subject=Product%20Carousel">let us know</a> before leaving a review. We'll try definitely to help you.</p>
            </div>
		</div>
	</div>
<?php }

/**
 * ShortCode Column
 */
function wpe_pc_add_shortcode_column( $columns ) {
	return array_merge( $columns,
		array( 'shortcode' => esc_html__( 'Shortcode', 'product-carousel' ) ) );
}
add_filter( 'manage_wpe_product_carousel_posts_columns' , 'wpe_pc_add_shortcode_column' );

/**
 * ShortCode Column Form
 */
function wpe_pc_add_shortcode_form( $column, $post_id ) {
	if ($column == 'shortcode'){
		?>
        <input style="width: 270px;padding: 6px;" type="text" onClick="this.select();" readonly="readonly" value="[product-carousel <?php echo 'id=&quot;'.$post_id .'&quot;';?>]" />
		<?php
	}
}
add_action( 'manage_wpe_product_carousel_posts_custom_column' , 'wpe_pc_add_shortcode_form', 10, 2 );

/**
 * Include files
 */
require_once( WPE_PRODUCT_CAROUSEL_DIR . 'inc/scripts.php' );
require_once( WPE_PRODUCT_CAROUSEL_DIR . 'inc/shortcodes.php' );
require_once( WPE_PRODUCT_CAROUSEL_DIR . 'inc/admin/meta-box/meta-box.php' );