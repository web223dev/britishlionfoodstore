<?php
if(!class_exists("pin")){
class pin{
	
	/**
	 * base url for oauth login 
	 */
	var	$base_url ="https://api.pinterest.com/oauth";
	
	/**
	 * 
	 * @var app id of pinterest application
	 */
	var $appid ;
	
	/**
	 * variable to hold app secret
	 * @var unknown
	 */
	var $app_sec ;
	
	/**
	 * variable to hold access token 
	 * @var unknown
	 */
	var $access_token ;
	
	/**
	 * base url for oauth login
	 */
	const AUTH_HOST = "https://api.pinterest.com/oauth/";
	
	/**
	 * base url for performing the post request
	 */
	const REQ_url = "https://api.pinterest.com/v1/";
	
	/**
	 * Construct th
	 * @param unknown $appid
	 * @param unknown $app_sec
	 */
	function __construct($appid ,$app_sec){
		$this->appid = $appid;
		$this->app_sec = $app_sec;
		
	}
	
	/**
	 * get login url for authorization
	 * @param unknown $redirect_uri
	 * @param unknown $scope_arr
	 */
	function getin_url($redirect_uri,$scope_arr){
		$postvalues = array(
				"response_type"     => 'code',
				"redirect_uri"      => $redirect_uri,
				"client_id"         => $this->appid,
				"client_secret"     => $this->app_sec,
				"scope"             => implode(",", $scope_arr),
				"state"             => substr(md5(rand()), 0, 7)
		);
		return sprintf("%s?%s", self::AUTH_HOST, http_build_query($postvalues));	
	}
	
	/**
	 * function to get access token
	 * @param unknown $cde
	 */
	function get_token($cde){
		$setup = array(
				"grant_type"    => "authorization_code",
				"client_id"     => $this->appid,
				"client_secret" => $this->app_sec,
				"code"          => $cde
		);	
		if(!isset($fields)){
			$fields='';
		}
		$req_url = sprintf("%s%s", self::REQ_url,"oauth/token");
		$result=$this->curl_request('POST',$req_url,$setup,$fields);
		
		return $result;
		
	}
	
	/**
	 * function to set access token 
	 * @param unknown $token
	 */
	function set_token($token){
		$this->access_token = $token;
	}
	
	
	/**
	 * function to perform curl request 
	 * @param unknown $method
	 * @param unknown $url
	 * @param unknown $params
	 * @param unknown $token
	 * @param array $tokenset
	 */
	function curl_request($method,$url,$params,$fields){
		//print_r($params);
		$tokenset= array();
		if ($this->access_token != null) {
            $tokenset = array(
                "access_token" => $this->access_token,
            		"fields"      => implode(",",$fields)
            );
            //$params = array_merge($tokenset,$params);
            $url = sprintf("%s?%s", $url, http_build_query($tokenset));
        } 
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_POST,count($params));
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST ,$method);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 90);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $curl,CURLOPT_HTTPHEADER,$tokenset);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($curl,CURLOPT_HEADER,false);
		curl_setopt($curl,CURLINFO_HEADER_OUT,true);
		$result = curl_exec($curl);
		return $result;
	}
	
	
	/**
	 * Function for creating pins to given board id
	 * @param unknown $board_id
	 * @param unknown $note
	 * @param unknown $image_url
	 */
	function pinit($board_id,$note,$image_url,$link){
		$pin_data = array(
			"note" => $note,
			"image_url" => $image_url,
			"board"	=> $board_id,
			"link"	=> $link
		);
		//print_r($pin_data);die;
		$req_url = sprintf("%s%s", self::REQ_url,"pins/");
		$fields = array("id","note","url");
		$new_pin = $this->curl_request('POST',$req_url,$pin_data,$fields);
		return $new_pin;
	}
	
	/**
	 * function to delete pin 
	 */
	function del_pin($pin_id){
		$req_url = sprintf("%s%s", self::REQ_url,"pins/$pin_id");
		$this->curl_request('GET', $req_url, $params, $fields);
	}
	
	/**
	 * function to create board 
	 * @param unknown $name
	 * @param unknown $desc
	 */
	function create_board($name,$desc){
		$board_data = array(
			"name" 			=> $name,
			"description"   => $desc	
		);
		$req_url = sprintf("%s%s", self::REQ_url,"boards/");
		$fields = array("id","name","url");
		$new_board=$this->curl_request('POST',$req_url,$board_data,$fields);
		return $new_board;
	}
	
	/**
	 * function to get all the boards available
	 */
	function getmyboards(){
		$req_url = sprintf("%s%s", self::REQ_url,"me/boards/");
		$fields = array("id","name","url");
		$boards =$this->curl_request('GET',$req_url,$boards_data=null,$fields);
		return $boards;
	}
	
	/**
	 * function to get info about board
	 */
	
    function getboarddesc($board_id){
        $req_url = sprintf("%s%s", self::REQ_url,"boards/$board_id");
        $fields = array("id","name","url");
        if ($this->access_token != null) {
            $tokenset = array(
                              "access_token" => $this->access_token,
                              "fields"      => implode(",",$fields)
                              );
            //$params = array_merge($tokenset,$params);
            $url = sprintf("%s?%s", $req_url, http_build_query($tokenset));
        }
        $arrContextOptions=array(
                                 "ssl"=>array(
                                              "verify_peer"=>false,
                                              "verify_peer_name"=>false,
                                              ),
                                 ); 
        $board =file_get_contents($url, false, stream_context_create($arrContextOptions));//$this->curl_request('POST',$req_url,$board_data,$fields);
        return $board;
    }
}
}
