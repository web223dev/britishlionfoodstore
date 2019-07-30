<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once(  'share/class-pas-share-fb.php' );
require_once('share/class-pas-share-tweet.php' );

global $current_user;
class ced_pas_bulkshare {
	
	/**
	 * This function is construct function for class.
	 * @name __construct()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function __construct(){
		add_filter( 'manage_edit-product_columns', array($this, PAS_PREFIX.'custom_product_column'),10);
		add_action( 'manage_product_posts_custom_column', array($this, PAS_PREFIX.'manage_custom_product_column'));
		add_action( 'admin_footer-edit.php',  array($this,'custom_bulk_admin_footer'));
		add_action( 'load-edit.php', array( $this, PAS_PREFIX.'custom_bulk_action' ) );
		add_action( 'admin_notices', array($this,PAS_PREFIX.'bulk_admin_notices'));
    	add_filter( 'bulk_actions-edit-product', array( $this, 'pas_custom_bulk_actions' ) );
	}

	function pas_custom_bulk_actions( $actions ) {
		if ( isset( $_GET[ 'ced_pas' ] ) and $_GET[ 'ced_pas' ] == 'true' ) {
			$res=get_option( "wsp_gen_settings");
			if(!empty($res['fb'])){
				$actions[ 'fb_share' ] = __( 'Share to Facebook', 'product-auto-share' );
			}
			if(!empty($res['tweet'])){
				$actions[ 'tweet' ] = __( 'Share to Twitter', 'product-auto-share' );
			}
			if(!empty($res['pint'])){
				$actions[ 'ced_pas_pinterest_share' ] = __( 'Share to Pinterest', 'product-auto-share' );
			}
			if(!empty($res['LinkedIn'])){
				$actions[ 'ced_pas_linkedin_share' ] = __( 'Share to Linkedin', 'product-auto-share' );
			}
			if(!empty($res['tumblr'])){
				$actions[ 'ced_pas_tumblr_share' ] = __( 'Share to Tumblr', 'product-auto-share' );
			}
			if(!empty($res['mail'])){
				$actions[ 'ced_pas_mail_share' ] = __( 'Share to Mail', 'product-auto-share' );
			}
		}
		return $actions;
    }
	
	/**
	 * This function is construct custom product coloumn for class.
	 * @name ced_pas_custom_product_column()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_custom_product_column( $columns ) {	
		if( isset( $_REQUEST['ced_pas'] ) && $_REQUEST['ced_pas'] == true ) {
			$new = array(
				'cb' 		=> "<input type='checkbox'>",	
				'name'		=> "Name<input type='hidden' name='ced_pas' value='true'/>",	
				'fb_share'	=> "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/facebook.png>",
				'tweet'		=> "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/twitter.png>",
				'link'  	=>  "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/linkedin.png>",
				'pint'		=> "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/pint.png>",
				'tumblr'   	=> "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/tumblr.png>",
			);
			$new[ 'mail' ] 	= "<img width = 40px src=".plugins_url()."/product-auto-share/assets/images/mail.png>";
		
    		return apply_filters( 'ced_pas_custom_product_column', $new );
		} else {
			return $columns;
		}
		
	}
	
	/**
	 * This function is construct custom product coloumn value for class.
	 * @name ced_pas_custom_product_column()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_manage_custom_product_column($column){
		global $post, $woocommerce, $the_product;
		//$the_product->id != $post->ID 
		//echo $the_product->get_id();
		if ( empty( $the_product ) || $the_product->get_id() != $post->ID )
			$the_product = get_product( $post );
		
		switch ($column) {
			case "woo_name" :
				$link = get_edit_post_link( $post->ID );
				$title = _draft_or_post_title();
				echo '<strong><a class="row-title" href="'.$link.'">' . $title.'</a>';
			case "fb_share" :
				$fb_shared =  get_post_meta( $post->ID, 'fb_postid', true ) ;
				$fb_pageshared = get_post_meta($post->ID, 'fbpage_postid', true);
				if($fb_shared != ""  || $fb_pageshared){
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
				}else{
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
				}
				break;
			case "tweet" :
				$tweet =  get_post_meta( $post->ID, 'tweet_postid', true ) ;
				if($tweet != ""){
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
				}else{
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
				}
				break;
			case "link" :
				$link =  get_post_meta( $post->ID, 'linkedpost_id', true ) ;
				if($link != ""){
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
				}else{
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
				}
				break;
			case "pint" :
				$pint =  get_post_meta( $post->ID, 'pint_id', true ) ;
				if($pint != ""){
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
				}else{
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
				}
				break;
			case "mail" :
				$mail =  get_post_meta( $post->ID, 'ced_pas_mail', true ) ;
				if($mail == 1){
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
				}else{
					echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
				}
				break;
				case "tumblr" :
					$tumblr =  get_post_meta( $post->ID, 'ced_pas_tumblr', true ) ;					
					if($tumblr != ""){
						echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_check.png>";
					}else{
						echo "<img width = 30px src=".plugins_url()."/product-auto-share/assets/images/share_cross.png>";
					}
					break;
			}
		
	}

	/**
	 * This function is for creating custom share bulk action class.
	 * @name custom_bulk_admin_footer()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function custom_bulk_admin_footer() {
		global $post_type;
		if(isset($_REQUEST['ced_pas'])){
			if($post_type == 'product' && $_REQUEST['ced_pas'] == true ) {
				wp_localize_script('task', 'post', array('post_type'=>'product'));
		  	}
		}
		
	}
	
	/**
	 * This function is for creating bulk action class.
	 * @name custom_bulk_admin_footer()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function ced_pas_custom_bulk_action(){	
		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$action = $wp_list_table->current_action();
		if( isset( $_REQUEST['post'] ) ) {
			$post_ids = array_map( 'intval', $_REQUEST['post'] );
		}
		if( empty( $post_ids ) )
			return;

		switch( $action ) {
			case 'ced_pas_linkedin_share':
				$shared = 0;
				$linkshare 	= new ced_pas_link_share();
				foreach( $post_ids as $post_id ) {
					$link = get_permalink( $post_id );
					if ( ! $linkshare->ced_pas_share_on_linkedin( $link, $post_id ) ) {
						if( get_option("linked_settings")['linkedin_apiid'] == "" ||  get_option("linked_settings")['linkedin_appsec'] == "" ) {
							wp_die( __( 'Error: Please check your Linkedin settings and authorize from Linkedin first.' ) );
						} else {
							$error = get_option( 'link_error' );
							wp_die( __( $error ) );
						}
					}
					$shared++;
					sleep(5);
				}
				$callback_url = add_query_arg(
					array(
						'post_type' => 'product',
						'ced_pas' 	=> 'true',
						'share_on' => 'linkedin',
						'shared' 	=> $shared, 
						'ids' 		=> join( ',', $post_ids ),
						'linkedin'	=> 'true' 
					), 
					$callback_url 
				);
		
				break;

			case 'ced_pas_pinterest_share':
				$shared = 0;
				$pinterest 	= new ced_pas_share_pin();
				foreach( $post_ids as $post_id ) {
					$link = get_permalink( $post_id );
					if ( ! $pinterest->ced_pas_pinthis( $link, $post_id ) ) {
						if( get_option( 'sharepro_pin_appid' ) == "" ||  get_option( 'sharepro_pin_appsec' ) == "" ) {
							wp_die( __( 'Error: Please check your pinterest settings and authorize from pinterest first.' ) );
						} else {
							$error = get_option( 'pint_error' );
							wp_die( __( $error ) );
						}
					}
					$shared++;
					sleep(5);
				}
				$callback_url = add_query_arg(
					array(
						'post_type' => 'product',
						'ced_pas' 	=> 'true',
						'share_on' => 'pinterest',
						'shared' 	=> $shared, 
						'ids' 		=> join( ',', $post_ids ),
						'pinterest'	=> 'true' 
					), 
					$callback_url 
				);
		
				break;

			case 'fb_share':
				$shared = 0;
				$fb = new ced_pas_fbshare();
				foreach( $post_ids as $post_id ) {
					$link = get_permalink( $post_id);
					if ( !$fb->ced_pas_shareonfb($link, $post_id ) ) {
						if( get_option( 'sharepro_appid' ) == "" ||  get_option( 'sharepro_appsec' ) == "" ) {
							wp_die( __('Error:Please check your facebook settings And Authorize from Facebook first.') );
						} else {
							$error = get_option('Fb_error');
							wp_die( __($error) );
						}
					}
					$shared++;
					sleep(5);
				}
				$callback_url = add_query_arg( 
					array(
						'post_type' => 'product',
						'share_on' => 'fb',
						'ced_pas' => 'true',
						'shared' => $shared, 
						'ids' => join(',', $post_ids) 
					), 
					$callback_url 
				);
		
				break;
				
			case "tweet" :
				
				$shared = 0;
				$tweet = new ced_pas_share_tweet();
				foreach( $post_ids as $post_id ) {
					$link = get_permalink( $post_id );
					if ( !$tweet->ced_pas_tweet( $link, $post_id ) ) {
						if( get_option( 'wsp_twitter_settings' )['twitter_app_id'] == "" ||  get_option('wsp_twitter_settings')[ 'twitter_app_sec' ] == "" ) {
							wp_die( __( 'Error: Please check your Twitter settings.' ) );
						} else {
							$error = get_option('tweet_error');
							wp_die( __( $error ) );
						}
					}
					$shared++;
					sleep(5);
				}
				$callback_url = add_query_arg( 
					array(
						'post_type' => 'product',
						'share_on' => 'tweet',
						'ced_pas' 	=> 'true',
						'shared' 	=> $shared, 
						'ids' 		=> join(',', $post_ids),
						'tweet'		=>'true' 
					), 
					$callback_url 
				);
				break;
				//Appending Mail feature 

			case 'ced_pas_tumblr_share':
				$shared = 0;
				$tumblrShare = new ced_pas_tumblr();
				foreach( $post_ids as $post_id ) {
					$link = get_permalink( $post_id);
					if ( !$tumblrShare->ced_pas_tumblrshare($link, $post_id ) ) {
						if( get_option('wsp_tumblr_settings')['tumblr_app_id'] == "" ||  get_option('wsp_tumblr_settings')['tumblr_app_sec'] == "" ) {
							wp_die( __('Error:Please check your tumbler settings!') );
						} else {
							$error = get_option('ced_pas_tumblr_error');
							wp_die( __($error) );
						}
					}
					$shared++;
					sleep(5);
				}
				$callback_url = add_query_arg( 
					array(
						'post_type' => 'product',
						'share_on' => 'tumblr',
						'ced_pas' => 'true',
						'shared' => $shared, 
						'ids' => join(',', $post_ids) 
					), 
					$callback_url 
				);
		
				break;
									
			default: 
				return;
		}
		wp_redirect( $callback_url );
		exit();
	}
		
	/**
	 * This function is for creating custom share messages.
	 * @name custom_bulk_admin_footer()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_bulk_admin_notices() {
		//print_r($_REQUEST);
		global $post_type, $pagenow;
		if( $pagenow == 'edit.php' && $post_type == 'product' && isset($_REQUEST['shared']) ) {
			if( isset($_REQUEST['share_on']) && $_REQUEST['share_on']=='tweet' ) {
				$message = sprintf( _n( 'Product shared.', '%s Products shared on Twitter.', $_REQUEST['shared'] ), number_format_i18n( $_REQUEST['shared'] ) );
				echo "<div class='updated'><p>{$message}</p></div>";
			} else if( isset($_REQUEST[ 'share_on' ]) && $_REQUEST['share_on']=='pinterest' ) {
				$message = sprintf( _n( 'Product shared.', '%s Products shared on Pinterest.', $_REQUEST['shared'] ), number_format_i18n( $_REQUEST['shared'] ) );
				echo "<div class='updated'><p>{$message}</p></div>";
			} else if( isset($_REQUEST[ 'share_on' ]) && $_REQUEST['share_on']=='tumblr' ) {
				$message = sprintf( _n( 'Product shared.', '%s Products shared on Tumbler.', $_REQUEST['shared'] ), number_format_i18n( $_REQUEST['shared'] ) );
				echo "<div class='updated'><p>{$message}</p></div>";
			} else if( isset($_REQUEST[ 'share_on' ]) && $_REQUEST['share_on']=='linkedin' ) {
				$message = sprintf( _n( 'Product shared.', '%s Products shared on linkedin.', $_REQUEST['shared'] ), number_format_i18n( $_REQUEST['shared'] ) );
				echo "<div class='updated'><p>{$message}</p></div>";
			} else {
				$message = sprintf( _n( 'Product shared.', '%s Products shared on Facebook.', $_REQUEST['shared'] ), number_format_i18n( $_REQUEST['shared'] ) );
				echo "<div class='updated'><p>{$message}</p></div>";
			}

		}
	}
}