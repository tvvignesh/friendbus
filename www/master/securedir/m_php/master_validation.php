<?php
/**
 *
 * CONTAINS VALIDATION FUNCTIONS WHICH FILTER DATA (SANITIZE OR VALIDATE)
 * @author T.V.VIGNESH
 *
 */
class ta_filtervalue
{
	/**
	 *
	 * Removes all whitespace from a string, including whitespace that isn't trailing or leading
	 *
	 * @param string $str String to be filtered
	 * @return string Filtered String
	 */
	public function sanitize_whitespace($str){return preg_replace('/\s\s+/',' ', $str);}

	/**
	 *
	 * Removes characters not valid in an e-mail address
	 *
	 * @param string $email Email ID to be filtered
	 * @return string Filtered Email Address
	 */
	public function sanitize_email($email){return strtolower(preg_replace('/[^a-z0-9+_.@-]/i','',$email));}

	/**
	 *
	 * Removes tags, whitespace
	 *
	 * @param string $str String to be filtered
	 * @return string Filtered String
	 */
	public function sanitize_removetags($str){$str = strval($str);$str = strip_tags($str);return $str;}

	/**
	 *
	 * Adds slashes to quotes
	 *
	 * @param string $str String to be filtered
	 * @return string Filtered String
	 */
	public function sanitize_quote($str){$str=addslashes($str);return $str;}

	/**
	 *
	 * Encodes all HTML tags
	 *
	 * @param string $str String to be filtered
	 * @return string Filtered String
	 */
	public function sanitize_enchtml($str){$str=htmlentities($str, ENT_QUOTES);return $str;}

	/**
	 *
	 * Filters a URL
	 *
	 * @param string $str URL to be filtered
	 * @return string Filtered URL
	 */
	public function sanitize_url($str)
	{
		$delimiters = '\\s"\\.\',';
		$schemes = 'https?|ftps?';
		$pattern = sprintf('#^(^|[%s])((?:%s)://\\S+[^%1$s])([%1$s]?)#i', $delimiters, $schemes);
		return strtolower(preg_replace($pattern,"",$str));
	}

	/**
	 *
	 * FUNCTION TO SEE WHAT FILTER TO USE FOR ALL KEYS
	 *
	 * @param string $str KEY OF THE REQUEST
	 * @param string $appid APP ID (Has default App id)
	 * @return string Filtered URL
	 */
	public function filtertype($str,$method="get",$appid="")
	{
		if($method=="get")
		$finalvar=$_GET[$str];
		else
		if($method=="post")
		$finalvar=$_POST[$str];

		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_incoming_requests::tblname." WHERE ".tbl_incoming_requests::col_requestkey."='$str' LIMIT 0,1",tbl_incoming_requests::dbname);

