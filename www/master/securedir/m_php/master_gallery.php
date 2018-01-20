<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO GALLERY
 * @author T.V.VIGNESH
 *
 */
class ta_galleryoperations
{
	/**
	 *
	 * CREATES A USER GALLERY OF GIVEN SPECIFICATIONS (AUDIENCE PUBLIC BY DEFAULT)
	 * @param unknown_type $galname Gallery Name
	 * @param unknown_type $galdesc Gallery Description
	 * @param unknown_type $uid User ID of person creating the gallery
	 * @param unknown_type $galtype Gallery Type (0-DEFAULT MIXED,1-MIXED,2-PHOTO,3-DOCS,4-VIDEO,5-AUDIO,6-FAVOURITE GALLERY,7-CUSTOM GALLERY,8-THUMBNAIL GALLERY,9-Vidproc,10-Docproc,11-Audproc,12-ScanReports,13-Metadata,14-Vault,15-Sandbox,16-Attachments) (Defaults to 1)
	 * @param unknown_type $parentgalid Gallery ID of the parent gallery (Defaults to "" which specifies no parent)
	 * @param unknown_type $galpic Cover PIC of Gallery (Takes in a URL)
	 * @param unknown_type $galflag Allowed Value (1-Allowed,2-Under Review,3-Blocked)
	 * @param unknown_type $tagid Tag ID from TAGDB for tagging the gallery
	 * @return Ambigous <string, unknown>|string Returns Gallery ID
	 */
	public function creategallery($galname,$galdesc,$uid="",$galtype="1",$parentgalid="",$galpic="",$galflag="1",$tagid="")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$audienceobj=new ta_audience();
		$userobj=new ta_userinfo();
		
		if(intval($galtype)<0||intval($galtype)>20)return FAILURE;
		
		$dbobj->transaction_start();
		$galid=$uiobj->randomstring(20,tbl_galinfo::dbname,tbl_galinfo::tblname,tbl_galinfo::col_galid);
		$audienceid=$audienceobj->audience_create();
		$rateid=$socialobj->rating_init(3);
		
		if($uid!="")
		{
			$userobj->user_initialize_data($uid);
		}
		else
		if($userobj->userinit()==FAILURE)
		{
			return FAILURE;
		}
		
		if(!mkdir($userobj->docroot."/gal/".$galid,0777,BOOL_SUCCESS))
		{
			return FAILURE;
		}
		
		$dbobj->dbinsert("INSERT INTO ".tbl_galinfo::tblname." (".tbl_galinfo::col_uid.",".tbl_galinfo::col_galid.",".tbl_galinfo::col_galname.",".tbl_galinfo::col_galdesc.",".tbl_galinfo::col_galflag.",".tbl_galinfo::col_galtype.",".tbl_galinfo::col_galpic.",".tbl_galinfo::col_tagid.",".tbl_galinfo::col_rateid.",".tbl_galinfo::col_audienceid.") VALUES
				('$uid','$galid','$galname','$galdesc','$galflag','$galtype','$galpic','$tagid','$rateid','$audienceid')",tbl_galinfo::dbname);
			
		if($dbobj->commit())
		{
			return $galid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000053');
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * ADD JSON ID TO A GALLERY
	 * @param unknown $galid GALID of the gallery
	 * @param unknown $jsondata The JSON data to be added
	 */
	public function gallery_add_json($galid,$jsondata)
	{
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$jsonid=$utilityobj->jsondata_add($jsondata);
		return $galobj->editgalleryinfo($galid,tbl_galinfo::col_jsonid,$jsonid);
	}
	
	/**
	 * 
	 * GET ALL GALLERIES CREATED BY A USER
	 * @param unknown $uid UID of the user
	 * @return Ambigous <string, unknown>
	 */
	public function gallery_get_user($uid,$galtype="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_uid."='$uid'";
		if($galtype!="")
		{
			$sql.=" AND ".tbl_galinfo::col_galtype."='$galtype'";
		}
		$sql.="ORDER BY ".tbl_galinfo::col_galtime." ASC";
		$res=$dbobj->dbquery($sql,tbl_galinfo::dbname);
		return $res;
	}
	
	/**
	 * 
	 * GET ALL MEDIA INFO ASSOCIATED WITH A GALLERY
	 * @param unknown $galid Gallery ID
	 * @return Ambigous <string, unknown> Returns DBARRAY of media
	 */
	public function gallery_getmedia($galid,$mediatype="",$start="0",$tot="15")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_galid."='$galid'";
		if($mediatype!="")
		{
			$sql.=" AND ".tbl_galdb::col_mediatype."='$mediatype'";
		}
		$sql.=" ORDER BY ".tbl_galdb::col_mediatime." DESC LIMIT $start,$tot";
		$res=$dbobj->dbquery($sql, tbl_galdb::dbname);
		return $res;
	}

	/**
	 *
	 * GET THE AUDIENCE ID OF THE GALLERY
	 * @param unknown $galid The GALID of the gallery whose audience is to be found
	 * @return unknown The AUDIENCEID for the gallery
	 */
	public function get_audience_gallery($galid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid'",tbl_galinfo::dbname);
		$audienceid=$dbobj->colval($res,tbl_galinfo::col_audienceid,0);
		return $audienceid;
	}

	/**
	 *
	 * GET THE AUDIENCE ID OF THE GALLERY
	 * @param unknown $galid The GALID of the gallery whose audience is to be found
	 * @return unknown The AUDIENCEID for the gallery
	 */
	public function get_audience_media($mediaid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid'",tbl_galdb::dbname);
		$audienceid=$dbobj->colval($res,tbl_galdb::col_audienceid,0);
		return $audienceid;
	}

	/**
	 * 
	 * GET INFORMATION ABOUT A GALLERY
	 * @param unknown $galid GALID of the gallery
	 */
	public function get_gallery_info($galid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' LIMIT 0,1",tbl_galinfo::dbname);
		return $res;
	}
	
	/**
	 *
	 * DELETE A GALLERY AND ITS CONTENTS
	 * @param unknown_type $galid GALLERY ID of the gallery to be deleted
	 * @param unknown_type $uid UID of person who owns the gallery
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletegallery($galid,$uid)
	{
		$dbobj=new ta_dboperations();
		$galobj=new ta_galleryoperations();
		$userobj=new ta_userinfo();
		$fileobj=new ta_fileoperations();
		$socialobj=new ta_socialoperations();
		$audienceobj=new ta_audience();
		
		$userobj->user_initialize_data($uid);
		
		if(count($dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' AND ".tbl_galinfo::col_uid."='$uid'", tbl_galinfo::dbname))==0)
		{
			return FAILURE;
		}
		
		$galres=$galobj->get_gallery_info($galid);
		$res=$galobj->gallery_getmedia($galid);
		$rateid=$galres[0][changesqlquote(tbl_galinfo::col_rateid,"")];
		$audienceid=$galres[0][changesqlquote(tbl_galinfo::col_audienceid,"")];
		$socialobj->rating_delete($rateid);
		$audienceobj->audience_remove($audienceid);
		
		for($i=0;$i<count($res);$i++)
		{
			$mediaid=$res[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
			$galobj->deletemedia($mediaid,$galid);
		}
		
		if($dbobj->dbdelete("DELETE FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' AND ".tbl_galinfo::col_uid."='$uid'",tbl_galinfo::dbname)==SUCCESS)
		{
			$dirpath=$userobj->docroot."/gal/".$galid."/";
			$fileobj->delTree($dirpath);
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000054');
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * GET INFORMATION REGARDING A MEDIA
	 * @param unknown $mediaid Media ID
	 * @return Ambigous <string, unknown> Returns DBARRAY result
	 */
	public function media_get_info($mediaid)
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1";
		return $dbobj->dbquery($sql, tbl_galdb::dbname);
	}
	
