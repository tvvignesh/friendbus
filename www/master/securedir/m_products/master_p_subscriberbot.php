<?php
/**
 *
 * MASTER FILE FOR SUBSCRIBER BOT
 * @author T.V.VIGNESH
 *
 */
class prod_subscriberbot
{
	/**
	 *
	 * GET ALL SUBCATEGORIES OF A CATEGORY
	 * @param unknown $mcatid The category ID for which subcategories are to be found
	 * @return Ambigous <string, NULL, unknown> A DB Array having all results on success, FAILURE on failure
	 */
	public function load_subcat($mcatid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_cats_mail::tblname." WHERE ".tbl_p_sb_cats_mail::col_pcatid."='$mcatid'",tbl_p_sb_cats_mail::dbname);
		if($res!=FAILURE)
		{
			return $res;
		}
		else
		{
			return FAILURE;
		}
	}

	public function subscription_add($subname,$emailarray,$note,$website,$catidarray,$subtype="1",$flag="1",$featured="1",$frequency=1)
	{
		$uiobj=new ta_uifriend();
		$subid=$uiobj->randomstring(30,tbl_p_sb_subscriptions_public::dbname,tbl_p_sb_subscriptions_public::tblname,tbl_p_sb_subscriptions_public::col_subid);

		for($i=0;$i<count($emailarray);$i++)
		{
			$colarray_email[$i][tbl_collection_email::col_emailaddr]=$emailarray[$i];
		}

		for($i=0;$i<count($catidarray);$i++)
		{
		$colarray_catid[$i][tbl_collection_p_sb_mailcats::col_col_mailcatid]=$catidarray[$i];
		}

		$collectionobj=new ta_collection();
		$colemailid=$collectionobj->add_collection(tbl_collection_email::tblname,$colarray_email,tbl_collection_email::col_col_emailid);
		$colcatid=$collectionobj->add_collection(tbl_collection_p_sb_mailcats::tblname,$colarray_catid,tbl_collection_p_sb_mailcats::col_col_mailcatid);

		$dbobj=new ta_dboperations();
		$dbobj->dbinsert("INSERT INTO ".tbl_p_sb_subscriptions_public::tblname." (".tbl_p_sb_subscriptions_public::col_avgfrequency.",".tbl_p_sb_subscriptions_public::col_col_emailid.",".tbl_p_sb_subscriptions_public::col_col_sb_mailcatid.",".tbl_p_sb_subscriptions_public::col_featuredflag.",".tbl_p_sb_subscriptions_public::col_subflag.",".tbl_p_sb_subscriptions_public::col_subid.",".tbl_p_sb_subscriptions_public::col_subname.",".tbl_p_sb_subscriptions_public::col_subnote.",".tbl_p_sb_subscriptions_public::col_subtype.",".tbl_p_sb_subscriptions_public::col_subwebsite.")
				VALUES ('$frequency','$colemailid','$colcatid','$featured','$flag','$subid','$subname','$note','$subtype','$website')",tbl_p_sb_subscriptions_public::dbname);
	}

	public function subscription_fetch($subid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_public::tblname." WHERE ".tbl_p_sb_subscriptions_public::col_subid."='$subid'",tbl_p_sb_subscriptions_public::dbname);
	}

	public function subscription_remove($subid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_subscriptions_public::tblname." WHERE ".tbl_p_sb_subscriptions_public::col_subid."='$subid'",tbl_p_sb_subscriptions_public::dbname);
	}

	public function subscription_rate_init($subid)
	{
		$socialobj=new ta_socialoperations();
		$rateid=$socialobj->rating_init(8);
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_subscriptions_rating::tblname." (".tbl_p_sb_subscriptions_rating::col_subid.",".tbl_p_sb_subscriptions_rating::col_rateid.") VALUES ('$subid','$rateid')",tbl_p_sb_subscriptions_rating::dbname);
	}

	public function subscription_get_rateid($subid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_rating::tblname." WHERE ".tbl_p_sb_subscriptions_rating::col_subid."='$subid'",tbl_p_sb_subscriptions_rating::dbname);
		return $res[0][changesqlquote(tbl_p_sb_subscriptions_rating::col_rateid,"")];
	}

	public function subscription_rate_up($subid,$uid)
	{
		$socialobj=new ta_socialoperations();
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->subscription_get_rateid($subid);
		return $socialobj->rating_up($uid,$rateid,8);
	}

	public function subscription_rate_down($subid,$uid)
	{
		$socialobj=new ta_socialoperations();
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->subscription_get_rateid($subid);
		return $socialobj->rating_down($uid,$rateid,8);
	}

	public function subscription_block($subid,$reason,$reasontype)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_p_sb_subscriptions_public::tblname." SET ".tbl_p_sb_subscriptions_public::col_subflag."='3' WHERE ".tbl_p_sb_subscriptions_public::col_subid."='$subid'",tbl_p_sb_subscriptions_public::dbname)==SUCCESS)
		{
			$blacklistobj=new ta_blacklists();
			return $blacklistobj->block_reason_add($subid,15,$reason,$reasontype);
		}
		else
		{
			return FAILURE;
		}
	}

	public function subscription_report($subid,$uid,$reason,$reasontype)
	{
		$blacklistobj=new ta_blacklists();
		return $blacklistobj->report_item($subid,15,$reason,$reasontype,$uid);
	}

	public function subscription_request($note,$url,$email,$uid="")
	{
		$uiobj=new ta_uifriend();
		$requestid=$uiobj->randomstring(30,tbl_p_sb_subscriptions_requests::dbname,tbl_p_sb_subscriptions_requests::tblname,tbl_p_sb_subscriptions_requests::col_requestid);
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_subscriptions_requests::tblname." (".tbl_p_sb_subscriptions_requests::col_requestid.",".tbl_p_sb_subscriptions_requests::col_requestnote.",".tbl_p_sb_subscriptions_requests::col_requesturl.",".tbl_p_sb_subscriptions_requests::col_uid.",".tbl_p_sb_subscriptions_requests::col_emailaddr.")
					VALUES ('$requestid','$note','$url','$uid','$email')",tbl_p_sb_subscriptions_requests::dbname);
	}

	/**
	 *
	 * COMMENT IN SUBSCRIPTION
	 * @param unknown $subid The ID of the subscription
	 * @param unknown $comment The comment to be made on this subscription
	 * @param unknown $uid The User ID of the person commenting
	 * @param string $attachmenturlarray The Array having attachment URLs
	 * @param string $tagid The tagid of the tags in this comment
	 * @return Ambigous <Ambigous, string, unknown> The comment ID on success, FAILURE on failure
	 */
	public function subscription_comment($subid,$comment,$uid,$attachmenturlarray="",$tagid="")
	{
		$messageobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_comments::tblname." WHERE ".tbl_p_sb_subscriptions_comments::col_subid."='$subid' AND ".tbl_p_sb_subscriptions_comments::dbname);
		if($res==EMPTY_RESULT)
		{
			$tid=$messageobj->thread_create($uid,"7", APPID_SUBSCRIBERBOT,"Comments for SUBSCRIBER BOT SUBSCRIPTION:".$subid,"Comment Thread");
		}
		else
		{
			$tid=$dbobj->colval($res,tbl_p_sb_subscriptions_comments::col_threadid,0);
			$dbobj->dbinsert("INSERT INTO ".tbl_p_sb_subscriptions_comments::tblname." (".tbl_p_sb_subscriptions_comments::col_subid.",".tbl_p_sb_submail_comments::col_threadid.") VALUES ('$subid','$tid')",tbl_p_sb_subscriptions_comments::dbname);
		}
		return $messageobj->sendcomment($uid,$comment,$tid,APPID_SUBSCRIBERBOT,$attachmenturlarray="",$tagid);
	}

	//TODO DRAFT A PROPER MAIL
	public function subscription_share_email($subid,$emailaddr,$name,$uid="",$fname="")
	{
		$link="";

		$msg="Dear $name,<br><br>Your friend $fname wanted to share this with you through Subscriber Bot. To know what it is, click the link below:<br><br>";
		$msg.=$link;

		$mailobj=new ta_mailclass();
		return $mailobj->sendmail($emailaddr,$msg,"SUBSCRIBER BOT - TECH AHOY");
	}

	public function subscription_get_rating($subid)
	{
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->subscription_get_rateid($subid);
		$socialobj=new ta_socialoperations();
		return $socialobj->rating_current($rateid);
	}

	public function submail_get_subid($submailid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submails::tblname." WHERE ".tbl_p_sb_submails::col_submailid."='$submailid' LIMIT 0,1",tbl_p_sb_submails::dbname);
		return $res[0][changesqlquote(tbl_p_sb_submails::col_subid,"")];
	}

	public function submail_add($from,$subject,$body,$headers,$timesent,$files="",$flag="1",$format="1",$mailtype="1",$scanstatus="3")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_public::tblname,tbl_p_sb_subscriptions_public::dbname);
		$collectionobj=new ta_collection();

		$flag=0;
		for($i=0;$i<count($res);$i++)
		{
			$col_emailid=$res[$i][changesqlquote(tbl_p_sb_subscriptions_public::col_col_emailid,"")];
			$subid=$res[$i][changesqlquote(tbl_p_sb_subscriptions_public::col_subid,"")];
			$ret=$collectionobj->check_collection(tbl_collection_email::tblname,tbl_collection_email::col_col_emailid,$col_emailid,tbl_collection_email::col_emailaddr,$from);
			if($ret==BOOL_SUCCESS)
			{
				$flag=1;break;
			}
		}
		if($flag==0)
		{
			$subid="";
		}

		for($i=0;$i<count($files);$i++)
		{
			$colarray_files[$i][tbl_collection_files::col_fileurl]=$files[$i];
		}

		$collectionobj=new ta_collection();
		$col_attachid=$collectionobj->add_collection(tbl_collection_email::tblname,$colarray_files,tbl_collection_email::col_col_emailid);


		$uiobj=new ta_uifriend();
		$submailid=$uiobj->randomstring(35,tbl_p_sb_submails::dbname,tbl_p_sb_submails::tblname,tbl_p_sb_submails::col_submailid);
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_submails::tblname." (".tbl_p_sb_submails::col_body.",".tbl_p_sb_submails::col_col_subbot_mailattachid.",".tbl_p_sb_submails::col_flag.",".tbl_p_sb_submails::col_headers.",".tbl_p_sb_submails::col_mailformat.",".tbl_p_sb_submails::col_mailtype.",".tbl_p_sb_submails::col_scanstatus.",".tbl_p_sb_submails::col_subid.",".tbl_p_sb_submails::col_subject.",".tbl_p_sb_submails::col_submailid.",".tbl_p_sb_submails::col_time_sent.",".tbl_p_sb_submails::col_emailaddr.") VALUES
					('$body','$col_attachid','$flag','$headers','$format','$mailtype','$scanstatus','$subid','$subject','$submailid','$timesent','$from')",tbl_p_sb_submails::dbname);
	}

	public function submail_remove($submailid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_submails::tblname." WHERE ".tbl_p_sb_submails::col_submailid."='$submailid'",tbl_p_sb_submails::dbname);
	}

	public function submail_rate_init($submailid)
	{
		$subobj=new prod_subscriberbot();
		$subid=$subobj->submail_get_subid($submailid);
		$socialobj=new ta_socialoperations();
		$rateid=$socialobj->rating_init(9);
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_submail_rating::tblname." (".tbl_p_sb_submail_rating::col_subid.",".tbl_p_sb_submail_rating::col_submailid.",".tbl_p_sb_submail_rating::col_rateid.") VALUES ('$subid','$submailid','$rateid')",tbl_p_sb_submail_rating::dbname);
	}

	public function submail_get_rateid($submailid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submail_rating::tblname." WHERE".tbl_p_sb_submail_rating::col_submailid."='$submailid'",tbl_p_sb_submail_rating::dbname);
		return $res[0][changesqlquote(tbl_p_sb_submail_rating::col_rateid,"")];
	}

	public function submail_rate_up($submailid,$uid)
	{
		$socialobj=new ta_socialoperations();
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->submail_get_rateid($submailid);
		return $socialobj->rating_up($uid,$rateid,9);
	}

	public function submail_rate_down($submailid,$uid)
	{
		$socialobj=new ta_socialoperations();
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->submail_get_rateid($submailid);
		return $socialobj->rating_down($uid,$rateid,9);
	}

	public function submail_block($submailid,$reason,$reasontype)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_p_sb_submails::tblname." SET ".tbl_p_sb_submails::col_flag."='3' WHERE ".tbl_p_sb_submails::col_submailid."='$submailid'",tbl_p_sb_submails::dbname)==SUCCESS)
		{
			$blacklistobj=new ta_blacklists();
			return $blacklistobj->block_reason_add($submailid,16,$reason,$reasontype);
		}
		else
		{
			return FAILURE;
		}
	}

	public function submail_fetch($submailid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submails::tblname." WHERE ",tbl_p_sb_submails::col_submailid."='$submailid'",tbl_p_sb_submails::dbname);
	}

	/**
	 *
	 * COMMENT IN SUBMAIL
	 * @param unknown $subid The ID of the subscription
	 * @param unknown $submailid The ID of the submail
	 * @param unknown $comment The comment to be made on this submail
	 * @param unknown $uid The User ID of the person commenting
	 * @param string $attachmenturlarray The Array having attachment URLs
	 * @param string $tagid The tagid of the tags in this comment
	 * @return Ambigous <Ambigous, string, unknown> The comment ID on success, FAILURE on failure
	 */
	public function submail_comment($subid,$submailid,$comment,$uid,$attachmenturlarray="",$tagid="")
	{
		$messageobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submail_comments::tblname." WHERE ".tbl_p_sb_submail_comments::col_subid."='$subid' AND ".tbl_p_sb_submail_comments::col_submailid."='$submailid'",tbl_p_sb_submail_comments::dbname);
		if($res==EMPTY_RESULT)
		{
			$tid=$messageobj->thread_create($uid,"7", APPID_SUBSCRIBERBOT,"Comments for SUBMAILID:".$submailid,"Comment Thread");
		}
		else
		{
			$tid=$dbobj->colval($res,tbl_p_sb_submail_comments::col_threadid,0);
			$dbobj->dbinsert("INSERT INTO ".tbl_p_sb_submail_comments::tblname." (".tbl_p_sb_submail_comments::col_subid.",".tbl_p_sb_submail_comments::col_submailid.",".tbl_p_sb_submail_comments::col_threadid.") VALUES ('$subid','$submailid','$tid')",tbl_p_sb_submail_comments::dbname);
		}
		return $messageobj->sendcomment($uid,$comment,$tid,APPID_SUBSCRIBERBOT,$attachmenturlarray="",$tagid);
	}

	//TODO DRAFT A PROPER SUBMAIL
	public function submail_share_email($submailid,$emailaddr,$name,$uid="",$fname)
	{
		$link="";

		$msg="Dear $name,<br><br>Your friend $fname wanted to share this with you through Subscriber Bot. To know what it is, click the link below:<br><br>";
		$msg.=$link;

		$mailobj=new ta_mailclass();
		return $mailobj->sendmail($emailaddr,$msg,"SUBSCRIBER BOT - TECH AHOY");
	}

	public function submail_readlist_assign($submailid,$uid,$readstatus,$readlistid="")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		if($readlistid=="")
		{
			$readlistid=$uiobj->randomstring(35,tbl_p_sb_submail_readlist::dbname,tbl_p_sb_submail_readlist::tblname,tbl_p_sb_submail_readlist::col_readlistid);
		}
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_submail_readlist::tblname." (".tbl_p_sb_submail_readlist::col_readlistid.",".tbl_p_sb_submail_readlist::col_readstatus.",".tbl_p_sb_submail_readlist::col_submailid.",".tbl_p_sb_submail_readlist::col_uid.")
					VALUES ('$readlistid','$readstatus','$submailid','$uid')",tbl_p_sb_submail_readlist::dbname);
	}

	public function submail_readlist_fetch($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submail_readlist::tblname." WHERE ".tbl_p_sb_submail_readlist::col_uid."='$uid'",tbl_p_sb_submail_readlist::dbname);
		return $res;
	}

	public function submail_readlist_item_get_status($readlistid,$submailid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submail_readlist::tblname." WHERE ".tbl_p_sb_submail_readlist::col_readlistid."='$readlistid' AND ".tbl_p_sb_submail_readlist::col_submailid."='$submailid'",tbl_p_sb_submail_readlist::dbname);
		return $res[0][changesqlquote(tbl_p_sb_submail_readlist::col_readstatus,"")];
	}

	public function submail_readlist_item_edit_status($readlistid,$submailid,$newstatus)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_submail_readlist::tblname." SET ".tbl_p_sb_submail_readlist::col_readstatus."='$newstatus' WHERE ".tbl_p_sb_submail_readlist::col_readlistid."='$readlistid' AND ".tbl_p_sb_submail_readlist::col_submailid."='$submailid'",tbl_p_sb_submail_readlist::dbname);
	}

	public function submail_readlist_item_remove($readlistid,$submailid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_submail_readlist::tblname." WHERE ".tbl_p_sb_submail_readlist::col_readlistid."='$readlistid' AND ".tbl_p_sb_submail_readlist::col_submailid."='$submailid'",tbl_p_sb_submail_readlist::dbname);
	}

	public function submail_readlist_remove($readlistid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_submail_readlist::tblname." WHERE ".tbl_p_sb_submail_readlist::col_readlistid."='$readlistid'",tbl_p_sb_submail_readlist::dbname);
	}

	public function submail_get_rating($submailid)
	{
		$subobj=new prod_subscriberbot();
		$rateid=$subobj->submail_get_rateid($submailid);
		$socialobj=new ta_socialoperations();
		return $socialobj->rating_current($rateid);
	}

	public function submail_set_scanstatus($submailid,$status)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_submails::tblname." SET ".tbl_p_sb_submails::col_scanstatus."='$status' WHERE ".tbl_p_sb_submails::col_submailid."='$submailid'",tbl_p_sb_submails::dbname);
	}

	public function submail_get_scanstatus($submailid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submails::tblname." WHERE ".tbl_p_sb_submails::col_submailid."='$submailid'",tbl_p_sb_submails::dbname);
		return $res[0][changesqlquote(tbl_p_sb_submails::col_scanstatus,"")];
	}

	public function user_digest_settings($uid,$frequency)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_user::tblname." SET ".tbl_p_sb_user::col_digestfrequency."='$frequency' WHERE ",tbl_p_sb_user::col_uid."='$uid'",tbl_p_sb_user::dbname);
	}

	public function user_digest_send($uid)
	{
		//TODO DRAFT A PROPER MAIL
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();

		$digarray=Array();

		$col_subid=$this->get_digest_subid($uid);
		$res=$collectionobj->get_collection_complete_info(tbl_collection_p_sb_subscriptions::tblname,tbl_collection_p_sb_subscriptions::col_col_subid,$col_subid);
		for($i=0;$i<count($res);$i++)
		{
			$subid=$dbobj->colval($res,tbl_collection_p_sb_subscriptions::col_subid,$i);
			$res1=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submails::tblname." WHERE ".tbl_p_sb_submails::col_subid."='$subid'",tbl_p_sb_submails::dbname);
			$totalreceived=count($res1);
			$res2=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_submail_readlist::tblname." WHERE ".tbl_p_sb_submail_readlist::col_uid."='$uid' AND ".tbl_p_sb_submail_readlist::col_readstatus."='1' AND ".tbl_p_sb_submail_readlist::col_subid."='$subid'",tbl_p_sb_submail_readlist::dbname);
			$readcount=count($res2);
			$digarray[$subid]["total"]=$totalreceived;
			$digarray[$subid]["read"]=$readcount;
			$digarray[$subid]["unread"]=$totalreceived-$readcount;
		}
		$mailobj=new ta_mailclass();
		$userobj=new ta_userinfo();
		$userobj->user_initialize_data($uid);
		$msg="Dear ".$userobj->fname.",<br><br>You have received the following newsletters through SUBSCRIBER BOT";
		foreach($digarray as $key=>$val)
		{
			$subid=$key;
			$name=$this->get_subscription_name($subid);
			$msg.="<br><br><u>$name</u><br>";
			foreach ($digarray[$subid] as $key1=>$val1)
			{
				if($key1=="total")
				{
					$msg.="<br>TOTAL : $val1";
				}
				else
				if($key1=="read")
				{
					$msg.="<br>READ : $val1";
				}
				else
				if($key1=="unread")
				{
					$msg.="<br>UNREAD : $val1";
				}
			}
		}
		$mailobj->sendmail($userobj->email,$msg,"SUBSCRIBER BOT DIGESTS");
	}

	public function get_subscription_name($subid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_public::tblname." WHERE ".tbl_p_sb_subscriptions_public::col_subid."='$subid' LIMIT 0,1",tbl_p_sb_subscriptions_public::dbname);
		return $dbobj->colval($res,tbl_p_sb_subscriptions_public::col_subname,0);
	}

	public function get_digest_subid($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user::tblname." WHERE ".tbl_p_sb_user::col_uid."='$uid' LIMIT 0,1",tbl_p_sb_user::dbname);
		$col_subid=$dbobj->colval($res,tbl_p_sb_user::col_col_digest_subid,0);
		return $col_subid;
	}

	public function user_submail_fav_add($submailid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user_favs::tblname." WHERE ".tbl_p_sb_user_favs::col_id."='$submailid' AND ".tbl_p_sb_user_favs::col_itemtype."='2'",tbl_p_sb_user_favs::dbname)==EMPTY_RESULT)
		{
			return SUCCESS;
		}
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_user_favs::tblname." (".tbl_p_sb_user_favs::col_uid.",".tbl_p_sb_user_favs::col_id.",".tbl_p_sb_user_favs::col_itemtype.")
					VALUES ('$uid','$submailid','2')",tbl_p_sb_user_favs::dbname);
	}

	public function user_submail_fav_remove($submailid,$uid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_user_favs::tblname." WHERE ".tbl_p_sb_user_favs::col_id."='$submailid' AND ".tbl_p_sb_user_favs::col_itemtype."='2' AND ".tbl_p_sb_user_favs::col_uid."='$uid'",tbl_p_sb_user_favs::dbname);
	}

	public function user_subscription_fav_add($subscriptionid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user_favs::tblname." WHERE ".tbl_p_sb_user_favs::col_id."='$subscriptionid' AND ".tbl_p_sb_user_favs::col_itemtype."='1'",tbl_p_sb_user_favs::dbname)==EMPTY_RESULT)
		{
			return SUCCESS;
		}
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_user_favs::tblname." (".tbl_p_sb_user_favs::col_uid.",".tbl_p_sb_user_favs::col_id.",".tbl_p_sb_user_favs::col_itemtype.")
				VALUES ('$uid','$subscriptionid','1')",tbl_p_sb_user_favs::dbname);
	}

	public function user_subscription_fav_remove($subscriptionid,$uid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_user_favs::tblname." WHERE ".tbl_p_sb_user_favs::col_id."='$subscriptionid' AND ".tbl_p_sb_user_favs::col_itemtype."='1' AND ".tbl_p_sb_user_favs::col_uid."='$uid'",tbl_p_sb_user_favs::dbname);
	}

	//TODO DRAFT A PROPER INVITATION
	public function user_invite_email($name,$emailaddr,$additionaltext="",$uid="",$sendername="")
	{
		$link="";
		$msg="Dear $name,<br><br>You have been invited to join SUBSCRIBER BOT by $sendername.<br><br>To join, Click the link below:<br>";
		$msg.=$link;
		$mailobj=new ta_mailclass();
		return $mailobj->sendmail($emailaddr,$msg,$sendername." - INVITATION TO SUBSCRIBER BOT");
	}

	public function improvement_feedback($feedback,$solution,$emailaddr,$uid="",$url="",$screenshot="")
	{
		$performanceobj=new ta_performance();
		return $performanceobj->feedbackcomplaints_send(APPID_SUBSCRIBERBOT,$uid,$url,$feedback,"1",$solution,$emailaddr,$screenshot);
	}

	public function improvement_complaint($complaint,$emailaddr,$uid="",$url="",$screenshot="",$solution="")
	{
		$performanceobj=new ta_performance();
		return $performanceobj->feedbackcomplaints_send(APPID_SUBSCRIBERBOT,$uid,$url,$complaint,"2",$solution,$emailaddr,$screenshot);
	}

	public function support_get_help_all($appid=APPID_SUBSCRIBERBOT)
	{
		$helpobj=new ta_support();
		return $helpobj->help_get_all($appid);
	}

	public function support_get_help_item($helpid)
	{
		$helpobj=new ta_support();
		return $helpobj->help_get($helpid);
	}

	public function search_subscription($text,$col_catid="")
	{
		//TODO SEARCH SUBSCRIPTION
	}

	public function search_submail($text,$col_catid="",$subscriptionid="")
	{
		//TODO SEARCH WITHIN A MAIL
	}

	public function activity_add_subscription_view($subid,$uid="")
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_views::tblname." WHERE ".tbl_p_sb_views::col_id."='$subid' AND ".tbl_p_sb_views::col_itemtype."='1' AND ".tbl_p_sb_views::col_uid."='$uid' AND ".tbl_p_sb_views::col_ip."='$ip'",tbl_p_sb_views::dbname);
		if($res==EMPTY_RESULT)
		{
			return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_views::tblname." (".tbl_p_sb_views::col_id.",".tbl_p_sb_views::col_ip.",".tbl_p_sb_views::col_itemtype.",".tbl_p_sb_views::col_uid.")
					VALUES ('$subid','$ip','1','$uid')",tbl_p_sb_views::dbname);
		}
		return SUCCESS;
	}

	public function activity_add_submailview($submailid,$uid="")
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_views::tblname." WHERE ".tbl_p_sb_views::col_id."='$submailid' AND ".tbl_p_sb_views::col_itemtype."='2' AND ".tbl_p_sb_views::col_uid."='$uid' AND ".tbl_p_sb_views::col_ip."='$ip'",tbl_p_sb_views::dbname);
		if($res==EMPTY_RESULT)
		{
			return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_views::tblname." (".tbl_p_sb_views::col_id.",".tbl_p_sb_views::col_ip.",".tbl_p_sb_views::col_itemtype.",".tbl_p_sb_views::col_uid.")
					VALUES ('$submailid','$ip','1','$uid')",tbl_p_sb_views::dbname);
		}
		return SUCCESS;
	}

	public function activity_add_websiteview($uid="")
	{
		$userobj=new ta_userinfo();
		$ip=$userobj->getip();
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_views::tblname." WHERE ".tbl_p_sb_views::col_itemtype."='3' AND ".tbl_p_sb_views::col_uid."='$uid' AND ".tbl_p_sb_views::col_ip."='$ip'",tbl_p_sb_views::dbname);
		if($res==EMPTY_RESULT)
		{
			return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_views::tblname." (".tbl_p_sb_views::col_id.",".tbl_p_sb_views::col_ip.",".tbl_p_sb_views::col_itemtype.",".tbl_p_sb_views::col_uid.")
					VALUES ('','$ip','3','$uid')",tbl_p_sb_views::dbname);
		}
		return SUCCESS;
	}

	public function scan_file($fileurl)
	{
		//TODO SCAN A FILE
	}

	public function user_subscription_create_category($uid,$catname,$parcatid="",$catdesc="",$col_subid="")
	{
		$uiobj=new ta_uifriend();
		$cid=$uiobj->randomstring(35,tbl_p_sb_user_mailcats::dbname,tbl_p_sb_user_mailcats::tblname,tbl_p_sb_user_mailcats::col_cid);
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_user_mailcats::tblname." (".tbl_p_sb_user_mailcats::col_cdesc.",".tbl_p_sb_user_mailcats::col_cid.",".tbl_p_sb_user_mailcats::col_cname."".tbl_p_sb_user_mailcats::col_col_subid.",".tbl_p_sb_user_mailcats::col_pcid.",".tbl_p_sb_user_mailcats::col_uid_creator.")
					VALUES ('$catdesc','$cid','$catname','$col_subid','$parcatid','$uid')",tbl_p_sb_user_mailcats::dbname);
	}

	public function mailcats_get_colsubid($cid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user_mailcats::tblname." WHERE ".tbl_p_sb_user_mailcats::col_cid."='$cid'",tbl_p_sb_user_mailcats::dbname);
		$colid=$res[0][changesqlquote(tbl_p_sb_user_mailcats::col_cid,"")];
		return $colid;
	}

	public function user_subscription_category_addfilter($catid,$subidarray)
	{
		$subobj=new prod_subscriberbot();
		$colid=$subobj->mailcats_get_colsubid($catid);
		for($i=0;$i<count($subidarray);$i++)
		{
			$colarray[$i][tbl_collection_p_sb_subscriptions::col_col_subid]=$subidarray[$i];
		}

		$collectionobj=new ta_collection();
		return $collectionobj->add_collection(tbl_collection_p_sb_subscriptions::tblname,$colarray,tbl_collection_p_sb_subscriptions::col_col_subid,$colid);
	}

	public function user_category_removefilter_subscription($catid,$subid)
	{
		$subobj=new prod_subscriberbot();
		$colid=$subobj->mailcats_get_colsubid($catid);
		$collectionobj=new ta_collection();
		$collectionobj->remove_collection_item(tbl_collection_p_sb_subscriptions::tblname,tbl_collection_p_sb_subscriptions::col_col_subid,$colid,tbl_collection_p_sb_subscriptions::col_subid,$subid);
	}

	public function user_subscription_categorize($uid,$catid,$parcatid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_user_mailcats::tblname." SET ".tbl_p_sb_user_mailcats::col_pcid."='$catid' WHERE ".tbl_p_sb_user_mailcats::col_cid."='$catid'",tbl_p_sb_user_mailcats::dbname);
	}

	public function alias_subscription_add($uid,$subid,$alias)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_alias::tblname." WHERE ".tbl_p_sb_subscriptions_alias::col_subid."='$subid'",tbl_p_sb_subscriptions_alias::dbname)==EMPTY_RESULT)
		{
			return $dbobj->dbinsert("INSERT INTO ".tbl_p_sb_subscriptions_alias::tblname." (".tbl_p_sb_subscriptions_alias::col_uid.",".tbl_p_sb_subscriptions_alias::col_subid.",".tbl_p_sb_subscriptions_alias::col_alias.") VALUES ('$uid','$subid','$alias')",tbl_p_sb_subscriptions_alias::dbname);
		}
		else
		{
			return FAILURE;
		}
	}

	public function alias_subscription_remove($uid,$subid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_p_sb_subscriptions_alias::tblname." WHERE ".tbl_p_sb_subscriptions_alias::col_subid."='$subid' AND ".tbl_p_sb_subscriptions_alias::col_uid."='$uid'",tbl_p_sb_subscriptions_alias::dbname);
	}

	public function comment_report($uid,$commentid,$reason,$reasontype,$refurl="")
	{
		$blacklistobj=new ta_blacklists();
		return $blacklistobj->report_item($commentid,10,$reason,$reasontype,$uid,$refurl);
	}

	public function conversations_rate_up($uid,$convid)
	{
		$messageobj=new ta_messageoperations();
		return $messageobj->conversations_rate_up($uid, $convid);
	}

	public function conversations_rate_down($uid,$convid)
	{
		$messageobj=new ta_messageoperations();
		return $messageobj->conversations_rate_down($uid, $convid);
	}

	public function get_toprated_subscriptions($start=0,$end=15)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$subarray=Array();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_rating::tblname." LIMIT $start,$end",tbl_p_sb_subscriptions_rating::dbname);
		for($i=0;$i<count($res);$i++)
		{
			$rateid=$dbobj->colval($res,tbl_p_sb_subscriptions_rating::col_rateid,$i);
			$subid=$dbobj->colval($res,tbl_p_sb_subscriptions_rating::col_subid,$i);
			$rating=$socialobj->rating_current($rateid);
			$subarray[$subid]=$rating;
		}
		asort($subarray);
		return $subarray;
	}

	public function get_subid_from_email($emailaddr)
	{
		$collectionobj=new ta_collection();
		$dbobj=new ta_dboperations();

		$subid="";
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_subscriptions_public::tblname,tbl_p_sb_subscriptions_public::dbname);
		for($k=0;$k<count($res);$k++)
		{
			$col_emailid=$dbobj->colval($res,tbl_p_sb_subscriptions_public::col_col_emailid,$k);
			if($collectionobj->check_collection_item(tbl_collection_email::tblname,tbl_collection_email::col_col_emailid,$col_emailid,tbl_collection_email::col_emailaddr,$emailaddr))
			{
				$subid=$dbobj->colval($res,tbl_p_sb_subscriptions_public::col_subid,$k);break;
			}
		}
		return $subid;
	}
}
?>