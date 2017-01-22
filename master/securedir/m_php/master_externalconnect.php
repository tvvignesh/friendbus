<?php
/**
 *
 * CONTAINS ALL FUNCTIONS RELATED TO EXTERNAL LINKS, REFERRALS,etc.
 * @author T.V.VIGNESH
 *
 */
class ta_externalconnect
{
	/**
	 *
	 * LOG TO DB WHEN AN EXTERNAL LINK IS CLICKED ANYWHERE IN TECH AHOY WEBSITE
	 * @param unknown_type $url URL of the external page which has been clicked
	 * @param unknown_type $uid User ID of person who clicks the link (Defaults to Unknown)
	 * @param unknown_type $pageurl Page in which the link exists (Defaults to Unknown)
	 * @return string SUCCESS on successful add, FAILURE on failure
	 */
	public function addextlinktodb($url,$uid="Unknown",$pageurl="Unknown")
	{
		$uiobj=new ta_uifriend();
		$linkid=$uiobj->randomstring(50,tbl_linkdb::dbname,tbl_linkdb::tblname,tbl_linkdb::col_linkid);
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_linkdb::tblname." WHERE ".tbl_linkdb::col_url."='$url'",tbl_linkdb::dbname);
		if($res==EMPTY_RESULT)
		{
			if($dbobj->dbinsert("INSERT INTO ".tbl_linkdb::tblname." (".tbl_linkdb::col_linkid.",".tbl_linkdb::col_url.",".tbl_linkdb::col_linktype.",".tbl_linkdb::col_url.",".tbl_linkdb::col_linkvisits.")
				VALUES ('".$linkid."','1','1','$url','0')",tbl_linkdb::dbname)==SUCCESS)
			{
				$userobj=new ta_userinfo();
				$userobj->logactivity($uid,"#act_0000000@0000002","Visited ".$url." SOURCE:".$pageurl);
				return SUCCESS;
			}
			else
			{
				return FAILURE;
			}
		}
		else
		{
			if($dbobj->dbupdate("UPDATE ".tbl_linkdb::tblname."SET ".tbl_linkdb::col_linkvisits."=".tbl_linkdb::col_linkvisits."+1 WHERE ".tbl_linkdb::col_url."='$url'",tbl_linkdb::dbname)==SUCCESS)
			{
				$userobj=new ta_userinfo();
				$userobj->logactivity($uid,"#act_0000000@0000002","Visited ".$url." SOURCE:".$pageurl);
				return SUCCESS;
			}
			else
			{
				return FAILURE;
			}
		}

	}

	/**
	 *
	 * CHANGE A LINK's STATUS TO ALLOW/UNDER REVIEW/BLOCKED
	 * @param unknown_type $url URL of the link whose status is to be changed
	 * @param unknown_type $status Flag value specifying link status (1-allowed,2-under review,3-blocked)
	 * @param unknown_type $reason Reason for changing the link status (Defaults to "")
	 * @param unknown_type $reasontype Flag value specifying reason for status change (1-SPAM,2-EXPLICIT CONTENT,3-NOT RELATED,4-HURTING,5-DUPLICATE,6-INVALID,7-OTHERS)
	 * @return string SUCCESS on successful status change,FAILURE on failure
	 */
	public function changelinkstatus($url,$status,$reason="",$reasontype="1")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_linkdb::tblname." WHERE ".tbl_linkdb::col_url."='$url' LIMIT 0,1",tbl_linkdb::dbname);
		if($res==EMPTY_RESULT)
		{
			return FAILURE;
		}
		$lid=$res[0][changesqlquote(tbl_linkdb::col_linkid,"")];
		if($dbobj->dbupdate("UPDATE ".tbl_linkdb::tblname." SET ".tbl_linkdb::col_url."='$status' WHERE ".tbl_linkdb::col_url."='$url'",tbl_linkdb::dbname)==SUCCESS)
		{
			if($status=="3")
			{
				$blacklistobj=new ta_blacklists();
				$blacklistobj->block_reason_add($lid,"5",$reason,$reasontype);
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
	 * ADD A NEW REFERRER TO THE DATABASE
	 * @param unknown_type $refuid User ID of the referrer
	 * @param unknown_type $refname Referral Name
	 * @param unknown_type $reftype Flag value specifying Referral Type (1-partners,2-promotions,3-others) (Defaults to 1)
	 * @param unknown_type $refdesc Description regarding the referral (Defaults to "")
	 * @param unknown_type $refweburl Website URL of the referral (Defaults to "")
	 * @return string SUCCESS on successful add,FAILURE on failure
	 */
	public function addreferraltodb($refuid,$refname,$reftype="1",$refdesc="",$refweburl="")
	{
		$uiobj=new ta_uifriend();
		$refid=$uiobj->randomstring(30,tbl_referraldb::dbname,tbl_referraldb::tblname,tbl_referraldb::col_referralid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_referraldb::tblname." (".tbl_referraldb::col_referralid.",".tbl_referraldb::col_referralname.",".tbl_referraldb::col_referraltype.",".tbl_referraldb::col_referraldesc.",".tbl_referraldb::col_referraluid.",".tbl_referraldb::col_referralvisits.",".tbl_referraldb::col_referralweburl.")
				VALUES ('$refid','$refname','$reftype','$refdesc','$refuid','0','$refweburl')",tbl_referraldb::dbname)==SUCCESS)
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
	 * LOG A REFERRAL ACTIVITY OF AN ALREADY EXISTING REFERRAL
	 * @param unknown_type $refid Referral ID of the referrer from the refferaldb
	 * @param unknown_type $reffereduid User ID of the person who is being referred
	 * @param unknown_type $points Points to be added or subtracted (+for add,-for subtract) (eg.-5)
	 * @param unknown_type $desc Description regarding the log
	 * @return string SUCCESS on successful log,FAILURE on failure
	 */
	public function logreferralactivity($refid,$reffereduid,$points=0,$desc="")
	{
		//TODO ADD REF POINTS TO DB
		$uiobj=new ta_uifriend();
		$instanceid=$uiobj->randomstring(40,tbl_referral_activity::dbname,tbl_referral_activity::tblname,tbl_referral_activity::col_instanceid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_referral_activity::tblname." (".tbl_referral_activity::col_referralid.",".tbl_referral_activity::col_referreduid.",".tbl_referral_activity::col_instanceid.",".tbl_referral_activity::col_description.")
				VALUES ('$refid','$reffereduid','$instanceid','$points','$desc')",tbl_referral_activity::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}
}