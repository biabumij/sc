<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Index Enkrip Class

1. EnkripPassword($password)
2. EnkripPasswordAdmin($password)
3. EnkripResellerID($member_id,$reseller_member_id)
4. EnkripResellerPassword($password)
5. EnkripAPIKey($reseller_id)
6. ArrStr()
7. GetEppPw()
8. MyEnkripPassword($str)
9. MyDekripPassword($str)

*/

class Enkrip
{
	

	function encryptor($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'opoono';
        $secret_iv = 'opoono123';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
    
	function EnkripPassword($password)
	{
		$result=false;
		
		$result=md5($password,'crack');
		$result=sha1($result,'crack');
		$result=substr($result,5,10);
		$result=md5($result);
		$result=substr($result,5,10);
		$result=md5(sha1($result));
		
		return $result;
	}
	
	function EnkripPasswordAdmin($password)
	{
		$result=false;
		
		$result=sha1($password,'admin');
		$result=sha1($result,'crack_password');
		$result=substr($result,5,10);
		$result=sha1($result);
		$result=substr($result,5,10);
		$result=md5(sha1($result));
		
		return $result;		
	}
	
	function EnkripResellerID($member_id,$reseller_member_id)
	{
		$output=false;
		
		$rand=md5(sha1($member_id.$reseller_member_id, 'crack'));
		$rand=substr($rand,0,5);
		
		$strlen=strlen($reseller_member_id);
		switch($strlen) {
			case 1 :
			$id = '000'.$reseller_member_id;
			break;
			
			case 2 :
			$id = '00'.$reseller_member_id;
			break;
			
			case 3 :
			$id = '0'.$reseller_member_id;
			break;
			
			default :
			$id=$reseller_member_id;
			break;
		}
		
		$output=$rand.'-'.$id;
		
		return $output;
	}
	
	function EnkripResellerPassword($password)
	{
		$output=false;
		
		$output=substr(md5($password),0,10);
		
		return $output;
	}
	
	function EnkripAPIKey($reseller_id)
	{
		$output=false;
		
		$output=sha1($reseller_id,'api_key');
		$output=md5(sha1($output));
		
		return $output;
	}	
	
	function ArrStr()
	{
		$arrstr=false;
		
		$arrstr=array(
			'a'=>'b',
			'i'=>'c',
			'u'=>'d',
			'e'=>'f',
			'o'=>'g',
			'A'=>'h',
			'I'=>'j',
			'E'=>'k',
			'O'=>'l',
			'1'=>'m',
			'2'=>'n',
			'3'=>'p',
			'4'=>'q',
			'5'=>'r',
			'!'=>'t',
			'@'=>'v',
			'#'=>'w',
			'$'=>'x',
			'%'=>'y',
			'&'=>'z'
			);
		
		return $arrstr;
	}
	
	function GetEppPw()
	{
		$output=false; 
		
		$arrstr=array('a','i','u','e','o','A','I','E','O','1','2','3','4','5','!','@','#','$','%','&');
		for($i=1;$i<=16;$i++) {
			$rand=rand(1,16);
			$output.=$arrstr[$rand];
		}		
		
		return $output;
	}
	
	function MyEnkripPassword($str)
	{
		$output=$str;
		
		$arrstr=$this->ArrStr();
		
		foreach($arrstr as $val => $key) {
			$output=str_replace($val,$key,$output);
		}
		return $output;
	}
	
	function MyDekripPassword($str)
	{
		$output=$str;

		$arrstr=$this->ArrStr();
		
		foreach($arrstr as $val => $key) {
			$output=str_replace($key,$val,$output);
		}
		
		return $output;		
	}

	function EnkripUrl($string)
	{
		$key = "MAL_26092016"; //key to encrypt and decrypts.
		  $result = '';
		  $test = "";
		   for($i=0; $i<strlen($string); $i++) {
		     $char = substr($string, $i, 1);
		     $keychar = substr($key, ($i % strlen($key))-1, 1);
		     $char = chr(ord($char)+ord($keychar));

		     $test[$char]= ord($char)+ord($keychar);
		     $result.=$char;
		   }

		   return urlencode(base64_encode($result));
		// $output=false;

		// $output=substr(md5($id),0,10);
		// // $result=md5($output,'crack');
		// $result=md5(sha1($output),'crack');
		// $strlen = strlen($result);
		// $b = $strlen / 2;

		// $last_string = substr($result,$b);
		// $first_string = substr($result,0,$b);

		// $all = $last_string.$id.$first_string;
		// $output = $all;
		// return $output;
	}

	function DekripUrl($string)
	{
		$key = "MAL_26092016"; //key to encrypt and decrypts.
	    $result = '';
	    $string = base64_decode(urldecode($string));
	   for($i=0; $i<strlen($string); $i++) {
	     $char = substr($string, $i, 1);
	     $keychar = substr($key, ($i % strlen($key))-1, 1);
	     $char = chr(ord($char)-ord($keychar));
	     $result.=$char;
	   }
	   return $result;
		// $output=false;
		// $bagi = floor(strlen($enkrip) / 2);
		// $get_id = substr($enkrip,$bagi,1);
		// $output=$get_id;
		// return $output;
	}

}
?>