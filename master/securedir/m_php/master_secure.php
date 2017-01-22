<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO SECURITY/ENCRYPTION/HASHING/ID GENERATION
 * @author T.V.VIGNESH
 *
 */
class ta_secureclass
{
	/**
	 *
	 * ENCRYPTS A STRING (DECRYPTABLE)
	 *
	 * @param string $string String to be Encrypted
	 * @param string $key Key used for encryption
	 * @return string Encrypted string
	 */
	public function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}

	/**
	 *
	 * DECRYPTS AN ENCRYPTED STRING
	 *
	 * @param string $string String to be Decrypted
	 * @param string $key Key used for Decryption
	 * @return string Decrypted string
	 */
	public function decrypt($string, $key) {
	  $result = '';
	  $string = base64_decode($string);
	  for($i=0; $i<strlen($string); $i++) {
	    $char = substr($string, $i, 1);
	    $keychar = substr($key, ($i % strlen($key))-1, 1);
	    $char = chr(ord($char)-ord($keychar));
	    $result.=$char;
	  }
	  return $result;
	}

	/**
	 *
	 * ENCRYPTS A PASSWORD (NON-DECRYPTABLE)
	 *
	 * @param string $str Password to be Encrypted
	 * @return string Encrypted Password
	 */
	public function encryptpass($str)
	{
		$subpass=substr($str,3,5);
		$s="".md5($subpass);
		$concat=substr($s, 2,4);
		$str="^ta".sha1($str).$concat;
		return $str;
	}

	/**
	 *
	 * CHECK FOR CORRECTNESS OF A PASSWORD
	 *
	 * @param string $str Password Entered
	 * @param string $unm Username Entered
	 * @param string $dbname Database Name (Defaults to DB MAIN)
	 * @return boolean false on failure, true on success
	 */
	public function checkpass($str,$unm,$dbname=tadb::db_people)
	{
		$secureobj=new ta_secureclass();
		$str=$secureobj->encryptpass($str);
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$unm' AND ".tbl_user_info::col_upass."='$str'",$dbname);
		if($res==EMPTY_RESULT)
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}


	/**
	 *
	 * GENERATES USER ID
	 *
	 * @param string $dbname Database Name (Defaults to DB MAIN)
	 * @return boolean false on failure, true on success
	 */
	public function useridgen()
	{
		$uid=substr(md5(microtime()),rand(0,26),10);
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname);
		if($res==EMPTY_RESULT)
		{
			return $uid;
		}
		else
		{
			$secureobj=new ta_secureclass();
			$secureobj->useridgen();
		}
	}
}
?>