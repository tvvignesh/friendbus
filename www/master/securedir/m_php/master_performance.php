<?php
/**
 *
 * CONTAINS FUNCTIONS WHICH IMPROVES PERFORMANCE
 * @author T.V.VIGNESH
 *
 */
class ta_performance
{
	/**
	 *
	 * SEND A FEEDBACK OR COMPLAINT TO TA ADMINISTRATION
	 * @param unknown_type $appid APPID of the Program making the feedback
	 * @param unknown_type $uid User ID of person making the feedback
	 * @param unknown_type $url URL on which feedback is made
	 * @param unknown_type $feedbacktext Feedback Text/Message
	 * @param unknown_type $type Type of feedback
	 * @param unknown_type $moodtype 1-Feedback,2-Feature request,3-Bug report,4-Contact
	 * @param unknown_type $solution Solution to the Problem
	 * @param unknown_type $screenshoturl Screen shot URL of problem/issue for which feedback is made
	 * @return Ambigous <string, unknown>|string Feedback ID
	 */
	public function feedbackcomplaints_send($appid,$uid,$url,$text,$moodtype,$solution="",$emailaddr="",$screenshoturl="")
	{
		$uiobj=new ta_uifriend();
		$feedbackid=$uiobj->randomstring(40,tbl_feedback_user::dbname,tbl_feedback_user::tblname,tbl_feedback_user::col_feedbackid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_feedback_user::tblname." (".tbl_feedback_user::col_appid.",".tbl_feedback_user::col_uid.",".tbl_feedback_user::col_feedback.",".tbl_feedback_user::col_screenshot.",".tbl_feedback_user::col_suggestedsol.",".tbl_feedback_user::col_emailaddr.",".tbl_feedback_user::col_feedbackid.",".tbl_feedback_user::col_moodtype.",".tbl_feedback_user::col_url.")
				VALUES ('$appid','$uid','$text','$screenshoturl','$solution','$emailaddr','$feedbackid','$moodtype','$url')",tbl_feedback_user::dbname)=="SUCCESS")
		{
			return $feedbackid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * UPDATE FEEDBACK READ STATUS
	 * @param unknown_type $feedbackid FEEDBACK ID
	 * @param unknown_type $status Read Status (1-unread,2-read,3-under review)
	 * @return string SUCCESS on successful update, "" on failure
	 */
	public function updatefeedbackreadstatus($feedbackid,$status)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_feedback_user::tblname." SET ".tbl_feedback_user::col_readstatus."='$status' WHERE ".tbl_feedback_user::col_feedbackid."='$feedbackid'",tbl_feedback_user::dbname)=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000084');
			return FAILURE;
		}
	}

	/**
	 *
	 * DELETE FEEDBACK WHEN FEEDBACK ID IS GIVEN
	 * @param unknown_type $feedbackid Feedback ID
	 * @param unknown_type $uid User ID of person who made the feedback
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletefeedback($feedbackid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_feedback_user::tblname." WHERE ".tbl_feedback_user::col_feedbackid."='$feedbackid' AND ".tbl_feedback_user::col_uid."='$uid'",tbl_feedback_user::dbname)=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000085');
			return FAILURE;
		}
	}
}
?>