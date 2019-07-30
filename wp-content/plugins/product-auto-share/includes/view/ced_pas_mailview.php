<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ced_pas_mail_template{
	
	/**Function for getting template view
	 * name get_mail_template()
	 * @param unknown $productlink
	 * @param unknown $id
	 */
	function get_mail_template($productlink,$id){
		global $post;
		$image_url 	= wp_get_attachment_url( get_post_thumbnail_id($id) );
		$product = get_post( $id );
			$content = '<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<title>New Product on Your Favourite Shop</title>
					<center><h2>'.get_the_title($id).'</h2></center>
				</head>
				<body>
					<center> <img alt="product link" src="'.$image_url.'"> <br>						
						<div>'.$product->post_content.'</div>
						<a href = '.$productlink.'>'.$productlink.'</a>
					</center>
					<footer>	
					</footer>
				</body>
			</html>';
			return $content;	
	}	
}



