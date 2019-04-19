<?php
namespace Lib\Util;
/**
 * @author wangbo
 * DES解密辅助类
 */
class DesCryptUtil {
	private $key="!@#%176f";
	function __construct($key) {
		$this->key = $key;
	}

	function encrypt($string) {
	      $input = str_replace("\n", "", $input);
        $input = str_replace("\t", "", $input);
        $input = str_replace("\r", "", $input);
        $key = substr(md5($this->key), 0, 24);
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim(chop(strtr(base64_encode($encrypted_data),'+/=', '-_,')));

	}
	function decrypt($string) {
		    $input = str_replace("\n", "", $input);
        $input = str_replace("\t", "", $input);
        $input = str_replace("\r", "", $input);
        $input = trim(chop(base64_decode( strtr ( $input, '-_,', '+/=' ))));
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $key = substr(md5($this->key), 0, 24);
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);
        $decrypted_data = mdecrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim(chop($decrypted_data));
	}

}

?>