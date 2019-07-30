<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    require_once(CED_PAS_PATH.'/Apis/Linkedin.php');
    class ced_pas_link_share{
        
        /**
         * This function is for sharing on linkedin.
         * @name ced_pas_share_on_linkedin($param1,$param2)
         * @param1 productlink
         * @param2 productid
         * @author CedCommerce <plugins@cedcommerce.com>
         * @link http://cedcommerce.com/
         */
        function ced_pas_share_on_linkedin($param1,$param2){
            $app_id = get_option("linked_settings")['linkedin_apiid'];
            if($app_id == ""){
                return;
            }
            $config = array(
                            'api_key' => get_option("linked_settings")['linkedin_apiid'],
                            'api_secret' => get_option("linked_settings")['linkedin_appsec'],
                            'callback_url' => get_admin_url()."admin.php?page=share-product-settings&tab=general"
                            );
           
            $image_url 	= wp_get_attachment_url( get_post_thumbnail_id($param2) );
            $msg = get_the_title($param2);
            //$tms= get_option('ced_pas_custom_msg');
            $tms=get_post_meta($param2,'ced_pas_custom_msg',true);
            $array = array(
                           'comment' => $msg,
                           'content' => array(
                                              'title' => get_the_title($param2),
                                              'description' => $tms,
                                              'submitted_url' => $param1
                                              ),
                           'visibility' => array(
                                                 'code' => 'anyone'
                                                 )
                           );
            
            $access_token = get_option("linked_token");
            try{
                $linked 	= new LinkedIn($config);
                $linked->setAccessToken($access_token);
                $res=$linked->post('/people/~/shares',$array);
                if(isset($res['updateKey'])){
                    update_post_meta($param2,'linkedpost_id',$res['updateKey']);
                    if(get_option("link_error") != ""){
                        delete_option("link_error");
                    }
                }
                else{
                    update_option("link_error","Some thing went wrong with Linkedin post. Please update your API keys and access token.");
                }
                return ;
            }
            catch(Exception $e){
                update_option("link_error","Some thing went wrong with Linkedin post. Please update your API keys and access token.");
                return;
            }
        }
        
    }