		if($res!=EMPTY_RESULT)
		{
			if($res[0][changesqlquote(tbl_incoming_requests::col_email,"")]=="1")
			{
				$finalvar=$this->sanitize_email($finalvar);
			}
			if($res[0][changesqlquote(tbl_incoming_requests::col_uname,"")]=="1")
			{
				$finalvar=$this->sanitize_whitespace($finalvar);
				$finalvar=$this->sanitize_quote($finalvar);
				$finalvar=$this->sanitize_removetags($finalvar);
			}
			if($res[0][changesqlquote(tbl_incoming_requests::col_url,"")]=="1")
			{
				$finalvar=$this->sanitize_quote($finalvar);
				$finalvar=$this->sanitize_url($finalvar);
			}
		}
		return $finalvar;
	}

	public function filterrequest()
	{
		$filterobj=new ta_filtervalue();
		$dbobj=new ta_dboperations();
		foreach($_GET as $key => $value)
		{
			$finalvar=$filterobj->filtertype($key,"get");
			$finalvar=$dbobj->escapestr($finalvar);
			$_GET[$key]=$finalvar;
		}
		foreach($_POST as $key => $value)
		{
			$finalvar=$filterobj->filtertype($key,"post");
			$finalvar=$dbobj->escapestr($finalvar);
			$_POST[$key]=$finalvar;
		}
	}

	/**
	 *
	 * CHECKS VAT FORMATS ACROSS EU
	 * @param integer $country Country name
	 * @param integer $vat_number VAT number to test e.g. GB123 4567 89
	 * @return integer -1 if country not included OR 1 if the VAT Num matches for the country OR 0 if no match
	 */
	public function checkVatNumber( $country, $vat_number ) {
		switch($country) {
			case 'Austria':
				$regex = '/^(AT){0,1}U[0-9]{8}$/i';
				break;
			case 'Belgium':
				$regex = '/^(BE){0,1}[0]{0,1}[0-9]{9}$/i';
				break;
			case 'Bulgaria':
				$regex = '/^(BG){0,1}[0-9]{9,10}$/i';
				break;
			case 'Cyprus':
				$regex = '/^(CY){0,1}[0-9]{8}[A-Z]$/i';
				break;
			case 'Czech Republic':
				$regex = '/^(CZ){0,1}[0-9]{8,10}$/i';
				break;
			case 'Denmark':
				$regex = '/^(DK){0,1}([0-9]{2}[\ ]{0,1}){3}[0-9]{2}$/i';
				break;
			case 'Estonia':
			case 'Germany':
			case 'Greece':
			case 'Portugal':
				$regex = '/^(EE|EL|DE|PT){0,1}[0-9]{9}$/i';
				break;
			case 'France':
				$regex = '/^(FR){0,1}[0-9A-Z]{2}[\ ]{0,1}[0-9]{9}$/i';
				break;
			case 'Finland':
			case 'Hungary':
			case 'Luxembourg':
			case 'Malta':
			case 'Slovenia':
				$regex = '/^(FI|HU|LU|MT|SI){0,1}[0-9]{8}$/i';
				break;
			case 'Ireland':
				$regex = '/^(IE){0,1}[0-9][0-9A-Z\+\*][0-9]{5}[A-Z]$/i';
				break;
			case 'Italy':
			case 'Latvia':
				$regex = '/^(IT|LV){0,1}[0-9]{11}$/i';
				break;
			case 'Lithuania':
				$regex = '/^(LT){0,1}([0-9]{9}|[0-9]{12})$/i';
				break;
			case 'Netherlands':
				$regex = '/^(NL){0,1}[0-9]{9}B[0-9]{2}$/i';
				break;
			case 'Poland':
			case 'Slovakia':
				$regex = '/^(PL|SK){0,1}[0-9]{10}$/i';
				break;
			case 'Romania':
				$regex = '/^(RO){0,1}[0-9]{2,10}$/i';
				break;
			case 'Sweden':
				$regex = '/^(SE){0,1}[0-9]{12}$/i';
				break;
			case 'Spain':
				$regex = '/^(ES){0,1}([0-9A-Z][0-9]{7}[A-Z])|([A-Z][0-9]{7}[0-9A-Z])$/i';
				break;
			case 'United Kingdom':
				$regex = '/^(GB){0,1}([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})|([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})|((GD|HA)[0-9]{3})$/i';
				break;
			default:
				return -1;
				break;
		}

		return preg_match($regex, $vat_number);
	}

	/**
	 * A simple function to check file from bad codes.
	 *
	 * @param (string) $file - file path.
	 */
	public function is_clean_file ($file)
	{
		if (file_exists($file))
		{
			$contents = file_get_contents($file);
		}
		else
		{
			exit($file." Not exists.");
		}

		if (preg_match('/(base64_|eval|system|shell_|exec|php_)/i',$contents))
		{
			return true;
		}
		else if (preg_match("#&\#x([0-9a-f]+);#i", $contents))
		{
			return true;
		}
		elseif (preg_match('#&\#([0-9]+);#i', $contents))
		{
			return true;
		}
		elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $contents))
		{
			return true;
		}
		elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $contents))
		{
			return true;
		}
		elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $contents))
		{
			return true;
		}
		elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $contents))
		{
			return true;
		}
		elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $contents))
		{
			return true;
		}
		elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset|html|body|title|div|p|form)[^>]*>#i", $contents))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * VALIDATES URL
	 * @param unknown_type $url
	 * @return boolean
	 */
	public function validate_url($url)
	{
		$regex = "((https?|ftp)\:\/\/)?"; // SCHEME
		$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
		$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
		$regex .= "(\:[0-9]{2,5})?"; // Port
		$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
		if(preg_match("/^$regex$/", $url))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * VALIDATE PAN NUMBER
	 * @param unknown_type $num
	 * @return number
	 */
	public function validate_pan($num){
		return preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/", $num);
	}
}

?>