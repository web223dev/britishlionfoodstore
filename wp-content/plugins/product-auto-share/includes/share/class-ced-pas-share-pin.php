<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once( CED_PAS_PATH . '/Apis/pin_it.php' );
class ced_pas_share_pin {
	/**
	  * This function is for sharing on pinterest.
	  * @name ced_pas_pinthis($link, $id)
	  * @param1 productlink 
	  * @param2 productid
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_pinthis( $link, $id ) {
		$app_id = get_option( "sharepro_pin_appid" );
		if( $app_id == "" ) {
			return false;
		}

		$pin 		=  new pin( get_option( "sharepro_pin_appid" ), get_option( "sharepro_pin_appsec" ) );
		$image_url 	= wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		$board_id 	= get_option( 'sharepro_pin_pageid' );
		$note 		= get_option( 'sharepro_pin_shmsg' )."\n".get_post_meta($id,'ced_pas_custom_msg',true);
		$pin->set_token( get_option( "sharepro_pin_acctoken" ) );
		$result 	= $pin->pinit($board_id, $note, $image_url, $link);
        try {
	        $pin->set_token( get_option( "sharepro_pin_acctoken" ) );
			$result 	= $pin->pinit($board_id, $note, $image_url, $link);
			$new_pin 	= json_decode( $result, true);
			if( isset( $new_pin['data']['id'] ) ) {
				update_post_meta( $id, 'pint_id', $new_pin['data']['id'] );	
				if( get_option( "pint_error" ) != "" ) {
					delete_option( "pint_error" );
				}
			} else {
				$error = $new_pin['message'];
				update_option( "pint_error", $error );
				return false;
			}
			return true;
        } catch( Exception $e ) { 
           update_option( "pint_error", $e->getMessage() );
            return false;
        }
	}
}
