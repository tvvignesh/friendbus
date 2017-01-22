<?php
class ta_support
{
	/**
	 *
	 * START CUSTOMER SUPPORT TICKET
	 * @param unknown_type $uid User ID of person requesting support
	 * @param unknown_type $uidarray User ID Array of people in the support conversation
	 * @param unknown_type $subject Subject of Support Conversation
	 * @param unknown_type $appid APP ID of the application which creates this thread
	 * @param unknown_type $tname Thread Name (Defaults to Customer Support)
	 * @param unknown_type $solvedflag Flag Value to see if thread is solved (Defaults to 3-not solved)
	 * @param unknown_type $comments Comments Made on the support (Defaults to "")
	 * @param unknown_type $rating Rating of the support (Defaults to 0)
	 * @param unknown_type $msgtype Message Type (Defaults to 6-Customer Support)
	 * @return Ambigous <string, unknown>|string The ticket ID of the Support
	 */
	public function support_customer_start($uid,$uidarray,$subject,$appid="000000",$tname="Customer Support",$solvedflag="3",$comments="",$rating=0,$msgtype="6")
	{
		$msgobj=new ta_messageoperations();
		$tid=$msgobj->thread_create($uid,$msgtype,$appid,$subject,$tname);
		$msgobj->thread_add_audience_users($tid,$uidarray);
		$uiobj=new ta_uifriend();
		$ticketid=$uiobj->randomstring(40,tbl_support_customer::dbname,tbl_support_customer::tblname,tbl_support_customer::col_ticketid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_support_customer::tblname." (".tbl_support_customer::col_threadid.",".tbl_support_customer::col_ticketid.",".tbl_support_customer::col_uid.",".tbl_support_customer::col_solvedflag.",".tbl_support_customer::col_comments.",".tbl_support_customer::col_rating.") VALUES
				('$tid','$ticketid','$uid','$solvedflag','$comments','$rating')",tbl_support_customer::dbname)=="SUCCESS")
				{
					return $ticketid;
				}
				else
				{
					return FAILURE;
				}
	}

	/**
	 *
	 * EDIT CUSTOMER SUPPORT INFORMATION
	 * @param unknown_type $ticketid Ticket ID of the SUPPORT to be edited
	 * @param unknown_type $uid User ID of person who started the support
	 * @param unknown_type $flag Flag Value of item to be edited (1-Comments,2-Rating)
	 * @param unknown_type $value Value to be inserted for the respective column
	 * @return string SUCCESS on successful edit, "" on failure
	 */
	public function support_customer_edit($ticketid,$uid,$flag,$value)
	{
		/*
		 * solved flag
		* 1-comments
		* 2-rating
		*/
		$dbobj=new ta_dboperations();
		switch ($flag)
		{
			case "1":$res=$dbobj->dbupdate("UPDATE ".tbl_support_customer::tblname." SET ".tbl_support_customer::col_comments."='$value' WHERE ".tbl_support_customer::col_ticketid."='$ticketid' AND ".tbl_support_customer::col_uid."='$uid'",tbl_support_customer::dbname);
			case "2":$res=$dbobj->dbupdate("UPDATE ".tbl_support_customer::tblname." SET ".tbl_support_customer::col_rating."='$value' WHERE ".tbl_support_customer::col_ticketid."='$ticketid' AND ".tbl_support_customer::col_uid."='$uid'",tbl_support_customer::dbname);
			default:throw new Exception('#ta@0000000_0000086');return FAILURE;
		}
		if ($res=="SUCCESS")
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
	 * CREATE HELP CONTENT
	 * @param unknown $appid The APPID of the application for which this help is created
	 * @param unknown $title The title of the help content
	 * @param unknown $content The content of the help content
	 * @param string $flag Permission flag (1-allowed,2-under review,3-blocked)
	 * @return Ambigous <string, unknown>|string Help ID on success, FAILURE on failure
	 */
	public function help_create($appid,$title,$content,$flag="1")
	{
		$uiobj=new ta_uifriend();
		$helpid=$uiobj->randomstring(35,tbl_support_help::dbname,tbl_support_help::tblname,tbl_support_help::col_helpid);

		$galobj=new ta_galleryoperations();
		$galid=$galobj->creategallery("Help Gallery_".$helpid,"A gallery for help contents","2");

		$socialobj=new ta_socialoperations();
		$rateid=$socialobj->rating_init(7);

		$messageobj=new ta_messageoperations();
		$tid=$messageobj->thread_create("","7",APPID_SUBSCRIBERBOT,"Comments for Help Item:".$helpid,"COMMENTS");

		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_support_help::tblname." (".tbl_support_help::col_helpid.",".tbl_support_help::col_helptitle.",".tbl_support_help::col_helpcontent.",".tbl_support_help::col_appid.",".tbl_support_help::col_galid.",".tbl_support_help::col_flag.",".tbl_support_help::col_rateid.",".tbl_support_help::col_threadid.")
				VALUES ('$helpid','$title','$content','$appid','$galid','$flag','$rateid','$tid')",tbl_support_help::dbname)==SUCCESS)
		{
			return $helpid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE A HELP CONTENT AND ITS GALLERY
	 * @param unknown $helpid The Help ID of the help content to be removed
	 * @return string SUCCESS on successful removal, FAILURE on failure
	 */
	public function help_remove($helpid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_helpid."='$helpid' LIMIT 0,1",tbl_support_help::dbname);
		$galid=$res[0][changesqlquote(tbl_support_help::col_galid,"")];
		$rateid=$res[0][changesqlquote(tbl_support_help::col_rateid,"")];

		if($dbobj->dbdelete("DELETE FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_helpid."='$helpid'",tbl_support_help::dbname)==SUCCESS)
		{
			$galobj=new ta_galleryoperations();
			if($galobj->deletegallery($galid,"")==SUCCESS)
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
			return FAILURE;
		}
	}

	public function help_rate_up($helpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$socialobj->rating_up($uid);
	}

	public function help_rate_down($helpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$socialobj->rating_down($uid);
	}

	public function help_comment($helpid,$uid,$comment,$attachmenturlarray="",$tagid="")
	{
		$dbobj=new ta_dboperations();
		$messageobj=new ta_messageoperations();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_helpid."='$helpid' LIMIT 0,1",tbl_support_help::dbname);
		$tid=$dbobj->colval($res,tbl_support_help::col_threadid,0);
		return $messageobj->sendcomment($uid,$comment,$tid,APPID_SUBSCRIBERBOT,$attachmenturlarray,$tagid);
	}

	public function help_get($helpid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_helpid."='$helpid'",tbl_support_help::dbname);
		return $res;
	}

	public function help_get_all($appid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_appid."='$appid'",tbl_support_help::dbname);
		return $res;
	}

	public function help_get_content($helpid)
	{
		$supportobj=new ta_support();
		$res=$supportobj->help_get($helpid);
		$content=$res[0][changesqlquote(tbl_support_help::col_helpcontent,"")];
		return $content;
	}

	public function help_get_rateid($helpid)
	{
		$supportobj=new ta_support();
		$res=$supportobj->help_get($helpid);
		$rateid=$res[0][changesqlquote(tbl_support_help::col_rateid,"")];
		return $rateid;
	}

	public function help_report($helpid,$uid,$reason,$reasontype,$refurl="")
	{
		$blacklistobj=new ta_blacklists();
		$reportid=$blacklistobj->report_item($helpid,12,$reason,$reasontype, $uid,$refurl);
		return $reportid;
	}
}
?>