	/**
	 *
	 * DELETE A MEDIA FROM THE GALLERY GIVEN THE MEDIA ID
	 * @param unknown_type $mediaid Media ID of media to be deleted
	 * @param unknown_type $galid Galid where the media belongs
	 * @return string SUCCESS on successful deletion, "" on failure
	 */
	public function deletemedia($mediaid,$galid="")
	{
		$dbobj=new ta_dboperations();
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		
		$res=$galobj->media_get_info($mediaid);
		if(count($res)==0)return SUCCESS;
		$mediaurl=$res[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
		$mediatype=$res[0][changesqlquote(tbl_galdb::col_mediatype,"")];
		$mediathumb=$res[0][changesqlquote(tbl_galdb::col_mediathumb,"")];
		$jsonid=$res[0][changesqlquote(tbl_galdb::col_jsonid,"")];
		$uid=$res[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		
		if($mediatype=="3"&&$jsonid!="")
		{
			$jsonobj=$utilityobj->jsondata_get($jsonid);
			if(isset($jsonobj->formats))
			{
				$galid_vidpro=$galobj->get_galid_special($uid,"9");
				foreach ($jsonobj->formats as $key=>$value)
				{
					$medid=$value;
					$galobj->deletemedia($medid,$galid_vidpro);
				}
			}
			if(isset($jsonobj->metafile))
			{
				$galid_meta=$galobj->get_galid_special($uid,"13");
				$galobj->deletemedia($jsonobj->metafile,$galid_meta);
			}
			$utilityobj->jsondata_remove($jsonid);
		}
		
		if($mediatype=="2"&&$jsonid!="")
		{
			$jsonobj=$utilityobj->jsondata_get($jsonid);
			if(isset($jsonobj->formats))
			{
				$galid_docpro=$galobj->get_galid_special($uid,"10");
				foreach ($jsonobj->formats as $key=>$value)
				{
					$medid=$value;
					$galobj->deletemedia($medid,$galid_docpro);
				}
			}
			if(isset($jsonobj->metafile))
			{
				$galid_meta=$galobj->get_galid_special($uid,"13");
				$galobj->deletemedia($jsonobj->metafile,$galid_meta);
			}
			$utilityobj->jsondata_remove($jsonid);
		}
		
		if($mediatype=="4"&&$jsonid!="")
		{
			$jsonobj=$utilityobj->jsondata_get($jsonid);
			if(isset($jsonobj->formats))
			{
				$galid_audpro=$galobj->get_galid_special($uid,"11");
				foreach ($jsonobj->formats as $key=>$value)
				{
					$medid=$value;
					$galobj->deletemedia($medid,$galid_audpro);
				}
			}
			if(isset($jsonobj->metafile))
			{
				$galid_meta=$galobj->get_galid_special($uid,"13");
				$galobj->deletemedia($jsonobj->metafile,$galid_meta);
			}
			$utilityobj->jsondata_remove($jsonid);
		}
		if($galid!="")
		$sql="DELETE FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' AND ".tbl_galdb::col_galid."='$galid'";
		else
		$sql="DELETE FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid'";
		if($dbobj->dbdelete($sql,tbl_galdb::dbname)=="SUCCESS")
		{
			unlink(ROOT_SERVER.$mediathumb);
			unlink($mediaurl);
			return SUCCESS;
		}
		else
		{
			throw new Exception('#ta@0000000_0000055');
			return FAILURE;
		}
	}
	
	/**
	 *
	 * ADD A MEDIA TO THE GALLERY
	 * @param unknown_type $url URL of the Media to be added
	 * @param unknown_type $galid Gallery ID where media has to be added
	 * @param unknown_type $mediatype Type of media being added (-1-Unknown,0-Thumbnail,1-Photo,2-Document,3-Video,4-Audio)
	 * @param unknown_type $title Title of the Media Being Added (Defaults to "")
	 * @param unknown_type $desc Description of the Media (Defaults to "")
	 * @param unknown_type $shareid List ID FROM LISTDB to share the media with
	 * @param unknown_type $tagid TAG ID from TAG DB to tag the media
	 * @param unknown_type $flag Flag value of media
	 * @param unknown_type $jsonid Json ID for meta data
	 * @param unknown_type $mediaid Media ID predefined (optional)
	 * @param unknown_type $uid UID of the person who is adding the media (optional)
	 * @return Ambigous <string, unknown>|string The Media ID of media being added
	 */
	public function addmediatogal($url,$galid,$mediatype,$thumburl="",$title="",$mediadesc="",$shareid="",$tagid="",$flag="1",$jsonid="",$mediaid="",$uid="")
	{
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		$audienceobj=new ta_audience();
		$userobj=new ta_userinfo();
		$fileobj=new ta_fileoperations();
		$galobj=new ta_galleryoperations();
	
		$title=preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '_', $title);
		$title = preg_replace("([\.]{2,})", '_', $title);
		
		$uid_galowner=$galobj->get_gallery_owner($galid);
		if($mediaid=="")
		{$mediaid=$galobj->generate_mediaid();}
		else
		{
			$result=$galobj->media_get_info($mediaid);
			if(count($result)!=0)return FAILURE;
			
		}
		
		$fext=$fileobj->fileinfo($url,"3");
		$filename=$fileobj->fileinfo($url,"2");
		
		if($uid=="")
		{$myres=$userobj->userinit();}
		else
		{$myres=$userobj->user_initialize_data($uid);}
		if($userobj->uid!=$uid_galowner)
		{
			//the user is not the owner of this gallery
			return FAILURE;
		}
		if($myres!=FAILURE)
		{rename(ROOT_SERVER.$url,$userobj->docroot."/gal/".$galid."/".$mediaid.".".$fext);}
		else
		{return FAILURE;}
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid'",tbl_galinfo::dbname);
		if(count($res)==0)
		{throw new Exception('#ta@0000000_0000122');}

		$audienceid=$dbobj->colval($res,tbl_galinfo::col_audienceid,0);
		//$audienceid=$audienceobj->audience_copy($audienceid);

		$sql="INSERT INTO ".tbl_galdb::tblname." (".tbl_galdb::col_galid.",".tbl_galdb::col_mediadesc.",".tbl_galdb::col_mediaid.",".tbl_galdb::col_audienceid.",".tbl_galdb::col_mediatype.",".tbl_galdb::col_mediaurl.",".tbl_galdb::col_tagid.",".tbl_galdb::col_mediatitle.",".tbl_galdb::col_mediaflag.",".tbl_galdb::col_mediathumb.",".tbl_galdb::col_jsonid.",".tbl_galdb::col_mediauid.",".tbl_galdb::col_fname.",".tbl_galdb::col_fext.")
				VALUES ('$galid','$mediadesc','$mediaid','$audienceid','$mediatype','$url','$tagid','$title','$flag','$thumburl','$jsonid','$userobj->uid','$filename','$fext')";
		
		if($dbobj->dbinsert($sql,tbl_galdb::dbname)=="SUCCESS")
		{
			return $mediaid;
		}
		else
		{
			throw new Exception('#ta@0000000_0000056');
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * GENERATE A NEW MEDIAID FOR ANY NEW MEDIA TO BE UPLOADED
	 */
	public function generate_mediaid()
	{
		$uiobj=new ta_uifriend();
		$mediaid=$uiobj->randomstring(30,tbl_galdb::dbname,tbl_galdb::tblname,tbl_galdb::col_mediaid);
		return $mediaid;
	}
	
	/**
	 * 
	 * GET EXTENSION OF MEDIA
	 * @param unknown $mediaid Media ID of media to check
	 * @return Ambigous Returns extension
	 */
	public function get_media_ext($mediaid)
	{
		$galobj=new ta_galleryoperations();
		$dbobj=new ta_dboperations();
		$res=$galobj->media_get_info($mediaid);
		if(count($res)!=0)
		{
			$ext=$res[0][changesqlquote(tbl_galdb::col_fext)];
			return $ext;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 *
	 * GET TYPE FLAG OF MEDIA
	 * @param unknown $mediaid Media ID of media to check
	 * @return Ambigous Returns Flag value of media
	 */
	public function get_media_typeflag($mediaid)
	{
		$galobj=new ta_galleryoperations();
		$dbobj=new ta_dboperations();
		$res=$galobj->media_get_info($mediaid);
		if(count($res)!=0)
		{
			$mediatype=$res[0][changesqlquote(tbl_galdb::col_mediatype)];
			return $mediatype;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * GET GALID of special gallery types
	 * @param unknown $uid UID of the user
	 * @param string $galtype Type of gallery (0-DEFAULT MIXED,8-THUMBNAILS,9-PROCESSED VIDEOS)
	 * @return string
	 */
	public function get_galid_special($uid,$galtype="0")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_uid."='$uid' AND ".tbl_galinfo::col_galtype."='$galtype' LIMIT 0,1",tbl_galinfo::dbname);
		if(count($res)==0)return FAILURE;
		return $res[0][changesqlquote(tbl_galinfo::col_galid,"")];
	}
	
	/**
	 * 
	 * GET URL OF A GALLERY
	 * @param unknown $uid UID of the user
	 * @param unknown $galid GALID of the gallery
	 * @param string $urltype 1-Absolute URL sys path, 2-Host URL
	 */
	public function geturl_gallery($uid,$galid,$urltype="1")
	{
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		
		$userobj->user_initialize_data($uid);
		
		$absurl=$userobj->docroot."/gal/".$galid;
		$hosturl=$utilityobj->pathtourl($absurl);		
		if($urltype=="1")
			return $absurl;
		else
		if($urltype=="2")
			return $hosturl;
	}
	
	/**
	 *
	 * GET GALID OF THUMBS GALLERY
	 * @param unknown $uid UID of the user
	 * @return The GALID of the thumbs gallery
	 */
	public function get_gallery_thumbs($uid)
	{
		$dbobj=new ta_dboperations();
	
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_uid."='$uid' AND ".tbl_galinfo::col_galtype."='8'", tbl_galinfo::dbname);
		$galid=$res[0][changesqlquote(tbl_galinfo::col_galid,"")];
		return $galid;
	}
	
	/**
	 *
	 * GET URL OF A MEDIA
	 * @param unknown $galid GALID of the gallery
	 * @param unknown $mediaid MediaID of the media
	 * @param string $urltype 1-Absolute URL sys path, 2-Host URL, 3-From DB
	 */
	public function geturl_media($galid,$mediaid,$urltype="1",$width="",$height="")
	{
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		
		$res=$galobj->media_get_info($mediaid);
		$mediatype=$galobj->get_media_typeflag($mediaid);
		
		if($urltype=="3")
			return $res[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
		
		if($mediatype=="0")
		{
			$absurl=$res[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
		}
		else
		{
			$fext=$res[0][changesqlquote(tbl_galdb::col_fext)];
			$uid=$res[0][changesqlquote(tbl_galdb::col_mediauid)];
			if($uid=="")return FAILURE;
			$userobj->user_initialize_data($uid);
			$absurl=$userobj->docroot."/gal/".$galid."/".$mediaid.".".$fext;
		}
		$hosturl=$utilityobj->pathtourl($absurl);
		if($urltype=="1")
			return $absurl;
			else
		if($urltype=="2")
			return $hosturl;
	}
	
	/**
	 * 
	 * ADD META DATA TO A MEDIA
	 * @param unknown $galid GALID of the gallery where media exists
	 * @param unknown $mediaid MediaID of the media
	 * @param unknown $jsondata Json DATA to be inserted (as string)
	 * @return Ambigous <string, string/object> Returns DBUPDATE result
	 */
	public function media_addmeta($galid,$mediaid,$jsondata)
	{
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		$jsonid=$utilityobj->jsondata_add($jsondata);
		return $galobj->editmediainfo($mediaid,$galid,tbl_galdb::col_jsonid,$jsonid);
	}

	/**
	 * 
	 * REMOVE META DATA FROM A MEDIA
	 * @param unknown $galid Galid of the Gallery
	 * @param unknown $mediaid Media ID of the Media to be added
	 * @return string/object Returns DBDELETE result
	 */
	public function media_removemeta($galid,$mediaid)
	{
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		
		$res=$galobj->media_get_info($mediaid);
		$jsonid=$res[0][changesqlquote(tbl_galdb::col_jsonid,"")];
		$galobj->editmediainfo($mediaid,$galid,tbl_galdb::col_jsonid,"");
		return $utilityobj->jsondata_remove($jsonid);
	}
	
	/**
	 *
	 * SHARE A MEDIA GIVEN WITH USERS
	 * @param unknown_type $mediaid Media ID of media to be shared
	 * @param unknown_type $uidarray Array having UID of users to share the media with
	 * @param unknown_type $uid UID of person sharing the media
	 * @param unknown_type $flag Restriction of List (1-Allowed,2-Under Review,3-Blocked)
	 * @return string SUCCESS on successful share, "" on failure
	 */
	//TODO SHARE MEDIA WITH USERS
	public function sharemediawithusers($mediaid,$uidarray,$uid,$flag="1")
	{
// 		$socialobj=new ta_socialoperations();
// 		$listid=$socialobj->createlist("MEDSHARE_".$mediaid,"3","MediaShare:".time(),$flag,$uid);
// 		if($socialobj->addtolist($uidarray,$listid, $uid)=="SUCCESS")
// 		{
// 			$dbobj=new ta_dboperations();
// 			if($dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_shareid."='$listid' WHERE ".tbl_galdb::col_mediaid."='$mediaid'",tbl_galdb::dbname)=="SUCCESS")
// 			{
// 				return SUCCESS;
// 			}
// 			else
// 			{
// 				throw new Exception('#ta@0000000_0000057');
// 				return FAILURE;
// 			}
// 		}
	}

	/**
	 *
	 * TAG A MEDIA WITH USERS
	 * @param unknown_type $mediaid MEDIA ID of media to be tagged
	 * @param unknown_type $tagarray Array having people to be tagged with. Associative (tagname,tagid)
	 * @param unknown_type $uid UID of person tagging others
	 * @return string TAG ID on success, "" on failure
	 */
	public function tagmediawithusers($mediaid,$tagarray,$uid)
	{
		$socialobj=new ta_socialoperations();
		$tagid=$socialobj->addtag($tagarray,$uid,"5");
		$dbobj=new ta_dboperations();
		if($dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_tagid."='$tagid' WHERE ".tbl_galdb::col_mediaid."='$mediaid'",tbl_galdb::dbname)=="SUCCESS")
		{
			return $tagid;
		}
		else
		{
			if($socialobj->removetag($tagid,$uid,"")=="SUCCESS")
			{
				throw new Exception('#ta@0000000_0000058');
				return FAILURE;
			}
			else
			{
				throw new Exception('#ta@0000000_0000059');
				return FAILURE;
			}
			throw new Exception('#ta@0000000_0000058');
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVES A USER TAG FROM A MEDIA
	 * @param unknown_type $mediaid MEDIA ID of the media
	 * @param unknown_type $tagid TAG ID of TAG BEING MADE
	 * @param unknown_type $fuid UID of person tagging the other person
	 * @param unknown_type $tuid UID of person being tagged (Defaults to "" which means remove all tags)
	 * @return string SUCCESS on successfull removal, "" on failure
	 */
	public function removemediatagusers($mediaid,$tagid,$fuid,$tuid="")
	{
		$socialobj=new ta_socialoperations();
		if($socialobj->removetag($tagid,$fuid,$tuid)=="SUCCESS")
		{
			if($tuid=="")
			{
				$dbobj=new ta_dboperations();
				if($dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_tagid."='' WHERE ".tbl_galdb::col_mediaid."='$mediaid' AND ".tbl_galdb::col_tagid."='$tagid'",tbl_galdb::dbname)=="SUCCESS")
				{
					return SUCCESS;
				}
				else
				{
					throw new Exception('#ta@0000000_0000060');
					return FAILURE;
				}
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000061');
			return FAILURE;
		}
	}

	/**
	 *
	 * EDIT MEDIA INFORMATION
	 * @param unknown_type $mediaid MEDIA ID of media to be edited
	 * @param unknown_type $galid GALID of gallery to which the Media belongs
	 * @param unknown_type $colname Name of column to be edited
	 * @param unknown_type $value Value to be inserted for the respective column
	 * @return string SUCCESS on successful editing, "" on failure
	 */
	public function editmediainfo($mediaid,$galid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".$colname."='$value' WHERE ".tbl_galdb::col_mediaid."='$mediaid' AND ".tbl_galdb::col_galid."='$galid'",tbl_galdb::dbname);		
	}

	/**
	 *
	 * EDIT GALLERY INFORMATION
	 * @param unknown_type $galid GAL ID of the gallery to be edited
	 * @param unknown_type $colname Name of column to be edited
	 * @param unknown_type $value Value to be inserted in the respective column
	 * @return string SUCCESS on successful editing, "" on failure
	 */
	public function editgalleryinfo($galid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbupdate("UPDATE ".tbl_galinfo::tblname." SET ".$colname."='$value' WHERE ".tbl_galinfo::col_galid."='$galid'",tbl_galinfo::dbname);
		
		return $res;
	}

	/**
	 *
	 * GET UID of the person who created the gallery
	 * @param unknown $galid The GALID of the gallery to be checked
	 * @return unknown The UID of the gallery owner on success
	 */
	public function get_gallery_owner($galid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' LIMIT 0,1",tbl_galinfo::dbname);
		$uid=$dbobj->colval($res,tbl_galinfo::col_uid,0);
		return $uid;
	}

	/**
	 *
	 * GET THE OWNER OF THE MEDIA
	 * @param unknown $mediaid The Media ID of the media to be checked
	 * @return unknown The UID of the media owner on success
	 */
	public function get_media_owner($mediaid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1",tbl_galdb::dbname);
		$galid=$dbobj->colval($res,tbl_galdb::col_galid,0);
		$uid=$this->get_gallery_owner($galid);
		return $uid;
	}

	/**
	 *
	 * GETS THE NUMBER OF GALLERIES OWNED BY THE USER
	 * @param unknown $uid The UID of the user
	 * @return number The number of galleries owned on success
	 */
	public function get_no_gallery_user($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_uid."='$uid'",tbl_galinfo::dbname);
		return count($res);
	}

	/**
	 *
	 * GET ALL THE GALLERY CHILDREN OF A GALLERY
	 * @param unknown_type $galid GALID of the gallery whose children are to be retrieved
	 * @return Ambigous <string, unknown>|string A DB Array having all children on success, FAILURE on failure
	 */
	public function get_children_gallery($galid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_parentgalid."='$galid'",tbl_galinfo::dbname);
		return $res;
	}

	/**
	 *
	 * GET ALL THE MEDIA CHILDREN OF A GALLERY
	 * @param unknown_type $galid GALID of the gallery whose children are to be retrieved
	 * @return Ambigous <string, unknown>|string A DB Array having all children on success, FAILURE on failure
	 */
	public function get_children_media($galid,$medtype="")
	{
		$dbobj=new ta_dboperations();
		$sql="SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_galid."='$galid'";
		if($medtype!="")
		{
			$sql.=" AND ".tbl_galdb::col_mediatype."='$medtype'";
		}
		$res=$dbobj->dbquery($sql,tbl_galdb::dbname);
		return $res;
	}

	/**
	 *
	 * GET THE GALID OF THE PARENT GALLERY
	 * @param unknown $galid GALID of the gallery to be checked for its parent
	 * @return string|unknown The GALID of the parent
	 */
	public function get_parent_gallery($galid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' LIMIT 0,1",tbl_galinfo::dbname);
		if($res==EMPTY_RESULT)return FAILURE;
		$parentgalid=$res[0][changesqlquote(tbl_galinfo::col_parentgalid,"")];
		return $parentgalid;
	}
	
	

	/**
	 *
	 * CREATES A CUSTOM GALLERY (AS SPECIFIED BY USER)
	 * @param unknown_type $uid User ID of person creating the gallery
	 * @param unknown_type $customgalname Custom Gallery Name
	 * @param unknown_type $customgaldesc Custom gallery description
	 * @param unknown_type $parentcustomgalid Gallery ID of the parent custom gallery
	 * @param unknown_type $customgalpic Cover Pic of the custom gallery
	 * @param unknown_type $shareid List ID from LISTDB for sharing
	 * @param unknown_type $tagid Tag ID from TAGDB for tagging the gallery
	 * @param unknown_type $customgalflag Allowed Value (1-Allowed,2-Under Review,3-Blocked)
	 * @return Ambigous <Ambigous, string, unknown> Returns Custom Gallery ID
	 */
	public function createcustomgal($uid,$customgalname,$customgaldesc="My gallery",$parentcustomgalid="",$customgalpic="",$shareid="",$tagid="",$customgalflag="1")
	{
		$galobj=new ta_galleryoperations();
		$customgalid=$galobj->creategallery($customgalname,$customgaldesc,"1",$uid,"7",$parentcustomgalid,$customgalpic,$customgalflag,$shareid,$tagid);
		return $customgalid;
	}

	/**
	 *
	 * CREATE A NEW NOTE (ONLY TEXT)
	 * @param unknown $uid User ID of the person creating the note
	 * @param unknown $text Text contents of the note
	 * @param string $priority Priority of the note assigned by the user
	 * @param string $notetype Flag value specifying Type of note assigned by the user (1-random,2-meeting,3-educational,etc)
	 * @param string $noteshare 1-private,2-public,Others-List ID from List DB
	 * @return string Note ID on successful creation, FAILURE on failure
	 */
	public function newnote($uid,$text,$priority="1",$notetype="1",$noteshare="")
	{
		$uiobj=new ta_uifriend();
		$noteid=$uiobj->randomstring(30,tbl_notesdb::dbname,tbl_notesdb::tblname,tbl_notesdb::col_noteid);
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_notesdb::tblname." (".tbl_notesdb::col_noteid.",".tbl_notesdb::col_uid.",".tbl_notesdb::col_notetext.",".tbl_notesdb::col_notepriority.",".tbl_notesdb::col_notetype.",".tbl_notesdb::col_noteshare.")
				VALUES ('$noteid','$uid','$text','$priority','$notetype','$noteshare')",tbl_notesdb::dbname)==SUCCESS)
		{
			return $noteid;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * EDIT AN EXISTING NOTE
	 * @param unknown $noteid The Note ID of the note
	 * @param unknown $uid User ID of the person who created the note
	 * @param unknown $colname Name of column to be edited
	 * @param unknown $value Value to be inserted in the respective column
	 * @return string SUCCESS on successful edit, FAILURE on failure
	 */
	public function editnote($noteid,$uid,$colname,$value)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbupdate("UPDATE ".tbl_notesdb::tblname." SET ".$colname."='$value' WHERE ".tbl_notesdb::col_noteid."='$noteid' AND ".tbl_notesdb::col_uid."='$uid' LIMIT 0,1",tbl_notesdb::dbname);break;
		return $res;
	}

	/**
	 *
	 * DELETE AN EXISTING NOTE
	 * @param unknown $noteid Note ID of the note to be deleted
	 * @param unknown $uid User ID of the person who created the note
	 * @return string SUCCESS on successful deletion,FAILURE on failure
	 */
	public function deletenote($noteid,$uid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_notesdb::tblname." WHERE ".tbl_notesdb::col_noteid."='$noteid' AND ".tbl_notesdb::col_uid."='$uid'",tbl_notesdb::dbname)==SUCCESS)
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
	 * GET MEDIA ID OF A SPECIFIC FORMAT FOR A MEDIA
	 * @param unknown $mediaid The media ID of the original video
	 * @param unknown $format The format to get (eg.flv)
	 */
	public function video_get_format_medid($mediaid,$format)
	{
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$res=$galobj->media_get_info($mediaid);
		if(count($res)==0)return FAILURE;
		$jsonid=$res[0][changesqlquote(tbl_galdb::col_jsonid,"")];
		$jsonobj=$utilityobj->jsondata_get($jsonid);
		if(!isset($jsonobj->formats->$format))return FAILURE;
		return $jsonobj->formats->$format;
	}
	
	/**
	 * 
	 * GET TOTAL DURATION OF A VIDEO IN SECONDS
	 * @param unknown $vidpath The path of video to be processed  
	 * @return number|string The duration in seconds on success, FAILURE on failure
	 */
	public function video_get_duration($vidpath)
	{
		$sysobj=new ta_system();
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		
		$time =  $sysobj->shellcmd("ffmpeg -i $vidpath 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
		
		$logobj->store_templogs("GET VID TIME:".$time);
		
		$duration = explode(":",$time);
		$duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);
		
		return $duration_in_seconds;
	}
	
	/**
	 *
	 * GET TOTAL DURATION OF A VIDEO IN SECONDS
	 * @param unknown $vidpath The path of video to be processed
	 * @return number|string The duration in seconds on success, FAILURE on failure
	 */
	public function audio_get_duration($audpath)
	{
		$sysobj=new ta_system();
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
	
		$time =  $sysobj->shellcmd("ffmpeg -i $audpath 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
	
		$logobj->store_templogs("GET AUD TIME:".$time);
	
		$duration = explode(":",$time);
		$duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);
	
		return $duration_in_seconds;
	}
	
	/**
	 *
	 * GET TOTAL DURATION OF A VIDEO IN SECONDS
	 * @param unknown $vidpath The path of video to be processed
	 * @return number|string The duration in seconds on success, FAILURE on failure
	 */
	public function video_get_bitrate($vidpath)
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i \"{$vidpath}\" 2>&1";
		$ret = $sysobj->shellcmd($cmd);
		if (preg_match('/bitrate: ((\d+))/s',$ret, $bitrate))
		{
			$bitrate = $bitrate[0];
			return $bitrate;
		}
		else
		{
			return FAILURE;
		}
	}
	
	public function video_add_watermark($vidpath,$watermarkpath,$oppath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $vidpath -vhook '/usr/lib/vhook/watermark.so -f $watermarkpath -m 1 -t 222222' -an $oppath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function video_get_audio($vidpath,$audiopath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $vidpath -vn -acodec copy $audiopath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function video_get_info($vidpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i \"{$vidpath}\" 2>&1";
		//$cmd="ffprobe -v quiet -print_format json -show_format -show_streams ".$vidpath." 2>&1";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function video_get_audio_mp3($vidpath,$audiopath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $vidpath -vn -ar 44100 -ac 2 -ab 192 -f mp3 $audiopath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_avi_to_mpg($avipath,$mpgpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $avipath $mpgpath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_mpg_to_avi($mpgpath,$avipath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $mpgpath $avipath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_to_flv($source,$dest,$mediaid="-1",$async="",$width="",$height="",$aspect="",$b="400",$r="25",$ab="56",$ar="22050",$bt="22792k",$mkey="galvid_process")
	{
		$sysobj=new ta_system();
		//$cmd="ffmpeg -i ".$source." -c:v libx264 -ar 22050 -crf 17 ".$dest;
		$cmd="ffmpeg -i \"".$source."\" -ar $ar -ab $ab -acodec mp3 -r $r -f flv -b:v $b ";
		if($width!=""&&$height!="")
		{
			$cmd.="-s ".$width."×".$height." ";
		}
		if($aspect!="")
		{
			$cmd.="-aspect ".$aspect." ";//eg:16:9
		}
		$cmd.="\"".$dest."\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galvid_convcomp.php\" ".$mediaid." flv \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_to_webm($source,$dest,$mediaid="-1",$async="",$width="",$height="",$aspect="",$b="7000k",$r="25",$mkey="galvid_process")
	{
		$sysobj=new ta_system();
		//$cmd="ffmpeg -i ".$source." -c:v libx264 -quality good -cpu-used 0 -ar 22050 -crf 17 ".$dest;
		$cmd="ffmpeg -i \"".$source."\" -c:v libvpx -quality good -cpu-used 0 -c:a libvorbis -r $r -f webm -b:v $b -bufsize 1500k -qmin 10 -qmax 42 -maxrate 500k -threads 8 ";
		if($width!=""&&$height!="")
		{
			$cmd.="-s ".$width."×".$height." ";
		}
		if($aspect!="")
		{
			$cmd.="-aspect ".$aspect." ";//eg:16:9
		}
		$cmd.="\"".$dest."\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galvid_convcomp.php\" ".$mediaid." webm \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	//ffmpeg -i xxx.mp4 -c:v libvpx -quality good -cpu-used 0 -b:v 7000k -qmin 10 -qmax 42 -maxrate 500k -bufsize 1500k -threads 8 -vf scale=-1:1080 -c:a libvorbis -b:a 192k -f webm xxx.webm 
	//ffmpeg -i input-file.mp4 -c:v libvpx -crf 10 -b:v 1M -c:a libvorbis output-file.webm
	
	public function convert_to_ogv($source,$dest,$mediaid="-1",$async="",$width="",$height="",$aspect="",$b="18550k",$r="25",$ab="320k",$ar="48000",$bt="22792k",$mkey="galvid_process")
	{
		/*
		 * -i; specify input file 
		 * -deinterlace; deinterlace video
		 * -ar; audio sample rate
		 * -ab; audio bitrate
		 * -acodec; audio codec
		 * -r; framerate
		 * -f; output format
		 * -b; bitrate (of video!)
		 * -s; resize to widthxheight
		 * -bt:max bitrate
		 */
		$sysobj=new ta_system();
		
		$cmd="ffmpeg -y -i '".$source."' ";
		if($width!=""&&$height!="")
		{
			$cmd.="-s ".$width."×".$height." ";
		}
		if($aspect!="")
		{
			$cmd.="-aspect ".$aspect." ";//16:9
		}
		$cmd.=" -r $r -b:v $b -bt $bt -vcodec libtheora -acodec libvorbis -ac 2 -ar $ar -ab $ab \"$dest\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galvid_convcomp.php\" ".$mediaid." ogv \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_avi_to_animgif($avipath,$gifpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $avipath $gifpath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function video_add_sound($vidpath,$audiopath,$outputpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $audiopath -i $vidpath $outputpath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_avi_to_dv($avipath,$dvpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $avipath -s pal -r pal -aspect 4:3 -ar 48000 -ac 2 $dvpath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function video_cut($vidpath,$start,$duration,$outputpath,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -sameq -ss $start -t $duration -i $vidpath $outputpath";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_wav_to_mp3($wavpath,$mp3path,$async="")
	{
		$sysobj=new ta_system();
		$cmd="ffmpeg -i $wavpath $mp3path";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	
	
	//CONVERT SPECIFIED FRAME TO JPG
	//ffmpeg -i video.mpg -an -ss 00:00:03 -t 00:00:01 -r 1 -y -s 320x240 video%d.jpg
	//TURN X IMAGES TO A VIDEO SEQUENCE
	//ffmpeg -f image2 -i image%d.jpg video.mpg
	//TURN A VIDEO TO X IMAGES
	//ffmpeg -i video.mpg image%d.jpg
	/**
	 * 
	 * GET A FRAME FROM A PARTICULAR SECOND FROM THE VIDEO
	 * @param unknown $vidpath The path to the video to be processed
	 * @param unknown $thumbpath The path where the thumbnail has to be stored
	 * @param number $second The second at which the frame is to be fetched. (Defaults to -1 which gets random second)
	 */
	public function video_get_frame($vidpath,$thumbpath,$second=-1,$async="",$thumbsize="640x480")
	{

		$galobj=new ta_galleryoperations();
		$sysobj=new ta_system();
		$logobj=new ta_logs();
		$total=$galobj->video_get_duration($vidpath);
		$logobj->store_templogs("VID DURATION:".$total);
		if($total!=FAILURE)
		{
			if($second==-1)
			{
				if($second!=-1 && $second<$total)
				{
					$second = rand(1, ($total-1));
				}
				else
				{
					$second=$total-1;
				}
			}
			
			$cmd = "ffmpeg -i \"{$vidpath}\" -deinterlace -an -ss $second -f mjpeg -t 1 -r 1 -y -s $thumbsize \"$thumbpath\" 2>&1";
			$logobj->store_templogs("THUMBNAIL CMD:".$cmd);
			//$cmd = "ffmpeg -i \"{$vidpath}\" -deinterlace -an -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg \"$thumbpath\" 2>&1";
			return $sysobj->shellcmd($cmd,$async);
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * 
	 * @param unknown $vidpath
	 * @param unknown $respath
	 * @param unknown $prefix
	 * @param unknown $seconds
	 */
	public function video_get_frames_everyn($vidpath,$respath,$prefix,$seconds)
	{
		$sec=1/$seconds;
		$cmd="ffmpeg -i $vidpath -r $sec -sameq -f image2 ".$respath."/".$prefix."_frame%02d.jpg";
	}
	
	/**
	 * 
	 * CONVERT DOCUMENT TO PDF
	 * @param unknown $source The source file
	 * @param unknown $destdir The destination directory with file same name as source file
	 * @param string $mediaid The mediaid of orig file
	 * @param string $async Async attribute for shell
	 * @param string $thumbpath The path if thumbnail has to be generated
	 * @param string $mkey
	 */
	public function convert_to_pdf($source,$destdir,$mediaid="-1",$async="",$mkey="galdoc_process")
	{
		$sysobj=new ta_system();
		
		$cmd="libreoffice --headless -convert-to pdf \"".$source."\" -outdir \"".$destdir."\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galdoc_convcomp.php\" ".$mediaid." pdf \"".$destdir."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	/**
	 * 
	 * GET THUMBNAIL FROM PDF DOCUMENT
	 * @param unknown $source Source path of pdf
	 * @param unknown $dest Destination path of thumbnail
	 * @param number $quality Quality of thumbnail
	 * @param string $width Width of thumbnail (Defaults to 150)
	 * @param string $height Height of thumbnail
	 * @return Returns BOOL_SUCCESS on success, BOOL_FAILURE on failure
	 */
	public function get_thumbnail_pdf($source,$dest,$quality=100,$width="150",$height="150")
	{
		$image = new imagick($source.'[0]');
		$image->setImageFormat('jpg');
		
		$geo=$image->getImageGeometry();
		$sizex=$geo['width'];
		$sizey=$geo['height'];
		if($sizex<$width)
		{
			$image->scaleImage($width, 0, BOOL_SUCCESS);
		}
		else
		{
			$image->scaleImage($width, 0, BOOL_FAILURE);
		}
		$image->setcompressionquality($quality);
		if($image->writeimage($dest))
		{
			return $image->getNumberImages();
		}
		else
		{
			return BOOL_FAILURE;
		}
	}
	
	public function convert_to_mp3($source,$dest,$mediaid="-1",$async="",$ar="44100",$ac="2",$ab="192k",$mkey="galaud_process")
	{
		$sysobj=new ta_system();
		//ffmpeg -i input.wav -vn -ar 44100 -ac 2 -ab 192k -f mp3 output.mp3
		$cmd="ffmpeg -i \"".$source."\" -vn -ar $ar -ac $ac -ab $ab -f mp3 \"$dest\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galaud_convcomp.php\" ".$mediaid." mp3 \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_to_oga($source,$dest,$mediaid="-1",$async="",$quality="5",$mkey="galaud_process")
	{
		$sysobj=new ta_system();
		//ffmpeg -i audio.wav  -acodec libvorbis audio.ogg
		//ffmpeg -i input.wav -c:a libvorbis -qscale:a 5 output.ogg
		$cmd="ffmpeg -i \"".$source."\" -c:a libvorbis -qscale:a ".$quality." \"".$dest."\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galaud_convcomp.php\" ".$mediaid." oga \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function test_virus($source,$dest,$mediaid="-1",$async="1",$mkey="galscan_process")
	{
		$sysobj=new ta_system();
		$galobj=new ta_galleryoperations();
		
		$res=$galobj->media_get_info($mediaid);
		$galid=$res[0][changesqlquote(tbl_galdb::col_galid,"")];
		$uid=$res[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		
		$galurl=$galobj->geturl_gallery($uid,$galid);
		if(!file_exists($dest))
		{
			$myfile = fopen($dest,"w");
			fclose($myfile);
		}
		$cmd="clamscan -r \"".$source."\"  >> \"".$galurl."/".$mediaid.".txt\";php \"".ROOT_SERVER."/scripts_shell/galvirus_scancomp.php\" ".$mediaid." scan \"".$dest."\" $mkey \"".$galurl."/".$mediaid.".txt\";";
		
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function metadata_get_vidaud($source,$dest,$mediaid="-1",$async="1")
	{
		$sysobj=new ta_system();
		//ffmpeg -i input_video -f ffmetadata metadata.txt
		$cmd="ffmpeg -i \"".$source."\" -f ffmetadata \"".$dest."\";";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function convert_to_image($source,$dest,$mediaid="-1",$quality="100",$resize="100%",$async="1",$mkey="galpic_process")
	{
		$sysobj=new ta_system();
		$cmd="convert \"".$source."\" -quality ".$quality." -resize ".$resize." \"".$dest."\";";
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galpic_convcomp.php\" ".$mediaid." img \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	public function image_get_thumbnail($source,$dest,$width,$height="",$mediaid="-1",$async="1",$mkey="galthumb_main",$format="img")
	{
		$sysobj=new ta_system();
		$fileobj=new ta_fileoperations();
		//$cmd="convert thumbnail <width>x<height> image.png thumbnail.png";
		$cmd="convert -thumbnail ".$width."x".$height." \"".$source."\" \"".$dest."\";";
		if($format=="img")
		{
			$format=$fileobj->fileinfo($source,"3");
		}
		$cmd.="php \"".ROOT_SERVER."/scripts_shell/galpic_convcomp.php\" ".$mediaid." ".$format." \"".$dest."\" $mkey;";
		return $sysobj->shellcmd($cmd,$async);
	}
	
	
}