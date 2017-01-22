<?php
/**
 *
 * CONTAINS SOME UTILITY FUNCTIONS WHICH IS USED TO EASE CODING
 * @author T.V.VIGNESH
 *
 */
class ta_utilitymaster
{
	/**
	 *
	 * CONVERT A RELATIVE PATH TO URL
	 * @param unknown_type $file
	 * @param unknown_type $path
	 * @return string
	 */
	public function pathtourl($file, $path=ROOT_SERVER)
	{
		if($file=="")return "";
		if(strpos($file, $path) !== BOOL_FAILURE)
		{return substr($file, strlen($path));}
		else
		{return $file;}
	}

	/**
	 * 
	 * CONVERT RELATIVE URL TO ABSOLUTE URL
	 * @param unknown $rel Relative URL
	 * @param unknown $base Base URL
	 * @return unknown|string Returns Absolute URL
	 */
	function rel2abs($rel, $base)
	{
		$scheme="http";
		/* return if already absolute URL */
		if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
	
		/* queries and anchors */
		if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
	
		/* parse base URL and convert to local variables:
		 $scheme, $host, $path */
		extract(parse_url($base));
	
		/* remove non-directory element from path */
		$path = preg_replace('#/[^/]*$#', '', $path);
	
		/* destroy path if relative url points to root */
		if ($rel[0] == '/') $path = '';
	
		/* dirty absolute URL */
		$abs = "$host$path/$rel";
	
		/* replace '//' or '/./' or '/foo/../' with '/' */
		$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}
	
