<?php
/**
 *
 * CONTAINS AUDIENCE RELATED FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_audience
{
	/**
	 *
	 * CREATE A NEW AUDIENCE (EMPTY SET WITH IGNORES)
	 * @return Ambigous <string, unknown> The audience ID
	 */
	public function audience_create()
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();
		$audienceid=$uiobj->randomstring(40,tbl_audience_target::dbname,tbl_audience_target::tblname,tbl_audience_target::col_audienceid);
		$dbobj->dbinsert("INSERT INTO ".tbl_audience_target::tblname." (".tbl_audience_target::col_audienceid.") VALUES ('$audienceid')",tbl_audience_target::dbname);
		return $audienceid;
	}

	public function audience_copy($audienceid)
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();

		$audienceid_copy=$uiobj->randomstring(40,tbl_audience_target::dbname,tbl_audience_target::tblname,tbl_audience_target::col_audienceid);

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_audience_target::tblname." WHERE ".tbl_audience_target::col_audienceid."='$audienceid' LIMIT 0,1",tbl_audience_target::dbname);
		if(count($res)==0)
		{
			return FAILURE;
		}
		$dbobj->dbrandquery_noclose("CREATE TEMPORARY TABLE tmptable SELECT * FROM ".tbl_audience_target::tblname." WHERE ".tbl_audience_target::col_audienceid."='$audienceid'",tbl_audience_target::dbname);
		$dbobj->dbrandquery_noclose("UPDATE tmptable SET ".tbl_audience_target::col_audienceid." ='$audienceid_copy' WHERE primarykey ='$audienceid'",tbl_audience_target::dbname);
		$dbobj->dbrandquery_noclose("INSERT INTO table SELECT * FROM tmptable WHERE primarykey ='.$audienceid_copy.'",tbl_audience_target::dbname);

		return $audienceid_copy;
	}

	/**
	 *
	 * CHECK IF AN AUDIENCE ID EXISTS IN THE DATABASE
	 * @param unknown $audienceid The audienceid which has to be checked for existence
	 * @return string BOOL_SUCCESS on success, BOOL_FAILURE on failure
	 */
	public function audience_check_exists($audienceid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_audience_target::tblname." WHERE ".tbl_audience_target::col_audienceid."='$audienceid'",tbl_audience_target::dbname);
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
	 * EDIT A COLUMN IN AUDIENCE TABLE
	 * @param unknown $audienceid The Audience ID OF THE AUDIENCE to be edited
	 * @param unknown $colname The name of the column
	 * @param unknown $value The value the column has to be updated with
	 * @return Ambigous <string/object, string, void> SUCCESS on success, FAILURE on failure
	 */
	public function audience_edit($audienceid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_audience_target::tblname." SET ".$colname."='$value' WHERE ".tbl_audience_target::col_audienceid."='$audienceid'",tbl_audience_target::dbname);
	}

	/**
	 *
	 * REMOVE AN AUDIENCE
	 * @param unknown $audienceid The audienceid of the audience to be removed
	 * @return Ambigous <string/object, string, void> SUCCESS on success, FAILURE on failure
	 */
	public function audience_remove($audienceid)
	{
		if($audienceid=="")return SUCCESS;
		$dbobj=new ta_dboperations();
		$audobj=new ta_audience();
		$colobj=new ta_collection();
		$audres=$audobj->audience_fetch($audienceid);
		$audid_not=$audres[0][changesqlquote(tbl_audience_target::col_col_audienceid_not,"")];
		$col_uid=$audres[0][changesqlquote(tbl_audience_target::col_col_users,"")];
		if($dbobj->dbdelete("DELETE FROM ".tbl_audience_target::tblname." WHERE ".tbl_audience_target::col_audienceid."='$audienceid'",tbl_audience_target::dbname)==SUCCESS)
		{
			$colobj->remove_collection_complete(tbl_collection_users::tblname, tbl_collection_users::col_col_uid, $col_uid);
			if($audid_not!="")
			{
				$audobj->audience_remove($audid_not);
			}
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * FETCH AN AUDIENCE
	 * @param unknown $audienceid The audienceid of the audience to be fetched
	 * @return Ambigous <string, unknown> A DB Array having the audience info on success, FAILURE on failure
	 */
	public function audience_fetch($audienceid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_audience_target::tblname." WHERE ".tbl_audience_target::col_audienceid."='$audienceid'",tbl_audience_target::dbname);
		return $res;
	}

	/**
	 * 
	 * CHECK IF AUDIENCE IS PUBLIC AND REQUIRES LOGIN
	 * @param unknown $audienceid Audience ID
	 * @return boolean BOOL_SUCCESS on yes,BOOL_FAILURE on failure
	 */
	public function audience_check_public($audienceid)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();

		$res=$audienceobj->audience_fetch($audienceid);
		$loginreq=$dbobj->colval($res,tbl_audience_target::col_loginreq,0);
		if($loginreq=="2")
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	public function audience_add_col_notaud($audienceid,$colarray_notaudid)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
		
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_audid_not=$audres[0][changesqlquote(tbl_audience_target::col_col_audienceid_not,"")];
		
		if($col_audid_not=="-1")
			$cid="";
		else
			$cid=$col_audid_not;
		
		$cid=$colobj->add_collection(tbl_collection_audience::tblname,$colarray_notaudid, tbl_collection_audience::col_col_audienceid,$cid);
		if($col_audid_not=="-1")
		{
			$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_audienceid_not,$cid);
		}
	}
	
	public function audience_add_col_age($audienceid,$colarray_age)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
		$logobj=new ta_logs();
		
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_ageid=$audres[0][changesqlquote(tbl_audience_target::col_col_ageid,"")];
	
		$logobj->store_templogs("AGEID COL:".$col_ageid);
		
		if($col_ageid=="-1")
		$cid="";
		else
		$cid=$col_ageid;
	
		$cid=$colobj->add_collection(tbl_collection_age::tblname,$colarray_age, tbl_collection_age::col_col_ageid,$cid);
		if($col_ageid=="-1")
		{
			$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_ageid,$cid);
		}
	}
	
	public function audience_add_col_weducat($audienceid,$colarray_weducat)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_weducat=$audres[0][changesqlquote(tbl_audience_target::col_col_weid,"")];
	
		if($col_weducat=="-1")
		$cid="";
		else
		$cid=$col_weducat;
	
				$cid=$colobj->add_collection(tbl_collection_workedu::tblname,$colarray_weducat, tbl_collection_workedu::col_col_typeid,$cid);
				if($col_weducat=="-1")
				{
					$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_weid,$cid);
				}
	}
	
	public function audience_add_col_reppoints($audienceid,$colarray_reppoints)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_reppointid=$audres[0][changesqlquote(tbl_audience_target::col_col_reppointid,"")];
	
		if($col_reppointid=="-1")
			$cid="";
			else
			$cid=$col_reppointid;
	
			$cid=$colobj->add_collection(tbl_collection_reppoints::tblname,$colarray_reppoints, tbl_collection_reppoints::col_col_reppointid,$cid);
			if($col_reppointid=="-1")
			{
				$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_reppointid,$cid);
			}
	}
	
	public function audience_add_col_lists($audienceid,$colarray_lists)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_listid=$audres[0][changesqlquote(tbl_audience_target::col_col_listid,"")];
	
		if($col_listid=="-1")
			$cid="";
			else
				$cid=$col_listid;
	
				$cid=$colobj->add_collection(tbl_collection_lists::tblname,$colarray_lists, tbl_collection_lists::col_col_listid,$cid);
				if($col_listid=="-1")
				{
					$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_listid,$cid);
				}
	}
	
	public function audience_add_col_country($audienceid,$colarray_country)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_country=$audres[0][changesqlquote(tbl_audience_target::col_col_country,"")];
	
		if($col_country=="-1")
			$cid="";
			else
				$cid=$col_country;
	
				$cid=$colobj->add_collection(tbl_collection_countries::tblname,$colarray_country, tbl_collection_countries::col_col_cid,$cid);
				if($col_country=="-1")
				{
					$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_country,$cid);
				}
	}
	
	public function audience_add_col_state($audienceid,$colarray_state)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_state=$audres[0][changesqlquote(tbl_audience_target::col_col_state,"")];
	
		if($col_state=="-1")
			$cid="";
			else
				$cid=$col_country;
	
				$cid=$colobj->add_collection(tbl_collection_states::tblname,$colarray_state, tbl_collection_states::col_col_sid,$cid);
				if($col_state=="-1")
				{
					$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_state,$cid);
				}
	}
	
	public function audience_add_col_users($audienceid,$colarray_users)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$audres=$audienceobj->audience_fetch($audienceid);
		$col_uid=$audres[0][changesqlquote(tbl_audience_target::col_col_users,"")];
	
		if($col_uid=="-1")
			$cid="";
			else
				$cid=$col_uid;
	
				$cid=$colobj->add_collection(tbl_collection_users::tblname,$colarray_users, tbl_collection_users::col_col_uid,$cid);
				if($col_uid=="-1")
				{
					$audienceobj->audience_edit($audienceid,tbl_audience_target::col_col_users,$cid);
				}
	}
	
	/**
	 * 
	 * CHECK IF THE USER BELONGS TO THE AUDIENCE
	 * @param unknown $audienceid Audience ID
	 * @param unknown $uid UID of the user
	 * @return string Returns BOOL_SUCCESS if yes BOOL_FAILURE if no
	 */
	public function audience_check_user($audienceid,$uid)
	{
		$logobj=new ta_logs();
		$audres=$this->audience_fetch($audienceid);
		
		if(count($audres)==0)return BOOL_SUCCESS;
		$cuid=$audres[0][changesqlquote(tbl_audience_target::col_cuid,"")];
		$a_not=$audres[0][changesqlquote(tbl_audience_target::col_col_audienceid_not,"")];
		if($cuid==$uid)return BOOL_SUCCESS;
		$loginstat=$this->audience_check_public($audienceid);
		if($audienceid==""||$loginstat)
		{
			return BOOL_SUCCESS;
		}
		if($uid==""&&$loginstat==BOOL_SUCCESS)
		{
			return BOOL_SUCCESS;
		}
		else
		if($uid=="")
		{
			return BOOL_FAILURE;
		}
		
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();

		$userobj->user_initialize_data($uid);
		$res=$this->audience_fetch($audienceid);

		if($res==FAILURE||$res==EMPTY_RESULT)
		{
			return BOOL_FAILURE;
		}

		//CHECK AGE LIMIT
		if(!$this->test_agelimit($audienceid,$res,$userobj->age))
		{
			$logobj->store_templogs("FAILED AGE...".$audienceid);
			return BOOL_FAILURE;
		}
		//CHECK GENDER
		if(!$this->test_gender($audienceid,$res,$userobj->gender))
		{
			$logobj->store_templogs("FAILED GENDER...".$audienceid);
			return BOOL_FAILURE;
		}
		//CHECK REPUTATION POINTS
		if(!$this->test_reppoints($audienceid,$res,$userobj->reppoints))
		{
			$logobj->store_templogs("FAILED REP...".$audienceid);
			return BOOL_FAILURE;
		}
		//CHECK USER COLLECTION
		if(!$this->test_user($audienceid,$res,$uid))
		{
			$logobj->store_templogs("FAILED USER COL...".$audienceid);
			return BOOL_FAILURE;
		}
		//CHECK USER COUNTRY
		if(!$this->test_country($audienceid,$res,$userobj->country))
		{
			$logobj->store_templogs("FAILED USER COUNTRY...".$audienceid);
			return BOOL_FAILURE;
		}
		//CHECK USER STATE
		if(!$this->test_state($audienceid,$res,$userobj->state))
		{
			$logobj->store_templogs("FAILED USER STATE...".$audienceid);
			return BOOL_FAILURE;
		}
		
		//CHECK NOT'ed AUDIENCE ID
		if($this->test_not_audience($a_not, $uid))
		{
			$logobj->store_templogs("ADDED TO NOT USER COL...".$audienceid);
			return BOOL_FAILURE;
		}

		return BOOL_SUCCESS;//TRUE IF IT PASSES ALL TESTS
	}

	public function test_not_audience($audienceid_not,$uid)
	{
		$audobj=new ta_audience();
		if($audienceid_not=="-1")return BOOL_FAILURE;
		if(!$audobj->audience_check_exists($audienceid_not))return BOOL_FAILURE;
		if($audobj->audience_check_user($audienceid_not, $uid))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}
	
	public function test_agelimit($audienceid,$res,$age)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();

		$flag=0;

		$col_age_id=$dbobj->colval($res,tbl_audience_target::col_col_ageid,0);
		if($col_age_id=="-1")
		{
			return BOOL_SUCCESS;
		}
		$col_age_res=$collectionobj->get_collection_complete_info(tbl_collection_age::tblname,tbl_collection_age::col_col_ageid,$col_age_id);

		if($col_age_res==EMPTY_RESULT||$col_age_res==FAILURE)
		{
			return BOOL_FAILURE;
		}

		for ($i=0;$i<count($col_age_res);$i++)
		{
			$flag=0;
			
			$minage=$dbobj->colval($col_age_res,tbl_collection_age::col_minage,$i);
			$maxage=$dbobj->colval($col_age_res,tbl_collection_age::col_maxage,$i);
			
			if($age>=$minage||$minage=="-1")
			{
				$flag++;
			}
			
			if($age<=$maxage||$maxage=="-1")
			{
				$flag++;
			}
			
			if($flag==2)
			{
				break;
			}
			
		}
		if($flag==2)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	public function test_gender($audienceid,$res,$gender)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();

		$flag=0;

		$audgender=$dbobj->colval($res,tbl_audience_target::col_gender,0);

		if(($audgender=="-1")||($audgender=="1"&&$gender=="m")||($audgender=="2"&&$gender=="f")||($audgender=="3"&&$gender=="o")||($audgender=="4"))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}
	
	public function test_country($audienceid,$res,$country)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();
	
		$flag=0;
	
		$col_country=$dbobj->colval($res,tbl_audience_target::col_col_country,0);
		if($col_country=="-1"||$col_country=="")
		{
			return BOOL_SUCCESS;
		}
		$col_country_res=$collectionobj->get_collection_complete_info(tbl_collection_countries::tblname,tbl_collection_countries::col_col_cid,$col_country);
	
		if(count($col_country_res)==0)
		{
			return BOOL_FAILURE;
		}
	
		for ($i=0;$i<count($col_country_res);$i++)
		{
			$audcountry=$dbobj->colval($col_country_res,tbl_collection_countries::col_csname,$i);
			if($audcountry==$country)
			{
				$flag=1;break;
			}
		}
		if($flag==1)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}
	
	public function test_state($audienceid,$res,$state)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();
	
		$flag=0;
	
		$col_state=$dbobj->colval($res,tbl_audience_target::col_col_state,0);
		if($col_state=="-1"||$col_state=="")
		{
			return BOOL_SUCCESS;
		}
		$col_state_res=$collectionobj->get_collection_complete_info(tbl_collection_states::tblname,tbl_collection_states::col_col_sid,$col_state);
	
		if(count($col_state_res)==0)
		{
			return BOOL_FAILURE;
		}
	
		for ($i=0;$i<count($col_state_res);$i++)
		{
			$audstate=$dbobj->colval($col_state_res,tbl_collection_states::col_sid,$i);
			if($audstate==$state)
			{
				$flag=1;break;
			}
		}
		if($flag==1)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	public function test_reppoints($audienceid,$res,$points)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();

		$flag=0;

		$col_rep_id=$dbobj->colval($res,tbl_audience_target::col_col_reppointid,0);
		if($col_rep_id=="-1")
		{
			return BOOL_SUCCESS;
		}
		$col_rep_res=$collectionobj->get_collection_complete_info(tbl_collection_reppoints::tblname,tbl_collection_reppoints::col_col_reppointid,$col_rep_id);

		if($col_rep_res==EMPTY_RESULT)
		{
			return BOOL_FAILURE;
		}

		for ($i=0;$i<count($col_rep_res);$i++)
		{
			$minpoint=$dbobj->colval($col_rep_res,tbl_collection_reppoints::col_minrep,$i);
			$maxpoint=$dbobj->colval($col_rep_res,tbl_collection_reppoints::col_maxrep,$i);
			if(($points>=$minpoint&&$points<=$maxpoint)||($minpoint=="0"&&$maxpoint=="0"))
			{
				$flag=1;break;
			}
		}
			if($flag==1)
			{
			return BOOL_SUCCESS;
			}
			else
			{
			return BOOL_FAILURE;
			}
	}

	public function test_user($audienceid,$res,$uid)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();

		$flag=0;

		$col_uid=$dbobj->colval($res,tbl_audience_target::col_col_users,0);
		if($col_uid=="-1")
		{
			return BOOL_SUCCESS;
		}
		$col_uid_res=$collectionobj->get_collection_complete_info(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_uid);

		if(count($col_uid_res)==0)
		{
			return BOOL_FAILURE;
		}

		for ($i=0;$i<count($col_uid_res);$i++)
		{
			$auduid=$dbobj->colval($col_uid_res,tbl_collection_users::col_uid,$i);
			if($auduid==$uid)
			{
				$flag=1;break;
			}
		}
		if($flag==1)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	public function get_audience_uid_matches($audienceid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname,tbl_user_info::dbname);
		$matches=Array();
		$c=0;
		for($i=0;$i<count($res);$i++)
		{
			$uid=$dbobj->colval($res,tbl_user_info::col_usrid,$i);
			if($this->audience_check_user($audienceid,$uid))
			{
				$matches[$c++]=$uid;
			}
		}
		return $matches;
	}
	
	/**
	 * 
	 * REMOVE A SET OF USERS FROM AUDIENCE
	 * @param unknown $audienceid Audience ID
	 * @param unknown $uidarray Array of UIDs
	 */
	public function audience_remove_users($audienceid,$uidarray)
	{
		$dbobj=new ta_dboperations();
		$audobj=new ta_audience();
		$collectionobj=new ta_collection();
		
		$audres=$audobj->audience_fetch($audienceid);
		$audienceid_not=$audres[0][changesqlquote(tbl_audience_target::col_col_audienceid_not,"")];
		
		if($audienceid_not=="-1")
		{
			$audid_not=$audobj->audience_create();
			$audobj->audience_edit($audienceid,tbl_audience_target::col_col_audienceid_not,$audid_not);
		}
		else
		{
			$audid_not=$audienceid_not;
		}
			
			$audnotres=$audobj->audience_fetch($audid_not);
			$col_userid=$audnotres[0][changesqlquote(tbl_audience_target::col_col_users,"")];
			
			if($col_userid=="-1")
			{
				$col_userid="";
			}
			$colarray=Array();
			$j=0;
			for($i=0;$i<count($uidarray);$i++)
			{
				if($audobj->audience_check_user($audienceid,$uidarray[$i]))
				{
					$colarray[$j++][tbl_collection_users::col_uid]=$uidarray[$i];
				}
			}
			$colid=$collectionobj->add_collection(tbl_collection_users::tblname,$colarray,tbl_collection_users::col_col_uid,$col_userid);
			if($col_userid=="")
			{
				$audobj->audience_edit($audid_not,tbl_audience_target::col_col_users,$colid);
			}
		
	}
}
?>