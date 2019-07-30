<?php

// Product Carousel ShortCode
function wpe_product_carousel_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '',
	), $atts, 'product-carousel' ) );

	$post_id                 = $atts['id'];
	$total_products          = get_post_meta( $post_id, 'total_products', true );
	$display_products        = get_post_meta( $post_id, 'display_products', true );
	$auto_play               = get_post_meta( $post_id, 'auto_play', true );
	$pagination              = get_post_meta( $post_id, 'pagination', true );
	$navigation              = get_post_meta( $post_id, 'navigation', true );
	$nav_color               = get_post_meta( $post_id, 'nav_color', true );
	$nav_bg                  = get_post_meta( $post_id, 'nav_bg', true );
	$nav_hover_color         = get_post_meta( $post_id, 'nav_hover_color', true );
	$nav_hover_bg            = get_post_meta( $post_id, 'nav_hover_bg', true );
	$pagination_color        = get_post_meta( $post_id, 'pagination_color', true );
	$pagination_active_color = get_post_meta( $post_id, 'pagination_active_color', true );
	$add_to_card_color       = get_post_meta( $post_id, 'add_to_card_color', true );
	$add_to_card_hover_color = get_post_meta( $post_id, 'add_to_card_hover_color', true );
	$add_to_card_border      = get_post_meta( $post_id, 'add_to_card_border', true );
	$add_to_card_hover_bg    = get_post_meta( $post_id, 'add_to_card_hover_bg', true );
	$add_to_card_hide        = get_post_meta( $post_id, 'add_to_card_hide', true );

	$que = new WP_Query(
		array(
			'posts_per_page' => $total_products,
			'post_type'      => 'product'
		)
	);

	$outline = '';

	$outline .= '
	    <script type="text/javascript">
	            jQuery(document).ready(function() {
				jQuery("#wpe-product-carousel' . $post_id . ' .wpe-product-carousel-area").slick({
			        infinite: true,
			        dots: ' . $pagination . ',
			        slidesToShow: ' . $display_products . ',
			        slidesToScroll: 1,
			        autoplay: ' . $auto_play . ',
		            arrows: ' . $navigation . ',
		            prevArrow: "<div class=\'slick-prev\'><i class=\'fa fa-angle-left\'></i></div>",
	                nextArrow: "<div class=\'slick-next\'><i class=\'fa fa-angle-right\'></i></div>",
		            responsive: [
						    {
						      breakpoint: 1000,
						      settings: {
						        slidesToShow: 3
						      }
						    },
						    {
						      breakpoint: 700,
						      settings: {
						        slidesToShow: 2
						      }
						    },
						    {
						      breakpoint: 460,
						      settings: {
						        slidesToShow: 1
						      }
						    }
						  ]
		        });

		    });
	    </script>';

	$outline .= '<style>
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section .slick-arrow{
			color: ' . $nav_color . ';
			background-color: ' . $nav_bg . ';
		}
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section .slick-arrow:hover{
			color: ' . $nav_hover_color . ';
			background-color: ' . $nav_hover_bg . ';
		}
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section ul.slick-dots li button{
			background-color: ' . $pagination_color . ';
		}
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section ul.slick-dots li.slick-active button{
			background-color: ' . $pagination_active_color . ';
		}
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section .wpe-pc-cart-button a.button{
			color: ' . $add_to_card_color . ';
			border-color: ' . $add_to_card_border . ';
		}
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section .wpe-pc-cart-button a.button:hover,
		#wpe-product-carousel' . $post_id . '.wpe-product-carousel-section .wpe-pc-cart-button a.added_to_cart{
			color: ' . $add_to_card_hover_color . ';
			background-color: ' . $add_to_card_hover_bg . ';
			border-color: ' . $add_to_card_hover_bg . ';
		}
	</style>';

	$outline .= '<div id="wpe-product-carousel' . $post_id . '" class="wpe-product-carousel-section">';
	$outline .= '<div class="wpe-product-carousel-area">';
	while ( $que->have_posts() ) : $que->the_post();
		global $product;

		$outline .= '<div class="wpe-product-carousel">';
		$outline .= '<a href="' . esc_url( get_the_permalink() ) . '" class="wpe-pc-product-image">';
		if ( has_post_thumbnail( $que->post->ID ) ) {
			$outline .= get_the_post_thumbnail( $que->post->ID, 'shop_catalog_image_size', array( 'class' => "wpe-pc-product-img" ) );
		}
		$outline .= '</a>';

		$outline .= '<div class="wpe-pc-product-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></div>';

		if ( class_exists( 'WooCommerce' ) ) {
			$average = $product->get_average_rating();
			if ( $average > 0 ) {
				$outline .= '<div class="star-rating" title="' . esc_html__( 'Rated', 'product-carousel' ) . ' ' . $average . '' . esc_html__( ' out of 5', 'product-carousel' ) . '"><span style="width:' . ( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">' . $average . '</strong> ' . esc_html__( 'out of 5', 'product-carousel' ) . '</span></div>';
			}
		}

		if ( class_exists( 'WooCommerce' ) && $price_html = $product->get_price_html() ) {
			$outline .= '<div class="wpe-pc-product-price">' . $price_html . '</div>';
		};
		if ( $add_to_card_hide == 'true' ) {
			$outline .= '<div class="wpe-pc-cart-button">' . do_shortcode( '[add_to_cart id="' . get_the_ID() . '"]' ) . '</div>';
		}
		$outline .= '</div>';
	endwhile;
	$outline .= '</div>';
	$outline .= '</div>';


	wp_reset_query();

	return $outline;

}

add_shortcode( 'product-carousel', 'wpe_product_carousel_shortcode' );