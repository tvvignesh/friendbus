<?php
/**
 *
 * CONTAINS USER INFORMATION AND RELATED FUNCTIONS, COOKIES, SESSIONS,etc.
 * @author T.V.VIGNESH
 *
 */
class ta_userinfo
{
	public  $fname,$mname,$lname,$uname,$pass,$email,$dob,$gender,$mobno,$phone,$country,$state,$pincode,$compaddr,$aggraccept,$expaccept,$newsaccept,$uid,$profpicurl,$compprofpic1,$compprofpic2,$loginstatus,$verifyid,$uflag,$docroot,$reppoints,$accountstatus,$age,$col_langid,$col_locid,$loginstat;
	public	$extras;

	/**
	 *
	 * INITIALIZE USER DATA (ASSIGNS VALUE TO THE SAME OBJECT CALLING IT)
	 *
	 * @return string "" on failure, "SUCCESS" on success
	 */

	public function userinit() 
	{
		$userobj=new ta_userinfo();
		if(!$userobj->checklogin())
		{
			throw new Exception('#ta@0000000_0000000');
			return FAILURE;
		}

		$uname=$userobj->sessionget("uname");
		if($uname==BOOL_FAILURE)
		{
			throw new Exception('#ta@0000000_0000000');return FAILURE;
		}
		$sesskey=$userobj->sessionget("usrsessionkey");
		if($sesskey==BOOL_FAILURE)
		{
			throw new Exception('#ta@0000000_0000000');return FAILURE;
		}
		//$dbobj=new ta_dboperations();
		//$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_usessionid."='$sesskey' LIMIT 0,1",tbl_user_info::dbname);
		/*$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' LIMIT 0,1",tbl_user_info::dbname);
		if($res==EMPTY_RESULT)
		{
			throw new Exception('#ta@0000000_0000001');return FAILURE;
		}

		$usrid=$res[0][changesqlquote(tbl_user_info::col_usrid,"")];*/
		$usrid=$GLOBALS["usrid_id"];
		$GLOBALS["qcache_2"]="1";
		$res=$this->user_initialize_data($usrid);

		return $res;
	}

	public function user_initialize_data($usrid,$vuid="",$refresh_data="")
	{
		if($vuid=="")
		{
			$vuid=$usrid;
		}
		
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		$colobj=new ta_collection();
		$socialobj=new ta_socialoperations();
		
		$res=$this->user_getinfo($usrid);
		if($res==EMPTY_RESULT)
		{
			throw new Exception('#ta@0000000_0000001');return FAILURE;
		}
		if(isset($GLOBALS[$usrid]["usrdata"]["extrainfo"])&&$refresh_data=="")
		{
			$result1=$GLOBALS[$usrid]["usrdata"]["extrainfo"];
		}
		else
		{
			$result1=$dbobj->dbquery("SELECT * FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$usrid' LIMIT 0,1",tbl_user_extras::dbname);
			$GLOBALS[$usrid]["usrdata"]["extrainfo"]=$result1;
		}		

		$this->uid=$res[changesqlquote(tbl_user_info::col_usrid,"")];
		$this->fname=$res[changesqlquote(tbl_user_info::col_ufname,"")];
		$this->mname=$res[changesqlquote(tbl_user_info::col_umname,"")];
		$this->lname=$res[changesqlquote(tbl_user_info::col_ulname,"")];
		$this->uname=$res[changesqlquote(tbl_user_info::col_unm,"")];
		$this->pass=$res[changesqlquote(tbl_user_info::col_upass,"")];
		$this->email=$res[changesqlquote(tbl_user_info::col_uemail,"")];
		$this->dob=$res[changesqlquote(tbl_user_info::col_udob,"")];
		$this->gender=$res[changesqlquote(tbl_user_info::col_ugender,"")];
		$this->mobno=$res[changesqlquote(tbl_user_info::col_umobno,"")];
		$this->phone=$res[changesqlquote(tbl_user_info::col_uphone,"")];
		$this->country=$res[changesqlquote(tbl_user_info::col_ucountry,"")];
		$this->state=$res[changesqlquote(tbl_user_info::col_ustate,"")];
		$this->pincode=$res[changesqlquote(tbl_user_info::col_upincode,"")];
		$this->compaddr=$res[changesqlquote(tbl_user_info::col_uaddress,"")];
		$this->aggraccept=$res[changesqlquote(tbl_user_info::col_uaggreementaccept,"")];
		$this->expaccept=$res[changesqlquote(tbl_user_info::col_uuserexp,"")];
		$this->newsaccept=$res[changesqlquote(tbl_user_info::col_usubscribe,"")];//TODO REMOVE THIS NEWS ACCEPT
		$this->profpicurl=$res[changesqlquote(tbl_user_info::col_profpicurl,"")];
		$this->compprofpic1=$res[changesqlquote(tbl_user_info::col_cprofpic1,"")];
		$this->compprofpic2=$res[changesqlquote(tbl_user_info::col_cprofpic2,"")];
		$this->docroot=$res[changesqlquote(tbl_user_info::col_docroot,"")];
		$this->accountstatus=$res[changesqlquote(tbl_user_info::col_uflag,"")];
		$this->reppoints=$userobj->getpoints($this->uid);
		$this->age=$this->getage($this->dob);
		$this->col_locid=$res[changesqlquote(tbl_user_info::col_col_userlocationid,"")];
		$this->col_langid=$res[changesqlquote(tbl_user_info::col_col_ulangid,"")];
		$this->loginstat=$res[changesqlquote(tbl_user_info::col_uloginstatus,"")];
		
		
		if($this->reppoints=="")
		{
			$this->reppoints=0;
		}
		
		$this->work=$userobj->work_get($usrid);
		
		if($result1!=EMPTY_RESULT)
		{
			$result1=$result1[0];
			
			$res_col=$colobj->get_collection_complete_info(tbl_collection_links::tblname,tbl_collection_links::col_col_linkid,$result1[changesqlquote(tbl_user_extras::col_col_social,"")]);
			$res_links=NULL;
			if($res_col!=EMPTY_RESULT)
			{
				for($i=0;$i<count($res_col);$i++)
				{
					$res_links[$i]=$utilityobj->link_get($res_col[$i][changesqlquote(tbl_collection_links::col_linkid,"")]);
				}
			}
			
			$res_col1=$colobj->get_collection_complete_info(tbl_collection_skills::tblname,tbl_collection_skills::col_col_skillid,$result1[changesqlquote(tbl_user_extras::col_col_skillid,"")]);
			$res_skills=NULL;
			if($res_col1!=EMPTY_RESULT)
			{
				for($i=0;$i<count($res_col1);$i++)
				{
					$res_skills[$i]=$utilityobj->skill_get($res_col1[$i][changesqlquote(tbl_collection_skills::col_skillid,"")]);
				}
			}
			
			$religs=$utilityobj->religion_get($result1[changesqlquote(tbl_user_extras::col_religid,"")]);
			$religion=$religs["label"];
			$religid=$religs["religid"];			
			
			//GETTING USER EXTRAS
			$this->extras=(object) array(
					'resume' => $result1[changesqlquote(tbl_user_extras::col_resume,"")], 
					'coverletter' => $result1[changesqlquote(tbl_user_extras::col_coverletter,"")],
					'biodata' => $result1[changesqlquote(tbl_user_extras::col_biodata,"")],
					'recommendations' => $result1[changesqlquote(tbl_user_extras::col_recommendations,"")],
					'rec_gp' => $result1[changesqlquote(tbl_user_extras::col_rec_groups,"")],
					'sociallinks' => $res_links,
					'religion'=>$religion,
					'religid'=>$religid,
					'politicalview'=>$result1[changesqlquote(tbl_user_extras::col_politicalview,"")],
					'skills'=>$res_skills,
					'achievements'=>$this->achievement_get_user($usrid),
					'col_achievementid'=>$result1[changesqlquote(tbl_user_extras::col_col_achievementid,"")],
					'work'=>$this->work_get($usrid),
					'education'=>$this->education_get($usrid),
					'groups'=>$socialobj->getgroups($usrid),
					'devices'=>$userobj->device_get_all($usrid),
					'aliases'=>$result1[changesqlquote(tbl_user_extras::col_aliases,"")],
					'relstat'=>$result1[changesqlquote(tbl_user_extras::col_relstat,"")],
					'scribbles'=>$result1[changesqlquote(tbl_user_extras::col_scribbles,"")],
					'cfeedaudid'=>$result1[changesqlquote(tbl_user_extras::col_cfeedaudid,"")],
					'bgprofpic'=>$result1[changesqlquote(tbl_user_extras::col_bgprofpic,"")]
			);
		}
		
		return SUCCESS;
	}
	
