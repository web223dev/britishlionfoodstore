<?php
/**
 * Plugin Name: Product Auto Share
 * Plugin URI: http://cedcommerce.com
 * Description: Product Auto Share shares link of new published products on social media like Facebook, Twitter, Linkedin, Pinterest,Tumblr and by mail automatically when you add new product on your shop it automatically post the link on your Facebook, Twitter, Linkedin, Pinterest profile and Tumblr Blog.
 * Version: 1.0.11
 * Author: CedCommerce <plugins@cedcommerce.com>
 * Author URI: http://cedcommerce.com
 * Text Domain: product-auto-share
 * Domain Path: /language/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Check if WooCommerce is active
 **/

$activated = true;
if (function_exists('is_multisite') && is_multisite())
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
	{
		$activated = false;
	}
}
else
{
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
	{
		$activated = false;
	}
}

if($activated)
{
	// woocommerce is actiated
	define('ced_graph_url','https://graph.facebook.com/');
	
	define('CED_PAS_PATH', plugin_dir_path( __FILE__ ));
	define('CED_PAS_URL', plugin_dir_url( __FILE__ ));
	define('PAS_PREFIX', 'ced_pas_');

	require_once( dirname( __FILE__ ) . '/includes/share/class-pas-share-fb.php' );
	require_once( dirname( __FILE__ ) . '/includes/settings.php' );
	require_once ( dirname( __FILE__ ) . '/includes/class-bulkshare.php' );
	include (dirname(__FILE__).'/includes/producttab.php');
	require_once(dirname( __FILE__ ) . '/includes/share/class-ced-pas-share-pin.php');
	require_once(dirname( __FILE__ ) . '/includes/share/class-pas-share-link.php');
	require_once(dirname(__FILE__).'/includes/share/class-pas-share-tweet.php');
	require_once(dirname(__FILE__).'/includes/share/class-ced_pas_mail.php');
	require_once(dirname(__FILE__).'/includes/share/class-ced-pas-tumblr.php');
	require_once(dirname(__FILE__).'/includes/view/ced_pas_msgmetabox.php');
class product_auto_share {
	/**
	  * This function is construct function for class.
	  * @name __construct()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function __construct() {
		add_action( 'init', array( $this,PAS_PREFIX.'addScripts') );
		add_action('transition_post_status', array( $this,PAS_PREFIX.'new_product_share'), 10, 3);
		add_filter('post_updated_messages',array( $this,PAS_PREFIX.'custom_message'),11);
		add_action( 'add_meta_boxes_product',array( $this,PAS_PREFIX.'fb_reviews') );
		
		if(get_option("Fb_error") != "" || get_option("pint_error") != "" || get_option("tweet_error") != "" || get_option("link_error") != "" || get_option("ced_pas_tumblr_error") != ""){
			add_action('admin_notices',array($this,PAS_PREFIX.'share_error'));
		}
		add_action("init",array($this,PAS_PREFIX."start_session"));
		add_action( 'plugins_loaded', array ( $this, PAS_PREFIX.'load_textdomain') );
		add_filter( 'plugin_row_meta', array ( $this,PAS_PREFIX.'row_meta'), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array ( $this,PAS_PREFIX.'action_links'));
		add_action('wp_ajax_ced_pas_save_text', array ( $this,PAS_PREFIX.'save_text'));
		add_action( 'wp_ajax_nopriv_ced_pas_save_text', array ( $this,PAS_PREFIX.'save_text'));
		add_action('wp_ajax_product_autoshare_send_mail',array($this,'product_autoshare_send_mail'));
		$woo=new ced_pas_bulkshare();

	}

	/**
	  * This function is for enqueue scripts and style.
	  * @name ced_pas_addScripts()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_addScripts(){
		wp_register_script("task", plugins_url( 'assets/js/task.js', __FILE__ ));
		wp_enqueue_script( 'task', plugins_url( 'assets/js/task.js', __FILE__ ),array('jquery'),1,true);	
		wp_register_style("style_share", plugins_url( 'assets/css/style_share.css', __FILE__ ));
		wp_enqueue_style( 'style_share', plugins_url( 'assets/css/style_share.css', __FILE__ ),1,true);
		wp_localize_script('task', 'id', array('id'=>get_option( 'sharepro_appid' )));
		wp_localize_script('task','ajax_url',admin_url('admin-ajax.php'));
	}
	/**
	 * This function is for loading textdomain.
	 * @name ced_pas_load_textdomain()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_load_textdomain(){
		$domain = "product-auto-share";
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, CED_PAS_PATH .'language/'.$domain.'-' . $locale . '.mo' );
		load_plugin_textdomain( 'product-auto-share', false, plugin_basename( dirname( __FILE__ ) ) . '/language' );	
	}
	
	
	/**
	  * This function is for calling the share functions when a product is added.
	  * @name ced_pas_new_product_share($new_status, $old_status, $post)
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_new_product_share($new_status, $old_status, $post) {
				//unset($_POST['meta'][245]);
				if( $new_status == 'publish' && !empty($post->ID)
						&& (in_array( $post->post_type,
								array( 'product') 
								)
						)){		
								$post_ID = $post->ID;
								$product = wc_get_product( $post->ID );
								$link = get_permalink($post->ID);
								$fb_share = get_post_meta($post_ID,'fb_postid',true);
								$tweet_share = get_post_meta($post_ID,'tweet_postid',true);
								$linkedin_share = get_post_meta($post_ID,'linkedpost_id',true);
								$pinterest_share = get_post_meta($post_ID,'pint_id',true);
								$mail_share  = get_post_meta($post_ID,'ced_pas_mail',true);
								$tumblr_share = get_post_meta($post_ID,'ced_pas_tumblr',true);
								//echo get_post_meta($post->ID,'ced_pas_custom_msg',true);die;
								if((get_option("wsp_gen_settings")['fb'] =="facebook")){
									$fbshare = new ced_pas_fbshare();							
									$fbshare->ced_pas_shareonfb( $link,$post->ID );
								}
								if((get_option("wsp_gen_settings")['tweet'] =="twiter")){
									$tweet = new ced_pas_share_tweet();
									$tweet->ced_pas_tweet($link,$post->ID);
								}
								if((get_option("wsp_gen_settings")['pint'] =="pinterest")){
									$pin = new ced_pas_share_pin();
									$pin->ced_pas_pinthis($link,$post->ID);
								}
								if((get_option("wsp_gen_settings")['LinkedIn'] =="LinkedIn")){
									$linkshare = new ced_pas_link_share();
									$linkshare->ced_pas_share_on_linkedin($link,$post->ID);
								}
								if((get_option("wsp_gen_settings")['mail'] =="mail") && $mail_share == ""){// not working now
									$mailshare = new ced_pas_mail();
									$mailshare->ced_pas_domail($link,$post->ID);
								}
								if((get_option("wsp_gen_settings")['tumblr'] =="tumblr")){
									$tumblrShare = new ced_pas_tumblr();
									$tumblrShare->ced_pas_tumblrshare($link, $post->ID);
								}
						  }
			
	}
	
	/**
	  * This function is for adding custom message when product is updated.
	  * @name ced_pas_custom_message($messages)
	  * @param messages
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	function ced_pas_custom_message($messages){	
		global $post, $post_ID;		
		$fb_share = get_post_meta($post_ID,'fb_postid',true);
        $fb_page_share = get_post_meta($post_ID,'fbpage_postid',true);
		$tweet_share = get_post_meta($post_ID,'tweet_postid',true);
		$linkedin_share = get_post_meta($post_ID,'linkedpost_id',true);
		$pinterest_share = get_post_meta($post_ID,'pint_id',true);
		$mail_share   = get_post_meta($post_ID,'ced_pas_mail',true);
		$tumblr_share = get_post_meta($post_ID,'ced_pas_tumblr',true);
		$share_msg = "and shared on ";
		if ($pinterest_share != "") {
		 	$share_msg .= ' Pinterest ' ;
		}
		if ($linkedin_share != "") {
			$share_msg .= 'Linkedin ' ;
		}
		
		if ($fb_share != "" || $fb_page_share != "") {
            if( $fb_page_share != "" && $fb_share != ""  ){
                $share_msg .= 'Facebook page and profile ';
            }elseif($fb_page_share != ""){
                $share_msg .= 'Facebook page ';
            }else{
               $share_msg .= 'Facebook profile ' ;
            }
		}
		if ($tweet_share == 200) {
			$share_msg .= ' Twitter' ;
		}
		if($tumblr_share != ""){
			$share_msg .= ' Tumblr Blog' ;
		}
		if ($mail_share == 1) {
			$share_msg .= ' mail to all your customers' ;
		}
		
		if($share_msg != "and shared on "){
				$messages['product'] = array(
				1 =>sprintf( __( 'Product updated','product-auto-share').' %s'.' <a href="%s">'.'__("View Product","product-auto-share")'.'</a>', $share_msg,esc_url( get_permalink( $post_ID ) ) ),	
				6 => sprintf( __( 'Product published','product-auto-share').' %s.'.' <a href="%s">'.'__("View Product","product-auto-share")'.'</a>', $share_msg,esc_url( get_permalink( $post_ID ) ) ),
			);
		}	
		
		return apply_filters("pas_custom_publish_msg",$messages); 	
	}
	
	
	/**
	  * This function is for adding error message when there is error in sharing.
	  * @name ced_pas_share_error()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	function ced_pas_share_error(){
		global $post;
		if(!isset($_GET['post'])){
			return;
		}
		?>	
			<div class="updated add_red">
				<?php $fb_error = get_option("Fb_error");
					  $pint_error = get_option("pint_error");
					  $tweet_error = get_option("tweet_error");
					  $link_error  = get_option("link_error");
					  $tumblr_error  = get_option("ced_pas_tumblr_error");				
					if($fb_error != ''){
						?>
						<p> <?php  _e("Facebook API Error: ".$fb_error,"product-auto-share")?></p>
					<?php }?>
					<?php if($pint_error != ''){?>
					<p> <?php  _e("Pinterest API Error: ".$pint_error,"product-auto-share")?></p>
					<?php }?>
					<?php if($tweet_error != ''){?>
					<p> <?php  _e("Twitter API Error: ".$tweet_error,"product-auto-share")?></p>
					<?php }?>
					<?php if($link_error != ''){?>
					<p> <?php  _e("Linkedin API Error: ".$link_error,"product-auto-share")?></p>
					<?php }?>	
					<?php if($tumblr_error != ''){?>
					<p> <?php  _e("Tumblr API Error: ".$tumblr_error,"product-auto-share")?></p>
					<?php }?>			
			</div>
		<?php
			delete_option("Fb_error");
			delete_option("pint_error");
			delete_option("tweet_error");
			delete_option("link_error");
			delete_option("ced_pas_tumblr_error");
		
	}
	
	/**
	  * This function is for starting the session.
	  * @name ced_pas_start_session()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	function ced_pas_start_session(){
        if (session_status() == PHP_SESSION_NONE) {
		  session_start();
        }
	}
	
	
	
	
	/**
	 * This function is for adding plugin row meta.
	 * @name ced_pas_row_meta()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_row_meta( $links, $file ) {
		if ( strpos( $file, 'ced-share-product.php' ) !== false ) {
			$new_links = array(
					//'demo' => '<a href="http://demo.cedcommerce.com/woocommerce/autoshare/wp-admin" target="_blank">Demo</a>',
					//'doc' => '<a href="http://demo.cedcommerce.com/woocommerce/autoshare/doc/index.html" target="_blank">Documentation</a>',
					  'doc' => '<a href="http://cedcommerce.com/media/userguides/woo-product-auto-share/Product-auto-share.pdf" target="_blank">Documentation</a>',
					  'more'	 =>	'<a href="http://cedcommerce.com/woocommerce-extensions" target="_blank">More plugins by CedCommerce</a>'
			);
	
			$links = array_merge( $links, $new_links );
		}
		return $links;
	}
	
	/**
	 * This function is for adding plugin action link.
	 * @name ced_pas_action_links()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */

	function ced_pas_action_links( $links ) {
		$link[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=share-product-settings') ) .'">Settings</a>';
		return array_merge($link,$links);
	}
	
	
	/**
	  * This function is for adding facebook review metabox.
	  * @name ced_pas_fb_reviews($param)
	  * @param 
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_fb_reviews($param) {	
		global $post;
		$addmetabox = new ced_pas_msg_metabox();
		add_meta_box('ced_pas_meta',__('Product Auto Share','product-auto-share'), array($addmetabox,'add_meta_box'),'product', 'side', 'high' );
		if(get_option('wsp_gen_settings')['fb_reviews'] == "on"){
			add_meta_box('fb_meta',__('Reviews on facebook','product-auto-share'), array($this,'ced_pas_fb_comments'));		
		}
	}
	
	/**
	 * 
	 */
	function ced_pas_save_text(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ced_pas' ) )
			die ( 'Busted!');
		$data =	sanitize_text_field($_POST['text']);
		$post_id = $_POST['post_id'];
		//update_option('ced_pas_custom_msg', $data);
		update_post_meta($post_id,'ced_pas_custom_msg',$data);
	}

	function product_autoshare_send_mail()
	{
		if(isset($_POST["flag"]) && $_POST["flag"]==true && !empty($_POST["emailid"]))
		{
			$to = "support@cedcommerce.com";
			$subject = "Wordpress Org Know More";
			$message = 'This user of our woocommerce extension "Product Auto Share" wants to know more about marketplace extensions.<br>';
			$message .= 'Email of user : '.$_POST["emailid"];
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$flag = wp_mail( $to, $subject, $message);	
			if($flag == 1)
			{
				echo json_encode(array('status'=>true,'msg'=>__('Soon you will receive the more details of this extension on the given mail.',"product-auto-share")));
			}
			else
			{
				echo json_encode(array('status'=>false,'msg'=>__('Sorry,an error occurred.Please try again.',"product-auto-share")));
			}
		}
		else
		{
			echo json_encode(array('status'=>false,'msg'=>__('Sorry,an error occured.Please try again.',"product-auto-share")));
		}
		wp_die();
	}
	
	
	
	/**
	  * This function is for getting facebook comments.
	  * @name ced_pas_fb_comments()
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_fb_comments(){
		 global $post;
		 $app_id= get_option( 'sharepro_appid' );
		 $app_secret= get_option( 'sharepro_appsec' );
		 $access_token= get_option('sharepro_acctoken');
		 $page = get_option('sharepro_pageid');
		 $post_fbshare = get_post_meta($post->ID, 'fb_postid', true );
		 $comments_url = ced_graph_url.$post_fbshare.'/comments/?summary=true&access_token='.$access_token;
		 $response = wp_remote_get($comments_url);
		 $params=json_decode($response['body'],true); 
		 foreach ($params as $key=>$value){
		 if($key=="data"){
			 if(is_array($value)){
				 foreach ($value as $key1=>$val){
					 foreach ($val as $key2=>$val1) {
						 if($key2=="from"){
							 $pic = ced_graph_url.$val1['id']."/picture?type=square";
									 echo "<div class=div12><div class=div14><img width=32 height=32 class=avatar avatar-32 photo height=25px width=25px src=$pic></div>";
									 echo   "<div class=div13><strong>&nbsp".$val1['name']."</strong>";
								 }
								 if($key2 == "message"){
								 	 echo "<p>&nbsp&nbsp"."$val1</p></div></div>";
							 }
						 }
					 }
				 	
				 }
			 }
		 }
	 }	
	 
}
	new product_auto_share();
}
else{
	
	class ced_pas_check_woommerce{
			/**
			 * This function is construct function for class pas_check_woommerce'.
			 * @name ced_pas_deactivate_plugin()
			 * @author CedCommerce <plugins@cedcommerce.com>
			 * @link http://cedcommerce.com/
			 */
			function __construct(){
				add_action('admin_init',array( $this,'ced_pas_deactivate_plugin'));
				add_action( 'admin_notices',array( $this,'ced_pas_add_reason_notice'));
				add_action( 'init', array( $this,'ced_pas_add_style') );
			}
			
			/**
			 * This function is for adding styles in admin notice.
			 * @name ced_pas_deactivate_plugin()
			 * @author CedCommerce <plugins@cedcommerce.com>
			 * @link http://cedcommerce.com/
			 */
			function ced_pas_add_style(){
				wp_register_style("hide_message", plugins_url( 'assets/css/hide_message.css', __FILE__ ));
				wp_enqueue_style( 'hide_message', plugins_url( 'assets/css/hide_message.css', __FILE__ ),1,true);
				
			}
			
			 /**
			  * This function is deactivating plugin if woocommerece is not installed'.
			  * @name ced_pas_deactivate_plugin()
			  * @author CedCommerce <plugins@cedcommerce.com>
			  * @link http://cedcommerce.com/
			  */	 
			function ced_pas_deactivate_plugin(){
				deactivate_plugins(plugin_basename( __FILE__ ));
			}
			
			
			/**
			  * This function is for adding reason notice when woocommerce is not installed.
			  * @name ced_pas_add_reason_notice()
			  * @author CedCommerce <plugins@cedcommerce.com>
			  * @link http://cedcommerce.com/
			  */	 	
			function ced_pas_add_reason_notice(){
				?>
				<div  class="updated notice is-dismissible add_red"> <p> <strong><?php  _e("Product Auto Share needs Woocommerce Plugin to activate.Please install Woocommerce.","product-auto-share")?></strong></p></div><?php 	 
			}	
	}	
	new ced_pas_check_woommerce();
}
