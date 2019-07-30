<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once(CED_PAS_PATH.'/Apis/tmhOAuth.php');
class ced_pas_share_tweet {
	/**
	  * This function is for sharing on Twiiter.
	  * @name ced_pas_tweet($link,$prodid)
	  * @param1 product link 
	  * @param2 productid
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_tweet($link,$prodid){
		$app_id= get_option('wsp_twitter_settings')['twitter_app_id'];		
		if($app_id == ""){
			return;
		}
		$settings = array(
				'consumer_key' => get_option('wsp_twitter_settings')['twitter_app_id'],
				'consumer_secret' => get_option('wsp_twitter_settings')['twitter_app_sec'],
				'user_token' => get_option('wsp_twitter_settings')['twitter_access'],
				'user_secret' => get_option('wsp_twitter_settings')['twitter_access_sec'],
				'curl_ssl_verifypeer'   => false
		);
		$twit = new tmhOAuth($settings);
		$imgurl = wp_get_attachment_url( get_post_thumbnail_id($prodid) );
		$postfields = array();
		
		$msg = get_the_title($prodid);
		//$msg .= "\n".get_option('ced_pas_custom_msg');
		$msg .= "\n".get_post_meta($prodid,'ced_pas_custom_msg',true);
		if($imgurl != ""){
			$postfields = array(
					'status' => $msg."\n".$link,
					'media[]'  => file_get_contents($imgurl)
						
			);
		}
		$tmh = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
        try{
		$response=$twit->request('POST',$tmh,$postfields,true,true);
		if($response == 200){
			update_post_meta($prodid,'tweet_postid',$response);
			if(get_option("tweet_error") != ""){
				delete_option("tweet_error");
			}
			
		}
		elseif($imgurl == ""){
				update_option('tweet_error', "You did't provide image of product to tweet please set featured image");
				return false;	
		}
			else{
				update_option('tweet_error', "Some error occured with tweet please verify your Api keys.");
				return  false;
			}
		
		return true;
        }catch(Exception $e){
            update_option('tweet_error', "Some error occured with tweet please verify your Api keys.");
            return  false;
        }
	}
	
}