	/**
	 * 
	 * GET BASIC USER INFO FROM USER DB
	 * @param unknown $uid UID of the person whose record is to be fetched
	 * @return Ambigous <string, unknown> Returns an array with keys as in DB
	 */
	public function user_getinfo($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		if(isset($GLOBALS[$uid]["usrdata"]["basicinfo"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["basicinfo"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid' LIMIT 0,1", tbl_user_info::dbname);
			$GLOBALS[$uid]["usrdata"]["basicinfo"]=$res;
		}		
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
	 * GET THE FULL NAME OF A
	 * @param unknown $uid
	 * @return Returns the full name
	 */
	public function user_getfullname($uid,$ind="")
	{
		$dbobj=new ta_dboperations();
		if($uid=="")return "";
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid' LIMIT 0,1", tbl_user_info::dbname);
		if(count($res)==0)return "";
		$fname=$res[0][changesqlquote(tbl_user_info::col_ufname,"")];
		$mname=$res[0][changesqlquote(tbl_user_info::col_umname,"")];
		$lname=$res[0][changesqlquote(tbl_user_info::col_ulname,"")];
		if($ind=="1")
		{
			return $fname;
		}
		return ucfirst($fname." ".$mname." ".$lname);
	}
	
	/**
	 * 
	 * GET THE DOCUMENT ROOT OF A USER
	 * @param unknown $uid UID of the user whose document root is to be fetched
	 * @param string $refresh_data
	 * @return The document root on success
	 */
	public function user_get_docroot($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		if(isset($GLOBALS[$uid]["usrdata"]["basicinfo"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["basicinfo"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid' LIMIT 0,1", tbl_user_info::dbname);
			$GLOBALS[$uid]["usrdata"]["basicinfo"]=$res;
		}		
		if($res!=EMPTY_RESULT)
		{
			return $res[0][changesqlquote(tbl_user_info::col_docroot,"")];
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * RETURN USER ID WHEN USER NAME IS GIVEN
	 * @param unknown $uname User Name
	 * @return Ambigous <string, unknown>|Ambigous <> Returns User ID, EMPTY_RESULT if empty
	 */
	public function user_unametouid($uname)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' LIMIT 0,1", tbl_user_info::dbname);
		if($res==EMPTY_RESULT)return $res;
		$uid=$res[0][changesqlquote(tbl_user_info::col_usrid,"")];
		return $uid;
	}
		
	public function user_getextrainfo($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$uid'", tbl_user_extras::dbname);
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
	 * EDIT EXTRA INFO OF A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $colname Name of column to be edited
	 * @param unknown $value Value to be substituted
	 * @return string/object Returns DBUPDATE result
	 */
	public function user_editextrainfo($uid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_user_extras::tblname." SET ".$colname."='$value' WHERE ".tbl_user_extras::col_uid."='$uid'", tbl_user_extras::dbname);
	}
	
	/**
	 * 
	 * EDIT USER DETAILS
	 * @param unknown $uid UID of the user
	 * @param unknown $colname Column to be edited
	 * @param unknown $value Value to be substituted
	 * @return string/object Returns DBUPDATE result
	 */
	public function user_editinfo($uid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		if($colname==tbl_user_info::col_unm)
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$value'", tbl_user_info::dbname);
			if(count($res)!=0)return FAILURE;
		}
		return $dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".$colname."='$value' WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname);
	}

	/**
	 *
	 * GET THE AGE OF A PERSON
	 * @param unknown $dob The date of birth of the user
	 * RETURNS THE DIFFERENCE DATE ARRAY
	 */
	public function getage($dob)
	{
		$dateobj=new ta_dateoperations();
		return $dateobj->datediff($dob,'today');
	}

	/**
	 *
	 * GET IP ADDRESS OF A USER
	 *
	 * @return string IP ADDRESS OF THE USER
	 */
	public static function getip()
	{
		$ip="UNKNOWN";
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "UNKNOWN";
		return $ip;
	}

	/**
	 *
	 * CHECK LOGIN STATUS OF THE USER
	 *
	 * @return boolean true on success false on failure
	 */
	public function checklogin()
	{
		$encobj=new ta_secureclass();
		$userobj=new ta_userinfo();
		$skey=$userobj->sessionget("usrsessionkey");
		$uname=$userobj->sessionget("uname");
		if(!($skey)||!($uname))
		{
			return BOOL_FAILURE;
		}
		$dbobj=new ta_dboperations();
		if(!isset($GLOBALS["qcache_1"]))
		{
			$resquery=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' LIMIT 0,1",tbl_user_info::dbname);
		}
		else
		{
			$resquery=$GLOBALS["qcache_1"];
		}
		
		$cookieval=$userobj->cookieget($uname);
		if(!$cookieval)return BOOL_FAILURE;
		if($resquery==EMPTY_RESULT)return BOOL_FAILURE;
		$uid=$resquery[0][changesqlquote(tbl_user_info::col_usrid,"")];

		$res=$encobj->decrypt($cookieval,$skey);
		$enc=$uid;
		
		$GLOBALS["qcache_1"]=$resquery;
		$GLOBALS["usrid_id"]=$uid;
		$GLOBALS["cprofpic2"]=$resquery[0][changesqlquote(tbl_user_info::col_cprofpic2,"")];

		if($res==$enc)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}

	}
	
	public function getuid()
	{
		if(isset($GLOBALS["usrid_id"]))
		return $GLOBALS["usrid_id"];
		else
		return "";
	}
	
	/**
	 * 
	 * GET PROFILE PIC OF A USER
	 * @param string $uid UID of the user
	 * @param string $size Size of pic to be fetched (1-Normal,2-Medium,3-Small)
	 */
	public function getprofpic($uid="",$size="3")
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$colname=tbl_user_info::col_profpicurl;
		switch ($size)
		{
			case "1":$colname=tbl_user_info::col_profpicurl;break;
			case "2":$colname=tbl_user_info::col_cprofpic1;break;
			case "3":$colname=tbl_user_info::col_cprofpic2;break;
			default:$colname=tbl_user_info::col_profpicurl;break;
		}
		if($uid!="")
		{
			$res=$dbobj->dbquery("SELECT ".$colname." FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid'", tbl_user_info::dbname);
			$mypic=$res[0][changesqlquote($colname,"")];
			if($mypic=="")
			{
				$mypic="/master/securedir/m_images/image-not-found.png";
			}
			return $mypic;
		}
		else
		{
			if($userobj->checklogin())
			{
				$userobj->userinit();
				$res=$dbobj->dbquery("SELECT ".$colname." FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$userobj->uid'", tbl_user_info::dbname);
				$mypic=$res[0][changesqlquote($colname,"")];
				if($mypic=="")
				{
					$mypic="/master/securedir/m_images/image-not-found.png";
				}
				return $mypic;
			}
			else
			{
				return FAILURE;
			}
		}
	}

	/**
	 *
	 * LOGIN A USER
	 * @param $usrname string USER NAME
	 * @param $password string Password
	 * @param $remember string 1-remember 2-dont remember
	 * @return user's object
	 */
	public function login($usrname,$password,$remember="0")
	{
		$dbobj=new ta_dboperations();
		$encobj=new ta_secureclass();
		$password=$encobj->encryptpass($password);
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$usrname' AND ".tbl_user_info::col_upass."='$password' LIMIT 0,1",tbl_user_info::dbname);
		if($res==EMPTY_RESULT)
		{
			throw new Exception('#ta@0000000_0000002');
			return FAILURE;
		}
		else
		{
			$uiobj=new ta_uifriend();
			$randstr=$uiobj->randomstring(10);
			$code=$dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_usessionid."='$randstr' WHERE ".tbl_user_info::col_unm."='$usrname' AND ".tbl_user_info::col_upass."='$password'",tbl_user_info::dbname);
			if($code!=SUCCESS)
			{
				throw new Exception('#ta@0000000_0000003');
			}
			else
			{
				if(headers_sent())
				{
					die("OOPS! Cannot login! Please close your browser and open it again!");
				}

				$userobj=new ta_userinfo();
				$userobj->sessionset("usrsessionkey",$randstr);
				$uid=$res[0][changesqlquote(tbl_user_info::col_usrid,"")];
				$uname=$res[0][changesqlquote(tbl_user_info::col_unm,"")];

				$userobj->logactivity($uid,"#act_0000000@0000001","Logged In");

				$userobj->sessionset("uname",$uname);
				$result=$encobj->encrypt($uid,$randstr);
				if($remember=="0")
				{
					$userobj->cookieset($uname,$result,time()+(10 * 365 * 24 * 60 * 60));
				}
				else
				if($remember=="1")
				{
					$userobj->cookieset($uname,$result,0);
				}

				return SUCCESS;
				//118
			}
		}
	}

	/**
	 *
	 * SET A COOKIE
	 * @param $key string Key of the COOKIE
	 * @param $val string Value of the COOKIE
	 * @param $expiry longint Expiry time of the cookie
	 * @param $path string Path of the domain, Default:/
	 * @param $domain string Domain setting the cookie, Default:"" Ignores if empty
	 * @param $secure string Secure or not (HTTPS), Default:""
	 * @param $httponly string Set by HTTP or not Default:""
	 * @return void
	 */
	public function cookieset($key,$val,$expiry=0,$path="/",$domain="",$secure="",$httponly="")
	{
		if($domain=="")
		{
			setcookie($key,$val,$expiry,$path);
		}
		else
		{
			setcookie($key,$val,$expiry,$path,$domain);
		}
	}

	/**
	 *
	 * GET A COOKIE
	 * @param $key string Key of the COOKIE
	 * @return string Cookie Value
	 */
	public function cookieget($key)
	{
		if(isset($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * DELETE A COOKIE
	 * @param $key string Key of the COOKIE
	 * @param $path string Path of the domain, Default:/
	 * @return string "" if unsuccessful,"SUCCESS" if successful
	 */
	public function cookiedelete($key,$path="/")
	{
		if(setcookie($key,"",time()-3600,$path))
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
	 * DESTROY ALL COOKIES SET
	 * @return void
	 */
	public function cookiedestroy()
	{
		if (isset($_SERVER['HTTP_COOKIE'])) {
		    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		    foreach($cookies as $cookie) {
		        $parts = explode('=', $cookie);
		        $name = trim($parts[0]);
		        setcookie($name, '', time()-1000);
		        setcookie($name, '', time()-1000, '/');
		    }
		}
	}

	/**
	 *
	 * SET A SESSION KEY,VALUE PAIR
	 * @param $key string Key of the SESSION
	 * @param $val string Value of the SESSION
	 * @return string "SUCCESS" on setting successfully
	 */
	public function sessionset($key,$val)
	{
		$_SESSION[$key]=$val;
		return SUCCESS;
	}

	/**
	 *
	 * GET A SESSION VALUE
	 * @param $key string Key of the SESSION
	 * @return string Session Value
	 */
	public function sessionget($key)
	{
		if(isset($_SESSION[$key]))
		{return $_SESSION[$key];}
		else
		{return BOOL_FAILURE;}
	}

	/**
	 *
	 * DESTROYS A SESSION
	 * @return void
	 */
	public function sessiondestroy()
	{
		session_destroy();
	}

	/**
	 *
	 * REMOVE A KEY VALUE PAIR FROM SESSION
	 * @param $key string Key of the SESSION
	 * @return void
	 */
	public function sessionremove($key)
	{
		if(isset($_SESSION[$key]))
		{
			unset($_SESSION[$key]);
		}
	}

	/**
	 *
	 * REMOVES ALL VARIABLES FROM A SESSION
	 * @return void
	 */
	public function sessionunset()
	{
		session_unset();
	}

	/**
	 *
	 * LOGOUT A USER AND REDIRECT TO A PAGE
	 * @param unknown_type $path URL of Page to which redirection is made after Logout (Defaults to index.php)
	 */
	public function logout($path="/index.php")
	{
		$userobj=new ta_userinfo();
		$userobj->sessiondestroy();
		$userobj->cookiedestroy();
		unset($userobj);
		$uiobj=new ta_uifriend();
		$utilityobj=new ta_utilitymaster();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		echo "Logging out.. Please wait.. If you are not redirected to the home page, <a href='index.php'>Click Here</a>";
		$uiobj->returnpath($path);
	}

	/**
	 *
	 * ADD REPUTATION POINTS TO A USER
	 * @param unknown_type $uid User ID of person for which points are to be added
	 * @param unknown_type $points Points to be added to the User
	 * @param unknown_type $reason Reason for Adding Points
	 * @param unknown_type $appid APPID of program adding point
	 * @return string SUCCESS on successful addition, "" on failure
	 */
	public function addreppoints($uid,$points,$reason,$appid="00000")
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_user_reppoints::tblname." (".tbl_user_reppoints::col_uid.",".tbl_user_reppoints::col_points.",".tbl_user_reppoints::col_reason.",".tbl_user_reppoints::col_appid.") VALUES ('$uid','$points','$reason','$appid')",tbl_user_reppoints::dbname)=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000005');
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE REPUTATION POINTS FROM A USER
	 * @param unknown_type $uid User ID of person for which points are to be removed
	 * @param unknown_type $points Points to be removed from the User
	 * @param unknown_type $reason Reason for removing points
	 * @param unknown_type $appid APPID of program removing point
	 * @return string SUCCESS on successful removal, "" on failure
	 */
	public function removereppoints($uid,$points,$reason,$appid="00000")
	{
		$points=0-$points;
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$oldpoint=$userobj->getpoints($uid);
		if($oldpoint-$points<0)
		{
			throw new Exception('#ta@0000000_0000006');
			return FAILURE;
		}
		if($dbobj->dbinsert("INSERT INTO ".tbl_user_reppoints::tblname." (".tbl_user_reppoints::col_uid.",".tbl_user_reppoints::col_points.",".tbl_user_reppoints::col_reason.",".tbl_user_reppoints::col_appid.") VALUES ('$uid','$points','$reason','$appid')",tbl_user_reppoints::dbname)=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000007');
			return FAILURE;
		}
	}

	/**
	 *
	 * GET POINTS OF A USER
	 * @param unknown_type $uid User ID of person for whose points is requested
	 * @return number The points of the user
	 */
	public function getpoints($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_reppoints::tblname." WHERE ".tbl_user_reppoints::col_uid."='$uid'",tbl_user_reppoints::dbname);
		if(count($res)==0)
		{
			return 0;
		}
		$points=0;
		for($i=0;$i<count($res);$i++)
		{
			$points+=$res[$i][changesqlquote(tbl_user_reppoints::col_points,"")];
		}
		return $points;
	}
	
	/**
	 *
	 * GET POINT LOG OF A USER
	 * @param unknown_type $uid User ID of person for whose point log is requested
	 * @return DBArray
	 */
	public function getpointlog($uid,$start="0",$tot="20")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_reppoints::tblname." WHERE ".tbl_user_reppoints::col_uid."='$uid' LIMIT $start,$tot",tbl_user_reppoints::dbname);
		return $res;
	}

	/**
	 *
	 * REMOVE A USER ACCOUNT
	 * @param unknown_type $uid User ID of person whose account is to be removed
	 * @return string SUCCESS on successful removal, "" on failure
	 */
	public function user_account_delete($uid)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		
		$logobj->store_templogs($uid." OPTED FOR DELETION");
		
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_uflag."='4' WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000008');
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * DELETE A USER ACCOUNT AND ALL ASSOCIATED DATA PERMANENTLY
	 * @param unknown $uid UID of the user
	 */
	public function user_account_delete_process($uid)
	{
		$galobj=new ta_galleryoperations();
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$fileobj=new ta_fileoperations();
		
		$userobj->user_initialize_data($uid);
		$res1=$galobj->gallery_get_user($uid);
		for($j=0;$j<count($res1);$j++)
		{
			$galid=$res1[$j][changesqlquote(tbl_galinfo::col_galid,"")];
			$galobj->deletegallery($galid,$uid);
		}
		$fileobj->delTree($userobj->docroot);
		
		$dbobj->dbdelete("DELETE FROM ".tbl_user_activity::tblname." WHERE ".tbl_user_activity::col_uid."='$uid'", tbl_user_activity::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_devices::tblname." WHERE ".tbl_user_devices::col_uid."='$uid'", tbl_user_devices::dbname);		
		$dbobj->dbdelete("DELETE FROM ".tbl_user_edu::tblname." WHERE ".tbl_user_edu::col_uid."='$uid'", tbl_user_edu::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$uid'", tbl_user_extras::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_health::tblname." WHERE ".tbl_user_health::col_uid."='$uid'", tbl_user_health::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$uid'", tbl_user_info::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_interests::tblname." WHERE ".tbl_user_interests::col_uid."='$uid'", tbl_user_interests::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_profilehits::tblname." WHERE ".tbl_user_profilehits::col_uid."='$uid'", tbl_user_profilehits::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_reppoints::tblname." WHERE ".tbl_user_reppoints::col_uid."='$uid'", tbl_user_reppoints::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_settings::tblname." WHERE ".tbl_user_settings::col_uid."='$uid'", tbl_user_settings::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_user_work::tblname." WHERE ".tbl_user_work::col_uid."='$uid'", tbl_user_work::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listuid."='$uid'", tbl_listinfo::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_uid."='$uid'", tbl_listsdb::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_uid."='$uid'", tbl_notifications::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid'", tbl_notifications::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_tuid."='$uid'", tbl_notifications::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_uid."='$uid'", tbl_notifications::dbname);
		$dbobj->dbdelete("DELETE FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_fuid."='$uid'", tbl_notifications::dbname);
		
		return SUCCESS;
	}
	
	/**
	 * 
	 * A CRON FUNCTION USED TO DELETE ALL USER RECORDS AND RELATED DATA WHICH IS FLAGGED FOR DELETION
	 * 
	 */
	public function user_delete_cron()
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$fileobj=new ta_fileoperations();
		$userobj=new ta_userinfo();
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_uflag."='4'", tbl_user_info::dbname);
		
		$uidarr=Array();
		for($i=0;$i<count($res);$i++)
		{
			$uidarr[$i]=$res[changesqlquote(tbl_user_info::col_usrid,"")];
			$emailarr[$i]=$res[changesqlquote(tbl_user_info::col_uemail,"")];
			$docroot[$i]=$res[changesqlquote(tbl_user_info::col_docroot,"")];
		}
		
		$logobj->store_templogs("STARTED DELETING USER RECORDS FROM DRIVE");
		
		for($i=0;$i<count($uidarr);$i++)
		{
			$tuid=$uidarr[$i];
			$tdoc=$docroot[$i];
			
			$userobj->user_account_delete_process($tuid);
			@$fileobj->delTree($tdoc);
			
			$logobj->store_templogs("DELETED A USER RECORD. EMAIL:".$emailarr[$i]);
		}
		
		$logobj->store_templogs("FINISHED DELETING ".count($uidarr)." RECORDS");
	}

	/**
	 *
	 * DEACTIVATE A USER ACCOUNT
	 * @param unknown_type $uid User ID of person for whom account is deactivated
	 * @return string SUCCESS on successful deactivation, "" on failure
	 */
	public function user_account_deactivate($uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_uflag."='5' WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000009');
			return FAILURE;
		}
	}

	/**
	 *
	 * ACTIVATE USER ACCOUNT
	 * @param unknown_type $uid User ID of person for whom account is to be activated
	 * @return string SUCCESS on successful activation, "" on failure
	 */
	public function user_account_activate($uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_uflag."='2' WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000010');
			return FAILURE;
		}
	}

