<?php
/**
 *
 * CONTAINS FUNCTIONS WHICH HELP IN BLACKLISTING (REPORT AND BLOCK)
 * @author T.V.VIGNESH
 *
 */
class ta_blacklists
{
	/**
	 *
	 * ADD REASON TO BLOCK DB FOR BLOCKING AN ITEM
	 * @param unknown_type $itemid Item ID of the item being blocked
	 * @param unknown_type $itemtype Flag value specifying type of item which is blocked (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location)
	 * @param unknown_type $blockdesc Description/Reason for blocking this item
	 * @param unknown_type $blocktype Flag value specifying type (1-SPAM,2-ADULT,3-ILLEGAL,4-COPYRIGHT,5-MOCK,6-VIRUS,7-DUPLICATE,8-FAKE,9-INAPPROPRIATE,10-Others)
	 * @param unknown_type $uid User ID of the person who blocked this item
	 * @return Ambigous <string, unknown>|string BLOCK ID on successful block, FAILURE on failure
	 */
	public function block_add($itemid,$itemtype,$blockdesc,$blocktype,$uid="Unknown")
	{
		$uiobj=new ta_uifriend();
		$blockid=$uiobj->randomstring(40,tbl_block_info::dbname,tbl_block_info::tblname,tbl_block_info::col_blockid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_block_info::tblname." (".tbl_block_info::col_blockid.",".tbl_block_info::col_blocktype.",".tbl_block_info::col_blockdesc.",".tbl_block_info::col_itemid.",".tbl_block_info::col_itemtype.",".tbl_block_info::col_blockuid.")
				VALUES ('$blockid','$blocktype','$blockdesc','$itemid','$itemtype','$uid')",tbl_block_info::dbname)==SUCCESS)
		{
			return $blockid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 * 
	 * CHECK IF AN ITEM IS BLOCKED OR NOT
	 * @param unknown $uid UID of the user
	 * @param unknown $itemid Item ID to be checked
	 * @param unknown $itemtype Type of item (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location)
	 * @return string BOOL_SUCCESS on success, BOOL_FAILURE on failure
	 */
	public function block_check($uid,$itemid,$itemtype)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_block_info::tblname." WHERE ".tbl_block_info::col_blockuid."='$uid' AND ".tbl_block_info::col_itemid."='$itemid' AND ".tbl_block_info::col_itemtype."='$itemtype'", tbl_block_info::dbname);
		if(count($res)>0)
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
	 * REMOVE A BLOCK
	 * @param unknown $uid UID of the user
	 * @param unknown $itemid ID of item
	 * @param unknown $itemtype Type of item (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location)
	 * @param string $blockid (Optional) Block ID of block to remove
	 * @return string/object Returns DBDELETE result
	 */
	public function block_remove($uid,$itemid,$itemtype,$blockid="")
	{
		$dbobj=new ta_dboperations();
		if($blockid!="")
		{
			$res=$dbobj->dbdelete("DELETE FROM ".tbl_block_info::tblname." WHERE ".tbl_block_info::col_blockid."='$blockid'", tbl_block_info::dbname);
		}
		else
		{
			$res=$dbobj->dbdelete("DELETE FROM ".tbl_block_info::tblname." WHERE ".tbl_block_info::col_blockuid."='$uid' AND ".tbl_block_info::col_itemid."='$itemid' AND ".tbl_block_info::col_itemtype."='$itemtype'", tbl_block_info::dbname);
		}
		
		return $res;
	}
	
	/**
	 * 
	 * TOGGLE BLOCK
	 * @param unknown $uid UID of the user
	 * @param unknown $itemid Item ID of the item to be blocked/unblocked
	 * @param unknown $itemtype Type of item (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location)
	 * @return string/object Returns SUCCESS on successful toggle,FAILURE on failure
	 */
	public function block_toggle($uid,$itemid,$itemtype)
	{
		$blacklistobj=new ta_blacklists();
		if($blacklistobj->block_check($uid,$itemid,$itemtype))
		{
			$res=$blacklistobj->block_remove($uid,$itemid,$itemtype);
			if($res!=SUCCESS)
			{
				$res=FAILURE;
			}
		}
		else
		{
			$res=$blacklistobj->block_add($itemid,$itemtype,"",$itemtype,$uid);
			if($res!=FAILURE)
			{
				$res=SUCCESS;
			}
		}
		return $res;
	}
	
	/**
	 *
	 * REPORT AN ITEM
	 * @param unknown $itemid ID identifying the item to be reported
	 * @param unknown $itemtype Type of item (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location,15-subbot_subscription,16-subbot_submail)
	 * @param unknown $reason Reason for reporting the item elaborately
	 * @param unknown $reasontype Flag value specifying reason type (1-SPAM,2-ADULT,3-ILLEGAL,4-COPYRIGHT,5-MOCK,6-VIRUS,7-DUPLICATE,8-FAKE,9-INAPPROPRIATE,10-Others)
	 * @param unknown $uid UID of person reporting the item
	 * @param string $refurl Reference URL relating to this item
	 * @return Ambigous <string, unknown>|string Report ID on success, FAILURE on failure
	 */
	public function report_item($itemid,$itemtype,$reason,$reasontype,$uid,$refurl="")
	{
		$uiobj=new ta_uifriend();
		$blacklistobj=new ta_blacklists();
		$dbobj=new ta_dboperations();
		if($blacklistobj->report_check($itemid,$itemtype,$reasontype,$uid))
		{
			return SUCCESS;
		}
		$repid=$uiobj->randomstring(40,tbl_reports_info::dbname,tbl_reports_info::tblname,tbl_reports_info::col_reportid);
		if($dbobj->dbinsert("INSERT INTO ".tbl_reports_info::tblname." (".tbl_reports_info::col_itemid.",".tbl_reports_info::col_itemtype.",".tbl_reports_info::col_reason.",".tbl_reports_info::col_reasontype.",".tbl_reports_info::col_refurl.",".tbl_reports_info::col_reportid.",".tbl_reports_info::col_repuid.")
				VALUES ('$itemid','$itemtype','$reason','$reasontype','$refurl','$repid','$uid')",tbl_reports_info::dbname)==SUCCESS)
		{
			return $repid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 * 
	 * CHECK IF A REPORT IS ALREADY MADE OR NOT
	 * @param unknown $itemid ID of item
	 * @param unknown $itemtype Type of item (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location,15-subbot_subscription,16-subbot_submail)
	 * @param unknown $reasontype Reason type (1-SPAM,2-ADULT,3-ILLEGAL,4-COPYRIGHT,5-MOCK,6-VIRUS,7-DUPLICATE,8-FAKE,9-INAPPROPRIATE,10-Others)
	 * @param unknown $uid UID of the person
	 * @param string $repid (Optional)Report ID of report to check
	 * @return string BOOL_SUCCESS if already reported, BOOL_FAILURE if not reported
	 */
	public function report_check($itemid,$itemtype,$reasontype,$uid,$repid="")
	{
		$dbobj=new ta_dboperations();
		if($repid=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_reports_info::tblname." WHERE ".tbl_reports_info::col_itemid."='$itemid' AND ".tbl_reports_info::col_itemtype."='$itemtype' AND ".tbl_reports_info::col_reasontype."='$reasontype' AND ".tbl_reports_info::col_repuid."='$uid'", tbl_reports_info::dbname);
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_reports_info::tblname." WHERE ".tbl_reports_info::col_reportid."='$repid'", tbl_reports_info::dbname);
		}
		
		if(count($res)>0)
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
	 * REPORT ABUSE A SHORTENED URL
	 * @param unknown $linkkey The short key of the short URL
	 * @param unknown $reason The reason for reporting abuse
	 * @param unknown $uid The User ID of person reporting the URL
	 * @param string $reasontype Flag value specifying reason type (1-SPAM,2-ADULT,3-ILLEGAL,4-COPYRIGHT,5-MOCK,6-VIRUS,7-DUPLICATE,8-FAKE,9-INAPPROPRIATE,10-Others)
	 * @return unknown Report ID on success, FAILURE on failure
	 */
	public function reportabuse_shorturl($linkkey,$reason,$uid,$reasontype="1")
	{
		$blacklistobj=new ta_blacklists();
		$repid=$blacklistobj->report_item($linkkey,"8",$reason, $reasontype,$uid);
		return $repid;
	}

	/**
	 *
	 * MARK A CONVERSATION AS SPAM
	 * @param unknown $uid User ID of person marking it spam
	 * @param unknown $tid The thread ID of the thread to which the conversation belongs
	 * @param unknown $msgid The message ID of the message
	 * @param unknown $reason The reason for marking it SPAM
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function mark_spam_conversations($uid,$tid,$msgid,$reason)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_spam::tblname." WHERE ".tbl_message_spam::col_repid."='$uid' AND ".tbl_message_spam::col_tid."='$tid' AND ".tbl_message_spam::col_msgid."='$msgid'",tbl_message_spam::dbname);
		if(count($res)==0)
		{
			$res=$dbobj->dbinsert("INSERT INTO ".tbl_message_spam::tblname." (".tbl_message_spam::col_tid.",".tbl_message_spam::col_msgid.",".tbl_message_spam::col_repid.",".tbl_message_spam::col_spamreason.")
							VALUES ('$tid','$msgid','$uid','$reason')",tbl_message_spam::dbname);
			return $res;
		}
		else
		{
			return SUCCESS;
		}
	}

	public function get_spambox($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_spam::tblname." WHERE ".tbl_message_spam::col_repid."='$uid'",tbl_message_spam::dbname);
		return $res;
	}

	public function get_spambox_no($uid)
	{
		$res=$this->get_spambox($uid);
		return count($res);
	}

	/**
	 *
	 * CHECK IF A CONVERSATION IS SPAM OR NOT
	 * @param unknown $tid The Thread ID of the conversation
	 * @param unknown $msgid The message ID of the conversation to be checked for spam
	 * @param unknown $uid The UID of the user who is checking his spambox
	 * @return string True if SPAM, False if NOT SPAM
	 */
	public function check_spambox($tid,$msgid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_spam::tblname." WHERE ".tbl_message_spam::col_tid."='$tid' AND ".tbl_message_spam::col_msgid."='$msgid' AND ".tbl_message_spam::col_repid."='$uid'",tbl_message_spam::dbname);
		if(count($res)==0)
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}
}