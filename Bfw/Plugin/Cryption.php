<?php
class Cryption {
	//private $securekey, $iv;
	private $securekey;
	function __construct($textkey) {
		$this->securekey = $textkey;
		//$this->securekey = hash ( 'sha256', $textkey, TRUE );
		//$this->iv = mcrypt_create_iv ( 32 );
	}
	function encrypt($input) {
		//return "111";
		//return strtr ( base64_encode ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv ) ), '+/=', '-_,' );
		return strtr ( base64_encode ( mcrypt_ecb ( MCRYPT_DES, $this->securekey, $input, MCRYPT_ENCRYPT ) ), '+/=', '-_,' );
	}
	function decrypt($input) {
		//return "222";
		//return trim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode ( strtr ( $input, '-_,', '+/=' ) ), MCRYPT_MODE_ECB, $this->iv ) );
		return trim ( mcrypt_ecb ( MCRYPT_DES, $this->securekey, base64_decode ( strtr ( $input, '-_,', '+/=' ) ), MCRYPT_DECRYPT ) );
	}
}
?>