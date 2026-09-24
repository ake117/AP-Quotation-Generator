<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*
* Omise charge api class 
* Thanupong B. Parnthongthanaphan
* OPTOK co.,ltd
*
*/
require_once( APPPATH.'/third_party/omise-php-master/lib/Omise.php' );

class Omise_api {
	var $_api_url = 'https://api.omise.co/';
	var $pkey, $skey;
	public function __construct()
	{

	}
	public function init($publish_key, $secret_key){
            define('OMISE_API_VERSION', '2017-11-02');
            define('OMISE_PUBLIC_KEY', $publish_key);
            define('OMISE_SECRET_KEY', $secret_key);
            $this->pkey = $publish_key;
            $this->skey = $secret_key;
	}
	public function create($card_token, $amount, $description = null, $return_uri, $capture = ture, $currency = 'thb'){
                $charge = OmiseCharge::create(array(
                    'amount' => $amount,
                    'currency' => 'thb',
                    'description' => $description,
                    'return_uri' => $return_uri,
                    'card' => $card_token
                ));
		return $charge;
	}
        
        public function offsetGet($charge,$data){
            return $charge->offsetGet($data);
	}
        
	public function retrieve($charge_id){
            $transactions = OmiseCharge::retrieve($charge_id);
            return $transactions;
	}
        
	private function _exeCurl($command = 'charges', $post_array = array()){
		//prepare url
		$gurl = $this->_api_url.$command;

		//prepare post value
		$qst = http_build_query($post_array);

		//header
	        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
	        $headers[] = 'Connection: Keep-Alive';
	        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 
	
	        //init
	        $process = curl_init($gurl); 
	
	        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($process, CURLOPT_HEADER, 0);
	        curl_setopt($process, CURLOPT_ENCODING , 'gzip');
	        curl_setopt($process, CURLOPT_TIMEOUT, 30);
	        curl_setopt($process, CURLOPT_POSTFIELDS, $qst);
	        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($process, CURLOPT_POST, 1);
	        curl_setopt($process, CURLOPT_USERPWD, $this->skey.":");
	        try{
	            $return = curl_exec($process);
	        }catch (Exception $e){
	            echo " error : ".$e->getMessage();
	        }
	        curl_close($process);
	        return json_decode($return);
	}
}
