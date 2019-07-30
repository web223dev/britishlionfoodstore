<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once(CED_PAS_PATH.'includes/view/ced_pas_mailview.php');
class ced_pas_mail {
	 var $subcribers;
	
	function ced_get_subscribers(){
		$this->subcribers=get_option('ced_wp_anl_users_list');	
	}
	
	function ced_pas_domail($productlink,$id){	
		$headers = array('Content-Type: text/html; charset=UTF-8'. "\r\n");
		$reg_mail_addresses = array();
		$view = new ced_pas_mail_template();
		$email_content = $view->get_mail_template($productlink, $id);
		
		$data = get_users( array('role'=>'customer') );
		foreach ($data as $user){
			$reg_mail_addresses[] =$user->user_email;
		}
	
		if(in_array('wp-advanced-newsletter/advancednewsletter.php', apply_filters('active_plugins', get_option('active_plugins')))){
			$this->ced_get_subscribers();
			$reg_mail_subscribers = array();
			foreach ($this->subcribers as $subscriber){
				$reg_mail_subscribers[] = $subscriber['email'];
			}
			$reg_mail_addresses=array_merge($reg_mail_subscribers,$reg_mail_addresses);
		}
		$to_email=$reg_mail_addresses[0];
		unset($reg_mail_addresses[0]);
        foreach ($reg_mail_addresses as $mail){
            $headers[] = 'Bcc:'.$mail."\r\n";
        }
		$subject = 'NEW PRODUCT ON YOUR FAVOURITE SHOP';
		$data=wp_mail( $to_email,$subject,$email_content,$headers);	
		if($data){
			update_post_meta($id,'ced_pas_mail', 1);
			return ;
		}
		else{			
			return ;
		}	
	}
			
	
}