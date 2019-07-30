<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( "ced_pas_msg_metabox" ) ) {
	class ced_pas_msg_metabox {
		function add_meta_box() {	
			$nonce = wp_create_nonce("ced_pas");
			$ced_pas_script = array(
					'ajaxurl' => admin_url( 'admin-ajax.php'),
					'nonce' => $nonce
			);
			wp_localize_script( 'task', 'task', $ced_pas_script );
			$prevmsg = "";
			global $post;
			$post_id = $post->ID;
			if(!empty(get_post_meta($post_id,'ced_pas_custom_msg',true))){
				$prevmsg=get_post_meta($post_id,'ced_pas_custom_msg',true);
			}
			global $wpdb;
			$meta_id='';
			$results = $wpdb->get_results( "SELECT meta_id FROM ".$wpdb->prefix."postmeta WHERE post_id = $post_id AND meta_key='ced_pas_custom_msg'", OBJECT );
			if(!empty($results)){
				$meta_id=$results[0]->meta_id;
			}
			$html = "<div id='ced_saveResult'></div>";
			$html .= "<b>Enter Message To Auto Share With Product Url</b>";
			$html .= "<textarea id='ced_pas_text' name='ced_pas_msg'>".$prevmsg."</textarea>";
		    $html .= "<input id='ced_pasmsg' class ='button' type ='button' data-meta_id='".$meta_id."' data-post_id='".$post_id."' value='Save Message'>";
		    $html .= "<p>Post Format : <code>#title#msg#link</code></p>";
			echo  $html;
		}
	}
}