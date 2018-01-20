<?php
/**
 *
* CONTAINS ALL ADVERTISEMENT RELATED FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_advertising
{
	/**
	 *
	 * CREATE A NEW CONTAINER FOR ADS
	 * @param unknown $pid The productid of the product for which the AD container is created
	 * @param unknown $cheight The height of the container
	 * @param unknown $cwidth The width of the container
	 * @param unknown $cost The total cost of the container (something like value of the container)
	 * @param unknown $note Notes made on this container
	 * @param string $cextflag Flag value specifying if the container is of a publisher or tech ahoy product (1-self,2-publisher)
	 * @param string $ctype Flag value specifying container type (1-all accepted,2-text only,3-html,4-flash,5-image,6-image and text,7-image and html)
	 * @param string $flag Flag value specifying permission of the ad container (1-allowed,2-under review,3-blocked)
	 * @return Ambigous <string, unknown>|string Container ID on success, FAILURE on failure
	 */
	public function newcontainer($pid,$cheight,$cwidth,$cost,$note,$cextflag="1",$ctype="1",$flag="1")
	{
		$uiobj=new ta_uifriend();
		$cid=$uiobj->randomstring(25,tbl_ad_containers::dbname,tbl_ad_containers::tblname,tbl_ad_containers::col_cid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_containers::tblname." (".tbl_ad_containers::col_prodid.",".tbl_ad_containers::col_cid.",".tbl_ad_containers::col_cheight.",".tbl_ad_containers::col_cwidth.",".tbl_ad_containers::col_ccost.",".tbl_ad_containers::col_cnote.",".tbl_ad_containers::col_cflag.",".tbl_ad_containers::col_cextflag.",".tbl_ad_containers::col_ctype.")
				VALUES ('$pid','$cid','$cheight','$cwidth','$cost','$note','$flag','$cextflag','$ctype')",tbl_ad_containers::dbname)==SUCCESS)
		{
			return $cid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * RECORD THE CLICK EVENT MADE ON AN AD CONTAINER BY THE USER
	 * @param unknown $adid The ID of the AD Clicked
	 * @param unknown $cid The container in which it was clicked
	 * @param unknown $uid The UID of the user who clicked it
	 * @return string SUCCESS on successful record, FAILURE on failure
	 */
	public function click_ad($adid,$cid,$uid)
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();

		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_clicks::tblname." (".tbl_ad_clicks::col_adid.",".tbl_ad_clicks::col_containerid.",".tbl_ad_clicks::col_uid.",".tbl_ad_clicks::col_clickip.",".tbl_ad_clicks::col_clicktime.")
				VALUES ('$adid','$cid','$uid','$ip')",tbl_ad_clicks::dbname)==SUCCESS)
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
	 * RECORD THE VIEW EVENT MADE ON AN AD CONTAINER BY THE USER
	 * @param unknown $adid The ID of the AD Viewed
	 * @param unknown $cid The container in which it was viewed
	 * @param unknown $uid The UID of the user who viewed it
	 * @return string SUCCESS on successful record, FAILURE on failure
	 */
	public function view_ad($adid,$cid,$uid)
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();

		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_views::tblname." (".tbl_ad_views::col_adid.",".tbl_ad_views::col_containerid.",".tbl_ad_views::col_uid.",".tbl_ad_views::col_viewip.",".tbl_ad_views::col_viewtime.")
				VALUES ('$adid','$cid','$uid','$ip')",tbl_ad_views::dbname)==SUCCESS)
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
	 * RECORD THE ACTION MADE ON AN AD CONTAINER BY THE USER
	 * @param unknown $adid The ID of the AD on which the action was made
	 * @param unknown $cid The container in which the action was made
	 * @param unknown $uid The UID of the user who made the action
	 * @param unknown $actiontype The type of action being made (1-Share,2-Favorite,3-Rate,4-highlight,5-Key on focus)
	 * @return string SUCCESS on successful record, FAILURE on failure
	 */
	public function action_ad($adid,$cid,$uid,$actiontype)
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();

		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_actions::tblname." (".tbl_ad_actions::col_adid.",".tbl_ad_actions::col_containerid.",".tbl_ad_actions::col_actiontype.",".tbl_ad_actions::col_uid.",".tbl_ad_actions::col_actionip.")
				VALUES ('$adid','$cid','$uid','$actiontype','$ip')",tbl_ad_actions::dbname)==SUCCESS)
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
	 * START AN AD CAMPAIGN
	 * @param unknown $col_cid The Collection ID having all containers
	 * @param unknown $mediatype Flag value specifying media type (1-Text,2-Image,3-HTML,4-Flash)
	 * @param unknown $adcontent The content of the AD which is either text,html or URL of the media if it is flash or image
	 * @param unknown $col_timeid The collection of time for which the campaign should start and end
	 * @param unknown $adflag Flag value specifying AD permissions (1-allowed,2-under review,3-blocked)
	 * @param unknown $adtype Flag value specifying type of AD (1-Product,2-Event,3-Page,4-Charity,5-Awareness,6-Company,7-Religious,8-Political,9-Life,10-Others)
	 * @param unknown $adstyle CSS to be applied to this AD when being displayed in container
	 * @param unknown $earntype Flag value specifying earning type (1-Cost Per Click,2-Cost Per View,3-Cost for time,4-Cost per action)
	 * @param unknown $socialact 1-show social activity next to ad,2-dont show social activity
	 * @param unknown $incometarget Set the income target for this AD (-1-ignore,Others-Show this AD only to people who have this min income in Rs.)
	 * @param unknown $campaignname Name of the campaign
	 * @param unknown $campaignbudget Budget of the campaign
	 * @param unknown $col_jobtargetid Collection ID specifying Job Target (-1-Ignore,Others-Collection ID specifying the JOB to be targeted)
	 * @return Ambigous <string, unknown>|string The AD ID on success, FAILURE on failure
	 */
	public function start_ad($col_cid,$mediatype,$adcontent,$col_timeid,$adflag,$adtype,$adstyle,$earntype,$socialact,$incometarget,$campaignname,$campaignbudget,$col_jobtargetid)
	{
		$uiobj=new ta_uifriend();
		$adid=$uiobj->randomstring(30,tbl_ad_web::dbname,tbl_ad_web::tblname,tbl_ad_web::col_adid);

		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_web::tblname." (".tbl_ad_web::col_adid.",".tbl_ad_web::col_col_containerid.",".tbl_ad_web::col_mediatype.",".tbl_ad_web::col_adcontent.",".tbl_ad_web::col_timeid.",".tbl_ad_web::col_adflag.",".tbl_ad_web::col_adtype.",".tbl_ad_web::col_adstyle.",".tbl_ad_web::col_earntype.",".tbl_ad_web::col_socialact.",".tbl_ad_web::col_incometarget.",".tbl_ad_web::col_campaignname.",".tbl_ad_web::col_campbudgetpd.",".tbl_ad_web::col_col_jobtargetid.")
				VALUES ('$adid','$col_cid','$mediatype','$adcontent','$col_timeid','$adflag','$adtype','$adstyle','$earntype','$socialact','$incometarget','$campaignname','$campaignbudget','$col_jobtargetid')",tbl_ad_web::dbname)==SUCCESS)
		{
			return $adid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * ADD A NEW PUBLISHER
	 * @param unknown $uid User ID of the person who is the publisher
	 * @param unknown $col_urlid The Collection ID of URLs where the publisher publishes the ADS
	 * @param unknown $payid The ID of the payment information
	 * @param unknown $flag Flag value specifying permissions (1-allowed,2-under review,3-blocked)
	 * @return Ambigous <string, unknown>|string The Publisher ID on success, FAILURE on failure
	 */
	public function publisher_add($uid,$col_urlid,$payid,$flag)
	{
		$uiobj=new ta_uifriend();
		$extid=$uiobj->randomstring(25,tbl_ad_publishers::dbname,tbl_ad_publishers::tblname,tbl_ad_publishers::col_extid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_publishers::tblname." (".tbl_ad_publishers::col_extid.",".tbl_ad_publishers::col_uid.",".tbl_ad_publishers::col_col_urlid.",".tbl_ad_publishers::col_payid.",".tbl_ad_publishers::col_flag.")
				VALUES ('$extid','$uid','$col_urlid','$payid','$flag')",tbl_ad_publishers::dbname)==SUCCESS)
		{
			return $extid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * FINALIZED AD PAYMENT DETAILS
	 * @param unknown $adid The ID of the AD for which the closing is made
	 * @param unknown $closingprice The final price to be paid/is paid for the AD
	 * @param unknown $paystatus Payment Status Flag (1-Payed,2-Pending Payment,3-Processing Payment,4-Partly Payed)
	 * @param unknown $paymode Mode of payment Flag (1-Credit Card,2-Debit Card,3-Web Transfer,4-Cash,5-DD,6-Cheque)
	 * @param unknown $paycurid Currency ID of the currency which is preffered for the closing
	 * @param unknown $paylocid Location ID of the location where the account exists
	 * @param unknown $paidamt The amount paid so far (if any) to the AD
	 * @return string SUCCESS on successful closing,FAILURE on failure
	 */
	public function closing_ad($adid,$closingprice,$paystatus,$paymode,$paycurid,$paylocid,$paidamt)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_ad_closings::tblname." (".tbl_ad_closings::col_adid.",".tbl_ad_closings::col_closingprice.",".tbl_ad_closings::col_paystatus.",".tbl_ad_closings::col_paymode.",".tbl_ad_closings::col_paycurrencyid.",".tbl_ad_closings::col_paylocid.",".tbl_ad_closings::col_paidamt.")
				VALUES ('$adid','$closingprice','$paystatus','$paymode','$paycurid','$paylocid','$paidamt')",tbl_ad_closings::dbname)==SUCCESS)
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
	 * GET NO OF CLICKS MADE ON AN AD
	 * @param unknown $adid The AD ID of the AD for which the info is to be retrieved
	 * @return number The no of clicks made on the AD
	 */
	public function get_click_no_byad($adid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_clicks::tblname." WHERE ".tbl_ad_clicks::col_adid."='$adid'",tbl_ad_clicks::dbname);
		return count($res);
	}

	/**
	 *
	 * GET NO OF CLICKS MADE ON AN AD CONTAINER
	 * @param unknown $containerid The Container ID of the container for which the info is to be retrieved
	 * @return number The no of clicks made on the container
	 */
	public function get_click_no_bycontainer($containerid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_clicks::tblname." WHERE ".tbl_ad_clicks::col_containerid."='$containerid'",tbl_ad_clicks::dbname);
		return count($res);
	}

	/**
	 *
	 * GET NO OF CLICKS MADE ON A SPECIFIC AD IN A SPECIFIC CONTAINER
	 * @param unknown $adid The AD ID of the AD for which the info is to be retrieved
	 * @param unknown $containerid The Container ID of the container for which the info is to be retrieved
	 * @return number The no of clicks made on the specific AD in the specific container
	 */
	public function get_click_no_byadandcontainer($adid,$containerid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_clicks::tblname." WHERE (".tbl_ad_clicks::col_containerid."='$containerid' AND ".tbl_ad_clicks::col_adid."='$adid')",tbl_ad_clicks::dbname);
		return count($res);
	}

	/**
	 *
	 * GET NO OF VIEWS MADE ON AN AD
	 * @param unknown $adid The AD ID of the AD for which the info is to be retrieved
	 * @return number The no of views made on the AD
	 */
	public function get_view_no_byad($adid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_views::tblname." WHERE ".tbl_ad_views::col_adid."='$adid'",tbl_ad_views::dbname);
		return count($res);
	}

	/**
	 *
	 * GET NO OF VIEWS MADE ON AN AD CONTAINER
	 * @param unknown $containerid The Container ID of the container for which the info is to be retrieved
	 * @return number The no of views made on the container
	 */
	public function get_view_no_bycontainer($containerid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_views::tblname." WHERE ".tbl_ad_views::col_containerid."='$containerid'",tbl_ad_views::dbname);
		return count($res);
	}

	/**
	 *
	 * GET NO OF VIEWS MADE ON A SPECIFIC AD IN A SPECIFIC CONTAINER
	 * @param unknown $adid The AD ID of the AD for which the info is to be retrieved
	 * @param unknown $containerid The Container ID of the container for which the info is to be retrieved
	 * @return number The no of views made on the specific AD in the specific container
	 */
	public function get_view_no_byadandcontainer($adid,$containerid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ad_views::tblname." WHERE (".tbl_ad_views::col_containerid."='$containerid' AND ".tbl_ad_views::col_adid."='$adid')",tbl_ad_views::dbname);
		return count($res);
	}

	public function create_container($cid,$weburl)
	{

	}

	public function check_container_existence($weburl,$cid)
	{

	}

	public function load_ad($cid,$uid="")
	{
		$dbobj=new ta_dboperations();
		if($uid=="")
		{
			//$dbobj->dbquery("SELECT * FROM ".tbl_ad_web::tblname." WHERE ".);
		}
	}
}
?>