	/**
	 *
	 * BLOCK A USER ACCOUNT
	 * @param unknown_type $uid User ID of person for whom account has to be blocked
	 * @return string SUCCESS on blocking, "" on failure
	 */
	public function user_account_block($uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_uflag."='3' WHERE ".tbl_user_info::col_usrid."='$uid'",tbl_user_info::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000011');
			return FAILURE;
		}
	}

	/**
	 *
	 * CREATES A FAVOURITE GALLERY
	 * @param unknown_type $uid User ID of person creating the gallery
	 * @param unknown_type $favgalname Favourite Gallery Name
	 * @param unknown_type $favdesc Favourite gallery description
	 * @param unknown_type $parentfavgalid Gallery ID of the parent favourite gallery
	 * @param unknown_type $favpic Cover Pic of the favourite gallery
	 * @param unknown_type $shareid List ID from LISTDB for sharing
	 * @param unknown_type $tagid Tag ID from TAGDB for tagging the gallery
	 * @param unknown_type $favflag Allowed Value (1-Allowed,2-Under Review,3-Blocked)
	 * @return Ambigous <Ambigous, string, unknown> Returns Favourite Gallery ID
	 */
	public function createfavouritegal($uid,$favgalname="Favourites",$favdesc="My favourite gallery",$parentfavgalid="",$favpic="",$shareid="",$tagid="",$favflag="1")
	{
		$galobj=new ta_galleryoperations();
		$favgalid=$galobj->creategallery($favgalname,$favdesc,"1",$uid,"6",$parentfavgalid,$favpic,$favflag,$shareid,$tagid);
		return $favgalid;
	}

	/**
	 *
	 * ADD A FAVOURITE
	 * @param unknown_type $url URL to be marked as favourite
	 * @param unknown_type $uid User ID of person marking URL as favourite
	 * @param unknown_type $favgalid Gallery ID of the favourite gallery where info has to be added
	 * @param unknown_type $favname Name of the User FAVOURITE
	 * @param unknown_type $favdesc Favourite Description
	 * @return string favid on successful add, FAILURE on failure
	 */
	public function addfavourite($url,$uid,$favgalid,$favname="",$favdesc="")
	{
		$uiobj=new ta_uifriend();
		$favid=$uiobj->randomstring(50,tbl_url_bookmarks::dbname,tbl_url_bookmarks::tblname,tbl_url_bookmarks::col_favid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_url_bookmarks::tblname." (".tbl_url_bookmarks::col_favid.",".tbl_url_bookmarks::col_favname.",".tbl_url_bookmarks::col_uid.",".tbl_url_bookmarks::col_favdesc.",".tbl_url_bookmarks::col_favurl.",".tbl_url_bookmarks::col_galid.") VALUES
				('$favid','$favname','$uid','$favdesc','$url','$favgalid')",tbl_url_bookmarks::dbname)==SUCCESS)
		{
			return $favid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE A USER FAVOURITE
	 * @param unknown_type $favid FAVOURITE ID of fav to be removed
	 * @param unknown_type $uid User ID of person who created the favourite
	 * @return string SUCCESS on successful removal, "" on failure
	 */
	public function removefavourite($favid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_url_bookmarks::tblname." WHERE ".tbl_url_bookmarks::col_favid."='$favid' AND ".tbl_url_bookmarks::col_uid."='$uid'",tbl_url_bookmarks::dbname)=="SUCCESS")
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
	 * EDIT A FAVOURITE
	 * @param unknown_type $favid FAVOURITE ID of favourite to be edited
	 * @param unknown_type $uid User ID of person who created the favourite
	 * @param unknown_type $favflag Flag Value to identify columns (1-Favname,2-Favdesc,3-Fav URL)
	 * @param unknown_type $value Value to be inserted into the respective column
	 * @return string SUCCESS on success, "" on failure
	 */
	public function editfavourite($favid,$uid,$favflag="1",$value)
	{
		$dbobj=new ta_dboperations();
		switch($favflag)
		{
			case "1":$res=$dbobj->dbupdate("UPDATE ".tbl_url_bookmarks::tblname." SET ".tbl_url_bookmarks::col_favname."='$value' WHERE ".tbl_url_bookmarks::col_favid."='$favid' AND ".tbl_url_bookmarks::col_uid."='$uid'",tbl_url_bookmarks::dbname);break;
			case "2":$res=$dbobj->dbupdate("UPDATE ".tbl_url_bookmarks::tblname." SET ".tbl_url_bookmarks::col_favdesc."='$value' WHERE ".tbl_url_bookmarks::col_favid."='$favid' AND ".tbl_url_bookmarks::col_uid."='$uid'",tbl_url_bookmarks::dbname);break;
			case "3":$res=$dbobj->dbupdate("UPDATE ".tbl_url_bookmarks::tblname." SET ".tbl_url_bookmarks::col_favurl."='$value' WHERE ".tbl_url_bookmarks::col_favid."='$favid' AND ".tbl_url_bookmarks::col_uid."='$uid'",tbl_url_bookmarks::dbname);break;
			default:throw new Exception('#ta@0000000_0000014');return FAILURE;
		}
		if($res=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000015');
			return FAILURE;
		}
	}

	/**
	 *
	 * READ USER'S FAVOURITE
	 * @param unknown_type $uid User ID of the person who created the favourite
	 * @param unknown_type $favid Favourite ID of the favourite to be read (Defaults to "" which means all user favourites are read)
	 * @return Ambigous <string, unknown> The favourites as db array
	 */
	public function readfavourite($uid,$favid="")
	{
		$dbobj=new ta_dboperations();
		if($favid!="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_url_bookmarks::tblname." WHERE ".tbl_url_bookmarks::col_favid."='$favid' AND ".tbl_url_bookmarks::col_uid."='$uid' LIMIT 0,1",tbl_url_bookmarks::dbname);
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_url_bookmarks::tblname." WHERE ".tbl_url_bookmarks::col_uid."='$uid'",tbl_url_bookmarks::dbname);
		}
		return $res;
	}

	/**
	 *
	 * GET THE NUMBER OF FAVORITES OF A USER
	 * @param unknown $uid The User ID of the person whose favorites are retrieved
	 * @return number The number of favorites
	 */
	public function get_no_favourite_user($uid)
	{
		$res=$this->readfavourite($uid);
		return count($res);
	}

	/**
	 *
	 * LOG A USER ACTIVITY TO THE DATABASE
	 * @param unknown_type $uid User ID of person whose activity is to be logged
	 * @param unknown_type $actid Activity ID from activityindex table
	 * @param unknown_type $desc Description regarding the activity
	 * @return string SUCCESS on successful log, FAILURE on failure
	 */
	public function logactivity($uid,$actid,$desc)
	{
		$dbobj=new ta_dboperations();
		$mysqli=$dbobj->getmysqliobj(tbl_user_activity::dbname);
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		$platform=$utilityobj->getplatforminfo();
		$platformstring=$utilityobj->convertarraytostring($platform);
		$platformstring=mysqli_real_escape_string($mysqli,$platformstring);
		$ip=$userobj->getip();
		$uiobj=new ta_uifriend();
		$instid=$uiobj->randomstring(30,tbl_user_activity::dbname,tbl_user_activity::tblname,tbl_user_activity::col_instanceid);
		if($dbobj->dbinsert("INSERT INTO ".tbl_user_activity::tblname." (".tbl_user_activity::col_uid.",".tbl_user_activity::col_activityid.",".tbl_user_activity::col_ipaddr.",".tbl_user_activity::col_platforminfo.",".tbl_user_activity::col_instanceid.",".tbl_user_activity::col_activitydesc.") VALUES ('$uid','$actid','$ip','$platformstring','$instid','$desc')",tbl_user_activity::dbname)==SUCCESS)
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
	 * GET ACTIVITIES OF A USER
	 * @param unknown $uid User ID
	 * @return Returns DBArray of activities
	 */
	public function activity_get_user($uid,$start="0",$tot="20")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_user_activity::tblname." WHERE ".tbl_user_activity::col_uid."='$uid' ORDER BY ".tbl_user_activity::col_activitytime." DESC LIMIT $start,$tot", tbl_user_activity::dbname);
	}
	
	/**
	 *
	 * GET ACTIVITY INFORMATION
	 * @param unknown_type $actid ACTIVITY ID of the activity whose information is to be retrieved
	 * @return string|Ambigous <string, unknown> A DB Array having activity info on success, FAILURE on failure
	 */
	public function getactivityinfo($actid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_activity_index::tblname." WHERE ".tbl_activity_index::col_activityid."='$actid'",tbl_activity_index::dbname);
		if($res==EMPTY_RESULT)
		{
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}
	
	/**
	 * 
	 * ADD WORK DETAILS FOR A USER
	 * @param unknown $instname Institution/Organization Name
	 * @param unknown $typeid typeid from workedu DB stating type of work
	 * @param unknown $locid Locid from Location DB stating Location of work
	 * @param unknown $role Role in the institution
	 * @param string $uid Defaults to "" (if user's work)
	 * @param string $stime Start time of workedu
	 * @param string $etime End time of work
	 * @param string $privacyid PrivacyID (Audienceid from audience db)
	 * @param string $notes Notes regarding this work
	 * @param string $salarymin Minimum Salary
	 * @param string $salarymax Maximum Salary
	 * @param string $galid Gallery ID from GalDB
	 * @return boolean|string/object Returns WID as result
	 */
	public function work_add($instname,$typeid,$locid,$role,$uid="",$insturl="",$stime="",$etime="",$privacyid="",$notes="",$salarymin="-1",$salarymax="-1",$galid="")
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$uiobj=new ta_uifriend();
		
		if($instname==""||$typeid==""||$locid==""||$role=="")return BOOL_FAILURE;
		
		if($uid=="")
		{
			if(!$userobj->checklogin())
			{
				return false;
			}			
			
			$uid=$userobj->getuid();
		}
		
		if($privacyid=="")
		{
			$privacyid="1";
		}
		$wid=$uiobj->randomstring(50,tbl_user_work::dbname,tbl_user_work::tblname,tbl_user_work::col_wid);
		
		
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_user_work::tblname." (".tbl_user_work::col_uid.",".tbl_user_work::col_wid.",".tbl_user_work::col_instname.",".tbl_user_work::col_typeid.",
				".tbl_user_work::col_locid.",".tbl_user_work::col_role.",".tbl_user_work::col_stime.",".tbl_user_work::col_etime.",".tbl_user_work::col_recprivacy.",".tbl_user_work::col_notes.",".tbl_user_work::col_salarymin.",".tbl_user_work::col_salarymax.",".tbl_user_work::col_galid.",".tbl_user_work::col_insturl.") VALUES 
				('$uid','$wid','$instname','$$typeid','$locid','$role','$stime','$etime','$privacyid','$notes','$salarymin','$salarymax','$galid','$insturl')", tbl_user_work::dbname);
		return $wid;
	}
	
	/**
	 * 
	 * EDIT WORK DETAILS OF A USER
	 * @param unknown $uid User ID of the person for whom editing has to be done
	 * @param unknown $wid Workid from the user_work DB stating record to be edited
	 * @param unknown $colname Column name of the column to be edited
	 * @param unknown $value Value to be substituted
	 * @return string/object Returns DBUPDATE result
	 */
	public function work_edit($uid,$wid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_user_work::tblname." SET ".$colname."='$value' WHERE ".tbl_user_work::col_wid."='$wid' AND ".tbl_user_work::col_uid."='$uid'",tbl_user_work::dbname);
	}
	
	/**
	 * 
	 * DELETE WORK DETAILS OF A USER
	 * @param unknown $uid UID of the person whose details are to be deleted
	 * @param unknown $wid WorkID of the work to be deleted
	 * @return string/object Returns DBDELETE result
	 */
	public function work_delete($uid,$wid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_user_work::tblname." WHERE ".tbl_user_work::col_wid."='$wid' AND ".tbl_user_work::col_uid."='$uid'",tbl_user_work::dbname);
	}
	
	/**
	 * 
	 * GET WORK DETAILS OF A USER
	 * @param unknown $uid UID of the person whose details are to be fetched
	 * @return Ambigous <string, unknown> Returns DBARRAY of results
	 */
	public function work_get($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		if(isset($GLOBALS[$uid]["usrdata"]["workinfo"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["workinfo"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_work::tblname." WHERE ".tbl_user_work::col_uid."='$uid'", tbl_user_work::dbname);
			$GLOBALS[$uid]["usrdata"]["workinfo"]=$res;
		}
		return $res;
	}
	
	/**
	 * 
	 * ADD EDUCATION DETAILS OF A USER
	 * @param unknown $instname Institution Name
	 * @param unknown $typeid Typeid from Wedu DB
	 * @param unknown $locid Locid from location db for this record
	 * @param unknown $degree Degree pursued in this institute
	 * @param string $uid UID of the user (Defaults to "" if self user)
	 * @param string $stime Start time of this education
	 * @param string $etime End time of this education
	 * @param string $privacyid Audience ID stating privacy of this record (1-Public,2-Private,rest-audienceid)
	 * @param string $notes Notes on this record
	 * @param string $galid Galid from galdb for this record
	 * @return string|boolean|string/object Returns eduid of the added record
	 */
	public function education_add($instname,$typeid,$locid,$degree,$uid="",$insturl="",$stime="",$etime="",$privacyid="",$notes="",$galid="",$listid="")
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$uiobj=new ta_uifriend();
		
		if($instname==""||$typeid==""||$locid==""||$degree=="")return BOOL_FAILURE;
		
		if($uid=="")
		{
			if(!$userobj->checklogin())
			{
				return false;
			}
			$uid=$userobj->getuid();
		}
		
		if($privacyid=="")
		{
			$privacyid="1";
		}
		$eduid=$uiobj->randomstring(50,tbl_user_edu::dbname,tbl_user_edu::tblname,tbl_user_edu::col_eduid);
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_user_edu::tblname." (".tbl_user_edu::col_eduid.",".tbl_user_edu::col_instname.",".tbl_user_edu::col_locid.",".tbl_user_edu::col_notes.",".tbl_user_edu::col_degree.",".tbl_user_edu::col_uid.",".tbl_user_edu::col_stime.",".tbl_user_edu::col_etime.",".tbl_user_edu::col_recprivacy.",".tbl_user_edu::col_galid.",".tbl_user_edu::col_insturl.",".tbl_user_edu::col_listid.") 
			VALUES ('$eduid','$instname','$locid','$notes','$degree','$uid','$stime','$etime','$privacyid','$galid','$insturl','$listid')", tbl_user_edu::dbname);
		return $eduid;
	}
	
	/**
	 * 
	 * EDIT EDUCATION DETAILS OF A USER
	 * @param unknown $uid UID of the user whose details are to be edited
	 * @param unknown $eduid EDUID of the record
	 * @param unknown $colname The name of the column to be edited
	 * @param unknown $value Value to be substituted in place
	 * @return string/object Returns DBUPDATE result
	 */
	public function education_edit($uid,$eduid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_user_edu::tblname." SET ".$colname."='$value' WHERE ".tbl_user_edu::col_eduid."='$eduid' AND ".tbl_user_edu::col_uid."='$uid'", tbl_user_edu::dbname);
	}
	
	/**
	 * 
	 * DELETE EDUCATION DETAILS OF A USER
	 * @param unknown $uid UID of the user whose details are to be deleted
	 * @param unknown $eduid EDUID of the record to be deleted
	 * @return string/object Returns DBDELETE result
	 */
	public function education_delete($uid,$eduid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_user_edu::tblname." WHERE ".tbl_user_edu::col_eduid."='$eduid' AND ".tbl_user_edu::col_uid."='$uid'", tbl_user_edu::dbname);
	}
	
	/**
	 * 
	 * GET ALL THE EDUCATION DETAILS OF A USER
	 * @param unknown $uid UID of the user whose details are to be fetched
	 * @return Ambigous <string, unknown> Returns DBArray of results
	 */
	public function education_get($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		if(isset($GLOBALS[$uid]["usrdata"]["eduinfo"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["eduinfo"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_edu::tblname." WHERE ".tbl_user_edu::col_uid."='$uid'", tbl_user_edu::dbname);
			$GLOBALS[$uid]["usrdata"]["eduinfo"]=$res;
		}
		return $res;
	}
	
	/**
	 * 
	 * ADD A WORK/EDUCATION CATEGORY TO WORKEDU DB
	 * @param unknown $name Name of the category
	 * @param string $parid Parent ID if any ("" if parent)
	 * @param string $notes Notes to this category
	 * @return string/object Returns wedutypeid as result
	 */
	public function weducat_add($name,$parid="",$notes="")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		
		$wedutypeid=$uiobj->randomstring(50,tbl_workedu::dbname,tbl_workedu::tblname,tbl_workedu::col_typeid);
		
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_workedu::tblname." (".tbl_workedu::col_typeid.",".tbl_workedu::col_name.",".tbl_workedu::col_parid.",".tbl_workedu::col_notes.") VALUES 
				('$wedutypeid','$name','$parid','$notes')", tbl_workedu::dbname);
		return $wedutypeid;
	}
	
	/**
	 * 
	 * EDIT WEDU CATEGORY IN WORKEDU DB
	 * @param unknown $wedutypeid Typeid of workedu cat to be edited
	 * @param unknown $colname Name of column to be edited
	 * @param unknown $value Value to substitute to the column
	 * @return string/object Returns DBUPDATE result
	 */
	public function weducat_edit($wedutypeid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_workedu::tblname." SET ".$colname."='$value' WHERE ".tbl_workedu::col_typeid."='$weduid'",tbl_workedu::dbname);
	}
	
	/**
	 * 
	 * DELETE WEDU CATEGORY FROM WORKEDU DB
	 * @param unknown $wedtypeid Typeid of the category to be deleted
	 * @return string/object Returns DBDELETE result
	 */
	public function weducat_delete($wedtypeid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_workedu::tblname." WHERE ".tbl_workedu::col_typeid."='$wedtypeid'",tbl_workedu::dbname);
	}
	
	/**
	 * 
	 * GET WEDU CAT DETAILS FROM WORKEDU DB
	 * @param unknown $wedtypeid Typeid of the record whose details are to be fetched
	 * @return Ambigous <string, unknown> Returns DBARRAY of results
	 */
	public function weducat_get($wedtypeid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_workedu::tblname." WHERE ".tbl_workedu::col_typeid."='$wedtypeid'", tbl_workedu::dbname);
		return $res;
	}
	
	/**
	 *
	 * GET ALL WEDU CAT DETAILS FROM WORKEDU DB
	 * @return Ambigous <string, unknown> Returns DBARRAY of results
	 */
	public function weducat_get_all()
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_workedu::tblname, tbl_workedu::dbname);
		return $res;
	}
	
	/**
	 * 
	 * ADD AN USER ACHIEVEMENT
	 * @param unknown $uid UID of the user
	 * @param unknown $label Label of the achievement
	 * @param string $desc Description of the achievement
	 * @param string $achievetime Time of achievement
	 * @param string $galid GALID of gallery related to achievement
	 * @return string Returns achievement id of the added achievement 
	 */
	public function achievement_add($uid,$label,$desc="",$achievetime="",$galid="")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$uiobj=new ta_uifriend();
		
		$res=$userobj->user_getextrainfo($uid);
		if($res==EMPTY_RESULT)return "";
		$col_aid=$res["col_achievementid"];
		if($col_aid=="")
		{
			$col_aid=$uiobj->randomstring(50,tbl_collection_achievements::dbname,tbl_collection_achievements::tblname,tbl_collection_achievements::col_col_achieveid);
		}
		$aid=$uiobj->randomstring(50,tbl_collection_achievements::dbname,tbl_collection_achievements::tblname,tbl_collection_achievements::col_achievementid);
		$sql="INSERT INTO ".tbl_collection_achievements::tblname." (".tbl_collection_achievements::col_col_achieveid.",".tbl_collection_achievements::col_achievementid.",".tbl_collection_achievements::col_label.",".tbl_collection_achievements::col_notes.",".tbl_collection_achievements::col_achievetime.",".tbl_collection_achievements::col_galid.")	VALUES ('$col_aid','$aid','$label','$desc','$achievetime','$galid')";
		$res=$dbobj->dbinsert($sql, tbl_collection_achievements::dbname);
		
		return $aid;
	}
	
	/**
	 * 
	 * GET ACHIEVEMENTS OF A USER
	 * @param unknown $uid The UID of the user whose achievements are to be fetched
	 * @return Ambigous Returns DBArray containing all achievements
	 */
	public function achievement_get_user($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
		if(isset($GLOBALS[$uid]["usrdata"]["extrainfo"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["extrainfo"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$uid' LIMIT 0,1", tbl_user_extras::dbname);
			$GLOBALS[$uid]["usrdata"]["extrainfo"]=$res;
		}
		$col_aid=$res[0]["col_achievementid"];
		$res1=$colobj->get_collection_complete_info(tbl_collection_achievements::tblname,tbl_collection_achievements::col_col_achieveid,$col_aid);
		return $res1;
	}
	
	/**
	 * 
	 * REMOVE AN ACHIEVEMENT OF A USER
	 * @param unknown $uid
	 * @param unknown $achieveid
	 * @return string
	 */
	public function achievement_remove($uid,$achieveid)
	{
		$userobj=new ta_userinfo();
		$colobj=new ta_collection();
		$res=$userobj->user_getextrainfo($uid);
		$col_aid=$res["col_achievementid"];
		$res=$colobj->remove_collection_item(tbl_collection_achievements::tblname,tbl_collection_achievements::col_col_achieveid,$col_aid,tbl_collection_achievements::col_achievementid,$achieveid);
		return $res;
	}
	
	/**
	 * 
	 * ADD DEVICES OF A USER
	 * @param unknown $uid UID of the user who owns the device
	 * @param unknown $label Label of the device
	 * @param unknown $ip IP Address of the device
	 * @param unknown $type Type of device to be added (1-PC,2-Smartphone,3-Tablet,4-Laptop,5-Watches,6-Other)
	 * @param unknown $description Description of the device
	 * @param unknown $resolution Resolution of the device
	 * @return string/object Returns Device ID as result
	 */
	public function device_add($uid,$label,$type,$ip,$description="",$resolution="")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		$did=$uiobj->randomstring(50,tbl_user_devices::dbname,tbl_user_devices::tblname,tbl_user_devices::col_did);
		
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_user_devices::tblname." (".tbl_user_devices::col_uid.",".tbl_user_devices::col_did.",".tbl_user_devices::col_label.",".tbl_user_devices::col_description.",".tbl_user_devices::col_resolution.",".tbl_user_devices::col_ip.",".tbl_user_devices::col_devicetype.") 
				VALUES ('$uid','$did','$label','$description','$resolution','$ip','$type')", tbl_user_devices::dbname);
		return $did;
	}
	
	/**
	 * 
	 * EDIT DEVICE INFO OF A USER
	 * @param unknown $uid UID of the user who owns the device
	 * @param unknown $did Device ID of the device to be edited
	 * @param unknown $colname Name of the column to be edited
	 * @param unknown $value Value to be substituted
	 * @return string/object Returns DBUPDATE result 
	 */
	public function device_edit($uid,$did,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_user_devices::tblname." SET ".$colname."='$value' WHERE ".tbl_user_devices::col_uid."='$uid' AND ".tbl_user_devices::col_did."='$did'", tbl_user_devices::dbname);
	}
	
	/**
	 * 
	 * DELETE A DEVICE OF A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $did Device ID of the device to be deleted
	 * @return string/object Returns DBDELETE result
	 */
	public function device_remove($uid,$did)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE ".tbl_user_devices::tblname." WHERE ".tbl_user_devices::col_uid."='$uid' AND ".tbl_user_devices::col_did."='$did'", tbl_user_devices::dbname);
	}
	
	/**
	 * 
	 * GET INFO ABOUT A DEVICE OF A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $did Device ID
	 * @return Ambigous <string, unknown>|Ambigous <> Returns the device info as array with keys as in DB
	 */
	public function device_get($uid,$did)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_devices::tblname." WHERE ".tbl_user_devices::col_uid."='$uid' AND ".tbl_user_devices::col_did."='$did'",tbl_user_devices::dbname);
		if($res==EMPTY_RESULT)
		{
			return $res;
		}
		else
		{
			return $res[0];
		}	
	}
	
	/**
	 * 
	 * GET INFO OF ALL DEVICES OF A USER
	 * @param unknown $uid UID of the user
	 * @return Ambigous <string, unknown>|Ambigous <> Returns a DBArray of results
	 */
	public function device_get_all($uid,$refresh_data="")
	{
		$dbobj=new ta_dboperations();
		if(isset($GLOBALS[$uid]["usrdata"]["devices"])&&$refresh_data=="")
		{
			$res=$GLOBALS[$uid]["usrdata"]["devices"];
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_devices::tblname." WHERE ".tbl_user_devices::col_uid."='$uid'",tbl_user_devices::dbname);
			$GLOBALS[$uid]["usrdata"]["devices"]=$res;
		}
		return $res;
	}
	
	/**
	 * 
	 * ADD A SKILL TO A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $skillid Skillid of the skill to be added
	 * @return string|unknown
	 */
	public function skill_add($uid,$skillid)
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$uiobj=new ta_uifriend();
		
		$res=$userobj->user_getextrainfo($uid);
		if($res==EMPTY_RESULT)return "";
		$col_skillid=$res["col_skillid"];
		if($col_skillid=="")
		{
			$col_skillid=$uiobj->randomstring(50,tbl_collection_skills::dbname,tbl_collection_skills::tblname,tbl_collection_skills::col_col_skillid);
		}
		
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_collection_skills::tblname." (".tbl_collection_skills::col_col_skillid.",".tbl_collection_skills::col_skillid.") VALUES ('$col_skillid','$skillid')", tbl_collection_skills::dbname);
		return $skillid;
	}
	
	/**
	 * 
	 * REMOVE A SKILL FROM A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $skillid Skillid of the skill to be removed
	 * @return string
	 */
	public function skill_remove($uid,$skillid)
	{
		$userobj=new ta_userinfo();
		$colobj=new ta_collection();
		
		$res=$userobj->user_getextrainfo($uid);
		$col_skillid=$res["col_skillid"];
		$res=$colobj->remove_collection_item(tbl_collection_skills::tblname,tbl_collection_skills::col_col_skillid,$col_skillid,tbl_collection_skills::col_skillid,$skillid);
		return $res;
	}
}
?>
