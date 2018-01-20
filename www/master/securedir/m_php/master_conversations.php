<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO THREADS,MESSAGES AND ATTACHMENTS
 * @author T.V.VIGNESH
 *
 */
class ta_messageoperations
{
	/**
	 *
	 * CREATE A NEW THREAD
	 * @param unknown_type $uid User ID of person who is creating the thread
	 * @param unknown_type $msgtype Message Type Flags (1-normal, 2-chat, 3-sent as email, 4-profile post, 5-group post, 6-customer support, 7-item comments)
	 * @param unknown_type $appid The APP ID of the application which creates this thread
	 * @param unknown_type $subject Subject of the thread
	 * @param unknown_type $tname Thread Name
	 * @param unknown_type $tpic Thread Cover Picture (Defaults to "")
	 * @param unknown_type $tflag Thread Flag (1-allowed,2-under review,3-blocked) (Defaults to 1)
	 * @return string|Ambigous <string, unknown> Thread ID on success, "" on failure
	 */
	public function thread_create($uid,$msgtype,$subject,$appid="0000",$tpic="",$tflag="1",$rateid="")
	{
		$randobj=new ta_uifriend();
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$colobj=new ta_collection();
		$userobj=new ta_userinfo();

		$tid=$randobj->randomstring(30,tbl_message_outline::dbname,tbl_message_outline::tblname,tbl_message_outline::col_tid);
		
		if($msgtype=="7"&&$rateid!="")
		{
			if($socialobj->rating_check_rateid_exists($rateid))
			{
				$messageobj=new ta_messageoperations();
				$tid=$messageobj->get_item_comthreadid($rateid);
				if($tid!=FAILURE)
				{
					return $tid;
				}
			}
		}
		else
		if($msgtype!="7")
		{
			$userobj->addreppoints($uid,"5",'Creating <a href="/post_display.php?tid='.$tid.'">this</a> thread');
		}

		$audid=$audienceobj->audience_create();
		if($rateid=="")
		{
			$rateid=$socialobj->rating_init(11);
		}

		$res=$dbobj->dbinsert("INSERT INTO ".tbl_message_outline::tblname." (".tbl_message_outline::col_tid.",".tbl_message_outline::col_subject.",".tbl_message_outline::col_fid.",".tbl_message_outline::col_audienceid.",".tbl_message_outline::col_msgtype.",".tbl_message_outline::col_threadpic.",".tbl_message_outline::col_threadflag.",".tbl_message_outline::col_appid.",".tbl_message_outline::col_rateid.") VALUES
				('$tid','$subject','$uid','$audid','$msgtype','$tpic','$tflag','$appid','$rateid')",tbl_message_outline::dbname);
		if($res==SUCCESS)
		{
			$dbobj->dbinsert("INSERT INTO ".tbl_message_incoming::tblname." (".tbl_message_incoming::col_tid.",".tbl_message_incoming::col_ruid.") VALUES ('$tid','$uid')", tbl_message_incoming::dbname);
			$colarray=Array();
			$colarray[0][tbl_collection_users::col_uid]=$uid;
			$res1=$audienceobj->audience_fetch($audid);
			$col_userid=$dbobj->colval($res1,tbl_audience_target::col_col_users,0);
			if($col_userid=="-1")$col_userid="";
			$colid=$colobj->add_collection(tbl_collection_users::tblname,$colarray,tbl_collection_users::col_col_uid,$col_userid);
			if($col_userid=="")
			{$audienceobj->audience_edit($audid,tbl_audience_target::col_col_users,$colid);}
			
			return $tid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000068');
			return FAILURE;
		}
	}
	
	public function get_userthreads($uid,$ttype="4",$start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		
		return $dbobj->dbquery("SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_fid."='$uid' AND ".tbl_message_outline::col_msgtype."='$ttype' ORDER BY ".tbl_message_outline::col_lastupdate." DESC LIMIT $start,$tot", tbl_message_outline::dbname);
	}
	
	/**
	 * 
	 * EDIT THREAD OUTLINE
	 * @param unknown $tid Thread ID
	 * @param unknown $colname Column Name
	 * @param unknown $value Value to be substituted
	 */
	public function thread_edit_outline($tid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_message_outline::tblname." SET ".$colname."='$value' WHERE ".tbl_message_outline::col_tid."='$tid'", tbl_message_outline::dbname);
	}

	public function thread_add_audienceid($tid,$audid)
	{
		$msgobj=new ta_messageoperations();
		return $msgobj->thread_edit_outline($tid,tbl_message_outline::col_audienceid, $audid);
	}
	
	/**
	 * 
	 * GET COMMENT THREAD DETAIL FOR A THREAD
	 * @param unknown $mtid The thread for which comment thread is to be fetched
	 * @return DB Array of result from thread_comments table
	 */
	public function thread_get_comthread($mtid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_thread_comments::tblname." WHERE ".tbl_thread_comments::col_mtid."='$mtid'", tbl_thread_comments::dbname);
	}
	
	/**
	 * 
	 * CREATE A COMMENT THREAD
	 * @param unknown $mtid The thread ID for which comment thread are to be created
	 * @return The Comment thread ID on success, FAILURE on failure
	 */
	public function thread_add_comthread($mtid)
	{
		$msgobj=new ta_messageoperations();
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		
		$comres=$msgobj->thread_get_comthread($mtid);
		if(count($comres)!=0)
		{
			return $comres[0][changesqlquote(tbl_thread_comments::col_ctid,"")];
		}
		$tres=$msgobj->getthreadoutline($mtid);
		$uid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		$subject=$tres[0][changesqlquote(tbl_message_outline::col_subject,"")];
		$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
		
		$comtid=$msgobj->thread_create($uid,"7","Comments for thread:".$subject);
		if($dbobj->dbinsert("INSERT INTO ".tbl_thread_comments::tblname." (".tbl_thread_comments::col_mtid.",".tbl_thread_comments::col_ctid.") VALUES ('$mtid','$comtid')", tbl_thread_comments::dbname)==SUCCESS)
		{
			$msgobj->thread_add_audienceid($comtid,$audid);
			return $comtid;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * DELETE A COMMENT THREAD AND ALL ITS CONTENTS FOR A MAIN THREAD
	 * @param unknown $mtid The main thread ID
	 * @return SUCCESS on success, FAILURE on failure
	 */
	public function thread_del_comthread($mtid)
	{
		$msgobj=new ta_messageoperations();
		
		$tres=$msgobj->getthreadoutline($mtid);
		$uid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		
		$comtid=$msgobj->thread_get_comthread($mtid);
		if($comtid!=FAILURE)
			return $msgobj->deletethread($comtid,$uid);
		else
			return SUCCESS;
	}
	
	/**
	 * 
	 * GET REPLIES TO A MESSAGE IN A THREAD
	 * @param unknown $msgid Message ID
	 * @return DB Array of replies from message_content table
	 */
	public function thread_get_replies($msgid)
	{
		$dbobj=new ta_dboperations();
		
		return $dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_replyto."='$msgid'", tbl_message_content::dbname);
	}
	
	/**
	 *
	 * ADD USERS TO THE AUDIENCE LIST OF A THREAD
	 * @param unknown $tid The Thread ID of the thread
	 * @param unknown $uidarray The Array containing User IDs
	 * @return SUCCESS on success, FAILURE on failure
	 */
	public function thread_add_audience_users($tid,$uidarray)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();
		$socialobj=new ta_socialoperations();
		
		if(count($uidarray)==0)return FAILURE;
		$audid=$this->get_thread_audienceid($tid);
		$res=$audienceobj->audience_fetch($audid);
		$col_userid=$dbobj->colval($res,tbl_audience_target::col_col_users,0);
		if($col_userid=="-1")
		{
			$col_userid="";
		}
		$colarray=Array();
		
		for($i=0;$i<count($uidarray);$i++)
		{
			$colarray[$i][tbl_collection_users::col_uid]=$uidarray[$i];
			$dbobj->dbinsert("INSERT INTO ".tbl_message_incoming::tblname." (".tbl_message_incoming::col_tid.",".tbl_message_incoming::col_ruid.") VALUES ('$tid','$uidarray[$i]')", tbl_message_incoming::dbname);
		}
		$colid=$collectionobj->add_collection(tbl_collection_users::tblname,$colarray,tbl_collection_users::col_col_uid,$col_userid);		
		if($col_userid=="")
		{
			$audienceobj->audience_edit($audid,tbl_audience_target::col_col_users,$colid);
		}
		if($colid!=FAILURE)
		{
			return $colid;
		}
		else
		return FAILURE;
	}
	