		/* absolute URL is ready! */
		return $scheme.'://'.$abs;
	}
	
	/**
	 *
	 * OUTPUT STORED BUFFER CONTENTS
	 * @return string
	 */
	public function outputbuffercont()
	{
		if(isset($GLOBALS["cont"]))
		{
			echo $GLOBALS["cont"];
			unset($GLOBALS["cont"]);
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 * 
	 * CONVERT BYTES TO APPROPRIATE BIG UNIT
	 * @param unknown $bytes
	 * @param number $precision
	 */
	public function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
	
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
	
		// Uncomment one of the following alternatives
		 $bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow));
	
		return round($bytes, $precision) . ' ' . $units[$pow];
	}
	
	public function enablebufferoutput()
	{
		$GLOBALS["cont"]=ob_get_contents();
		if (ob_get_level())
		{
			ob_clean();
			ob_end_clean();
		}
	}

	public function includeextassets()
	{
		if(!file_exists(MASTER_JS_ROOT."/tempjs.js"))
		{
			JSIncluder::includeFile(MASTER_JS_ROOT.'/jsclassfile.js');
			$fhandle=fopen(MASTER_JS_ROOT."/tempjs.js",'w');
			if($fhandle)
			{
				fwrite($fhandle, $GLOBALS["alljs"]);
				fclose($fhandle);
			}
			else
			{
				die("Some unexpected error occured in loading javascript files. ".MASTER_JS_ROOT."/tempjs.js");
			}
		}

		require_once(ROOT_SECURE.'/masterassets.php');
	}

	/**
	 *
	 * FUNCTION TO HIGHLIGHT SEARCH WORDS
	 * @param unknown_type $word
	 * @param unknown_type $subject
	 * @return string
	 */
	public function highlight($word, $subject) {

		$split_subject = explode(" ", $subject);
		$split_word = explode(" ", $word);

		foreach ($split_subject as $k => $v){
			foreach ($split_word as $k2 => $v2){
				if($v2 == $v){

					$split_subject[$k] = "<span class='highlight'>".$v."</span>";

				}
			}
		}

		return implode(' ', $split_subject);
	}

	/**
	 *
	 * PARSES A URL AND RETURNS AN ARRAY (
	[scheme] => http
    [host] => wild.subdomain.orgy.domain.co.uk
    [port] => 10000
    [user] => name
    [pass] => pass
    [path] => /path/to/file.php
    [query] => a=1&b=2
    [fragment] => anchor )
	 * @param unknown_type $url URL to be parsed
	 * @return mixed
	 */
	public function parseurl($url)
	{
		$url_data = parse_url ( $url );
		return $url_data;
	}

	/**
	 *
	 * GETS PLATFORM AND BROWSER INFORMATION
	 */
	public function getplatforminfo()
	{
		$utilityobj=new ta_utilitymaster();
		return $utilityobj->getBrowser();
	}

	/**
	 *
	 * FUNCTION WHICH RETURNS THE BROWSER INFORMATION AS AN ARRAY
	 * array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
		);
	 * @return multitype:string unknown
	 */
	public function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}

		// check if we have a number
		if ($version==null || $version=="") {$version="?";}

		return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
		);
	}


	/**
	 *
	 * CONVERTS ARRAY TO STRING EACH SEPARATED BY ,
	 * @param unknown_type $array
	 */
	public function convertarraytostring($array)
	{
		$arrstr=implode("','",$array);
		return $arrstr;
	}

	/**
	 * 
	 * ADD A LINK TO THE DATABASE
	 * @param unknown $url URL of the website to be added
	 * @param unknown $label The label for the website
	 * @param string $type The type of the web site (1-External)
	 * @param string $flag Flag values (1-Allowed,2-Under Review,3-Blocked)
	 * @param string $favico URL specifying favico for this website
	 * @return string/object Returns Linkid of the inserted link
	 */
	public function link_add($url,$label,$type="1",$flag="1",$favico="")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		$linkid=$uiobj->randomstring(7,tbl_linkdb::dbname,tbl_linkdb::tblname,tbl_linkdb::col_linkid);
		
		if($favico=="")
		{
			$favico=$utilityobj->favicon_get($url);
		}
		
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_linkdb::tblname." (".tbl_linkdb::col_linkid.",".tbl_linkdb::col_url.",".tbl_linkdb::col_label.",".tbl_linkdb::col_linkflag.",".tbl_linkdb::col_linktype.",".tbl_linkdb::col_favico.") 
				VALUES ('$linkid','$url','$label','$flag','$type','$favico')", tbl_linkdb::dbname);
		return $linkid;
	}
	
	/**
	 * 
	 * EDIT LINK DETAILS 
	 * @param unknown $linkid Link ID of the link to be edited
	 * @param unknown $colname Name of the Column to be edited
	 * @param unknown $value Value to be substituted
	 * @return string/object Returns DBUPDATE result
	 */
	public function link_edit($linkid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_linkdb::tblname." SET ".$colname."='$value' WHERE ".tbl_linkdb::col_linkid."='$linkid'", tbl_linkdb::dbname);
	}
	
	/**
	 * 
	 * DELETE A LINK FROM THE LINK DB
	 * @param unknown $linkid Link ID of the link to be deleted
	 * @return string/object Returns DBDELETE result
	 */
	public function link_remove($linkid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_linkdb::tblname." WHERE ".tbl_linkdb::col_linkid."='$linkid'", tbl_linkdb::dbname);
	}
	
	/**
	 * 
	 * GET LINK DETAILS FROM LINK DB
	 * @param unknown $linkid Link ID of the link whose details are to be fetched
	 * @return Ambigous <string, unknown> Returns the link details with array of appropriate keys as in db
	 */
	public function link_get($linkid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_linkdb::tblname." WHERE ".tbl_linkdb::col_linkid."='$linkid'", tbl_linkdb::dbname)[0];
	}
	
	/**
	 * 
	 * REFRESH THE FAVICO OF A LINK FROM LINKDB
	 * @param unknown $linkid Link ID from link db
	 * @return string/object Returns new fav icon url after updating to db
	 */
	public function link_favico_refresh($linkid)
	{
		$utilityobj=new ta_utilitymaster();
		$res=$utilityobj->link_get($linkid);
		$favico=$res["favico"];
		$url=$res["url"];
		$newfavico=$utilityobj->favicon_get($url);
		if($newfavico==""){$newfavico=$favico;}
		$utilityobj->link_edit($linkid,tbl_linkdb::col_favico,$newfavico);
		return $newfavico;
	}
	
	/**
	 *
	 * SHORTEN AN URL
	 * @param unknown $bigurl The URL to be shortened
	 * @param string $key The key to be assigned for the shortened URL (Defaults to "" during which the key is generated automatically)
	 * @param string $uid User ID of person who shortened the URL (Defaults to "")
	 * @param string $linkflag Flag value of link (1-allowed,2-under review,3-blocked)
	 * @throws Exception If the key already exists
	 * @return string Shortened Key on success, FAILURE on failure
	 */
	public function shortenurl($bigurl,$key="",$uid="",$linkflag="1")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		if($key=="")
		{
			$skey=$uiobj->randomstring(7,tbl_shorturldb::dbname,tbl_shorturldb::tblname,tbl_shorturldb::col_linkkey);
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_shorturldb::tblname." WHERE ".tbl_shorturldb::col_linkkey."='$skey'",tbl_shorturldb::dbname);
			if($res!=EMPTY_RESULT)
			{
				throw new Exception('#ta@0000000_0000119');
				return FAILURE;
			}
			$skey=$key;
		}
		if($dbobj->dbinsert("INSERT INTO ".tbl_shorturldb::tblname." (".tbl_shorturldb::col_linkflag.",".tbl_shorturldb::col_linkkey.",".
				tbl_shorturldb::col_linkvisits.",".tbl_shorturldb::col_nooflinkrep.",".tbl_shorturldb::col_uid.",".tbl_shorturldb::col_url.") VALUES
				('$linkflag','$skey','0','0','$uid','$bigurl')",tbl_shorturldb::dbname)==SUCCESS)
		{
			return $skey;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * BLOCK A SHORTENED URL
	 * @param unknown $skey Shortened Key which is to be blocked
	 * @return string SUCCESS on successful block, FAILURE on failure
	 */
	public function blockshortenedurl($skey)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_shorturldb::tblname." SET ".tbl_shorturldb::col_linkflag."='3',".tbl_shorturldb::col_nooflinkrep."=".tbl_shorturldb::col_nooflinkrep."+1 WHERE ".tbl_shorturldb::col_linkkey."='$skey'",tbl_shorturldb::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * GET REAL URL WHEN Shortened key is given
	 * @param unknown $skey Shortened Key of URL
	 * @return unknown|string The Real URL on success, FAILURE on failure
	 */
	public function getrealurl($skey)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_shorturldb::tblname." WHERE ".tbl_shorturldb::col_linkkey."='$skey'",tbl_shorturldb::dbname);
		if($res!=EMPTY_RESULT)
		{
			$url=$res[0][changesqlquote(tbl_shorturldb::col_url,"")];
			return $url;
		}
		else
		{
			throw new Exception('#ta@0000000_0000121');
			return FAILURE;
		}
	}

	/**
	 *
	 * CHANGE THE ORIGINAL URL BY GIVING THE SHORTURL KEY
	 * @param unknown $skey The shortened URL key
	 * @param unknown $uid User ID of the person who created the shortened URL
	 * @param unknown $newurl New URL to be changed
	 * @return string SUCCESS on successful change of URL, FAILURE on failure
	 */
	public function changebigurl($skey,$uid,$newurl)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbquery("SELECT * FROM ".tbl_shorturldb::tblname." WHERE ".tbl_shorturldb::col_linkkey."='$skey' AND ".tbl_shorturldb::col_uid."='$uid'",tbl_shorturldb::dbname)!=EMPTY_RESULT)
		{
			if($dbobj->dbupdate("UPDATE ".tbl_shorturldb::tblname." SET ".tbl_shorturldb::col_url."='$newurl' WHERE ".tbl_shorturldb::col_linkkey."='$skey' AND ".tbl_shorturldb::col_uid."='$uid'",tbl_shorturldb::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				return FAILURE;
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000120');
			return FAILURE;
		}
	}

	/**
	 *
	 * SEND A POST REQUEST TO A SCRIPT AND RECEIVE A RESPONSE ACCORDING TO THE OPTIONS SET
	 * @param unknown $baseurl The complete URL of the PHP script where the request has to be sent
	 * @param unknown $paramarray The parameter array with key value pairs
	 * eg. array('field1'=>$field1,'field2'=>$field2); Defaults to "" (Empty request)
	 * @param unknown $options The options externally defined by the user apart from the defaults
	 * DEFAULTS:
	 * array(
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_URL => $url,
				CURLOPT_FRESH_CONNECT => 1,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_FORBID_REUSE => 1,
				CURLOPT_TIMEOUT => 4,
				CURLOPT_POSTFIELDS => http_build_query($post)
		);
	 */
	public function send_request_post($baseurl,array $paramarray=array(),array $options = array())
	{
		$defaults = array(
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_URL => $baseurl,
				CURLOPT_FRESH_CONNECT => 1,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_FORBID_REUSE => 1,
				CURLOPT_TIMEOUT => 4,
				CURLOPT_POSTFIELDS => http_build_query($paramarray)
		);

		$ch = curl_init();
	    curl_setopt_array($ch, ($options + $defaults));
	    if(!$result = curl_exec($ch))
	    {
	        trigger_error(curl_error($ch));
	    }
	    curl_close($ch);
	    return $result;
	}

	/**
	 *
	 * SEND A GET REQUEST TO A SCRIPT AND RECEIVE A RESPONSE ACCORDING TO THE OPTIONS SET
	 * @param unknown $baseurl The complete URL of the PHP script where the request has to be sent
	 * @param unknown $paramarray The parameter array with key value pairs
	 * eg. array('field1'=>$field1,'field2'=>$field2); Defaults to "" (Empty request)
	 * @param unknown $options The options externally defined by the user apart from the defaults
	 * DEFAULTS:
	 * array(
	        CURLOPT_URL => $baseurl. (strpos($baseurl, '?') === FALSE ? '?' : ''). http_build_query($paramarray),
	        CURLOPT_HEADER => 0,
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_TIMEOUT => 4
    	);
	 */
	public function send_request_get($baseurl,array $paramarray=array(),array $options = array())
	{
		$defaults = array(
	        CURLOPT_URL => $baseurl. (strpos($baseurl, '?') === FALSE ? '?' : ''). http_build_query($paramarray),
	        CURLOPT_HEADER => 0,
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_TIMEOUT => 4
    	);

	    $ch = curl_init();
	    curl_setopt_array($ch, ($options + $defaults));
	    if( ! $result = curl_exec($ch))
	    {
	        trigger_error(curl_error($ch));
	    }
	    curl_close($ch);
	    return $result;
	}
	
	/**
	 * 
	 * GET THE FAVICON OF A WEBSITE WHEN URL IS PASSED TO IT
	 * @param unknown $url URL of the website whose favicon is to be fetched
	 * @return string The URL of the favicon if it has, "" if no favicon
	 */
	public function favicon_get($url)
	{
		$utilityobj=new ta_utilitymaster();
		$doc = new DOMDocument();
		$doc->strictErrorChecking = FALSE;
		@$doc->loadHTML(file_get_contents($url));
		$xml = simplexml_import_dom($doc);
		$arr = $xml->xpath('//link[@rel="shortcut icon"]');
		if (!empty($arr[0]['href'])) {
			
			if ((substr($arr[0]['href'], 0, 7) == 'http://') || (substr($arr[0]['href'], 0, 8) == 'https://')) {
				return $arr[0]['href'];
			}
			else
			{
				return $utilityobj->rel2abs($arr[0]['href'],parse_url($url)["host"]);
			}
		}
		else
			return "";
	}
	
	/**
	 * 
	 * GET DETAILS REGARDING A RELIGION BY GIVING RELIGID
	 * @param unknown $religid Religid to be passed to get religion info
	 * @return Ambigous <>|string The result as a array with appropriate keys as in DB
	 */
	public function religion_get($religid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_religion::tblname." WHERE ".tbl_religion::col_religid."='$religid'", tbl_religion::dbname);
		if($res!=EMPTY_RESULT)
		{
			return $res[0];
		}
		else
		{
			return EMPTY_RESULT;
		}
	}
	
	/**
	 * 
	 * GET LIST OF ALL RELIGIONS
	 * @return Ambigous <string, unknown>|string
	 */
	public function religion_get_all()
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_religion::tblname, tbl_religion::dbname);
		if($res!=EMPTY_RESULT)
		{
			return $res;
		}
		else
		{
			return EMPTY_RESULT;
		}
	}
	
	/**
	 * 
	 * GET DETAILS REGARDING A SKILL
	 * @param unknown $skillid Skill ID of skill to be fetched
	 * @return Ambigous <>|string Returns Array with keys as in DB
	 */
	public function skill_get($skillid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_skills::tblname." WHERE ".tbl_skills::col_skillid."='$skillid'", tbl_skills::dbname);
		if($res!=EMPTY_RESULT)
		{
			return $res[0];
		}
		else
		{
			return EMPTY_RESULT;
		}
	}
	
	/**
	 * 
	 * GET DETAILS REGARDING AN ACHIEVEMENT
	 * @param unknown $achievementid Achievement ID of the achievement
	 * @return Ambigous Returns Array with keys as in DB
	 */
	public function achievement_get($achievementid)
	{
		$colobj=new ta_collection();
		$res=$colobj->get_collection_complete_info(tbl_collection_achievements::tblname,tbl_collection_achievements::col_achievementid,$achievementid)[0];
		return $res;
	}
	
	/**
	 * 
	 * EDIT ACHIEVEMENT OF A USER
	 * @param unknown $col_achieveid Collection ID of the achievement
	 * @param unknown $achievementid Achievement ID of the item
	 * @param unknown $colname Column name to be edited
	 * @param unknown $value Value to be substituted
	 * @return Ambigous Returns SUCCESS on success FAILURE on failure
	 */
	public function achievement_edit($col_achieveid,$achievementid,$colname,$value)
	{
		$colobj=new ta_collection();
		return $colobj->edit_collection_item(tbl_collection_achievements::tblname,tbl_collection_achievements::col_col_achieveid,$col_achieveid,tbl_collection_achievements::col_achievementid,$achievementid,$colname,$value);
	}
	
	/**
	 * 
	 * CONVERT A SKILL FROM LABEL TO ID
	 * @param unknown $skill Skill to be converted
	 * @return string Returns skill id
	 */
	public function skilltoid($skill)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_skills::tblname." WHERE ".tbl_skills::col_label."='$skill'", tbl_skills::dbname);
	if($res!=EMPTY_RESULT)
		{
			return $res[0]["skillid"];
		}
		else
		{
			return EMPTY_RESULT;
		}
	}
	
	/**
	 * 
	 * ADD A JSON DATA TO THE JSON DB
	 * @param unknown $jsondata The JSON DATA as string
	 * @return string/object Returns JSON ID
	 */
	public function jsondata_add($jsondata)
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		
		$jsonid=$uiobj->randomstring(30,tbl_jsondb::dbname,tbl_jsondb::tblname,tbl_jsondb::col_jsonid);
		
		if($dbobj->dbinsert("INSERT INTO ".tbl_jsondb::tblname." (".tbl_jsondb::col_jsonid.",".tbl_jsondb::col_jsondata.") VALUES ('$jsonid','$jsondata')", tbl_jsondb::dbname)==SUCCESS)
		{
			return $jsonid;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * DELETE A JSON DATA FROM JSON DB
	 * @param unknown $jsonid JSON ID
	 * @return string/object Returns DBDELETE result
	 */
	public function jsondata_remove($jsonid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_jsondb::tblname." WHERE ".tbl_jsondb::col_jsonid."='$jsonid'", tbl_jsondb::dbname);
	}
	
	/**
	 * 
	 * GET JSON DATA FROM THE JSON DB
	 * @param unknown $jsonid JSON ID
	 * @param string $format Format in which result is wanted (1-(Default)JSON Object,2-String,3-Ass. Array,4-XML)
	 * @return string|unknown Returns the result
	 */
	public function jsondata_get($jsonid,$format="1",$lock="")
	{
		if($jsonid=="")return FALSE;
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_jsondb::tblname." WHERE ".tbl_jsondb::col_jsonid."='$jsonid' LIMIT 0,1";
		if($lock!="")
		{
			$sql.=" FOR UPDATE";
		}
		$res=$dbobj->dbquery($sql, tbl_jsondb::dbname);
		if(count($res)==0)return EMPTY_RESULT;
		$jsondata=$res[0][changesqlquote(tbl_jsondb::col_jsondata,"")];
		switch ($format)
		{
			case "1":
				$data_new=json_decode($jsondata);
				break;
			case "2":
				$data_new=$jsondata;
				break;
			case "3":
				$data_new=json_decode($jsondata,true);
				break;
			case "4":
				$data_new=$this->json_to_xml($jsondata);
				break;
			default:
				$data_new=$jsondata;
				break;
		}
		return $data_new;
	}
	
	/**
	 * 
	 * CONVERT JSON STRING TO XML
	 * @param unknown $json JSON
	 * @return XML string on success or NULL on failure
	 */
	public function json_to_xml($json)
	{
		require_once ROOT_SERVER."/XML/Serializer.php";
		$serializer = new XML_Serializer();
		$obj = json_decode($json);
		
		if ($serializer->serialize($obj)) {
			return $serializer->getSerializedData();
		}
		else {
			return null;
		}
	}
	
	/**
	 * 
	 * CONVERT XML TO ARRAY
	 * @param unknown $xml XML Node
	 * @param unknown $options
	 * @return multitype:Ambigous <multitype:, unknown> Returns Array
	 */
	public function xmlToArray($xml, $options = array()) 
	{
		$defaults = array(
				'namespaceSeparator' => ':',//you may want this to be something other than a colon
				'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
				'alwaysArray' => array(),   //array of xml tag names which should always become arrays
				'autoArray' => true,        //only create arrays for tags which appear more than once
				'textContent' => '$',       //key used for the text content of elements
				'autoText' => true,         //skip textContent key if node has no attributes or child nodes
				'keySearch' => false,       //optional search and replace on tag and attribute names
				'keyReplace' => false       //replace values for above search values (as passed to str_replace())
		);
		$options = array_merge($defaults, $options);
		$namespaces = $xml->getDocNamespaces();
		$namespaces[''] = null; //add base (empty) namespace
	
		//get attributes from all namespaces
		$attributesArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
				//replace characters in attribute name
				if ($options['keySearch']) $attributeName =
				str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
				$attributeKey = $options['attributePrefix']
				. ($prefix ? $prefix . $options['namespaceSeparator'] : '')
				. $attributeName;
				$attributesArray[$attributeKey] = (string)$attribute;
			}
		}
	
		//get child nodes from all namespaces
		$tagsArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->children($namespace) as $childXml) {
				//recurse into child nodes
				$childArray = xmlToArray($childXml, $options);
				list($childTagName, $childProperties) = each($childArray);
	
				//replace characters in tag name
				if ($options['keySearch']) $childTagName =
				str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
				//add namespace prefix, if any
				if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
	
				if (!isset($tagsArray[$childTagName])) {
					//only entry with this key
					//test if tags of this type should always be arrays, no matter the element count
					$tagsArray[$childTagName] =
					in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
					? array($childProperties) : $childProperties;
				} elseif (
						is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
						=== range(0, count($tagsArray[$childTagName]) - 1)
				) {
					//key already exists and is integer indexed array
					$tagsArray[$childTagName][] = $childProperties;
				} else {
					//key exists so convert to integer indexed array with previous value in position 0
					$tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
				}
			}
		}
	
		//get text content of node
		$textContentArray = array();
		$plainText = trim((string)$xml);
		if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
	
		//stick it all together
		$propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
		? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
	
		//return node as array
		return array(
				$xml->getName() => $propertiesArray
		);
	}
	
	/**
	 * 
	 * CONVERT XML TO JSON
	 * @param unknown $xmlnode The XML NODE eg:simplexml_load_file('example.xml');
	 * @return string The JSON string
	 */
	public function xml_to_json($xmlnode)
	{
		$arrayData = $this->xmlToArray($xmlnode);
		return json_encode($arrayData);
	}
	
	/**
	 * 
	 * UPDATE JSON DATA FROM JSON DB
	 * @param unknown $jsonid JSON ID
	 * @param unknown $newdata New data to be stored
	 * @return string/object Returns DBUPDATE result
	 */
	public function jsondata_edit($jsonid,$newdata)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_jsondb::tblname." SET ".tbl_jsondb::col_jsondata."='$newdata' WHERE ".tbl_jsondb::col_jsonid."='$jsonid'", tbl_jsondb::dbname);
	}
	
	/**
	 * 
	 * CONVERT JSON TO OBJECT
	 * @param unknown $json The JSON DATA
	 * @return mixed
	 */
	public function json_to_object($json)
	{
		return json_decode($json);
	}
	
	/**
	 * 
	 * CONVERT OBJECT TO JSON STRING
	 * @param unknown $object Returns JSON STRING
	 */
	public function object_to_json($object)
	{
		return json_encode($object);
	}
	
	/**
	 * GET LANGUAGE INFORMATION
	 * @param unknown $langid Language ID
	 * @return Returns DBArray of results
	 */
	public function language_get($langid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_languages::tblname." WHERE ".tbl_languages::col_langcode."='$langid' LIMIT 0,1", tbl_languages::dbname);
	}
	
	/**
	 * 
	 * GET LIST OF ALL LANGUAGES
	 */
	public function languages_get_all()
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_languages::tblname, tbl_languages::dbname);
	}
	
	/**
	 * 
	 * GET COUNTRY INFO FROM CODE
	 * @param unknown $code
	 */
	public function countryfromcode($code)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_country::tblname." WHERE ".tbl_country::col_code2l."='$code' LIMIT 0,1", tbl_country::dbname);
	}
	
	/**
	 * 
	 * GET STATE FROM ID
	 * @param unknown $id
	 */
	public function statefromid($id)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_states::tblname." WHERE ".tbl_states::col_id."='$id' LIMIT 0,1", tbl_states::dbname);
	}
}