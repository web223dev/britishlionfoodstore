<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * MetaBox Class
 */
class WPE_Product_Carousel_MetaBox {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Add MetaBox
	 */
	function add_meta_boxes() {
		add_meta_box( 'product_carousel_shortcode_meta', esc_html__( 'Shortcode Generator', 'product-carousel' ), 'wpe_product_carousel_meta_callback', 'wpe_product_carousel', 'normal' );
	}


	/**
	 * Add MetaBox Save
	 */
	function save_post( $post_id ) {
		if ( ! isset( $_POST['product_carousel_metabox_nonce'] ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( 'wpe_product_carousel' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$mymeta = array( 'total_products', 'display_products', 'auto_play', 'pagination', 'navigation', 'nav_color', 'nav_bg', 'nav_hover_color', 'nav_hover_bg', 'pagination_color', 'pagination_active_color', 'add_to_card_color', 'add_to_card_hover_color', 'add_to_card_border', 'add_to_card_hover_bg', 'add_to_card_hide' );

		foreach ( $mymeta as $keys ) {

			if ( is_array( $_POST[ $keys ] ) ) {
				$data = array();

				foreach ( $_POST[ $keys ] as $key => $value ) {
					$data[] = $value;
				}
			} else {
				$data = sanitize_text_field( $_POST[ $keys ] );
			}

			update_post_meta( $post_id, $keys, $data );
		}

	}

}

new WPE_Product_Carousel_MetaBox();


/**
 * Product Carousel callback
 */
function wpe_product_carousel_meta_callback( $shortcode_id ) {

	wp_nonce_field( 'product_carousel_metabox', 'product_carousel_metabox_nonce' ); ?>

    <div id="wpe-product-carousel-shortcode">

        <div class="nav-tab-wrapper">
            <a class="nav-tab" href="javascript:;"><?php esc_html_e('General Settings', 'product-carousel') ?></a>
            <a class="nav-tab" href="javascript:;"><?php esc_html_e('Carousel Settings', 'product-carousel') ?></a>
            <a class="nav-tab" href="javascript:;"><?php esc_html_e('Stylization', 'product-carousel') ?></a>
        </div>

        <div id="wpe-product-carousel-meta-content">
            <div class="inside hidden">
                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="total_products"><?php esc_html_e( 'Total Products', 'product-carousel' ) ?></label>
                    </div>
					<?php
					$default_value  = '20';
					$total_products = get_post_meta( $shortcode_id->ID, 'total_products', true );
					$total_products = $total_products ? $total_products : $default_value;
					?>
                    <div class="wpe-meta-td">
                        <input type="number" name="total_products" id="total_products" class="total_products" value="<?php echo esc_html( $total_products ) ?>"/>
                        <p class="wpe-meta-des"><?php esc_html_e('Number of products to be shown', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="add_to_card_hide"><?php esc_html_e( 'Add to card Button', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = 'true';
		            $add_to_card_hide     = get_post_meta( $shortcode_id->ID, 'add_to_card_hide', true );
		            $add_to_card_hide     = $add_to_card_hide ? $add_to_card_hide : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <ul>
                            <li><input type="radio" name="add_to_card_hide" value="true" <?php checked( $add_to_card_hide, 'true' ) ?>><?php esc_html_e('Show', 'product-carousel') ?></li>
                            <li><input type="radio" name="add_to_card_hide" value="false" <?php checked( $add_to_card_hide, 'false' ) ?>><?php esc_html_e('Hide', 'product-carousel') ?></li>
                        </ul>
                        <p class="wpe-meta-des"><?php esc_html_e('Show or Hide add to card button', 'product-carousel') ?></p>
                    </div>
                </div>

            </div>
            <div class="inside hidden">
                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="display_products"><?php esc_html_e( 'Display Products', 'product-carousel' ) ?></label>
                    </div>
					<?php
					$default_value    = '4';
					$display_products = get_post_meta( $shortcode_id->ID, 'display_products', true );
					$display_products = $display_products ? $display_products : $default_value;
					?>
                    <div class="wpe-meta-td">
                        <input type="number" name="display_products" id="display_products" class="display_products" value="<?php echo esc_html( $display_products ) ?>"/>
                        <p class="wpe-meta-des"><?php esc_html_e('Number of display products', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="auto_play"><?php esc_html_e( 'Auto Play', 'product-carousel' ) ?></label>
                    </div>
					<?php
					$default_value = 'true';
					$auto_play     = get_post_meta( $shortcode_id->ID, 'auto_play', true );
					$auto_play     = $auto_play ? $auto_play : $default_value;
					?>
                    <div class="wpe-meta-td">
                        <ul>
                            <li><input type="radio" name="auto_play" value="true" <?php checked( $auto_play, 'true' ) ?>><?php esc_html_e('Yes', 'product-carousel') ?></li>
                            <li><input type="radio" name="auto_play" value="false" <?php checked( $auto_play, 'false' ) ?>><?php esc_html_e('No', 'product-carousel') ?></li>
                        </ul>
                        <p class="wpe-meta-des"><?php esc_html_e('Number of display products', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="pagination"><?php esc_html_e( 'Pagination', 'product-carousel' ) ?></label>
                    </div>
					<?php
					$default_value = 'false';
					$pagination     = get_post_meta( $shortcode_id->ID, 'pagination', true );
					$pagination     = $pagination ? $pagination : $default_value;
					?>
                    <div class="wpe-meta-td">
                        <ul>
                            <li><input type="radio" name="pagination" value="true" <?php checked( $pagination, 'true' ) ?>><?php esc_html_e('Yes', 'product-carousel') ?></li>
                            <li><input type="radio" name="pagination" value="false" <?php checked( $pagination, 'false' ) ?>><?php esc_html_e('No', 'product-carousel') ?></li>
                        </ul>
                        <p class="wpe-meta-des"><?php esc_html_e('Pagination option on/off', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="navigation"><?php esc_html_e( 'Navigation', 'product-carousel' ) ?></label>
                    </div>
					<?php
					$default_value = 'true';
					$navigation     = get_post_meta( $shortcode_id->ID, 'navigation', true );
					$navigation     = $navigation ? $navigation : $default_value;
					?>
                    <div class="wpe-meta-td">
                        <ul>
                            <li><input type="radio" name="navigation" value="true" <?php checked( $navigation, 'true' ) ?>><?php esc_html_e('Yes', 'product-carousel') ?></li>
                            <li><input type="radio" name="navigation" value="false" <?php checked( $navigation, 'false' ) ?>><?php esc_html_e('No', 'product-carousel') ?></li>
                        </ul>
                        <p class="wpe-meta-des"><?php esc_html_e('Navigation option on/off', 'product-carousel') ?></p>
                    </div>
                </div>

            </div>
            <div class="inside hidden">

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="nav_color"><?php esc_html_e( 'Nav Color', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#333333';
		            $nav_color     = get_post_meta( $shortcode_id->ID, 'nav_color', true );
		            $nav_color     = $nav_color ? $nav_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="nav_color" id="nav_color" value="<?php echo esc_html($nav_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set navigation color', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="nav_bg"><?php esc_html_e( 'Nav Background', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#f3f3f3';
		            $nav_bg     = get_post_meta( $shortcode_id->ID, 'nav_bg', true );
		            $nav_bg     = $nav_bg ? $nav_bg : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="nav_bg" id="nav_bg" value="<?php echo esc_html($nav_bg) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set navigation background color', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="nav_hover_color"><?php esc_html_e( 'Nav Hover Color', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#ffffff';
		            $nav_hover_color     = get_post_meta( $shortcode_id->ID, 'nav_hover_color', true );
		            $nav_hover_color     = $nav_hover_color ? $nav_hover_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="nav_hover_color" id="nav_hover_color" value="<?php echo esc_html($nav_hover_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set navigation hover color', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="nav_hover_bg"><?php esc_html_e( 'Nav Hover Background', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#444444';
		            $nav_hover_bg     = get_post_meta( $shortcode_id->ID, 'nav_hover_bg', true );
		            $nav_hover_bg     = $nav_hover_bg ? $nav_hover_bg : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="nav_hover_bg" id="nav_hover_bg" value="<?php echo esc_html($nav_hover_bg) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set navigation hover background color', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="pagination_color"><?php esc_html_e( 'Pagination Color', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#cccccc';
		            $pagination_color     = get_post_meta( $shortcode_id->ID, 'pagination_color', true );
		            $pagination_color     = $pagination_color ? $pagination_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="pagination_color" id="pagination_color" value="<?php echo esc_html($pagination_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set pagination color', 'product-carousel') ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="pagination_active_color"><?php esc_html_e( 'Pagination Active Color', 'product-carousel' ) ?></label>
                    </div>
		            <?php
		            $default_value = '#444444';
		            $pagination_active_color     = get_post_meta( $shortcode_id->ID, 'pagination_active_color', true );
		            $pagination_active_color     = $pagination_active_color ? $pagination_active_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="pagination_active_color" id="pagination_active_color" value="<?php echo esc_html($pagination_active_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set pagination active color', 'product-carousel')
                            ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="add_to_card_color"><?php esc_html_e( 'Add to card Color', 'product-carousel' )
                            ?></label>
                    </div>
		            <?php
		            $default_value = '#333333';
		            $add_to_card_color     = get_post_meta( $shortcode_id->ID, 'add_to_card_color', true );
		            $add_to_card_color     = $add_to_card_color ? $add_to_card_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="add_to_card_color" id="add_to_card_color" value="<?php echo esc_html($add_to_card_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set add to card button color', 'product-carousel')
                            ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="add_to_card_border"><?php esc_html_e( 'Add to card border Color', 'product-carousel' )
                            ?></label>
                    </div>
		            <?php
		            $default_value = '#333333';
		            $add_to_card_border     = get_post_meta( $shortcode_id->ID, 'add_to_card_border', true );
		            $add_to_card_border     = $add_to_card_border ? $add_to_card_border : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="add_to_card_border" id="add_to_card_border" value="<?php echo esc_html($add_to_card_border) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set add to card button border color', 'product-carousel')
                            ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="add_to_card_hover_color"><?php esc_html_e( 'Add to card Hover Color', 'product-carousel' )
                            ?></label>
                    </div>
		            <?php
		            $default_value = '#ffffff';
		            $add_to_card_hover_color     = get_post_meta( $shortcode_id->ID, 'add_to_card_hover_color', true );
		            $add_to_card_hover_color     = $add_to_card_hover_color ? $add_to_card_hover_color : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="add_to_card_hover_color" id="add_to_card_hover_color" value="<?php echo esc_html($add_to_card_hover_color) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set add to card button hover color', 'product-carousel')
                            ?></p>
                    </div>
                </div>

                <div class="wpe-single-meta">
                    <div class="wpe-meta-th">
                        <label for="add_to_card_hover_bg"><?php esc_html_e( 'Add to card hover BG', 'product-carousel' )
                            ?></label>
                    </div>
		            <?php
		            $default_value = '#444444';
		            $add_to_card_hover_bg     = get_post_meta( $shortcode_id->ID, 'add_to_card_hover_bg', true );
		            $add_to_card_hover_bg     = $add_to_card_hover_bg ? $add_to_card_hover_bg : $default_value;
		            ?>
                    <div class="wpe-meta-td">
                        <input type="text" class="wpe-color-picker-field" name="add_to_card_hover_bg" id="add_to_card_hover_bg" value="<?php echo esc_html($add_to_card_hover_bg) ?>">
                        <p class="wpe-meta-des"><?php esc_html_e('Set add to card button hover background color', 'product-carousel')
                            ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php }