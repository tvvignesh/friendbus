<?php
/**
 *
 * CONTAINS ALL SOCIAL OPERATION FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_socialoperations
{
	/**
	 *
	 * CREATE A NEW LIST
	 * @param unknown_type $listname List Name
	 * @param unknown_type $listtype Type of List Flag (1-friend list,2-message list,3-share list,4-link list)
	 * @param unknown_type $listdesc List Description
	 * @param unknown_type $listflag List Flag Value for Permissions (1-allowed 2-under review 3-blocked 4-autocreated)
	 * @param unknown_type $listpic List Picture
	 * @param unknown_type $listprivacy List Privacy Flag Value (1-no one,2-public,others (from list db))
	 * @return Ambigous <string, unknown>|string The List ID on successful creation, FAILURE on failure
	 */
	public function createlist($listname,$uid,$listtype,$listdesc,$listflag,$listpic="",$listprivacy="")
	{
		$uiobj=new ta_uifriend();
		$galobj=new ta_fileoperations();
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		
		$listid=$uiobj->randomstring(50,tbl_listinfo::dbname,tbl_listinfo::tblname,tbl_listinfo::col_listid);
		if($listtype=="2")
		{
			$listprivacy=$listid;
		}
		
		//$fpath=$galobj->picupload($listpic,"contbook_thumb");
		$fpath="";
		
		$logobj->store_templogs($fpath);
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_listinfo::tblname." (".tbl_listinfo::col_listid.",".tbl_listinfo::col_listname.",".tbl_listinfo::col_listdesc.",".tbl_listinfo::col_listpic.",".tbl_listinfo::col_listflag.",".tbl_listinfo::col_listprivacy.",".tbl_listinfo::col_listtype.",".tbl_listinfo::col_listuid.",".tbl_listinfo::col_parlistid.")
			VALUES ('$listid','$listname','$listdesc','$fpath','$listflag','$listprivacy','$listtype','$uid','')",tbl_listinfo::dbname);
		if($res==SUCCESS)
		{
			return $listid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000088');
			return FAILURE;
		}
	}

	/**
	 * 
	 * ADD A PARENT TO A LIST
	 * @param unknown $listid List ID of the List for which parent has to be added
	 * @param unknown $parlistid List ID of the parent to this list
	 * @return string/object SUCCESS on success, Object if error
	 */
	public function addparent_list($listid,$parlistid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_listinfo::tblname." SET ".tbl_listinfo::col_parlistid."='$parlistid' WHERE ".tbl_listinfo::col_listid."='$listid'", tbl_listinfo::dbname);
	}
	
	/**
	 * 
	 * GET INFORMATION REGARDING A LIST
	 * @param unknown $listid List ID of the list
	 * @return Ambigous <string, unknown> Returns EMPTY RESULT if empty else array with key value as in db
	 */
	public function get_listinfo($listid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listid."='$listid' LIMIT 0,1", tbl_listinfo::dbname);
		if($res==EMPTY_RESULT)return EMPTY_RESULT;
		return $res[0];
	}
	
	/**
	 * 
	 * ADD AN ITEM TO A LIST
	 * @param unknown $listid List ID of list where item is to be added
	 * @param unknown $uid User ID of person adding the item
	 * @param unknown $itemid Item ID
	 * @param unknown $itemtype Item Type
	 * @param unknown $itemurl URL of item
	 */
	public function list_add_item($listid,$uid,$itemid,$itemtype,$itemurl,$jsondata="")
	{
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		
		$jsonid="";
		if($jsondata!="")
		{
			$utilityobj->jsondata_add($jsondata);
		}
		
		return $dbobj->dbinsert("INSERT INTO ".tbl_listsdb::tblname." (".tbl_listsdb::col_listid.",".tbl_listsdb::col_itemid.",".tbl_listsdb::col_itemtype.",".tbl_listsdb::col_itemurl.",".tbl_listsdb::col_uid.") VALUES 
				('$listid','$itemid','$itemtype','$itemurl','$uid')", tbl_listsdb::dbname);
	}
	
	/**
	 * 
	 * GET LIST INFO OF THE LISTS WHICH BELONGS TO THE USER AND IS OF A SPECIFIC TYPE
	 * @param unknown $uid UID of the user
	 * @param unknown $listtype Type of the list (1-Friends)
	 * @return string|Ambigous <> Returns DBARRAY result
	 */
	public function get_list_user($uid,$listtype,$start="0",$tot="15")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listuid."='$uid' AND ".tbl_listinfo::col_listtype."='$listtype' ORDER BY ".tbl_listinfo::col_listtime." DESC LIMIT $start,$tot", tbl_listinfo::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET MUTUAL FRIENDS OF 2 USERS
	 * @param unknown $uid1 UID of person 1
	 * @param unknown $uid2 UID of person 2
	 * @return Ambigous <string, unknown> Returns DBARRAY of UID of mutual Friends
	 */
	public function get_mutualfriends($uid1,$uid2)
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_frienddb::tblname." AS `A`, ".tbl_frienddb::tblname." AS `B` WHERE `A`.".tbl_frienddb::col_tuid." = '$uid1' AND `A`.".tbl_frienddb::col_fuid." = `B`.".tbl_frienddb::col_fuid." AND `B`.".tbl_frienddb::col_tuid." = '$uid2'";
		$res=$dbobj->dbquery($sql, tbl_frienddb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET ALL FRIEND REQUESTS RECEIVED BY A USER
	 * @param unknown $uid UID of the user
	 * @return multitype:Ambigous <> Retuns a DBARRAY from frienddb
	 */
	public function get_friend_requests($uid)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		
		$reqarr=Array();$j=0;
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_tuid."='$uid'", tbl_frienddb::dbname);
		
		for($i=0;$i<count($res);$i++)
		{
			$fid=$res[$i][changesqlquote(tbl_frienddb::col_fuid,"")];
			if(!$socialobj->friend_check($uid,$fid))
			$reqarr[$j++]=$res[$i];
		}
		return $reqarr;
	}
	
	/**
	 * 
	 * GET ALL FRIEND REQUESTS SENT BY A USER BUT NOT APPROVED YET
	 * @param unknown $uid UID of the user
	 * @return multitype:Ambigous <> Returns DBARRAY from frienddb
	 */
	public function get_friend_sentrequest($uid)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		
		$reqarr=Array();$j=0;
		$res=$socialobj->getfriends($uid);
		
		for($i=0;$i<count($res);$i++)
		{
			$fid=$res[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			if(!$socialobj->friend_check_mutual($uid,$fid))
			$reqarr[$j++]=$res[$i];
		}
		return $reqarr;
	}

	/**
	 *
	 * ADD A PERSON TO THE LIST
	 * @param unknown_type $listarray Array containing item id of all items to be added to list
	 * @param unknown_type $listid List ID of the list where person has to be added
	 * @param unknown_type $uid User ID of person who is adding to the list
	 * @return string SUCCESS on successful add, FAILURE on failure
	 */
	public function addtolist($listarray,$listid,$uid)
	{
		$dbobj=new ta_dboperations();
		$erc=0;$errmsg="";

		if(is_array($listarray))
		{
			foreach ($listarray as $elem)
			{
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_listsdb::tblname." (".tbl_listsdb::col_uid.",".tbl_listsdb::col_itemid.",".tbl_listsdb::col_listid.") VALUES ('$uid','$elem','$listid')",tbl_listsdb::dbname);
				if($res!=SUCCESS)
				{
					$errmsg.="An error occured in adding item to list! ".$res;
					$erc++;
				}
			}
		}
		else
		{
			$res=$dbobj->dbinsert("INSERT INTO ".tbl_listsdb::tblname." (".tbl_listsdb::col_uid.",".tbl_listsdb::col_itemid.",".tbl_listsdb::col_listid.") VALUES ('$uid','$elem','$listid')",tbl_listsdb::dbname);
			if($res!=SUCCESS)
			{
				$errmsg.="An error occured in adding item to list! ".$res;
				$erc++;
			}
		}

		if($erc!=0)
		{
			throw new Exception('#ta@0000000_0000089');
			return FAILURE;
		}
		else
		{
			return SUCCESS;
		}
	}
	
	/**
	 * 
	 * CHECK IF A USER EXISTS IN A LIST OR NOT
	 * @param unknown $uid UID of the user
	 * @param unknown $fuid UID of the friend
	 * @param unknown $listid List ID to check against
	 * @return string BOOL_SUCCESS if exists, BOOL_FAILURE if not
	 */
	public function list_check_user($uid,$fuid,$listid)
	{
		$dbobj=new ta_dboperations();
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_uid."='$uid' AND ".tbl_listsdb::col_listid."='$listid' AND ".tbl_listsdb::col_itemid."='$fuid'", tbl_listsdb::dbname);
		
		if(count($res)==0)
			return BOOL_FAILURE;
		else
			return BOOL_SUCCESS;
	}	
	
	/**
	 * 
	 * TOGGLE USER FROM A LIST
	 * @param unknown $uid UID of the user who owns the list
	 * @param unknown $fuid UID of the friend
	 * @param unknown $listid List ID to be toggled
	 * @return string|string/object SUCCESS on success, FAILURE on failure
	 */
	public function list_user_toggle($uid,$fuid,$listid)
	{
		$socialobj=new ta_socialoperations();
		$dbobj=new ta_dboperations();
		
		if($socialobj->get_listinfo($listid)==EMPTY_RESULT)return FAILURE;
		if($socialobj->list_check_user($uid,$fuid,$listid))
		{
			$res=$socialobj->removefromlist($listid,$fuid,$uid);
		}
		else
		{
			$listarray=Array($fuid);
			$res=$socialobj->addtolist($listarray,$listid,$uid);
		}
		
		return $res;
	}

	/**
	 * 
	 * GET LIST CONTENTS
	 * @param unknown $listid List ID of the LIST
	 * @param unknown $uid (Optional) UID of the user to check
	 * @return Ambigous <string, unknown> Returns a DBARRAY of contents
	 */
	public function get_listcontents($listid,$uid="",$start="0",$tot="15")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_listid."='$listid'";
		if($uid!="")
		{
			$sql.=" AND ".tbl_listsdb::col_uid."='$uid'";
		}
		$sql.=" LIMIT $start,$tot";
		$res=$dbobj->dbquery($sql, tbl_listsdb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * REMOVE AN ELEMENT FROM A LIST WITH ITS ITEMID
	 * @param unknown $listid List ID of the list from where the item has to be removed
	 * @param unknown $itemid Item ID of the item to be removed
	 * @param unknown $uid User ID of the person who created the list
	 * @return string/object SUCCESS on successful deletion
	 */
	public function removefromlist($listid,$itemid,$uid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_listid."='$listid' AND ".tbl_listsdb::col_itemid."='$itemid' AND ".tbl_listsdb::col_uid."='$uid'",tbl_listsdb::dbname);
	}
	
	/**
	 * 
	 * GET THE CHILDREN OF A LIST
	 * @param unknown $parlistid List ID of the parent whose children are to be fetched
	 * @return Ambigous <string, unknown> The result dbarray with children lists and their info
	 */
	public function get_list_children($parlistid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_parlistid."='$parlistid'",tbl_listinfo::dbname);
		return $res;
	}
	
	/**
	 *
	 * GET THE PARENT OF A LIST
	 * @param unknown $listid List ID of the list whose parent is to be fetched
	 * @return Ambigous <string, unknown> List ID or EMPTY_RESULT on success
	 */
	public function get_list_parent($listid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT ".tbl_listinfo::col_parlistid." FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listid."='$listid'",tbl_listinfo::dbname);
		if($res!=EMPTY_RESULT)
		{
			return $res[0][changesqlquote(tbl_listinfo::col_parlistid,"")];
		}
		else
		{
			return $res;
		}
	}
	
	/**
	 *
	 * GET THE NUMBER OF LISTS CREATED BY A USER
	 * @param unknown $uid The UID of the person whose lists are checked
	 * @param unknown $ltype (Optional) List type to check against
	 * @return number The number of lists created by the user
	 */
	public function get_no_lists_user($uid,$ltype="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listuid."='$uid'";
		if($ltype!="")
		{
			$sql.="AND ".tbl_listinfo::col_listtype."='$ltype'";
		}
		$res=$dbobj->dbquery($sql,tbl_listinfo::dbname);
		return count($res);
	}
	
	/**
	 * 
	 * GET INFO REGARDING ALL LISTS CREATED BY A USER
	 * @param unknown $uid UID of the user
	 * @param string $ltype (Optional) Get only specific type of list
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function get_list_info_all_user($uid,$ltype="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listuid."='$uid'";
		if($ltype!="")
		{
			$sql.="AND ".tbl_listinfo::col_listtype."='$ltype'";
		}
		$res=$dbobj->dbquery($sql,tbl_listinfo::dbname);
		return $res;
	}

	//TODO UPDATE LIST

	/**
	 *
	 * DELETE A LIST
	 * @param unknown_type $listid LIST ID of list to be deleted
	 * @param unknown_type $uid User ID of person who created the list
	 * @return string SUCCESS on success, FAILURE on FAILURE
	 */
	public function deletelist($listid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbdelete("DELETE FROM ".tbl_listinfo::tblname." WHERE ".tbl_listinfo::col_listid."='$listid'",tbl_listinfo::dbname);
		if($res==SUCCESS)
		{
			$res1=$dbobj->dbdelete("DELETE FROM ".tbl_listsdb::tblname." WHERE ".tbl_listinfo::col_listid."='$listid'",tbl_listinfo::dbname);
			if($res1==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000090');
				return FAILURE;
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000091');
			return FAILURE;
		}
	}

	/**
	 *
	 * UPDATE LIST INFORMATION
	 * @param unknown_type $listid LIST ID of the list to be edited
	 * @param unknown_type $colname Name of column to be edited
	 * @param unknown_type $value Value to be inserted in the respective column
	 * @return Returns DBUPDATE result
	 */
	public function updatelistinfo($listid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_listinfo::tblname." SET $colname='$value' WHERE ".tbl_listinfo::col_listid."='$listid'",tbl_listinfo::dbname);
	}

	/**
	 *
	 * ADD A FRIEND
	 * @param unknown_type $friendid User ID of person who is to be added as a friend
	 * @param unknown_type $uid User ID of person who is adding the friend
	 * @param unknown_type $fmsg Friend intro message (Defaults to "")
	 * @param unknown_type $reldesc Relationship Description (Defaults to "")
	 * @param unknown_type $flevel Friendship Level (Defaults to 0)
	 * @param unknown_type $nickname Nickname to be assigned to the friend
	 * @param unknown_type $flag Flag value specifying the permissions (1-allowed,2-under review,3-blocked)
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function addfriend($friendid,$uid,$fmsg="",$reldesc="",$flevel=0,$nickname="",$flag="1")
	{
		$userobj=new ta_userinfo();
		$socialobj=new ta_socialoperations();
		
		if(!$userobj->checklogin())
		{
			throw new Exception('#ta@0000000_0000094');
			return FAILURE;
		}
		$dbobj=new ta_dboperations();
		$res=$userobj->user_getinfo($friendid);
		if($res==EMPTY_RESULT)
		{
			throw new Exception('#ta@0000000_0000095');
			return FAILURE;
		}
		
		if($socialobj->friend_check($uid, $friendid))
		{
			return SUCCESS;
		}
		
		if($dbobj->dbinsert("INSERT INTO ".tbl_frienddb::tblname." (".tbl_frienddb::col_fuid.",".tbl_frienddb::col_tuid.",".tbl_frienddb::col_fmsg.",".tbl_frienddb::col_reldesc.",".tbl_frienddb::col_flevel.",".tbl_frienddb::col_nickname.",".tbl_frienddb::col_fflag.")
				VALUES ('$uid','$friendid','$fmsg','$reldesc','$flevel','$nickname','$flag')",tbl_frienddb::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000096');
			return FAILURE;
		}
	}
	
	/**
	 *
	 * GET INFO REGARDING YOU AND A FRIEND FROM FRIEND DB
	 * @param unknown $uid UID of the user
	 * @param unknown $friendid UID of friend
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function friend_get_info($uid,$friendid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'", tbl_frienddb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * CHECK IF A PERSON IS FRIEND OR NOT
	 * @param unknown $uid UID of the user
	 * @param unknown $friendid Friend ID of the user to be checked
	 * @return string BOOL_FAILURE if not friend BOOL_SUCCESS if friend
	 */
	public function friend_check($uid,$friendid)
	{
		$socialobj=new ta_socialoperations();
		$res=$socialobj->friend_get_info($uid, $friendid);
		if(count($res)==0)
			return BOOL_FAILURE;
		else
			return BOOL_SUCCESS;
	}
	
	/**
	 * 
	 * TOGGLE FRIEND
	 * @param unknown $uid UID of the user
	 * @param unknown $friendid UID of the user who is to be Friended or Unfriended
	 * @return string Returns SUCCESS on success,FAILURE on failure
	 */
	public function friend_toggle($uid,$friendid)
	{
		$socialobj=new ta_socialoperations();
		$comobj=new ta_communication();
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		
		$userobj->userinit();
		$fpic=$userobj->getprofpic($userobj->uid);
		$nm=$userobj->fname." ".$userobj->mname." ".$userobj->lname;
		$ntype="3";
		if(!($socialobj->friend_check($friendid,$uid)))
		{
			$nottext=$nm." has sent you a Friend Request";
			$ntype="3";
			$jsonid=$utilityobj->jsondata_add('{"fuid":"'.$friendid.'","suid":"'.$uid.'"}');
			$notlink="/users.php?uid=".$friendid;
		}
		else
		{
			$nottext=$nm." has accepted your Friend Request";
			$ntype="8";
			$jsonid=$utilityobj->jsondata_add('{"fuid":"'.$friendid.'","suid":"'.$uid.'"}');
			$notlink="/users.php?uid=".$uid;
		}
		if(!($socialobj->friend_check($uid,$friendid)))
		{
			$res=$socialobj->addfriend($friendid,$uid);
			if($res!=FAILURE)
			{
				$extrajson='\"fuid\":\"'.$friendid.'\",\"suid\":\"'.$uid.'\"';
				$res=$socialobj->sendnotification($friendid,$nottext, $ntype, $fpic, $notlink,"2",$extrajson,$jsonid);
			}
			
		}
		else
		{
			$res=$socialobj->deletefriend($friendid,$uid);
		}
		
		return $res;
	}
	
	/**
	 *
	 * CHECK IF 2 PERSONS ARE FRIEND MUTUALLY
	 * @param unknown $uid UID of the user
	 * @param unknown $friendid Friend ID of the user 2
	 * @return string BOOL_FAILURE if not friend BOOL_SUCCESS if mutual friends
	 */
	public function friend_check_mutual($uid,$friendid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'", tbl_frienddb::dbname);
		$res1=$dbobj->dbquery("SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$friendid' AND ".tbl_frienddb::col_tuid."='$uid'", tbl_frienddb::dbname);
		if(count($res)==0||count($res1)==0)
			return BOOL_FAILURE;
		else
		if(count($res)!=0&&count($res1)!=0)
			return BOOL_SUCCESS;
	}
	
	/**
	 *
	 * REMOVE A FRIEND
	 * @param unknown_type $friendid User ID of friend who is to be removed from the friend list
	 * @param unknown_type $uid User ID of person who wants to remove the friend
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function deletefriend($friendid,$uid)
	{
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		if(!$userobj->checklogin())
		{
			throw new Exception('#ta@0000000_0000097');
			return FAILURE;
		}
		if($dbobj->dbdelete("DELETE FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'",tbl_frienddb::dbname)==SUCCESS)
		{
			if($socialobj->friend_check($friendid,$uid))
			{
				$socialobj->follower_add($friendid,$uid);
			}
			if($dbobj->dbdelete("DELETE FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$friendid' AND ".tbl_frienddb::col_tuid."='$uid'",tbl_frienddb::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000098');
			return FAILURE;
		}
	}

	/**
	 *
	 * UPDATE EDITABLE FRIEND INFORMATION BY ANOTHER USER
	 * @param unknown_type $friendid User ID of friend whose editable info is to be edited
	 * @param unknown_type $uid User ID of person who is editing the information
	 * @param unknown_type $infoflag Flag value specifying column to be edited (1-update nickname,2-update reldesc,3-update flevel)
	 * @param unknown_type $value Value to be inserted in the respective column
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function updatefriendinfo($friendid,$uid,$infoflag,$value)
	{
		$dbobj=new ta_dboperations();
		
		if($infoflag=="1")
		{
			if($dbobj->dbupdate("UPDATE ".tbl_frienddb::tblname." SET ".tbl_frienddb::col_nickname."='$value' WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'",tbl_frienddb::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000099');
				return FAILURE;
			}
		}
		else
		if($infoflag=="2")
		{
			if($dbobj->dbupdate("UPDATE ".tbl_frienddb::tblname." SET ".tbl_frienddb::col_reldesc."='$value' WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'",tbl_frienddb::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000100');
				return FAILURE;
			}
		}
		else
		if($infoflag=="3")
		{
			if($dbobj->dbupdate("UPDATE ".tbl_frienddb::tblname." SET ".tbl_frienddb::col_flevel."='$value' WHERE ".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid."='$friendid'",tbl_frienddb::dbname)==SUCCESS)
			{
				return SUCCESS;
			}
			else
			{
				throw new Exception('#ta@0000000_0000101');
				return FAILURE;
			}
		}
	}
	
	/**
	 * 
	 * CONVERTS FRIENDSHIP LEVEL INT TO TEXT
	 * @param unknown $flvl FRIENDSHIP LEVEL AS INTEGER
	 * @return string The text returned
	 */
	public function friend_leveltotext($flvl)
	{
		switch ($flvl)
		{
			case "-1":$dispmsg="Not Friends";break;
			case "0":$dispmsg="Not Yet Set";break;
			case "1":$dispmsg="V.V.Low";break;
			case "2":$dispmsg="V.Low";break;
			case "3":$dispmsg="Low";break;
			case "4":$dispmsg="Fair";break;
			case "5":$dispmsg="Good";break;
			case "6":$dispmsg="V.Good";break;
			case "7":$dispmsg="V.V.Good";break;
			case "8":$dispmsg="Excellent";break;
			case "9":$dispmsg="Amazing";break;
			case "10":$dispmsg="Best Buddies";break;
			default:$dispmsg="Invalid Option";break;
		}
		$dispmsg.=" (".$flvl.")";
		return $dispmsg;
	}

	/**
	 *
	 * GET FRIEND LIST OF A USER
	 * @param unknown_type $uid User ID of the person whose friend list is to be retrieved
	 * @param unknown_type $start Start Limit (Defaults to "" which means retrieve all friends)
	 * @param unknown_type $tot Total Limit (Defaults to "" which means retrieve all friends)
	 * @return string|Ambigous <string, unknown> A DB Array having all friend info from frienddb on success, FAILURE on failure
	 */
	public function getfriends($uid,$start="",$tot="",$listid="")
	{
		$dbobj=new ta_dboperations();
		
		$sql="";
		if($listid!="")
		{
			$sql="SELECT * FROM ".tbl_frienddb::tblname." AS `A`,".tbl_listsdb::tblname." AS `B` WHERE `A`.".tbl_frienddb::col_fuid."='$uid' AND `B`.".tbl_listsdb::col_uid."='$uid' AND `B`.".tbl_listsdb::col_listid."='$listid' AND `A`.".tbl_frienddb::col_tuid."=`B`.".tbl_listsdb::col_itemid;
		}
		else
		{
			$sql="SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid' ORDER BY ".tbl_frienddb::col_flevel." DESC,".tbl_frienddb::col_ftime." ASC";
		}
		
		if($start!=""&&$tot!="")
		{
			$sql.=" LIMIT $start,$tot";
		}
		
		$res=$dbobj->dbquery($sql,tbl_frienddb::dbname);
		return $res;
	}

	/**
	 *
	 * GET NO OF FRIENDS OF A USER
	 * @param unknown $uid UID of person whose friends are to be checked
	 * @param unknown $listid (Optional) Get no. of friends who belong to a list
	 * @return number The number of friends
	 */
	public function get_no_friends($uid,$listid="")
	{
		$dbobj=new ta_dboperations();
		if($listid=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid'",tbl_frienddb::dbname);
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_uid."='$uid' AND ".tbl_listsdb::col_listid."='$listid'", tbl_listsdb::dbname);
		}
		return count($res);
	}
	
	/**
	 * 
	 * GET LISTS TO WHICH A FRIEND BELONGS TO
	 * @param unknown $fuid UID of the friend
	 * @param unknown $uid User UID
	 * @return Ambigous <string, unknown> Returns DBARRAY of results
	 */
	public function get_belonginglist_friend($fuid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_listsdb::tblname." WHERE ".tbl_listsdb::col_itemid."='$fuid' AND ".tbl_listsdb::col_uid."='$uid'", tbl_listsdb::dbname);
		return $res;
	}

	/**
	 *
	 * ADD A SUBSCRIBER TO A PERSON
	 * @param unknown_type $personid User ID of person to whom the user want to subscribe
	 * @param unknown_type $uid User ID of person who want to subscribe
	 * @param unknown_type $flag Flag value for permissions (1-allowed,2-under review,3-blocked)
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function addsubscriber($personid,$uid,$flag="1")
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		if(!$userobj->checklogin())
		{
			throw new Exception('#ta@0000000_0000102');
			return FAILURE;
		}
		if($dbobj->dbinsert("INSERT INTO ".tbl_subalerts_users::tblname." (".tbl_subalerts_users::col_uid.",".tbl_subalerts_users::col_suid.",".tbl_subalerts_users::col_sflag.") VALUES ('$uid','$personid','$flag')",tbl_subalerts_users::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000103');
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE A SUBSCRIBER
	 * @param unknown_type $personid User ID of the person to whom the user is subscribed
	 * @param unknown_type $uid User ID of the person who subscribed to this user
	 * @return string SUCCESS on successful removal, FAILURE on failure
	 */
	public function deletesubscriber($personid,$uid)
	{
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		if(!$userobj->checklogin())
		{
			throw new Exception('#ta@0000000_0000104');
			return FAILURE;
		}
		if($dbobj->dbdelete("DELETE FROM ".tbl_subalerts_users::tblname." WHERE ".tbl_subalerts_users::col_uid."='$uid' AND ".tbl_subalerts_users::col_suid."='$personid'",tbl_subalerts_users::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000105');
			return FAILURE;
		}
	}

	/**
	 *
	 * GET LIST OF ALL SUBSCRIPTIONS ADDED BY A USER
	 * @param unknown_type $uid User ID of the person (who subscribed to another user)
	 * @return string|Ambigous <string, unknown> A DB Array having all user subscriptions on success, FAILURE on failure
	 */
	public function get_subscriptions($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_subalerts_users::tblname." WHERE ".tbl_subalerts_users::col_uid."='$uid'",tbl_subalerts_users::dbname);
		if(count($res)==0)
		{
			return EMPTY_RESULT;
		}
		if($res==FAILURE)
		{
			throw new Exception('#ta@0000000_0000106');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}

	/**
	 *
	 * GET LIST OF ALL SUBSCRIBERS TO A USER
	 * @param unknown $uid The UID of a person to whom other people are subscribed
	 * @return Ambigous <string, unknown> A DB Array having all people subscribed on success, FAILURE on failure
	 */
	public function get_subscribers($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_subalerts_users::tblname." WHERE ".tbl_subalerts_users::col_suid."='$uid'",tbl_subalerts_users::dbname);
		return $res;
	}

	/**
	 *
	 * ADD SUBSCRIBER ALERTS (TA & PARTNERS)
	 * @param unknown_type $pid Product ID to which the user want to subscribe
	 * @param unknown_type $uid User ID of person who is subscribing to the product
	 * @param unknown_type $itemid Item ID of the product section to which the user is subscribing
	 * @return string SUCCESS on successful subscription, FAILURE on failure
	 */
	public function addsubalert($pid,$uid,$itemid="")
	{
		$dbobj=new ta_dboperations();
		if($itemid=="")
		{
			$res=$dbobj->dbinsert("INSERT INTO ".tbl_subalerts_products::tblname."(".tbl_subalerts_products::col_pid.",".tbl_subalerts_products::col_uid.") VALUES ('$pid','$uid')",tbl_subalerts_products::dbname);
		}
		else
		{
			$res=$dbobj->dbinsert("INSERT INTO ".tbl_subalerts_products::tblname."(".tbl_subalerts_products::col_pid.",".tbl_subalerts_products::col_uid.",".tbl_subalerts_products::col_itemid.") VALUES ('$pid','$uid','$itemid')",tbl_subalerts_products::dbname);
		}
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000107');
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE SUBSCRIPTION ALERT
	 * @param unknown_type $pid Product ID of the product to which the user want to subscribe
	 * @param unknown_type $uid User ID of the person who is subscribed to this alert
	 * @param unknown_type $itemid Item ID of the section of product to which the user want to unsubscribe from (Defaults to "" which removes all subscriptions)
	 * @return string SUCCESS on successful removal, FAILURE on failure
	 */
	public function deletesubalert($pid,$uid,$itemid="")
	{
		$dbobj=new ta_dboperations();
		if($itemid=="")
		{
			$res=$dbobj->dbdelete("DELETE FROM ".tbl_subalerts_products::tblname." WHERE ".tbl_subalerts_products::col_pid."='$pid' AND ".tbl_subalerts_products::col_uid."='$uid'",tbl_subalerts_products::dbname);
		}
		else
		{
			$res=$dbobj->dbdelete("DELETE FROM ".tbl_subalerts_products::tblname." WHERE ".tbl_subalerts_products::col_pid."='$pid' AND ".tbl_subalerts_products::col_uid."='$uid' AND ".tbl_subalerts_products::col_itemid."='$itemid'",tbl_subalerts_products::dbname);
		}
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	public function get_subalerts($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_subalerts_products::tblname." WHERE ".tbl_subalerts_products::col_uid."='$uid'",tbl_subalerts_products::dbname);
		return $res;
	}

	public function get_no_subalerts($uid)
	{
		$res=$this->get_subalerts($uid);
		return count($res);
	}

	public function get_no_subscribers($uid)
	{
		$res=$this->get_subscribers($uid);
		return count($res);
	}

	/**
	 *
	 * CREATE A NEW GROUP
	 * @param unknown_type $gpname Group Name
	 * @param unknown_type $uid User ID of person who created the group
	 * @param unknown_type $gpprivacy Group Privacy (1-public,2-secret,3-closed,others-list id) (Defaults to 1)
	 * @param unknown_type $gpdesc Group Description
	 * @param unknown_type $gpemail Group Email Address
	 * @param unknown_type $gppic Group Cover pic (Defaults to "")
	 * @param unknown_type $gpflag Group Permission flag (1-allowed,2-under review,3-blocked)
	 * @return Ambigous <string, unknown>|string SUCCESS on successful creation, FAILURE on failure
	 */
	public function creategroup($gpname,$uid,$gpprivacy="1",$gpdesc="",$gpemail="",$gppic="",$gpflag="1",$memtype="2")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		$gpid=$uiobj->randomstring(30,tbl_groups_info::dbname,tbl_groups_info::tblname,tbl_groups_info::col_gpid);
		if($dbobj->dbinsert("INSERT INTO ".tbl_groups_info::tblname." (".tbl_groups_info::col_gpname.",".tbl_groups_info::col_gpprivacy.",".tbl_groups_info::col_gpdesc.",".tbl_groups_info::col_gpemail.",".tbl_groups_info::col_gpflag.",".tbl_groups_info::col_gppic.",".tbl_groups_info::col_gpid.",".tbl_groups_info::col_gpmemtype.") VALUES
				('$gpname','$gpprivacy','$gpdesc','$gpemail','$gpflag','$gppic','$gpid','$memtype')",tbl_groups_info::dbname)==SUCCESS)
		{
			$socialobj=new ta_socialoperations();
			if($socialobj->addgpmember($gpid, $uid,"3","1")==SUCCESS)
			{
				return $gpid;
			}
			else
			{
				throw new Exception('#ta@0000000_0000108');
				return FAILURE;
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000109');
			return FAILURE;
		}
	}

	/**
	 *
	 * ADD A NEW MEMBER TO THE GROUP
	 * @param unknown_type $gpid Group ID of the group to which the user is to be added
	 * @param unknown_type $uid User ID of person who is to be added
	 * @param unknown_type $memrole User role Flag (1-user,2-admin,3-creator)
	 * @param unknown_type $memflag User Permission Flag (1-allowed,2-under review,3-blocked) (Defaults to 1)
	 * @param unknown_type $addby User ID of person who added the other person to the group (Defaults to "")
	 * @return string
	 */
	public function addgpmember($gpid,$uid,$memrole="1",$memflag="1",$addby="")
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		
		$gpres=$socialobj->groups_get($gpid);
		$memtype=$gpres[0][changesqlquote(tbl_groups_info::col_gpmemtype,"")];
		
		switch ($memtype)
		{
			case "1":
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_members_attached::tblname." (".tbl_members_attached::col_gpid.",".tbl_members_attached::col_uid.",".tbl_members_attached::col_memrole.",".tbl_members_attached::col_addby.",".tbl_members_attached::col_memflag.") VALUES ('$gpid','$uid','$memrole','$addby','1')",tbl_members_attached::dbname);
				break;
			case "2":
				if($memrole=="3")
				{
					$memflag="1";
				}
				else
				{
					$memflag="2";
				}
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_members_attached::tblname." (".tbl_members_attached::col_gpid.",".tbl_members_attached::col_uid.",".tbl_members_attached::col_memrole.",".tbl_members_attached::col_addby.",".tbl_members_attached::col_memflag.") VALUES ('$gpid','$uid','$memrole','$addby','$memflag')",tbl_members_attached::dbname);
				break;
			case "3":
				if($memrole=="3")
				{
					$memflag="1";
				}
				else
				{
					$memflag="2";
				}
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_members_attached::tblname." (".tbl_members_attached::col_gpid.",".tbl_members_attached::col_uid.",".tbl_members_attached::col_memrole.",".tbl_members_attached::col_addby.",".tbl_members_attached::col_memflag.") VALUES ('$gpid','$uid','$memrole','$addby','$memflag')",tbl_members_attached::dbname);
				break;
			case "4":
				if($memrole=="3")
				{
					$memflag="1";
				}
				else
				{
					$memflag="3";
				}
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_members_attached::tblname." (".tbl_members_attached::col_gpid.",".tbl_members_attached::col_uid.",".tbl_members_attached::col_memrole.",".tbl_members_attached::col_addby.",".tbl_members_attached::col_memflag.") VALUES ('$gpid','$uid','$memrole','$addby','$memflag')",tbl_members_attached::dbname);
				break;
			default:
				if($memrole=="3")
				{
					$memflag="1";
				}
				else
				{
					$memflag="2";
				}
				$res=$dbobj->dbinsert("INSERT INTO ".tbl_members_attached::tblname." (".tbl_members_attached::col_gpid.",".tbl_members_attached::col_uid.",".tbl_members_attached::col_memrole.",".tbl_members_attached::col_addby.",".tbl_members_attached::col_memflag.") VALUES ('$gpid','$uid','$memrole','$addby','$memflag')",tbl_members_attached::dbname);
				break;
		}
		
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000110');
			return FAILURE;
		}
	}

	/**
	 * 
	 * GET NUMBER OF MEMBERS BELONGING TO A GROUP
	 * @param unknown $gpid Group ID
	 */
	public function group_get_nomem($gpid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='1'", tbl_members_attached::dbname);
		return count($res);
	}
	
	/**
	 * 
	 * GET MEMBERS OF A GROUP
	 * @param unknown $gpid Group ID
	 * @param string $start Start Limit
	 * @param string $tot Total Limit
	 * @return A DBArray of all members in the group
	 */
	public function group_get_mem($gpid,$start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		if($start!=""&&$tot!="")
		{
			$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='1' ORDER BY ".tbl_members_attached::col_memrole." DESC,".tbl_members_attached::col_jointime." DESC LIMIT $start,$tot";			
		}
		else
		{
			$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='1' ORDER BY ".tbl_members_attached::col_memrole." DESC,".tbl_members_attached::col_jointime." DESC";
		}
			
		return $dbobj->dbquery($sql, tbl_members_attached::dbname);
	}
	
	/**
	 *
	 * GET REQUESTS OF A GROUP
	 * @param unknown $gpid Group ID
	 * @param string $start Start Limit
	 * @param string $tot Total Limit
	 * @return A DBArray of all members in the group
	 */
	public function group_get_requests($gpid,$start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		if($start!=""&&$tot!="")
		{
			$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='2' ORDER BY ".tbl_members_attached::col_jointime." DESC LIMIT $start,$tot";
		}
		else
		{
			$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='2' ORDER BY ".tbl_members_attached::col_jointime." DESC";
		}
			
		return $dbobj->dbquery($sql, tbl_members_attached::dbname);
	}
	
	/**
	 * 
	 * GET NUMBER OF REQUESTS RECEIVED BY A GROUP
	 * @param unknown $gpid Group ID
	 * @return Returns no of requests received
	 */
	public function group_get_no_requests($gpid)
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_memflag."='2'";
		return count($dbobj->dbquery($sql, tbl_members_attached::dbname));
	}
	
	/**
	 * 
	 * CHECK IF A USER BELONGS TO A GROUP OR NOT
	 * @param unknown $gpid Group ID to check
	 * @param unknown $uid UID of the user
	 * @return True if he belongs to the group BOOL_FAILURE if he does not
	 */
	public function group_user_check($gpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$uid' AND ".tbl_members_attached::col_memflag."='1'", tbl_members_attached::dbname);
		if(count($res)!=0)
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
	 * CHECK IF A USER's REQUEST TO JOIN A GROUP IS BEING PROCESSED OR NOT
	 * @param unknown $gpid Group ID to check
	 * @param unknown $uid UID of the user
	 * @return True if he belongs to the group BOOL_FAILURE if he does not
	 */
	public function group_user_check_processing($gpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$uid' AND ".tbl_members_attached::col_memflag."='2'", tbl_members_attached::dbname);
		if(count($res)!=0)
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
	 * CHECK IF A USER IS THE CREATOR OF THE GROUP OR NOT
	 * @param unknown $gpid Group ID
	 * @param unknown $uid User ID
	 * @return Returns BOOL_SUCCESS if yes, BOOL_FAILURE if no
	 */
	public function group_user_check_creator($gpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$uid' AND ".tbl_members_attached::col_memrole."='3'", tbl_members_attached::dbname);
		if(count($res)!=0)
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
	 * CHECK IF A USER IS THE ADMIN OF THE GROUP OR NOT
	 * @param unknown $gpid Group ID
	 * @param unknown $uid User ID
	 * @return Returns BOOL_SUCCESS if yes, BOOL_FAILURE if no
	 */
	public function group_user_check_admin($gpid,$uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$uid' AND (".tbl_members_attached::col_memrole."='3' OR ".tbl_members_attached::col_memrole."='2')", tbl_members_attached::dbname);
		if(count($res)!=0)
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
	 * GET A GROUP PICTURE
	 * @param unknown $gpid Group ID
	 * @param string $size Size
	 */
	public function group_get_pic($gpid,$size="1")
	{
		$socialobj=new ta_socialoperations();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		//TODO SIZE
		$gpres=$socialobj->groups_get($gpid);
		$gppic_medid=$gpres[0][changesqlquote(tbl_groups_info::col_gppic,"")];
		if($gppic_medid=="")
		{
			return "/master/securedir/m_images/image-not-found.png";
		}
		return $utilityobj->pathtourl($galobj->geturl_media("", $gppic_medid,"3"));
	}
	
	/**
	 * 
	 * GET GROUP ADMINS
	 * @param unknown $gpid Group ID
	 * @return string A DBArray having list of admins
	 */
	public function group_get_admins($gpid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND (".tbl_members_attached::col_memrole."='2' OR ".tbl_members_attached::col_memrole."='3') ORDER BY ".tbl_members_attached::col_memrole." DESC", tbl_members_attached::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET NUMBER OF BLOCKED USERS IN A GROUP
	 * @param unknown $gpid Group ID
	 * @return The number of blocked users
	 */
	public function group_get_blockedusers_no($gpid)
	{
		$dbobj=new ta_dboperations();
		return count($dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_memflag."='3'", tbl_members_attached::dbname));
	}
	
	/**
	 *
	 * GET BLOCKED USERS IN A GROUP
	 * @param unknown $gpid Group ID
	 * @return A DBArray of blocked users
	 */
	public function group_get_blockedusers($gpid,$start="0",$tot="20")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_memflag."='3' LIMIT $start,$tot", tbl_members_attached::dbname);
	}
	
	/**
	 *
	 * REMOVE A MEMBER FROM A GROUP
	 * @param unknown_type $gpid Group ID of the group from which the member has to be removed
	 * @param unknown_type $memid User ID of person who is to be removed from the group
	 * @return string SUCCESS on successful removal, FAILURE on failure
	 */
	public function deletegpmember($gpid,$memid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$memid'",tbl_members_attached::dbname)==SUCCESS)
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
	 * ATTACH A THREAD TO THE GROUP
	 * @param unknown_type $gpid Group ID of the group where the thread has to be attached
	 * @param unknown_type $tid Thread ID which has to be attached to the group
	 * @return string SUCCESS on successful, FAILURE on failure
	 */
	public function attachthreadtogroup($gpid,$tid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_threads_attached::tblname." (".tbl_threads_attached::col_gpid.",".tbl_threads_attached::col_tid.") VALUES ('$gpid','$tid')",tbl_threads_attached::dbname)==SUCCESS)
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
	 * ATTACH A THREAD TO THE HELP CENTER
	 * @param unknown_type $tid Thread ID which has to be attached to the group
	 * @param unknown_type $appid APP ID for which help is asked
	 * @return string SUCCESS on successful, FAILURE on failure
	 */
	public function attachthreadtohelp($tid,$appid="00000")
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_support_help::tblname." (".tbl_support_help::col_appid.",".tbl_support_help::col_threadid.") VALUES ('$appid','$tid')",tbl_support_help::dbname)==SUCCESS)
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
	 * GET HELP THREADS
	 * @param string $start Start Limit
	 * @param string $tot Total Limit
	 * @param string $appid APPID for which the question is asked
	 */
	public function get_help_threads($start="0",$tot="10",$appid="00000")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_appid."='$appid' ORDER BY ".tbl_support_help::col_asktime." DESC LIMIT $start,$tot", tbl_support_help::dbname);
	}

	/**
	 * 
	 * DELETE A HELP THREAD FROM HELP CENTER
	 * @param unknown $tid Thread ID
	 * @param string $appid APPID of app where thread is posted
	 */
	public function delete_help_thread($tid,$appid="00000")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_support_help::tblname." WHERE ".tbl_support_help::col_appid."='$appid' AND ".tbl_support_help::col_threadid."='$tid'", tbl_support_help::dbname);
	}

	/**
	 * 
	 * REMOVE ATTACHED THREAD FROM A GROUP
	 * @param unknown $gpid Group ID
	 * @param unknown $tid Thread ID
	 * @return SUCCESS on successful removal FAILURE on failure
	 */
	public function attachedthread_remove($gpid,$tid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_threads_attached::tblname." WHERE (".tbl_threads_attached::col_gpid."='$gpid' AND ".tbl_threads_attached::col_tid."='$tid')",tbl_threads_attached::dbname)==SUCCESS)
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
	 * READ ALL THREAD IDs ATTACHED TO A GROUP
	 * @param unknown_type $gpid Group ID of the group where the thread has to be attached
	 * @param unknown_type $start Start Limit (Defaults to "" which means retrieve info of all threads attached to the group)
	 * @param unknown_type $tot Total Limit (Defaults to "" which means retrieve info of all threads attached to the group)
	 * @return Ambigous <string, unknown> A DB Array containing all the thread info from groupmsg, FAILURE on failure
	 */
	public function readgrouptids($gpid,$start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_gpid."='$gpid' ORDER BY ".tbl_threads_attached::col_attachtime." DESC LIMIT $start,$tot",tbl_threads_attached::dbname);
		/*if($start==""||$tot=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_gpid."='$gpid'",tbl_threads_attached::dbname);
		}
		else
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_gpid."='$gpid' LIMIT $start,$tot",tbl_threads_attached::dbname);
		}*/
		return $res;
	}

	/**
	 *
	 * GET LIST OF ALL GROUPS TO WHICH THE USER IS ADDED
	 * @param unknown_type $uid User ID of person whose list is to be retrieved
	 * @param unknown_type $memrole Member role (0 to ignore)
	 * @return string|Ambigous <string, unknown> A DB Array
	 */
	public function getgroups($uid="",$memrole="0")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_uid."='$uid'";
		
		if($memrole!="0")
		{
			$sql.=" AND ".tbl_members_attached::col_memrole."='$memrole'";
		}
		$res=$dbobj->dbquery($sql,tbl_members_attached::dbname);
		if(count($res)==0)
		{
			return EMPTY_RESULT;
		}
		if($res==FAILURE)
		{
			throw new Exception('#ta@0000000_0000113');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}
	
	/**
	 * 
	 * GET ALL GROUPS
	 */
	public function get_groups_all()
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_groups_info::tblname;
	
		$res=$dbobj->dbquery($sql,tbl_groups_info::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET LIST OF FEATURED GROUPS
	 * @param string $start Start Limit
	 * @param string $tot Total Limit
	 */
	public function groups_get_featured($start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_groups_featured::tblname." ORDER BY ".tbl_groups_featured::col_flvl." DESC LIMIT $start,$tot", tbl_groups_featured::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET INFO ABOUT A GROUP
	 * @param unknown $gpid Group ID of the group
	 * @return Ambigous <string, unknown> Returns an Array with keys as in DB
	 */
	public function groups_get($gpid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_groups_info::tblname." WHERE ".tbl_groups_info::col_gpid."='$gpid' LIMIT 0,1", tbl_groups_info::dbname);
	}
	
	/**
	 * 
	 * EDIT GROUP MEMBER DETAILS
	 * @param unknown $gpid Group ID
	 * @param unknown $colname Name of column to be edited
	 * @param unknown $value Value to be substituted
	 */
	public function groups_mem_edit($gpid,$memuid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_members_attached::tblname." SET ".$colname."='$value' WHERE ".tbl_members_attached::col_gpid."='$gpid' AND ".tbl_members_attached::col_uid."='$memuid'", tbl_members_attached::dbname);
	}
	
	/**
	 * 
	 * EDIT GROUP INFORMATION
	 * @param unknown $gpid Group ID
	 * @param unknown $colname Column Name
	 * @param unknown $value Value
	 * @return DBUPDATE result
	 */
	public function group_edit($gpid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_groups_info::tblname." SET ".$colname."='$value' WHERE ".tbl_groups_info::col_gpid."='$gpid'", tbl_groups_info::dbname);
	}

	/**
	 *
	 * GET THE NUMBER OF GROUPS TO WHICH THE USER IS ADDED
	 * @param unknown $uid The UID of the user
	 * @return number The number of groups
	 */
	public function get_no_groups($uid)
	{
		$res=$this->getgroups($uid);
		return count($res);
	}

	/**
	 *
	 * ADD A NEW TAG
	 * @param unknown_type $tagarray An array having list of all tags (tagname,uid)
	 * @param unknown_type $uid User ID of the person who adds the tag
	 * @param unknown_type $tagtype Flag value specifying tag type (1-post,2-comment,3-chat,4-gallery,5-media,6-message)
	 * @param unknown_type $tagid Tag ID (Defaults to "")
	 * @return string|Ambigous <string, unknown> TAG ID on success, FAILURE on failure
	 */
	public function tag_user_add($tagarray,$uid,$tagtype="1",$tagid="")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		
		if(count($tagarray)==0)return $tagid;
		if($tagid=="")
		{
			$tagid=$uiobj->randomstring(30,tbl_tagdb::dbname,tbl_tagdb::tblname,tbl_tagdb::col_tagid);
		}
		for($i=0;$i<count($tagarray);$i++)
		{
			$tagname=$tagarray[$i]["tagname"];
			$taguid=$tagarray[$i]["uid"];
			$dbobj->dbinsert("INSERT INTO ".tbl_tagdb::tblname." (".tbl_tagdb::col_tagid.",".tbl_tagdb::col_fuid.",".tbl_tagdb::col_tuid.",".tbl_tagdb::col_tagname.",".tbl_tagdb::col_tagtype.") VALUES
					('$tagid','$uid','$taguid','$tagname','$tagtype')",tbl_tagdb::dbname);
		}
		
		return $tagid;
	}

	/**
	 *
	 * REMOVE AN EXISTING TAG
	 * @param unknown_type $tagid TAG ID of the tag to be removed
	 * @param unknown_type $fuid User ID of person who tagged the other
	 * @param unknown_type $tuid User ID of person who was being tagged
	 * @return string SUCCESS on successful removal of tag, FAILURE on failure
	 */
	public function tag_user_remove($tagid,$fuid,$tuid="")
	{
		if($tagid=="")return SUCCESS;
		$dbobj=new ta_dboperations();
		if($tuid=="")
		{
			$result=$dbobj->dbdelete("DELETE FROM ".tbl_tagdb::tblname." WHERE ".tbl_tagdb::col_tagid."='$tagid' AND ".tbl_tagdb::col_fuid."='$fuid'",tbl_tagdb::dbname);
		}
		else
		{
			$result=$dbobj->dbdelete("DELETE FROM ".tbl_tagdb::tblname." WHERE ".tbl_tagdb::col_tagid."='$tagid' AND ".tbl_tagdb::col_fuid."='$fuid' AND ".tbl_tagdb::col_tuid."='$tuid'",tbl_tagdb::dbname);
		}
		if($result==SUCCESS)
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
	 * GET LIST OF ALL TAGS
	 * @param unknown $tagid
	 */
	public function tag_user_get($tagid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_tagdb::tblname." WHERE ".tbl_tagdb::col_tagid."='$tagid'", tbl_tagdb::dbname);
	}
	
	/**
	 *
	 * INITIALIZE RATING (0 RATE FOR ITEM)
	 * @param number $rtype Type of rating
	 * @return Ambigous <Ambigous, string, unknown>|string RATE ID on success, FAILURE on failure
	 */
	public function rating_init($rtype=1,$defrateid="")
	{
		$socialobj=new ta_socialoperations();
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();

		if($defrateid!="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$defrateid' AND ".tbl_ratings::col_ratestat."='0'",tbl_ratings::dbname);
			if(count($res)!=0)
			{
				return FAILURE;
			}
			else
			{
				$rateid=$defrateid;
			}
		}
		else
		{
			$rateid=$uiobj->randomstring(40,tbl_ratings::dbname,tbl_ratings::tblname,tbl_ratings::col_rateid);
		}
		if($dbobj->dbinsert("INSERT INTO ".tbl_ratings::tblname." (".tbl_ratings::col_rateid.",".tbl_ratings::col_ratestat.",".tbl_ratings::col_ratetype.",".tbl_ratings::col_rateuid.") VALUES
				('$rateid','0','$rtype','000000')",tbl_ratings::dbname)==SUCCESS)
		{
			return $rateid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * RATE UP AN ELEMENT
	 * @param unknown_type $uid User ID of the person who rated it up
	 * @param unknown_type $rateid RATE ID of the element where rating has to be done
	 * @param unknown_type $rtype Flag value specifying Rate type (1-post,2-comment,3-gallery,4-media,5-message,6-chat,7-helpitem) (Defaults to 1)
	 * @param unknown_type $stat Rate status or points to be rated up(Defaults to 1 for rate up 1 point)
	 * @return string SUCCESS on successful rate up, FAILURE on failure
	 */
	public function rating_up($uid,$rateid="",$rtype=1,$stat=1)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		if($rateid=="")
		{
			$rateid=$socialobj->rating_init($rtype);
		}
		else
		{
			if(!($this->rating_check_rateid_exists($rateid)))
			{
				$rateid=$this->rating_init($rtype,$rateid);
			}
		}

		if($this->rating_check_alreadyrated_down_user($uid,$rateid))
		{
			$dbobj->dbdelete("DELETE FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_ratestat."='-1' AND ".tbl_ratings::col_rateuid."='$uid'",tbl_ratings::dbname);
			return SUCCESS;
		}

		if($this->rating_check_alreadyrated_up_user($uid, $rateid))
		{
			return SUCCESS;
		}

		if($dbobj->dbinsert("INSERT INTO ".tbl_ratings::tblname." (".tbl_ratings::col_rateid.",".tbl_ratings::col_ratestat.",".tbl_ratings::col_ratetype.",".tbl_ratings::col_rateuid.") VALUES
				('$rateid','$stat','$rtype','$uid')",tbl_ratings::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	public function rating_check_alreadyrated_up_user($uid,$rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_rateuid."='$uid' AND ".tbl_ratings::col_ratestat."='1'",tbl_ratings::dbname);
		if($res!=EMPTY_RESULT&&(!($this->rating_check_alreadyrated_down_user($uid,$rateid))))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	public function rating_check_alreadyrated_down_user($uid,$rateid)
	{
		$dbobj=new ta_dboperations();
		$res2=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_ratestat."='-1' AND ".tbl_ratings::col_rateuid."='$uid'",tbl_ratings::dbname);
		if(count($res2)!=0)
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
	 * RATE AN ITEM DOWN
	 * @param unknown_type $uid User ID of the person who rated it down
	 * @param unknown_type $rateid RATE ID of the element where rating has to be done
	 * @param unknown_type $rtype Flag value specifying Rate type (1-post,2-comment,3-gallery,4-media,5-message,6-chat) (Defaults to 1)
	 * @param unknown_type $stat Rate status or points to be rated down(Defaults to -1 for rate down 1 point)
	 * @return string
	 */
	public function rating_down($uid,$rateid="",$rtype=1,$stat=-1)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		if($rateid=="")
		{
			$rateid=$socialobj->rating_init($rtype);
		}
		else
		{
			if(!($this->rating_check_rateid_exists($rateid)))
			{
				$rateid=$this->rating_init($rtype,$rateid);
			}
		}

		if($this->rating_check_alreadyrated_up_user($uid,$rateid))
		{
			$dbobj->dbdelete("DELETE FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_rateuid."='$uid' AND ".tbl_ratings::col_ratestat."='1'",tbl_ratings::dbname);
			return SUCCESS;
		}
		else
		if(!($this->rating_check_alreadyrated_down_user($uid,$rateid)))
		{
			if($dbobj->dbinsert("INSERT INTO ".tbl_ratings::tblname." (".tbl_ratings::col_rateid.",".tbl_ratings::col_ratestat.",".tbl_ratings::col_ratetype.",".tbl_ratings::col_rateuid.") VALUES
					('$rateid','-1','$rtype','$uid')",tbl_ratings::dbname)==SUCCESS)
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
			return SUCCESS;
		}
	}

	/**
	 *
	 * CURRENT RATING OF AN ITEM
	 * @param unknown_type $rateid Rate ID of the item for which rating has to be retrieved
	 * @return number THE TOTAL RATING OF THE RESPECTIVE ITEM
	 */
	public function rating_current($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid'",tbl_ratings::dbname);
		$c=0;
		for($i=0;$i<count($res);$i++)
		{
			$c+=$res[$i][changesqlquote(tbl_ratings::col_ratestat,"")];
		}
		return $c;
	}
	
	public function rating_get_upvotes($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_ratestat."='1'",tbl_ratings::dbname);
		$c=0;
		for($i=0;$i<count($res);$i++)
		{
			$c+=$res[$i][changesqlquote(tbl_ratings::col_ratestat,"")];
		}
		return $c;
	}
	
	public function rating_get_downvotes($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_ratestat."='-1'",tbl_ratings::dbname);
		$c=0;
		for($i=0;$i<count($res);$i++)
		{
			$c+=$res[$i][changesqlquote(tbl_ratings::col_ratestat,"")];
		}
		return $c;
	}

	/**
	 * GET PEOPLE WHO UPVOTED AN ITEM
	 * @param unknown $rateid Rate ID
	 * @return DBArray from rate db
	 */
	public function rating_get_upvoters($rateid,$start="0",$tot="10")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid' AND ".tbl_ratings::col_ratestat."='1' LIMIT $start,$tot",tbl_ratings::dbname);
	}
	
	/**
	 *
	 * REMOVE A RATING
	 * @param unknown_type $rateid RATE ID of the item for which rating information has to be removed
	 * @return string SUCCESS on successful removal, failure on failure
	 */
	public function rating_delete($rateid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid'",tbl_ratings::dbname)==SUCCESS)
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
	 * CHECK IF AN ITEM WITH A RATEID EXISTS
	 * @param unknown $rateid Rate ID of the item
	 * @return boolean True if exists, False if not exists
	 */
	public function rating_check_rateid_exists($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_ratings::tblname." WHERE ".tbl_ratings::col_rateid."='$rateid'",tbl_ratings::dbname);
		if(count($res)==0)
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
	 * SEND A NEW NOTIFICATION TO A USER
	 * @param unknown_type $uid User ID of person to whom notification has to be sent
	 * @param unknown_type $notcontent Content of the notification in HTML/Plain-Text
	 * @param unknown_type $nottype Flag value specifying type of notification (1-post,2-message,3-friend request,4-new addition to group,5-tag,6-shared,7-starred alerts)
	 * @param unknown_type $noticon Icon to be displayed near notification text
	 * @param unknown_type $notlink URL of link to which the notification has to redirect when clicked
	 * @param unknown_type $notstatus Flag value specifying notification status (1-read,2-unread)
	 * @param unknown_type $extrajson Extra json to be appended to the inner msg (Defaults to "")
	 * @return Ambigous <string, unknown>|string Notification ID of the notification on success, FAILURE on failure
	 */
	public function sendnotification($uid,$notcontent,$nottype,$noticon,$notlink,$notstatus,$extrajson="",$jsonid="",$elid="")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		$comobj=new ta_communication();
		$utilityobj=new ta_utilitymaster();
		$userobj=new ta_userinfo();
		
		if($extrajson!="")$extrajson=",".$extrajson;
		
		$notcontent=ucfirst($notcontent);
		$userobj->userinit();
		$notifid=$uiobj->randomstring(50,tbl_notifications::dbname,tbl_notifications::tblname,tbl_notifications::col_notifyid);
		if($elid==""&&$nottype!="2")
		{
			$sql="INSERT INTO ".tbl_notifications::tblname." (".tbl_notifications::col_notifyid.",".tbl_notifications::col_notifytext.",".tbl_notifications::col_notifyicon.",".tbl_notifications::col_notifylink.",".tbl_notifications::col_notifystatus.",".tbl_notifications::col_notifytype.",".tbl_notifications::col_uid.",".tbl_notifications::col_jsonid.",".tbl_notifications::col_elid.")
			VALUES ('$notifid','$notcontent','$noticon','$notlink','$notstatus','$nottype','$uid','$jsonid','$elid')";
		}
		else
		{
			$myres=$dbobj->dbquery("SELECT * FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_uid."='$uid' AND ".tbl_notifications::col_elid."='$elid' AND ".tbl_notifications::col_notifytype."='$nottype'", tbl_notifications::dbname);
			if(count($myres)==0)
			{
				$sql="INSERT INTO ".tbl_notifications::tblname." (".tbl_notifications::col_notifyid.",".tbl_notifications::col_notifytext.",".tbl_notifications::col_notifyicon.",".tbl_notifications::col_notifylink.",".tbl_notifications::col_notifystatus.",".tbl_notifications::col_notifytype.",".tbl_notifications::col_uid.",".tbl_notifications::col_jsonid.",".tbl_notifications::col_elid.")
				VALUES ('$notifid','$notcontent','$noticon','$notlink','$notstatus','$nottype','$uid','$jsonid','$elid')";
			}
			else
			{
				$notifid=$myres[0][changesqlquote(tbl_notifications::col_notifyid,"")];
				$sql="UPDATE ".tbl_notifications::tblname." SET ".tbl_notifications::col_cnt."=".tbl_notifications::col_cnt."+1,".tbl_notifications::col_notifystatus."='2' WHERE ".tbl_notifications::col_notifyid."='$notifid'";
			}
		}
		
		if($dbobj->dbinsert($sql,tbl_notifications::dbname)==SUCCESS)
		{			
			$ntype="";
			switch($nottype)
			{
				case "1":$ntype="post_new";break;
				case "2":$ntype="msg_new";break;
				case "3":$ntype="frequest";break;
				case "4":$ntype="gp_newmem";break;
				case "8":$ntype="frequest_accepted";break;
				case "9":$ntype="thread_addedparticipant";break;
				case "10":$ntype="gp_reqaccepted";break;
				case "12":$ntype="tbx_commentsent";break;
				default:$ntype=$nottype;break;
			}
			
			
			$myjson='{\"uid\":\"'.$userobj->uid.'\",\"mtype\":\"3\",\"msg\":{\"elkey\":\"'.$ntype.'\",\"elid\":\"'.$notifid.'\",\"ellink\":\"'.$notlink.'\",\"elval\":\"'.$notcontent.'\",\"elpic\":\"'.$utilityobj->pathtourl($noticon).'\",\"eltime\":\"'.date('Y-m-d H:i:s').'\"'.$extrajson.'},\"target\":\"'.$uid.'\"}';
			
			$res=$comobj->socket_sendnotif($myjson);
			
			return $res;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * GET NOTIFICATION BY NOTIFICATION ID
	 * @param unknown $notid Notification ID
	 * @return DBArray having the notification result
	 */
	public function notification_get($notid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_notifyid."='$notid' LIMIT 0,1", tbl_notifications::dbname);
	}

	/**
	 *
	 * MARK NOTIFICATION AS READ OR UNREAD
	 * @param unknown_type $notid Notification ID of the notification which has to be marked
	 * @param unknown_type $status Flag value specifying the status (1-Read,2-Unread)
	 * @return string SUCCESS on successful mark,FAILURE on failure
	 */
	public function marknotification($notid,$status)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_notifications::tblname." SET ".tbl_notifications::col_notifystatus."='$status' WHERE ".tbl_notifications::col_notifyid."='$notid'",tbl_notifications::dbname)==SUCCESS)
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
	 * DELETE A NOTIFICATION FROM THE DATABASE
	 * @param unknown_type $notid Notification ID of the notification to be deleted
	 * @return string SUCCESS on successful deletion,FAILURE on failure
	 */
	public function deletenotification($notid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_notifyid."='$notid'",tbl_notifications::dbname)==SUCCESS)
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
	 * READ NOTIFICATIONS SENT TO A USER
	 * @param unknown_type $uid User ID of person to whom the notification was sent
	 * @param unknown_type $status Notification Status (1-Read,2-Unread,3-All) (Defaults to 2)
	 * @param unknown_type $start Start Limit
	 * @param unknown_type $tot Total Limit
	 * @return string|Ambigous <string, unknown> A DB Array having all notifications on success,FAILURE on failure
	 */
	public function readnotifications($uid,$status="2",$start="0",$tot="15")
	{
		$dbobj=new ta_dboperations();
		if($status!="3")
		{
			$sql="SELECT * FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_uid."='$uid' AND ".tbl_notifications::col_notifystatus."='$status' ORDER BY ".tbl_notifications::col_notifytime." DESC";
		}
		else
		{
			$sql="SELECT * FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_uid."='$uid' ORDER BY ".tbl_notifications::col_notifytime." DESC";
		}
		
		$sql.=" LIMIT $start,$tot";
		
		$res=$dbobj->dbquery($sql,tbl_notifications::dbname);
		if(count($res)==0)
		{
			return EMPTY_RESULT;
		}
		else
		{
			return $res;
		}
	}
	
	/**
	 * 
	 * GET NOTIFICATION COUNT
	 * @param unknown $uid UID of the user whose notification is to be checked
	 * @param string $status Status to be fetched (1-Read,2-Unread,3-All)
	 */
	public function notifications_getcount($uid,$status="2")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT count(*) as total FROM ".tbl_notifications::tblname." WHERE ".tbl_notifications::col_uid."='$uid'";
		if($status!="3")
		{
			$sql.=" AND ".tbl_notifications::col_notifystatus."='$status'";
		}
		$res=$dbobj->dbquery($sql, tbl_notifications::dbname);
		return $res[0]["total"];
	}
	
	/**
	 * 
	 * CHECK IF A USER IS FOLLOWING A PERSON OR NOT
	 * @param unknown $uid UID of the user
	 * @param unknown $fuid UID of the person to check
	 * @return string BOOL_FAILURE if not, BOOL_SUCCESS if yes
	 */
	public function follower_check($uid,$fuid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_uid."='$uid' AND ".tbl_followdb::col_fuid."='$fuid'", tbl_followdb::dbname);
		if($res==0)
			return BOOL_FAILURE;
		else
			return BOOL_SUCCESS;
	}
	
	/**
	 * 
	 * FOLLOW A USER
	 * @param unknown $uid User ID
	 * @param unknown $fuid UID of the user to follow
	 * @return string|string/object Returns DBINSERT result
	 */
	public function follower_add($uid,$fuid)
	{
		$socialobj=new ta_socialoperations();
		$dbobj=new ta_dboperations();
		
		if($socialobj->follower_check($uid, $fuid))return BOOL_SUCCESS;
		$res=$dbobj->dbinsert("INSERT INTO ".tbl_followdb::tblname." (".tbl_followdb::col_uid.",".tbl_followdb::col_fuid.") VALUES ('$uid','$fuid')", tbl_followdb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * REMOVE A FOLLOWER
	 * @param unknown $uid UID of the user
	 * @param unknown $fuid UID of the user to be removed from follow
	 * @return string/object Returns DBDELETE result
	 */
	public function follower_remove($uid,$fuid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbdelete("DELETE FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_uid."='$uid' AND ".tbl_followdb::col_fuid."='$fuid'", tbl_followdb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * TOGGLE FOLLOW A USER
	 * @param unknown $uid UID of the user
	 * @param unknown $fuid UID of the person to follow or not
	 * @return Returns DBINSERT/DBDELETE result
	 */
	public function follower_toggle($uid,$fuid)
	{
		$socialobj=new ta_socialoperations();
		if($socialobj->follower_check($uid, $fuid))
		{
			$res=$socialobj->follower_remove($uid,$fuid);
		}
		else
		{
			$res=$socialobj->follower_add($uid,$fuid);
		}
		return $res;
	}
	
	/**
	 * 
	 * GET LIST OF PEOPLE A USER IS FOLLOWING
	 * @param unknown $uid UID of the user to be checked
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function following_get($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_uid."='$uid'", tbl_followdb::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET LIST OF PEOPLE WHO ARE FOLLOWING A USER
	 * @param unknown $fuid User ID
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function follower_get($fuid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_followdb::tblname." WHERE ".tbl_followdb::col_fuid."='$fuid'", tbl_followdb::dbname);
		return $res;
	}
	
	
	
	public function feed_get($uid,$lvl,&$start,&$tot,&$resarr=Array(),&$c=0,$actneed)
	{
		if(!isset($GLOBALS["myresarr"]))
		{
			$GLOBALS["myresarr"]=Array();
		}
		$msgobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		$audobj=new ta_audience();
		$logobj=new ta_logs();
		
		$totf=$socialobj->get_no_friends($uid);
		
		$start=intval($start);$tot=intval($tot);
		$cantot=$totf-$start;
		if($cantot<=0)
		{
			$start=0;
			$lvl++;
			$cantot=$totf-$start;
		}
		if($tot<0||$cantot<0)
		{
			$GLOBALS["mys"]=$start+$tot;
			$GLOBALS["mye"]=$tot;
			$GLOBALS["myl"]=$resarr;
			if($start+$tot>=$totf){$GLOBALS["mys"]=0;$GLOBALS["myl"]++;}
			return json_encode(Array('st'=>$start+$tot,'tot'=>$tot,'lvl'=>$lvl,'res'=>$resarr));
		}
		if($tot>$cantot)$tot=$cantot;
		
		$fres=$socialobj->getfriends($uid,$start,$tot);
		
		$chk=0;
		$inc=0;
		for($i=0;$i<count($fres);$i++)
		{
			$tuid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			$postres=$msgobj->get_recentpost($tuid,$lvl);
			$chk++;
			if(count($postres)==0)continue;
			$audid=$postres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
			if(!$audobj->audience_check_user($audid,$uid))continue;
			
			$tid=$postres[0][changesqlquote(tbl_message_outline::col_tid,"")];
			
			$c++;$inc++;
			$resarr[$tid]=$postres;
			$GLOBALS["myresarr"][$tid]=$postres;
		}
		
		if($c<$actneed&&count($fres)!=0)
		{
			if($inc==0&&$chk==$totf)
			{
				$GLOBALS["mys"]=0;
				$GLOBALS["mye"]=0;
				$GLOBALS["myl"]=0;
				if($start+$tot>=$totf){$GLOBALS["mys"]=0;$GLOBALS["myl"]++;}
				return json_encode(Array('st'=>'0','tot'=>'0','lvl'=>'0','res'=>$resarr));
			}
			
			$needed=$actneed-$c;
			$ns=$start+$tot;
			if($ns>$totf)
			{
				$lvl++;
				$ns=0;
				$socialobj->feed_get($uid,$lvl,$ns,$needed,$resarr,$c,$actneed);
			}
			else
			{
				$socialobj->feed_get($uid,$lvl,$ns,$needed,$resarr,$c,$actneed);
			}
		}
		else
		if($c==$actneed)
		{
			$GLOBALS["mys"]=$start+$tot;
			$GLOBALS["mye"]=$tot;
			$GLOBALS["myl"]=$lvl;
			if($start+$tot>=$totf){$GLOBALS["mys"]=0;$GLOBALS["myl"]++;}
			return json_encode(Array('st'=>$start+$tot,'tot'=>$tot,'lvl'=>$lvl,'res'=>$resarr));
		}
	}
	
	public function feed_getcustom($uid,$lvl,&$start,&$tot,&$resarr1=Array(),&$c=0,$actneed,$uaudid)
	{
		if(!isset($GLOBALS["myresarr1"]))
		{
			$GLOBALS["myresarr1"]=Array();
		}
		$msgobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		$audobj=new ta_audience();
		$logobj=new ta_logs();
	
		$totf=$socialobj->get_no_friends($uid);
	
		$start=intval($start);$tot=intval($tot);
		$cantot=$totf-$start;
		if($cantot<=0)
		{
			$start=0;
			$lvl++;
			$cantot=$totf-$start;
		}
		if($tot<0||$cantot<0)
		{
			$GLOBALS["mys1"]=$start+$tot;
			$GLOBALS["mye1"]=$tot;
			$GLOBALS["myl1"]=$resarr1;
			if($start+$tot>=$totf){$GLOBALS["mys1"]=0;$GLOBALS["myl1"]++;}
			return json_encode(Array('st'=>$start+$tot,'tot'=>$tot,'lvl'=>$lvl,'res'=>$resarr1));
		}
		if($tot>$cantot)$tot=$cantot;
	
		$fres=$socialobj->getfriends($uid,$start,$tot);
	
		$chk=0;
		$inc=0;
		for($i=0;$i<count($fres);$i++)
		{
			$tuid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			$chk++;
			if(!$audobj->audience_check_user($uaudid,$tuid))continue;
			$postres=$msgobj->get_recentpost($tuid,$lvl);
			if(count($postres)==0)continue;
			$audid=$postres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
			if(!$audobj->audience_check_user($audid,$uid))continue;
				
			$tid=$postres[0][changesqlquote(tbl_message_outline::col_tid,"")];
				
			$c++;$inc++;
			$resarr1[$tid]=$postres;
			$GLOBALS["myresarr1"][$tid]=$postres;
			$logobj->store_templogs("LOADED TH:".$tid);
		}
	
		if($c<$actneed&&count($fres)!=0)
		{
			if($inc==0&&$chk==$totf)
			{
				$GLOBALS["mys1"]=0;
				$GLOBALS["mye1"]=0;
				$GLOBALS["myl1"]=0;
				if($start+$tot>=$totf){$GLOBALS["mys1"]=0;$GLOBALS["myl1"]++;}
				return json_encode(Array('st'=>'0','tot'=>'0','lvl'=>'0','res'=>$resarr1));
			}
				
			$needed=$actneed-$c;
			$ns=$start+$tot;
			if($ns>$totf)
			{
				$lvl++;
				$ns=0;
				$socialobj->feed_get($uid,$lvl,$ns,$needed,$resarr1,$c,$actneed);
			}
			else
			{
				$socialobj->feed_get($uid,$lvl,$ns,$needed,$resarr1,$c,$actneed);
			}
		}
		else
			if($c==$actneed)
			{
				$GLOBALS["mys1"]=$start+$tot;
				$GLOBALS["mye1"]=$tot;
				$GLOBALS["myl1"]=$lvl;
				if($start+$tot>=$totf){$GLOBALS["mys1"]=0;$GLOBALS["myl1"]++;}
				return json_encode(Array('st'=>$start+$tot,'tot'=>$tot,'lvl'=>$lvl,'res'=>$resarr1));
			}
	}
	
	
	/**
	 * 
	 * ADD AN IMPORTED CONTACT TO THE DB
	 * @param unknown $uid UID who is importing
	 * @param unknown $uname
	 * @param unknown $email
	 * @param string $prodid
	 * @param string $prodflag
	 * @param string $jsondata
	 */
	public function importer_add($uid,$uname,$email,$prodid="",$prodflag="1",$jsondata="")
	{
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		$socialobj=new ta_socialoperations();
		
		if($email=="")return;
		if(count($socialobj->importer_get($uid,tbl_user_import::col_email,$email))!=0)return;
		
		if($jsondata!="")
		{
			$jsonid=$utilityobj->jsondata_add($jsondata);
		}
		else
		{
			$jsonid="";
		}
		
		if($prodid=="")$prodid="-1";
		
		$dbobj->dbinsert("INSERT INTO ".tbl_user_import::tblname." (".tbl_user_import::col_uid.",".tbl_user_import::col_usrname.",".tbl_user_import::col_email.",".tbl_user_import::col_jsonid.",".tbl_user_import::col_prodid.",".tbl_user_import::col_prodflag.") VALUES 
				('$uid','$uname','$email','$jsonid','$prodid','$prodflag')", tbl_user_import::dbname);
	}
	
	public function importer_invite($uid,$prodflag="1",$email="",$prodid="",$msg="",$subject="")
	{
		$dbobj=new ta_dboperations();
		$mailobj=new ta_mailclass();
		$userobj=new ta_userinfo();
		
		$fullname=$userobj->user_getfullname($uid);
		$userobj->user_initialize_data($uid);
		
		$gendercall="him";
		if($userobj->gender=="f")
		{
			$gendercall="her";
		}
		
		if($email==""&&$prodid=="")
		{
			$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_import::tblname." WHERE ".tbl_user_import::col_uid."='$uid' AND ".tbl_user_import::col_prodflag."='$prodflag'", tbl_user_import::dbname);
			for($i=0;$i<count($res);$i++)
			{
				$emailaddr=$res[$i][changesqlquote(tbl_user_import::col_email,"")];
				$usrname=$res[$i][changesqlquote(tbl_user_import::col_usrname,"")];
				
				
				if($msg=="")
				{
					$msg='
				Hey '.$usrname.' !<br><br>
				'.$fullname.' has invited you to join '.$gendercall.' at <a href="https://www.friendbus.com">Friendbus</a>.
				<br><br>
				Friendbus is a modern generation social networking website where you have impact on everything. You can have conversations, upload & share files, organize stuff, protect data, scan files and much more and all this with <b>Complete Privacy Control</b>.
				<br><br>
				So, why wait? People are waiting for you to join.. Visit www.friendbus.com
			';
				}
				
				if($subject=="")
				{
					$subject="Invitation to join Friendbus";
				}
				
				$mailobj->sendmail($emailaddr,$msg,$subject);
			}
		}
	}
	
	public function importer_get($uid,$colname,$colval)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_user_import::tblname." WHERE ".tbl_user_import::col_uid."='$uid' AND ".$colname."='$colval'", tbl_user_import::dbname);
	}
	
	/**
	 * 
	 * CONVERT EMAIL ADDRESS TO USER INFO
	 * @param unknown $email
	 * @param string $start
	 * @param string $tot
	 */
	public function emailtouser($email,$start="0",$tot="1")
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_uemail."='$email' LIMIT $start,$tot", tbl_user_info::dbname);
	}
}