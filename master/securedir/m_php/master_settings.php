<?php
/**
 *
 * CONTAINS USER SETTINGS & PRIVACY
 * @author T.V.VIGNESH
 *
 *KEYS-fname,mname,lname,email,dob,relstat,gender,aliases,phone,mobile,compaddr,country,state,pincode,sociallinks,prof_groups,education,achievements,skills
 */
class ta_settings
{
	
	/**
	 * 
	 * ADD A SETTING TO THE DB
	 * @param unknown $uid UID of the user
	 * @param unknown $mkey Main key of the setting
	 * @param unknown $skey Sub key of the setting
	 * @param unknown $type Type of setting
	 * @param unknown $val Value of the setting
	 * @return string/object Returns DBINSERT result
	 */
	public function setting_add($uid,$mkey,$skey,$type,$val)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_user_settings::tblname." (".tbl_user_settings::col_uid.",".tbl_user_settings::col_mainkey.",".tbl_user_settings::col_subkey.",".tbl_user_settings::col_settype.",".tbl_user_settings::col_setval.") VALUES ('$uid','$mkey','$skey','$type','$val')", tbl_user_settings::dbname);
		return $res;
	}
	
	/**
	 * 
	 * REMOVE A SETTING
	 * @param unknown $uid UID of the user
	 * @param string $mkey Mainkey (Defaults to "")
	 * @param string $skey Subkey (Defaults to "")
	 * @param string $type Type (Defaults to "")
	 * @return string/object Returns DBDELETE result
	 */
	public function setting_remove($uid,$mkey="",$skey="",$type="")
	{
		$dbobj=new ta_dboperations();
		$sql="DELETE FROM ".tbl_user_settings::tblname." WHERE ".tbl_user_settings::col_uid."='$uid'";
		
		if($mkey!="")
		{
			$sql.=" AND ".tbl_user_settings::col_mainkey."='$mkey'";
		}
		
		if($skey!="")
		{
			$sql.=" AND ".tbl_user_settings::col_subkey."='$skey'";
		}
		
		if($type!="")
		{
			$sql.=" AND ".tbl_user_settings::col_settype."='$type'";
		}
		
		return $dbobj->dbdelete($sql,tbl_user_settings::dbname);
	}
	
	/**
	 * 
	 * UPDATE A SETTING OF A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $colname Name of Column to be updated 
	 * @param unknown $val Value to be substituted
	 * @return string/object Returns DBUPDATE result
	 */
	public function setting_edit($uid,$colname,$val)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_user_settings::tblname." SET ".$colname."='$val' WHERE ".tbl_user_settings::col_uid."='$uid'", tbl_user_settings::dbname);
	}
	
	/**
	 * 
	 * GET A SETTING INFO
	 * @param unknown $uid UID of the user
	 * @param string $mkey Main key (Defaults to "")
	 * @param string $skey Sub key (Defaults to "")
	 * @param string $type Type (Defaults to "")
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function setting_get($uid,$mkey="",$skey="",$type="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_user_settings::tblname." WHERE ".tbl_user_settings::col_uid."='$uid'";
		
		if($mkey!="")
		{
			$sql.=" AND ".tbl_user_settings::col_mainkey."='$mkey'";
		}
		
		if($skey!="")
		{
			$sql.=" AND ".tbl_user_settings::col_subkey."='$skey'";
		}
		
		if($type!="")
		{
			$sql.=" AND ".tbl_user_settings::col_settype."='$type'";
		}
		
		$res=$dbobj->dbquery($sql, tbl_user_settings::dbname);
		return $res;
	}
	
	/**
	 * 
	 * SET DEFAULT SETTINGS FOR A USER (Unless obvious like public vis. etc)
	 * @param unknown $uid UID of the user
	 */
	public function setting_defaults($uid)
	{
		$setobj=new ta_settings();
		$setobj->setting_add($uid,"email","base","2","1");
		$setobj->setting_add($uid,"dob","base","2","1");
		$setobj->setting_add($uid,"compaddr","base","2","2");
		$setobj->setting_add($uid,"theme","base","1","default");
	}
	
	/**
	 * 
	 * CHECK IF A FIELD SHOULD BE VISIBLE FOR A USER 
	 * @param unknown $uid UID of the user who has the setting
	 * @param unknown $mkey Main Key
	 * @param unknown $skey Sub Key
	 * @param unknown $vuid UID of the person viewing
	 * @return string Returns BOOL_SUCCESS if it can be viewed, BOOL_FAILURE if it cant be viewed
	 */
	public function checkvis($uid,$mkey,$skey,$vuid)
	{
		$setobj=new ta_settings();
		$audobj=new ta_audience();
		
		$res=$setobj->setting_get($uid,$mkey,$skey,"2");
		if($res==EMPTY_RESULT)
		{
			return BOOL_SUCCESS; 
		}
		
		$ret=$res[0];
		$val=$ret[changesqlquote(tbl_user_settings::col_setval,"")];
		if($val=="1")return BOOL_SUCCESS;
		if($val=="2")return BOOL_FAILURE;
		return $audobj->audience_check_user($val,$vuid);
	}
}

?>