	/**
	 *
	 * REMOVE A PERSON FROM AUDIENCE OF A THREAD
	 * @param unknown $tid The thread ID of the thread
	 * @param unknown $uid The User ID of person to be removed from the thread
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function thread_remove_audience_user($tid,$uid)
	{
		$audienceobj=new ta_audience();
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();

		$audid=$this->get_thread_audienceid($tid);
		$res=$audienceobj->audience_fetch($audid);
		$col_userid=$dbobj->colval($res,tbl_audience_target::col_col_users,0);

		$collectionobj->remove_collection_item(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_userid,tbl_collection_users::col_uid,$uid);
		return SUCCESS;
	}

	/**
	 *
	 * GET THE AUDIENCE ID OF A THREAD
	 * @param unknown $tid The thread ID for which the audience has to be retrieved
	 * @return unknown The audience ID on success,FAILURE on failure
	 */
	public function get_thread_audienceid($tid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_tid."='$tid' LIMIT 0,1",tbl_message_outline::dbname);
		if($res==EMPTY_RESULT||$res==FAILURE)
		{
			return FAILURE;
		}
		return $dbobj->colval($res,tbl_message_outline::col_audienceid,0);
	}

	/**
	 * 
	 * GET THE NUMBER OF AUDIENCE FOR A THREAD
	 * @param unknown $tid Thread ID
	 * @return number The number of audience
	 */
	public function get_thread_noaudience($tid)
	{
		$msgobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();
		$audobj=new ta_audience();
		$colobj=new ta_collection();
		
		$audid=$msgobj->get_thread_audienceid($tid);
		$res=$audobj->audience_fetch($audid);
		$col_uid=$res[0][changesqlquote(tbl_audience_target::col_col_users,"")];
		$res=$colobj->get_collection_complete_info(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_uid);
		return count($res);
	}
	
	/**
	 * 
	 * GET THE AUDIENCE OF A THREAD
	 * @param unknown $tid Thread ID
	 */
	public function get_thread_audience($tid)
	{
		$msgobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();
		$audobj=new ta_audience();
		$colobj=new ta_collection();
	
		$audid=$msgobj->get_thread_audienceid($tid);
		$res=$audobj->audience_fetch($audid);
		$col_uid=$res[0][changesqlquote(tbl_audience_target::col_col_users,"")];
		$res=$colobj->get_collection_complete_info(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_uid);
		return $res;
	}
	
	public function get_threadid_byuser($fuid,$uid)
	{
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$uobj=new ta_userinfo();
		
		$inres=$dbobj->dbquery("SELECT * FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_ruid."='$fuid' ORDER BY ".tbl_message_incoming::col_rtime." DESC", tbl_message_incoming::dbname);
		for($i=0;$i<count($inres);$i++)
		{
			$intid=$inres[$i][changesqlquote(tbl_message_incoming::col_tid,"")];
			$tres=$msgobj->getthreadoutline($intid);
			$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
			$inres1=$dbobj->dbquery("SELECT * FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_tid."='$intid'", tbl_message_incoming::dbname);
			if(count($inres1)==2)
			{
				$inres2=$dbobj->dbquery("SELECT * FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_ruid."='$uid' AND ".tbl_message_incoming::col_tid."='$intid'", tbl_message_incoming::dbname);
				if(count($inres2)!=0)
				{
					$ftid=$intid;
					break;
				}
				else
				{
					continue;
				}
			}
		}
		if(!isset($ftid))
		{
			return FAILURE;
		}
		else
		{
			return $ftid;
		}
	}
	
	/**
	 * 
	 * Share a thread message
	 * @param unknown $tid Thread ID 
	 * @param unknown $msgid Message ID
	 * @param unknown $subject_new New subject
	 * @param unknown $content_new New content
	 */
	public function thread_share($tid,$msgid,$uid,$audid_new,$subject_new,$content_new)
	{
		$msgobj=new ta_messageoperations();
		$uobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		
		$tres=$msgobj->getthreadoutline($tid);
		if(count($tres)==0)return FAILURE;
		$mres=$msgobj->getmsg($tid,"","",$msgid);
		if(count($mres)==0)return FAILURE;
		
		$msg_old=$mres[0][changesqlquote(tbl_message_content::col_msg,"")];
		$uid_old=$mres[0][changesqlquote(tbl_message_content::col_fuid,"")];
		$colmedid=$mres[0][changesqlquote(tbl_message_content::col_col_mediaid,"")];
		
		//$ttype=$tres[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
		$ttype="4";
		
		$uobj->user_initialize_data($uid_old);
		
		$subject=$subject_new.' (via <a href="/users.php?uid='.$uobj->uid.'">'.$uobj->fname.' '.$uobj->mname.' '.$uobj->lname.'</a>)';
		$content=$content_new."<hr>".$msg_old;
		$tid_new=$msgobj->thread_create($uid,$ttype,$subject);
		$msgid_new=$msgobj->sendmsg($uid,$content,$tid_new);

		$msgobj->thread_add_audienceid($tid_new,$audid_new);
		$msgobj->addattachment($colmedid,$tid_new,$msgid_new);
		
		if($msgobj->share_add($tid,$msgid,$tid_new,$msgid_new)==SUCCESS)
		{
			return $msgid_new;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * ADD SHARE TO SHARE DB
	 * @param unknown $tid Original Thread ID
	 * @param unknown $msgid Original Message ID
	 * @param unknown $ntid New thread ID
	 * @param unknown $nmsgid New message ID
	 */
	public function share_add($tid,$msgid,$ntid,$nmsgid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_message_shares::tblname." (".tbl_message_shares::col_tid.",".tbl_message_shares::col_msgid.",".tbl_message_shares::col_ntid.",".tbl_message_shares::col_nmsgid.") VALUES ('$tid','$msgid','$ntid','$nmsgid')", tbl_message_shares::dbname);
	}
	
	/**
	 * 
	 * CHECK IF A POST IS ORIGINAL OR SHARED
	 * @param unknown $tid The thread ID
	 * @param unknown $msgid The message ID
	 */
	public function share_checkoriginal($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		if(count($dbobj->dbquery("SELECT * FROM ".tbl_message_shares::tblname." WHERE ".tbl_message_shares::col_ntid."='$tid' AND ".tbl_message_shares::col_nmsgid."='$msgid'", tbl_message_shares::dbname))==0)
		{
			return BOOL_SUCCESS;//original
		}
		else
		{
			return BOOL_FAILURE;//shared
		}
	}
	
	/**
	 * GET DETAILS REGARDING A SHARE
	 * @param unknown $tid The thread ID (new)
	 * @param unknown $msgid The message ID (new)
	 * @return unknown DB Array of results from message_shares tbl 
	 */
	public function share_getdetail($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_message_shares::tblname." WHERE ".tbl_message_shares::col_ntid."='$tid' AND ".tbl_message_shares::col_nmsgid."='$msgid'", tbl_message_shares::dbname);
	}
	
	/**
	 * 
	 * GET ORIGINAL THREAD ID AND MESSAGE ID OF A SHARE
	 * @param unknown $tid Thread ID
	 * @param unknown $msgid Message ID
	 */
	public function share_getoriginal($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		$tid_this=$tid;
		$msgid_this=$msgid;
		do
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_shares::tblname." WHERE ".tbl_message_shares::col_ntid."='$tid_this' AND ".tbl_message_shares::col_nmsgid."='$msgid_this'", tbl_message_shares::dbname);
			if(count($res)!=0)
			{
				$tid_this=$res[0][changesqlquote(tbl_message_shares::col_tid,"")];
				$msgid_this=$res[0][changesqlquote(tbl_message_shares::col_msgid,"")];
			}
		}while(count($res)!=0);
		$arr=Array();
		$arr["tid"]=$tid_this;$arr["msgid"]=$msgid_this;
		return $arr;
	}
	
	/**
	 * 
	 * GET TOTAL NO OF SHARES OF A POST
	 * @param unknown $tid Thread ID
	 * @param unknown $msgid Message ID
	 * @return No. of shares
	 */
	public function share_get_no($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		return count($dbobj->dbquery("SELECT * FROM ".tbl_message_shares::tblname." WHERE ".tbl_message_shares::col_tid."='$tid' AND ".tbl_message_shares::col_msgid."='$msgid'", tbl_message_shares::dbname));
	}
	
	/**
	 * 
	 * GET THE PICTURE FOR A THREAD
	 * @param unknown $tid Thread ID
	 * @return The URL of the picture
	 */
	public function get_threadpic($tid)
	{
		$msgobj=new ta_messageoperations();
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		
		if(!$userobj->checklogin())
			$uid="";
		else
			$userobj->userinit();
			$uid=$userobj->uid;
		$res1=$msgobj->getthreadoutline($tid);
		$res2=$msgobj->get_thread_audience($tid);
		$audno=count($res2);
		if($audno==2&&$res1[0][changesqlquote(tbl_message_outline::col_threadpic,"")]=="")
		{
			for($j=0;$j<$audno;$j++)
			{
				$fuid=$res2[$j][changesqlquote(tbl_collection_users::col_uid,"")];
				if($fuid==$uid)continue;
				$tpicurl=$userobj->getprofpic($fuid);
				break;
			}
		}
		else
		{
			$tpicmedid=$res1[0][changesqlquote(tbl_message_outline::col_threadpic,"")];
			if($tpicmedid=="")
			{
				$tpicurl='/master/securedir/m_images/image-not-found.png';
			}
			else
			{
				$tpicurl=$galobj->geturl_media("",$tpicmedid,"3");
			}
		}
		return $utilityobj->pathtourl($tpicurl);
	}
	
	public function get_item_comthreadid($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_rateid."='$rateid' AND ".tbl_message_outline::col_msgtype."='7' LIMIT 0,1",tbl_message_outline::dbname);
		if(count($res)==0)
		{
			return FAILURE;
		}
		$tid=$dbobj->colval($res,tbl_message_outline::col_tid,0);
		return $tid;
	}

	/**
	 *
	 * SEND MESSAGE TO A USER
	 * @param unknown_type $from The User ID of person Sending the message
	 * @param unknown_type $msg The Message to be sent as text
	 * @param unknown_type $tid The Thread ID of Message (Contains all imp info like participants,outlines,etc.)
	 * @param unknown_type $tags TAG ID from TAG DB of all the people tagged in this message
	 * @param unknown_type $app The APPID of the APP sending the message
	 * @return Ambigous <string, unknown>|string The message ID on success, "" on failure
	 */
	public function sendmsg($from,$msg,$tid,$tags="",$app="0000")
	{
		$socialobj=new ta_socialoperations();
		$uiobj=new ta_uifriend();
		$msgid=$uiobj->randomstring(30,tbl_message_content::dbname,tbl_message_content::tblname,tbl_message_content::col_msgid);
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$rateid=$socialobj->rating_init(5);
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_message_content::tblname." (".tbl_message_content::col_tid.",".tbl_message_content::col_msg.",".tbl_message_content::col_fuid.",".tbl_message_content::col_tagid.",".tbl_message_content::col_msgmode.",".tbl_message_content::col_deststate.",".tbl_message_content::col_msgflag.",".tbl_message_content::col_msgid.",".tbl_message_content::col_replyto.",".tbl_message_content::col_rateid.") VALUES
				('$tid','$msg','$from','$tags','$app','0','1','$msgid','','$rateid')",tbl_message_content::dbname);
		$dbobj->dbupdate("UPDATE ".tbl_message_outline::tblname." SET ".tbl_message_outline::col_lastupdate."=CURRENT_TIMESTAMP() WHERE ".tbl_message_outline::col_tid."='$tid'", tbl_message_outline::dbname);
		$dbobj->dbupdate("UPDATE ".tbl_message_incoming::tblname." SET ".tbl_message_incoming::col_rtime."=CURRENT_TIMESTAMP() WHERE ".tbl_message_incoming::col_tid."='$tid'", tbl_message_incoming::dbname);
		if($res=="SUCCESS")
		{
			return $msgid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000069');
			return FAILURE;
		}
	}

	/**
	 *
	 * SEND REPLY TO A MESSAGE
	 * @param unknown_type $from The User ID of person Sending the reply
	 * @param unknown_type $msg The reply to be sent as text
	 * @param unknown_type $tid The Thread ID of reply (Contains all imp info like participants,outlines,etc.)
	 * @param unknown_type $repmsgid The Message ID of the message to which reply has to be sent
	 * @param unknown_type $app The APPID of the APP sending the message
	 * @param unknown_type $attachmenturlarray An Array containing URL of all attachments to this thread
	 * @param unknown_type $tags TAG ID from TAG DB of all the people tagged in this message
	 * @return Ambigous <string, unknown>|string The message ID on success, "" on failure
	 */
	public function sendreply($from,$msg,$tid,$repmsgid,$app,$attachmenturlarray,$tags)
	{
		$uiobj=new ta_uifriend();
		$msgid=$uiobj->randomstring(30,tbl_message_content::dbname,tbl_message_content::tblname,tbl_message_content::col_msgid);
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$attachid=$msgobj->addattachment($attachmenturlarray, $tid, $msgid, $from);
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_message_content::tblname." (".tbl_message_content::col_tid.",".tbl_message_content::col_msg.",".tbl_message_content::col_fuid.",".tbl_message_content::col_attachid.",".tbl_message_content::col_tagid.",".tbl_message_content::col_msgmode.",".tbl_message_content::col_deststate.",".tbl_message_content::col_msgflag.",".tbl_message_content::col_msgid.",".tbl_message_content::col_replyto.") VALUES
				('$tid','$msg','$from','$attachid','$tags','$app','0','1','$msgid','$repmsgid')",tbl_message_content::dbname);
		if($res=="SUCCESS")
		{
			return $msgid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000117');
			return FAILURE;
		}
	}

	/**
	 *
	 * SEND COMMENT TO AN ELEMENT
	 * @param unknown_type $from The User ID of person Sending the comment
	 * @param unknown_type $comment The comment to be sent as text
	 * @param unknown_type $tid The Thread ID of comment (Contains all imp info like participants,outlines,etc.)
	 * @param unknown_type $app The APPID of the APP sending the comment
	 * @param unknown_type $attachmenturlarray An Array containing URL of all attachments to this thread
	 * @param unknown_type $tags TAG ID from TAG DB of all the people tagged in this comment
	 * @return Ambigous <string, unknown>|string The message ID on success, "" on failure
	 */
	public function sendcomment($from,$comment,$tid,$tags="",$app="0000")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		
		$msgid=$uiobj->randomstring(30,tbl_message_content::dbname,tbl_message_content::tblname,tbl_message_content::col_msgid);
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_message_content::tblname." (".tbl_message_content::col_tid.",".tbl_message_content::col_msg.",".tbl_message_content::col_fuid.",".tbl_message_content::col_col_mediaid.",".tbl_message_content::col_tagid.",".tbl_message_content::col_msgmode.",".tbl_message_content::col_deststate.",".tbl_message_content::col_msgflag.",".tbl_message_content::col_msgid.",".tbl_message_content::col_replyto.") VALUES
				('$tid','$comment','$from','','$tags','$app','0','1','$msgid','-1')",tbl_message_content::dbname);
		if($res==SUCCESS)
		{
			return $msgid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * GET ALL COMMENTS FROM A COMMENT THREAD
	 * @param unknown_type $tid Thread ID of the comment thread
	 * @param unknown_type $s Start Limit - Defaults to 0
	 * @param unknown_type $inc Increment
	 * @return Ambigous <string, unknown>|string A DB Array having all comments on success, FAILURE on failure
	 */
	public function getcomments($tid,$s=0,$inc=2)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_replyto."='-1' ORDER BY ".tbl_message_content::col_msgtime." ASC LIMIT $s,$inc",tbl_message_content::dbname);
		if($res!=FAILURE)
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
	 * GET ALL REPLIES TO A MESSAGE
	 * @param unknown_type $tid Thread ID of the message and its reply
	 * @param unknown_type $msgid Message ID of the message whose replies are to be retrieved
	 * @return Ambigous <string, unknown>|string A DB Array having all replies on success, FAILURE on failure
	 */
	public function getreplies($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_replyto."='$msgid'",tbl_message_content::dbname);
		if($res!=FAILURE)
		{
			return $res;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * RETRIEVE MESSAGE(S) FROM A THREAD
	 * @param unknown_type $tid Thread ID of thread having the message
	 * @param unknown_type $msgtype Type of message to be retreived
	 * @param unknown_type $msgid Message ID of message to be retrieved (Defaults to "" which means all messages in this thread)
	 * @param unknown_type $start Start Limit (Defaults to 0)
	 * @param unknown_type $tot Total Limit (Defaults to 10)
	 * @return Ambigous <string, unknown> The DB Array having all messages
	 */
	public function getmsg($tid,$start="0",$tot="10",$msgid="",$avoidrep="1")
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		
		if($msgid=="")
		{
			$sql="SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid'";
			if($avoidrep!="1")
			{
				$sql.=" AND ".tbl_message_content::col_replyto."=''";
			}
			$sql.=" ORDER BY ".tbl_message_content::col_msgtime." DESC";
			$sql.=" LIMIT $start,$tot";
			$res=$dbobj->dbquery($sql,tbl_message_content::dbname);
			return $res;
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_msgid."='$msgid' LIMIT 0,1",tbl_message_content::dbname);
			return $res;
		}

	}
	
	/**
	 * 
	 * GET NUMBER OF MESSAGES IN A THREAD
	 * @param unknown $tid Thread ID
	 */
	public function get_no_msg($tid)
	{
		$dbobj=new ta_dboperations();
		return count($dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid'", tbl_message_content::dbname));
	}

	/**
	 *
	 * GET A THREAD OUTLINE
	 * @param unknown_type $tid Thread ID of thread whose outline has to be retrieved
	 * @param unknown_type $uid User ID of person who created the thread
	 * @return Ambigous <string, unknown> A DB Array having all thread outlines
	 */
	public function getthreadoutline($tid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_tid."='$tid'",tbl_message_outline::dbname);
		return $res;
	}

	/**
	 *
	 * GET ATTACHMENT(S) OF A THREAD
	 * @param unknown_type $tid Thread ID in which the attachment is present
	 * @param unknown_type $uid User ID of person who created the attachment
	 * @param unknown_type $msgid Message ID in which the attachment belongs (Defaults to "" which means retrieve all attachments in the thread)
	 * @return Ambigous <string, unknown> A DB Array having all attachment info
	 */
	public function getattachment($tid,$msgid="")
	{
		$dbobj=new ta_dboperations();
		if($msgid=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_attachment::tblname." WHERE ".tbl_message_attachment::col_tid."='$tid'",tbl_message_attachment::dbname);
			return $res;
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_attachment::tblname." WHERE ".tbl_message_attachment::col_tid."='$tid' AND ".tbl_message_attachment::col_msgid."='$msgid'",tbl_message_attachment::dbname);
			return $res;
		}
	}

	/**
	 *
	 * DELETE A THREAD AND ALL ITS CONTENTS
	 * @param unknown_type $tid Thread ID of the thread to be deleted
	 * @param unknown_type $uid User ID of person who created the thread
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletethread($tid,$uid)
	{
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$audobj=new ta_audience();
		$socialobj=new ta_socialoperations();
		$userobj=new ta_userinfo();
		
		$tres=$msgobj->getthreadoutline($tid);
		$fuid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
		$msgtype=$tres[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
		$msgsubject=$tres[0][changesqlquote(tbl_message_outline::col_subject,"")];
		
		if($msgtype=="5")
		{
			$gtres=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_tid."='$tid' LIMIT 0,1", tbl_threads_attached::dbname);
			$gpid=$gtres[0][changesqlquote(tbl_threads_attached::col_gpid,"")];
			
			if($msgtype=="5"&&$socialobj->group_user_check_admin($gpid,$uid))
			{
				$uid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
			}
		}
		
		if($fuid!=$uid)
		{
			return $msgobj->thread_leave($uid,$tid);
		}
		
		if($audobj->audience_remove($audid))
		{
			if($msgobj->incomingmsg_delete($uid,$tid,"1")==SUCCESS)
			{
				if($msgobj->deleteattachment($tid, $uid)==SUCCESS)
				{
					if($msgobj->deletemsg($tid,$uid,"")==SUCCESS)
					{
						if($msgobj->deletemsgoutline($tid, $uid)==SUCCESS)
						{
							$userobj->removereppoints($uid,"5",'Deleting thread titled '.$msgsubject);
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
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * LEAVE A THREAD WITHOUT DELETING IT
	 * @param unknown $uid UID of the user who wants to leave thread
	 * @param unknown $tid Thread ID
	 */
	public function thread_leave($uid,$tid)
	{
		$msgobj=new ta_messageoperations();
		$audobj=new ta_audience();
		
		$uidarray=Array($uid);
		$audid=$msgobj->get_thread_audienceid($tid);
		$audobj->audience_remove_users($audid,$uidarray);
		$msgobj->incomingmsg_delete($uid, $tid);
		$msgobj->sendmsg($uid,"I am leaving this conversation. Bye.",$tid);
		return SUCCESS;
	}

	/**
	 *
	 * DELETE A MESSAGE FROM THE THREAD
	 * @param unknown_type $tid Thread ID where the message belongs
	 * @param unknown_type $uid User ID of person who created the message
	 * @param unknown_type $msgid Message ID of message to be deleted. (Defaults to "" which means delete all messages created by this user in this thread)
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletemsg($tid,$uid,$msgid="")
	{
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		
		$tres=$msgobj->getthreadoutline($tid);
		$fuid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		
		if($msgid=="")
		{
			if($fuid!=$uid)
			{
				$msgres=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_fuid."='$uid'", tbl_message_content::dbname);
			}
			else
			{
				$msgres=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid'", tbl_message_content::dbname);
			}
			
			for($i=0;$i<count($msgres);$i++)
			{
				$msgid=$msgres[$i][changesqlquote(tbl_message_content::col_msgid,"")];
				$sql="DELETE FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_msgid."='$msgid'";
				$dbobj->dbdelete($sql,tbl_message_content::dbname);
			}
		}
		else
		{
			$mres=$msgobj->getmsg($tid,"0","1",$msgid);
			$tagid=$mres[0][changesqlquote(tbl_message_content::col_tagid,"")];
			$socialobj->tag_user_remove($tagid,$uid);
			$sql="DELETE FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_fuid."='$uid' AND ".tbl_message_content::col_msgid."='$msgid'";
			$dbobj->dbdelete($sql,tbl_message_content::dbname);
		}
		return SUCCESS;
	}

	/**
	 *
	 * DELETE ATTACHMENT(S) OF A MESSAGE/THREAD
	 * @param unknown_type $tid Thread ID where the attachment belongs
	 * @param unknown_type $uid User ID of person who created the attachment
	 * @param unknown_type $msgid Message ID where the attachment belongs (Defaults to "" which means delete all attachments created by this user in this thread)
	 * @param unknown_type $attachid Attachment ID of attachment to be deleted (Defaults to "" which means delete all attachments in the respective message)
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deleteattachment($tid,$uid,$msgid="",$attachid="")
	{
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$colobj=new ta_collection();
		$galobj=new ta_galleryoperations();
		
		$msgres=$msgobj->getmsg($tid,"","",$msgid);
		
		for($i=0;$i<count($msgres);$i++)
		{
			$col_medid=$msgres[$i][changesqlquote(tbl_message_content::col_col_mediaid,"")];
			$fuid=$msgres[$i][changesqlquote(tbl_message_content::col_fuid,"")];
			$mid=$msgres[$i][changesqlquote(tbl_message_content::col_msgid,"")];
			$mtid=$msgres[$i][changesqlquote(tbl_message_content::col_tid,"")];
			if($col_medid==""||$fuid!=$uid)continue;
			$colres=$colobj->get_collection_complete_info(tbl_collection_media::tblname, tbl_collection_media::col_col_mediaid,$col_medid);

			if($attachid=="")
			{
				for($j=0;$j<count($colres);$j++)
				{
					$mediaid=$colres[$j][changesqlquote(tbl_collection_media::col_mediaid,"")];
					$galobj->deletemedia($mediaid);
					$colobj->remove_collection_item(tbl_collection_media::tblname,tbl_collection_media::col_col_mediaid,$col_medid,tbl_collection_media::col_mediaid,$mediaid);
				}
			}
			else
			{
				for($j=0;$j<count($colres);$j++)
				{
					$mediaid=$colres[$j][changesqlquote(tbl_collection_media::col_mediaid,"")];
					if($attachid!=$mediaid)continue;
					$galobj->deletemedia($mediaid);
					$colobj->remove_collection_item(tbl_collection_media::tblname,tbl_collection_media::col_col_mediaid,$col_medid,tbl_collection_media::col_mediaid,$mediaid);
				}
			}
			if($msgid=="")
			{
				$msgobj->editmsg($tid, $mid, tbl_message_content::col_col_mediaid, "");
			}
		}
		
		return SUCCESS;
		
	}

	/**
	 *
	 * DELETE A MESSAGE OUTLINE
	 * @param unknown_type $tid Thread ID
	 * @param unknown_type $uid User ID of person who created the thread
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletemsgoutline($tid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_tid."='$tid' AND ".tbl_message_outline::col_fid."='$uid'",tbl_message_outline::dbname)=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000073');
			return FAILURE;
		}
	}

	/**
	 *
	 * EDIT A MESSAGE (NOT OUTLINE)
	 * @param unknown_type $tid Thread ID of the thread where the message belongs
	 * @param unknown_type $msgid Message ID of the message to be edited
	 * @param unknown_type $colname Name of column to be edited
	 * @param unknown_type $value Value to be inserted in the respective column
	 * @return string SUCCESS on successful edit, "" on failure
	 */

	public function editmsg($tid,$msgid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
			if($dbobj->dbupdate("UPDATE ".tbl_message_content::tblname." SET ".$colname."='$value' WHERE ".tbl_message_content::col_tid."='$tid' AND ".tbl_message_content::col_msgid."='$msgid'",tbl_message_content::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				return FAILURE;
			}
	}

	//TODO EDIT MESSAGE OUTLINE

	/**
	 * ADD AN ATTACHMENT TO A MESSAGE
	 * @param unknown_type $colmedid Collection Media ID
	 * @param unknown_type $tid Thread ID
	 * @param unknown_type $msgid Message ID
	 * @return string SUCCESS on successful add of attachment, "" on failure
	 */
	public function addattachment($colmedid,$tid,$msgid)
	{
		$msgobj=new ta_messageoperations();
		return $msgobj->editmsg($tid,$msgid,tbl_message_content::col_col_mediaid,$colmedid);
	}

	/**
	 *
	 * ADD A TAG
	 * @param unknown_type $tid Thread ID of the thread where the message belongs
	 * @param unknown_type $msgid Message ID of the message where tags have to be added
	 * @param unknown_type $tagarray An array having all tags (tagname,tagid)
	 * @param unknown_type $uid User ID of person tagging the others
	 * @param unknown_type $tagtype Type of TAG (1-post,2-comment,3-chat,4-gallery,5-media,6-message) (Defaults to 6)
	 * @return string SUCCESS on successful tag, "" on failure
	 */
	public function tagmsg($tid,$msgid,$tagarray,$uid,$tagtype="6")
	{
		$socialobj=new ta_socialoperations();
		$tagid=$socialobj->addtag($tagarray, $uid,$tagtype);
		if($tagid!="")
		{
			$messageobj=new ta_messageoperations();
			if($messageobj->editmsg($tid,$msgid,$uid,"3",$tagid)=="SUCCESS")
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
			throw new Exception('#ta@0000000_0000079');
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE A TAG FROM A MESSAGE
	 * @param unknown_type $tagid TAGID of the TAG to be removed
	 * @param unknown_type $tid Thread ID of the thread where the TAG belongs
	 * @param unknown_type $msgid Message ID of message where the TAG belongs
	 * @param unknown_type $fuid User ID of person who added the TAG
	 * @param unknown_type $uid User ID of person who created the message
	 * @param unknown_type $tuid User ID of person whose TAG is to be removed (Defaults to "" which means all tags added by the From User is removed)
	 * @return string SUCCESS when tag is removed successfully, "" on failure
	 */
	public function deletemsgtag($tagid,$tid,$msgid,$fuid,$uid,$tuid="")
	{
		$socialobj=new ta_socialoperations();
		$res=$socialobj->removetag($tagid, $fuid,$tuid);
		if($tuid=="")
		{
			$messageobj=new ta_messageoperations();
			if($messageobj->editmsg($tid,$msgid,$uid,"3","")=="SUCCESS")
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000080');
				return FAILURE;
			}
		}
		else
		{
			if($res=="SUCCESS")
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000081');
				return FAILURE;
			}
		}
	}

	/**
	 *
	 * UPLOAD AN ATTACHMENT WHICH IS LATER ADDED TO THREADS/MESSAGES
	 * @param unknown_type $fileobj The file object $_FILE which contains file contents
	 * @param unknown_type $tid Thread ID where the attachment has to be uploaded
	 * @param unknown_type $msgid Message ID of message which holds the attachment
	 * @param unknown_type $uid User ID of person who uploaded the attachment
	 * @param unknown_type $path Path where the attachment is to be uploaded (Defaults to "" which means it will choose its own appropriate path)
	 * @return string The path where the attachment is uploaded, "" on failure
	 */
	public function uploadattachment($fileobj,$tid,$msgid,$uid,$path="")
	{
		$filetypearray=Array("txt","doc","docx","pdf","xls","xlsx","ppt","jpg","png","bmp","gif","mp3");
		$filesobj=new ta_fileoperations();
		$attachpath=$filesobj->fileupload($fileobj,$filetypearray,"attachments",31460000,$path);
		if($attachpath!="")
		{
			return $attachpath;
		}
		else
		{
			throw new Exception('#ta@0000000_0000082');
			return FAILURE;
		}
	}

	/**
	 *
	 * ASSIGN READ STATUS TO A MESSAGE FOR A USER
	 * @param unknown_type $uid User ID for whom read status is assigned
	 * @param unknown_type $tid Thread ID of the thread where the message belongs
	 * @param unknown_type $msgid Message ID of message for which read status is to be assigned
	 * @param unknown_type $status Status Flag (1-read,2-unread,3-ignored) (Defaults to 1)
	 * @return string SUCCESS on successful assign, "" on failure
	 */
	public function assignreadstatus($uid,$tid,$msgid,$status="1")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_readstatus::tblname." WHERE ".tbl_message_readstatus::col_uid."='$uid' AND ".tbl_message_readstatus::col_threadid."='$tid' AND ".tbl_message_readstatus::col_msgid."='$msgid'",tbl_message_readstatus::dbname);
		if($res==EMPTY_RESULT)
		{
			if($dbobj->dbinsert("INSERT INTO ".tbl_message_readstatus::tblname." (".tbl_message_readstatus::col_threadid.",".tbl_message_readstatus::col_uid.",".tbl_message_readstatus::col_msgid.",".tbl_message_readstatus::col_readstatus.") VALUES
					('$tid','$uid','$msgid','$status')",tbl_message_readstatus::dbname)=="SUCCESS")
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
			if($dbobj->dbupdate("UPDATE ".tbl_message_readstatus::tblname." SET ".tbl_message_readstatus::col_readstatus."='$status' WHERE ".tbl_message_readstatus::col_msgid."='$msgid' AND ".tbl_message_readstatus::col_threadid."='$tid' AND ".tbl_message_readstatus::col_uid."='$uid'",tbl_message_readstatus::dbname)=="SUCCESS")
			{
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
	 * REMOVE READ STATUS OF A USER FOR A MESSAGE
	 * @param unknown_type $uid The User ID of the person for whom the read status has to be removed
	 * @param unknown_type $tid The Thread ID of the thread which has the message.
	 * @param unknown_type $msgid The Message ID of the message whose read status is to be removed
	 * @return string SUCCESS on successful removal, "" on failure
	 */
	public function removereadstatus($uid,$tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_message_readstatus::tblname." WHERE ".tbl_message_readstatus::col_threadid."='$tid' AND ".tbl_message_readstatus::col_msgid."='$msgid' AND ".tbl_message_readstatus::col_uid."='$uid'",tbl_message_readstatus::dbname)=="SUCCESS")
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
	 * GET THE READ STATUS OF A MESSAGE
	 * @param unknown $uid User ID of the person whose readstatus is checked
	 * @param unknown $tid The thread ID of the thread
	 * @param unknown $msgid The message ID of the message
	 * @return unknown The read status
	 */
	public function get_readstatus($uid,$tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_readstatus::tblname." WHERE ".tbl_message_readstatus::col_uid."='$uid' AND ".tbl_message_readstatus::col_threadid."='$tid' AND ".tbl_message_readstatus::col_msgid."='$msgid'",tbl_message_readstatus::dbname);
		$readstatus=$dbobj->colval($res,tbl_message_readstatus::col_readstatus,0);
		return $readstatus;
	}
	
	/**
	 * 
	 * GET TOTAL NO OF PEOPLE WHO READ A MESSAGE
	 * @param unknown $tid Thread ID
	 * @param unknown $msgid Message ID
	 * @return The no. of reads
	 */
	public function get_total_views($tid,$msgid)
	{
		$dbobj=new ta_dboperations();
		return count($dbobj->dbquery("SELECT * FROM ".tbl_message_readstatus::tblname." WHERE ".tbl_message_readstatus::col_threadid."='$tid' AND ".tbl_message_readstatus::col_msgid."='$msgid' AND ".tbl_message_readstatus::col_readstatus."='1'", tbl_message_readstatus::dbname));
	}

	/**
	 *
	 * GET THE NUMBER OF UNREAD MESSAGES BY A USER
	 * @param unknown $uid The User ID of the person
	 * @param unknown $tid The Thread ID (Defaults to "" which returns all unread msgs)
	 * @return number The number of unread messages
	 */
	public function get_no_unread_messages($uid,$tid="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_message_readstatus::tblname." WHERE ".tbl_message_readstatus::col_uid."='$uid' AND ".tbl_message_readstatus::col_readstatus."='2'";
		if($tid!="")
		{
			$sql.=" AND ".tbl_message_readstatus::col_threadid."='$tid'";
		}
		$res=$dbobj->dbquery($sql,tbl_message_readstatus::dbname);
		return count($res);
	}

	/**
	 *
	 * GET USER's THREADS
	 * @param unknown_type $uid User ID of person who created/received the thread(s)
	 * @param unknown_type $start The start value of the thread(Defaults to "" which means all thread outlines are returned)
	 * @param unknown_type $tot The total value of the thread(Defaults to "" which means all thread outlines are returned)
	 * @return string|Ambigous <string, unknown> A DB Array having all thread outlines, "" on failure
	 */
	public function getuserthreadoutline($uid,$start="0",$tot="15",$mtype="")
	{
		$dbobj=new ta_dboperations();
		
		if($mtype=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_ruid."='$uid' ORDER BY ".tbl_message_incoming::col_rtime." DESC LIMIT $start,$tot", tbl_message_incoming::dbname);
		}
		else
		if($mtype=="1")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_incoming::tblname." AS a,".tbl_message_outline::tblname." AS b WHERE a.".tbl_message_incoming::col_ruid."='$uid' AND (b.".tbl_message_outline::col_msgtype."='1' OR b.".tbl_message_outline::col_msgtype."='2') AND a.".tbl_message_incoming::col_tid."=b.".tbl_message_outline::col_tid." ORDER BY a.".tbl_message_incoming::col_rtime." DESC LIMIT $start,$tot", tbl_message_incoming::dbname);
		}
		
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
	 * DELETE AN INCOMING thread WITHOUT DELETE THE CONTENTS 
	 * @param unknown $uid UID who receives the thread
	 * @param unknown $tid Thread ID
	 */
	public function incomingmsg_delete($uid,$tid,$flag="")
	{
		$dbobj=new ta_dboperations();
		if($flag!="")
		{
			return $dbobj->dbdelete("DELETE FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_tid."='$tid'", tbl_message_incoming::dbname);
		}
		else
		{
			return $dbobj->dbdelete("DELETE FROM ".tbl_message_incoming::tblname." WHERE ".tbl_message_incoming::col_tid."='$tid' AND ".tbl_message_incoming::col_ruid."='$uid'", tbl_message_incoming::dbname);
		}
	}

	/**
	 *
	 * GET THREAD CONTENTS OF A USER
	 * @param unknown_type $uid User ID of person who created the message
	 * @param unknown_type $start The start value of the contents (LIMIT) (Defaults to "" which means all contents are retrieved)
	 * @param unknown_type $end The end value of the contents (Defaults to "" which means all contents are retrieved)
	 * @return string|Ambigous <string, unknown> A DB Array having all thread contents on success, "" on failure
	 */
	public function getuserthreadcontents($uid,$start="",$end="")
	{
		$dbobj=new ta_dboperations();
			if($start==""&&$end=="")
			{
				$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_fuid."='$uid'",tbl_message_content::dbname);
			}
			else
			{
				$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_fuid."='$uid' LIMIT $start,$end",tbl_message_content::dbname);
			}
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
	 * GET ALL LISTS OF A USER
	 * @param unknown_type $uid User ID of person whose lists are to be retrieved
	 * @return Ambigous <string, unknown>|string A DB Array having all lists (not outlines), "" on failure
	 */
	public function getuserlists($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listsdb::tblname." WHERE ".tbl_listinfo::col_uid."='$uid'",tbl_listsdb::dbname);
		if($res!=EMPTY_RESULT)
		{
			return $res;
		}
		else
		{
			return FAILURE;
		}
	}

	public function get_rateid_conversations($msgid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_content::tblname." WHERE ".tbl_message_content::col_msgid."='$msgid'",tbl_message_content::dbname);
		if($res==FAILURE||$res==EMPTY_RESULT)
		{
			return FAILURE;
		}
		$rateid=$dbobj->colval($res,tbl_message_content::col_rateid,0);
		return $rateid;
	}

	public function conversations_rate_init($uid,$convid)
	{
		$socialobj=new ta_socialoperations();
		$rateid=$socialobj->rating_init(5);
		$dbobj=new ta_dboperations();
		$dbobj->dbinsert("INSERT INTO ".tbl_message_outline::tblname." (".tbl_message_outline::col_rateid.") VALUES ('$rateid')",tbl_message_outline::dbname);
		return $rateid;
	}

	public function conversations_rate_up($uid,$convid)
	{
		$messageobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		$rateid=$messageobj->get_rateid_conversations($convid);
		return $socialobj->rating_up($uid,$rateid,2);
	}

	public function conversations_rate_down($uid,$convid)
	{
		$messageobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		$rateid=$messageobj->get_rateid_conversations($convid);
		return $socialobj->rating_down($uid,$rateid,2);
	}

	public function user_data_init($uid)
	{
		$dbobj=new ta_dboperations();
		$dbobj->dbinsert("INSERT INTO ".tbl_p_sb_user::tblname." (".tbl_p_sb_user::col_uid.",".tbl_p_sb_user::col_lastdigestsent.",".tbl_p_sb_user::col_digestfrequency.",".tbl_p_sb_user::col_col_digest_subid.",".tbl_p_sb_user::col_col_catid.")
					VALUES ('$uid','','2','','')",tbl_p_sb_user::dbname);
	}

	public function digests_add_subscription($uid,$subidarray)
	{
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user::tblname." WHERE ".tbl_p_sb_user::col_uid."='$uid'",tbl_p_sb_user::dbname);
		$col_subid=$dbobj->colval($res,tbl_p_sb_user::col_col_digest_subid,0);

		for($i=0;$i<count($subidarray);$i++)
		{
			$colarray[$i][tbl_collection_p_sb_subscriptions::col_subid]=$subidarray[$i];
		}

		$col_subid_final=$collectionobj->add_collection(tbl_collection_p_sb_subscriptions::tblname,$colarray,tbl_collection_p_sb_subscriptions::col_col_subid,$col_subid);
	}

	public function digests_remove_subscription($uid,$subid)
	{
		$dbobj=new ta_dboperations();
		$collectionobj=new ta_collection();

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_p_sb_user::tblname." WHERE ".tbl_p_sb_user::col_uid."='$uid'",tbl_p_sb_user::dbname);
		$col_subid=$dbobj->colval($res,tbl_p_sb_user::col_col_digest_subid,0);
		return $collectionobj->remove_collection_item(tbl_collection_p_sb_subscriptions::tblname,tbl_collection_p_sb_subscriptions::col_col_subid,$col_subid,tbl_collection_p_sb_subscriptions::col_subid,$subid);
	}

	public function digests_edit_frequency($uid,$newfrequency)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_user::tblname." SET ".tbl_p_sb_user::col_digestfrequency."='$newfrequency' WHERE ".tbl_p_sb_user::col_uid."='$uid'",tbl_p_sb_user::dbname);
	}

	public function digests_edit_lastsent($uid,$newlastsent)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_p_sb_user::tblname." SET ".tbl_p_sb_user::col_lastdigestsent."='$newlastsent' WHERE ".tbl_p_sb_user::col_uid."='$uid'",tbl_p_sb_user::dbname);
	}
	
	/**
	 * 
	 * GET RECENT POST OF A USER
	 * @param unknown $uid UID of the user to check
	 * @param string $level The depth (0-Latest (default))
	 * @return A DB Array of results
	 */
	public function get_recentpost($uid,$level="0")
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		
		$sql="SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_fid."='$uid' AND ".tbl_message_outline::col_msgtype."='4' ORDER BY ".tbl_message_outline::col_lastupdate." DESC LIMIT $level,1";
		return $dbobj->dbquery($sql, tbl_message_outline::dbname);
	}
	
	/**
	 * 
	 * ADD A NEW TAG FOR A POST
	 * @param unknown $tagname Name of tag
	 * @param unknown $uid UID of person adding the tag
	 * @param string $tagdesc Tag description
	 * @param string $tagpic URL of picture to the tag
	 */
	public function tagpost_add($tagname,$uid,$tagdesc="",$tagpic="")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();

		$tagid=$uiobj->randomstring(30,tbl_tags_post::dbname,tbl_tags_post::tblname,tbl_tags_post::col_tagid);
		if($dbobj->dbinsert("INSERT INTO ".tbl_tags_post::tblname." (".tbl_tags_post::col_tagid.",".tbl_tags_post::col_tagname.",".tbl_tags_post::col_usrid.",".tbl_tags_post::col_details.",".tbl_tags_post::col_tagpic.") VALUES ('$tagid','$tagname','$uid','$tagdesc','$tagpic')", tbl_tags_post::dbname)==SUCCESS)
		{
			return $tagid;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * GET TAGS FROM TAGPOST DB
	 * @param string $uid User ID (Defaults to "" which means retrieve all default tags)
	 * @param string $tagid Tag ID (Defaults to "")
	 * @return Return DBArray result
	 */
	public function tagpost_get($uid="",$tagid="")
	{
		$dbobj=new ta_dboperations();
		
		if($tagid!="")
		{
			$sql="SELECT * FROM ".tbl_tags_post::tblname." WHERE ".tbl_tags_post::col_tagid."='$tagid'";
		}
		else
		{
			$sql="SELECT * FROM ".tbl_tags_post::tblname." WHERE ".tbl_tags_post::col_usrid."='$uid'";
		}
		
		return $dbobj->dbquery($sql, tbl_tags_post::dbname);
	}
	
	/**
	 * 
	 * DELETE A TAG FROM POST
	 * @param unknown $tagid TAGID of tag to be deleted
	 */
	public function tagpost_delete($tagid)
	{
		$dbobj=new ta_dboperations();
		
		return $dbobj->dbdelete("DELETE FROM ".tbl_tags_post::tblname." WHERE ".tbl_tags_post::col_tagid."='$tagid'", tbl_tags_post::dbname);
	}
	
	/**
	 * 
	 * EDIT A TAG POST COLUMN
	 * @param unknown $tagid Tagid of tag to be edited 
	 * @param unknown $colname Name of column
	 * @param unknown $value Value to be updated
	 */
	public function tagpost_edit($tagid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		
		return $dbobj->dbupdate("UPDATE ".tbl_tags_post::tblname." SET ".$colname."='$value' WHERE ".tbl_tags_post::col_tagid."='$tagid'", tbl_tags_post::dbname);
	}
	
	/**
	 * 
	 * ADD a tag to a collection
	 * @param unknown $tagidarr TAGID Array
	 * @param string $colid Collection ID (Defaults to "" to generate new collection ID)
	 */
	public function tagpost_addcol($tagidarr,$colid="")
	{
		$colobj=new ta_collection();
		
		$tagcolarr=Array();
		for($i=0;$i<count($tagidarr);$i++)
		{
			$tagcolarr[$i][tbl_collection_tagpost::col_tagid]=$tagidarr[$i];
		}
		
		return $colobj->add_collection(tbl_collection_tagpost::tblname, $tagcolarr, tbl_collection_tagpost::col_col_tagid,$colid);
	}
	
	/**
	 * 
	 * GET TAG COLLECTION
	 * @param unknown $colid Collection ID from tagpost DB
	 * @return DBArray of collection
	 */
	public function tagpost_getcol($colid)
	{
		$colobj=new ta_collection();
		
		return $colobj->get_collection_complete_info(tbl_collection_tagpost::tblname, tbl_collection_tagpost::col_col_tagid, $colid);
	}
	
	/**
	 * 
	 * REMOVE A TAG FROM A COLLECTION
	 * @param unknown $tagid TAGID of the tag to be removed from collection
	 * @param unknown $colid Collection ID from tagpost collection DB
	 */
	public function tagpost_remcol($tagid,$colid)
	{
		$colobj=new ta_collection();
		
		return $colobj->remove_collection_item(tbl_collection_tagpost::tblname, tbl_collection_tagpost::col_col_tagid, $colid, tbl_collection_tagpost::col_tagid, $tagid);
	}
	
	/**
	 * 
	 * LINK A POST AND A TAG COLLECTION
	 * @param unknown $tid Thread ID
	 * @param unknown $colid Collection ID
	 * @return string/object Returns DBINSERT result
	 */
	public function tagpost_linkposttag($tid,$colid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_message_tags::tblname." (".tbl_message_tags::col_tid.",".tbl_message_tags::col_col_ptagid.") VALUES ('$tid','$colid')", tbl_message_tags::dbname);
	}
	
	public function tagpost_removelink($tid)
	{
		$dbobj=new ta_dboperations();
		
		return $dbobj->dbdelete("DELETE FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_tid."='$tid'", tbl_message_tags::dbname);
	}
	
	public function tagpost_getlinks($tid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_tid."='$tid'", tbl_message_tags::dbname);
	}
	
	/**
	 * 
	 * GET FEATURED THREADS
	 * @param string $start Start Limit
	 * @param string $tot Total Limit
	 */
	public function get_threads_featured($start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_message_featured::tblname." WHERE ".tbl_message_featured::col_flag."='1' ORDER BY ".tbl_message_featured::col_flvl." DESC,".tbl_message_featured::col_addtime." DESC LIMIT $start,$tot", tbl_message_featured::dbname);
	}
}

?>