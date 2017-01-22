<?php
/**
 *
* CONTAINS FUNCTIONS RELATED TO PROCESSING REQUESTS
* @author T.V.VIGNESH
*
*/
class ta_requests
{
	public function r_contact_flvl()
	{
		global $mkey,$userobj,$socialobj;
	
		$fid=$_POST["fid"];
		$flvl=$_POST["flvl"];
		$flvl=(int)$flvl;
		if($flvl<0||$flvl>10)
		{
			$data = array( 'returnval' =>-2, 'message' =>"Invalid Value supplied for Friendship Level!" );
			die(json_encode($data));
		}
		if($socialobj->updatefriendinfo($fid,$userobj->uid,"3",$flvl)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$dispmsg=$socialobj->friend_leveltotext($flvl);
			$data = array( 'returnval' =>1, 'message' =>$dispmsg);
			echo json_encode($data);
		}
	}
	
	public function r_contact_fnick()
	{
		global $mkey,$userobj,$socialobj;
	
		$fid=$_POST["fid"];
		$nick=$_POST["nick"];
		if($socialobj->updatefriendinfo($fid,$userobj->uid,"1",$nick)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>$nick);
			echo json_encode($data);
		}
	}
	
	public function r_contact_flisttog()
	{
		global $mkey,$userobj,$socialobj;
	
		$fuid=$_POST["fuid"];
		$flid=$_POST["flid"];
		if($socialobj->list_user_toggle($userobj->uid,$fuid,$flid)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$res1=$socialobj->get_belonginglist_friend($fuid,$userobj->uid);
			$listcount=count($res1);
			$displabel='';
			if($listcount!=0)
			{
				$displabel='';
			}
			for($j=0;$j<$listcount;$j++)
			{
				$listid=$res1[$j][changesqlquote(tbl_listsdb::col_listid,"")];
				$res2=$socialobj->get_listinfo($listid);
				$listlabel=$res2["listname"];
				$listdesc=$res2["listdesc"];
					
				$displabel.='<span class="label label-success contacts_lists" data-elemid="'.$listid.'" title="'.$listdesc.'">'.$listlabel.'</span> ';
			}
	
			$data = array( 'returnval' =>1, 'message' =>$displabel);
			echo json_encode($data);
		}
	}
	
	public function r_contacts_togfollow()
	{
		global $mkey,$userobj,$socialobj;
	
		$fuid=$_POST["fuid"];
		$disp='<i class="fa fa-rss"></i> <span class="hidden-xs hidden-sm hidden-md">Follow</span>';
		if($socialobj->follower_toggle($userobj->uid,$fuid)==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			if($socialobj->follower_check($userobj->uid,$fuid))$disp='<i class="fa fa-stop"></i> <span class="hidden-xs hidden-sm hidden-md">Unsubscribe</span>';
			$data = array( 'returnval' =>1, 'message' =>$disp);
			die(json_encode($data));
		}
	}
	
	public function r_contacts_newbook()
	{
		global $mkey,$userobj,$socialobj;
	
		$label=$_POST["label"];
		$notes=$_POST["notes"];
		$cbk_img=$_FILES["cbk_img"];
		if($socialobj->createlist($label,$userobj->uid,"1",$notes,"1",$cbk_img)==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
		}
	}
	
	public function r_s_togfriend()
	{
		global $mkey,$userobj,$socialobj;
	
		$logobj=new ta_logs();
		
		$fuid=$_POST["fuid"];
		$myres=$socialobj->friend_toggle($userobj->uid,$fuid);
		if($myres==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			if($myres==SUCCESS)$myres="";
			if($socialobj->friend_check_mutual($userobj->uid,$fuid))
			{
				$data = array( 'returnval' =>1, 'message' =>'<i class="fa fa-times"></i> <span class="hidden-xs hidden-sm hidden-md">Remove Friend</span>','execscript'=>$myres);//Mutual Friends
			}
			else
			if($socialobj->friend_check($userobj->uid,$fuid))
			{
				$data = array( 'returnval' =>2, 'message' =>'<i class="fa fa-times"></i> <span class="hidden-xs hidden-sm hidden-md">Cancel Request</span>','execscript'=>$myres);//Not Mutual Friends
			}
			else
			if($socialobj->friend_check($fuid,$userobj->uid))
			{
				$data = array( 'returnval' =>3, 'message' =>'<i class="fa fa-check"></i> <span class="hidden-xs hidden-sm hidden-md">Accept Request</span>','execscript'=>$myres);
			}
			else
			{
				$data = array( 'returnval' =>4, 'message' =>'<i class="fa fa-user-plus"></i> <span class="hidden-xs hidden-sm hidden-md">Add Friend</span>','execscript'=>$myres);
			}
			
			$logobj->store_templogs("GIGI");
			$logobj->store_templogs(print_r($data,true));
			
			echo json_encode($data);
		}
	}
	
	public function r_s_removefriend()
	{
		global $mkey,$userobj,$socialobj;
	
		$fuid=$_POST["fuid"];
		if($socialobj->deletefriend($fuid,$userobj->uid)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>'Add Friend');
		}
		echo json_encode($data);
	}
	
	public function r_s_togblockfriend()
	{
		global $mkey,$userobj,$socialobj;
	
		$fuid=$_POST["fuid"];
		$blacklistobj=new ta_blacklists();
		if($blacklistobj->block_toggle($userobj->uid,$fuid,"1")!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			if($blacklistobj->block_check($userobj->uid,$fuid,"1"))
			{
				$data = array( 'returnval' =>1, 'message' =>'<i class="fa fa-unlock"></i> Unblock');
			}
			else
			{
				$data = array( 'returnval' =>1, 'message' =>'<i class="fa fa-ban"></i> Block');
			}
		}
		echo json_encode($data);
	}
	
	public function r_s_repsubmit()
	{
		global $mkey,$userobj,$socialobj;
		$blacklistobj=new ta_blacklists();
	
		$itemid=$_POST["itemid"];
		$itemtype=$_POST["itemtype"];
		$reasontype=$_POST["rep_rtype"];
		$reason=$_POST["rep_reason"];
		$url=$_POST["rep_url"];
	
		if($blacklistobj->report_item($itemid, $itemtype, $reason, $reasontype, $userobj->uid,$url)==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Report Submitted Successfully!" );
			echo json_encode($data);
		}
	}
	
	public function r_s_remflist()
	{
		global $mkey,$userobj,$socialobj;
	
		$elem=$_POST["elem"];
		if($elem!="all")
		{
			if($socialobj->deletelist($elem,$userobj->uid)!=SUCCESS)
			{
				$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
				die(json_encode($data));
			}
			else
			{
				$data = array( 'returnval' =>1, 'message' =>"List Deleted Successfully!" );
				echo json_encode($data);
			}
		}
	}
	
	public function gbx_newgal()
	{
		global $mkey,$userobj,$socialobj,$logobj,$dbobj;
	
		if(isset($_POST["galtitle"])){$galname=$_POST["galtitle"];}else{$galname="";}
		if(isset($_POST["galdesc"])){$galdesc=$_POST["galdesc"];}else{$galdesc="";}
		if(isset($_POST["galtype"])){$galtype=$_POST["galtype"];}else{$galtype="1";}
	
		$galobj=new ta_galleryoperations();
		$dbobj->transaction_start($dbobj->mysqli);
		$galid=$galobj->creategallery($galname, $galdesc,$userobj->uid,$galtype);
		if($galid==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Gallery Created Successfully!",'galid'=>$galid);
		}
		echo json_encode($data);
	}
	
	public function gbx_galopen_pic()
	{
		global $mkey,$userobj,$socialobj,$logobj,$dbobj;
	
		$uiobj=new ta_uifriend();
		echo $uiobj->disp_gal_pic($userobj->uid,$_POST["galid"]);
	}
	
	public function gbx_galpic_upload()
	{
		global $mkey,$userobj,$socialobj,$logobj;
	
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
	
		$galid=$_POST["galid"];
		$error="";$ercount=0;
		$fil=$_FILES["galbx_newpic_file"]["name"];
	
		if(is_array($fil))
		{
			$file_ary=$fileobj->reArrayFiles($_FILES["galbx_newpic_file"]);
				
			foreach ($file_ary as $file)
			{
				$co=0;
				do
				{
					$mediaid=$fileobj->upload_pic($file,$userobj->uid,$galid);
					$co++;
				}while($mediaid==""&&$co<4);
	
				if($mediaid=="")
				{
					$error="<br>Error in uploading ".$file["name"];
					$ercount++;
				}
				else
				{
					$mediaid_thumb=$fileobj->generate_thumbs($galid,$mediaid,$userobj->uid,"1",80,80,100);
				}
			}
		}
	
		if($ercount!=0)
		{
			$logobj->store_templogs($ercount."..".$error);
			$data = array( 'returnval' =>-1, 'message' =>$error);
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Files Uploaded Successfully!" );
			echo json_encode($data);
		}
	}
	
	public function gbx_pic_remove()
	{
		global $mkey,$userobj,$socialobj,$logobj;
	
		$mediaid=$_POST["mediaid"];
		$galid=$_POST["galid"];
		$galobj=new ta_galleryoperations();
		$medres=$galobj->media_get_info($mediaid);
		$meduid=$medres[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		if($meduid!=$userobj->uid)
		{
			$data = array( 'returnval' =>-4, 'message' =>"Unable to delete file as you dont own it!" );
			die(json_encode($data));
		}
		if($galobj->deletemedia($mediaid,$galid)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Files Deleted Successfully!" );
			echo json_encode($data);
		}
	}
	
	public function gbx_galdel()
	{
		global $mkey,$userobj,$socialobj;
	
		$galobj=new ta_galleryoperations();
		$galid=$_POST["galid"];
		if($galobj->deletegallery($galid,$userobj->uid)!=SUCCESS)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to update information onto the database!" );
			die(json_encode($data));
		}
		else
		{
			$data = array( 'returnval' =>1, 'message' =>"Gallery Deleted Successfully!" );
			echo json_encode($data);
		}
	}
	
	public function gbx_galopen_vid()
	{
		global $mkey,$userobj,$logobj,$dbobj;
	
		$uiobj=new ta_uifriend();
		echo $uiobj->disp_gal_vid($userobj->uid,$_POST["galid"]);
	}
	
	public function gbx_galopen_doc()
	{
		global $mkey,$userobj,$logobj,$dbobj;
	
		$uiobj=new ta_uifriend();
		echo $uiobj->disp_gal_doc($userobj->uid,$_POST["galid"]);
	}
	
	public function gbx_galopen_aud()
	{
		global $mkey,$userobj,$logobj,$dbobj;
		
		$uiobj=new ta_uifriend();
		echo $uiobj->disp_gal_aud($userobj->uid,$_POST["galid"]);
	}
	
	public function gbx_vid_infoload()
	{
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		$userobj=new ta_userinfo();
		
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		$res=$galobj->media_get_info($mediaid);
		if(count($res)==0)
		{
			echo 'OOPS! No such video exists in our database!';return;
		}
		$jsonid=$res[0][changesqlquote(tbl_galdb::col_jsonid,"")];
		$mediatime=$res[0][changesqlquote(tbl_galdb::col_mediatime,"")];
		$mediadesc=$res[0][changesqlquote(tbl_galdb::col_mediadesc,"")];
		$mediauid=$res[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		$meta=$utilityobj->jsondata_get($jsonid);
		echo
		'
		<ul class="list-group">
			<li class="list-group-item"><h4>Description: </h4><p><a id="media_edit_desc">'.$mediadesc.'</a></p></li>
			<li class="list-group-item"><h4>Duration:</h4> '.$meta->duration.' seconds</li>
			<!--<li class="list-group-item"><h4>Views:</h4>1,00,00,000</li>
			<li class="list-group-item"><h4>Rating:</h4>4.5</li>
			<li class="list-group-item"><h4>Tags: </h4>Technology, Entertainment</li>-->
			<li class="list-group-item"><h4>By: </h4>'.$userobj->user_getfullname($mediauid).'</li>
			<!--<li class="list-group-item"><h4>Language: </h4>English</li>
			<li class="list-group-item"><h4>Subtitles:</h4>Yes</li>-->
			<li class="list-group-item"><h4>Uploaded: </h4>'.$mediatime.'</li>
			<!--<li class="list-group-item"><h4>Age Restriction: </h4>13+</li>-->
			<!--<li class="list-group-item"><h4>Licence: </h4>Creative Commons 2.0</li>-->
		</ul>';
	}
	
	public function gbx_vid_infodownload()
	{
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		$fileobj=new ta_fileoperations();
	
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		$res=$galobj->media_get_info($mediaid);
		$mediaurl=$res[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
		$jsonid=$res[0][changesqlquote(tbl_galdb::col_jsonid,"")];
		$uid=$res[0][changesqlquote(tbl_galdb::col_mediauid,"")];
	
		$galid_vidpro=$galobj->get_galid_special($uid,"9");
	
		$jsonobj=$utilityobj->jsondata_get($jsonid);
	
		$ext=$fileobj->fileinfo($mediaurl,"3");
	
		$result='<strong>Download video:</strong><br>
				<ul class="list-group"><li class="list-group-item">'.$ext.' Format <a class="btn btn-default pull-right" href="'.$utilityobj->pathtourl($mediaurl).'"><i class="fa fa-download"></i> Download</a><div style="clear:both;"></div></li>';
			
		if(isset($jsonobj->formats))
		{
			foreach($jsonobj->formats as $key=>$value)
			{
				$medid=$value;
				$medurl=$galobj->geturl_media($galid_vidpro,$medid,"3");
				$format=$key;
				$result.='<li class="list-group-item">'.$format.' Format <a class="btn btn-default pull-right" href="'.$utilityobj->pathtourl($medurl).'"><i class="fa fa-download"></i> Download</a><div style="clear:both;"></div></li>';
			}
		}
	
		$result.='</ul>';
		echo $result;
	}
	
	public function gbx_vid_del()
	{
		global $mkey,$userobj;
	
		$galobj=new ta_galleryoperations();
	
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		if($galobj->deletemedia($mediaid,$galid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Video Deleted Successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete video!" );
			die(json_encode($data));
		}
	}
	
	public function gbx_doc_del()
	{
		global $mkey,$userobj;
	
		$galobj=new ta_galleryoperations();
	
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		if($galobj->deletemedia($mediaid,$galid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Document Deleted Successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete document!" );
			die(json_encode($data));
		}
	}
	
	public function gbx_aud_del()
	{
		global $mkey,$userobj;
	
		$galobj=new ta_galleryoperations();
	
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		if($galobj->deletemedia($mediaid,$galid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Track Deleted Successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete the Track!" );
			die(json_encode($data));
		}
	}
	
	public function gbx_va_del()
	{
		global $mkey,$userobj;
	
		$galobj=new ta_galleryoperations();
	
		$galid=$_POST["galid"];
		$mediaid=$_POST["mediaid"];
	
		if($galobj->deletemedia($mediaid,$galid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"File Deleted Successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete the File!" );
			die(json_encode($data));
		}
	}
	
	public function tbx_thread_new()
	{
		global $mkey,$userobj;
	
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$socialobj=new ta_socialoperations();
		
		$participants=$_POST["globalsender"];
		$subject=$_POST["thread_lbl"];
		
		$participants = explode(',', $participants);
		$scriptres='';
		$tid=$msgobj->thread_create($userobj->uid,"1",$subject);
		if($msgobj->thread_add_audience_users($tid,$participants)!=FAILURE)
		{
			$nottext=$userobj->fname." ".$userobj->mname." ".$userobj->lname." has added you to a Thread";
			$pic=$utilityobj->pathtourl($msgobj->get_threadpic($tid));
			$notlink="/dash_conversations.php?tid=".$tid;
			for($i=0;$i<count($participants);$i++)
			{
				$uid=$participants[$i];
				if($uid==$userobj->uid)continue;
				$scriptres.=$socialobj->sendnotification($uid,$nottext,"9",$pic, $notlink,"2","","",$tid);
			}
			
			$data = array( 'returnval' =>1, 'message' =>"Thread Created Successfully!",'execscript'=>$scriptres);
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to create the Thread!" );
			die(json_encode($data));
		}
	}
	
	public function tbx_thread_addparticipants()
	{
		global $mkey,$userobj;
	
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$socialobj=new ta_socialoperations();
	
		$participants=$_POST["globalsender"];
		$tid=$_POST["threadid"];
	
		$participants = explode(',', $participants);
		$scriptres='';
		if($msgobj->thread_add_audience_users($tid,$participants)!=FAILURE)
		{
			$nottext=$userobj->fname." ".$userobj->mname." ".$userobj->lname." has added you to a Thread";
			$pic=$utilityobj->pathtourl($msgobj->get_threadpic($tid));
			$notlink="/dash_conversations.php?tid=".$tid;
			for($i=0;$i<count($participants);$i++)
			{
				$uid=$participants[$i];
				if($uid==$userobj->uid)continue;
				$scriptres.=$socialobj->sendnotification($uid,$nottext,"9",$pic, $notlink,"2","","",$tid);
			}
			
			$data = array( 'returnval' =>1, 'message' =>"Participants Added Successfully!",'execscript'=>$scriptres);
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to add Participants!" );
			die(json_encode($data));
		}
	}
	
	public function ta_gp_invitemem()
	{
		global $mkey,$userobj;
		
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$socialobj=new ta_socialoperations();
		$uobj=new ta_userinfo();
		
		$participants=$_POST["globalsender"];
		$gpid=$_POST["gpid"];
		
		$gpres=$socialobj->groups_get($gpid);
		$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
		
		$participants = explode(',', $participants);
		$scriptres='';
		for($i=0;$i<count($participants);$i++)
		{
			$uid=$participants[$i];
			$fullname_send=$uobj->user_getfullname($userobj->uid);
			$notcontent=$fullname_send." has just invited you to join a group (".$gpname.")";
			$notlink="/social_groups.php?gpid=".$gpid;
			$noticon="/master/securedir/m_images/img_icons/group_forum.png";
			$scriptres.=$socialobj->sendnotification($uid, $notcontent,"11",$noticon, $notlink,"2","","",$gpid);
		}
		
		$data = array( 'returnval' =>1, 'message' =>"Participants Added Successfully!",'execscript'=>$scriptres);
		echo json_encode($data);
	}
	
	public function tbx_thread_sendmsg()
	{
		global $mkey,$userobj;
	
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$logobj=new ta_logs();
		$galleryobj=new ta_galleryoperations();
		$colobj=new ta_collection();
		$socialobj=new ta_socialoperations();
	
		$tid=$_POST["tid"];
		$suid=$_POST["uid"];
		$msg=$_POST["msg"];
		
		
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		if(isset($_POST["colmedid"])&&$_POST["colmedid"]!="undefined")
		{
			$colmedid=$_POST["colmedid"];
		}
		else
		{
			$colmedid="";
		}
		
		$galid_att=$galleryobj->get_galid_special($userobj->uid,"16");
		
		$colatt=Array();
		for($i=0;$i<count($attachments);$i++)
		{
			$colatt[$i][tbl_collection_media::col_mediaid]=$attachments[$i];
			$colatt[$i][tbl_collection_media::col_galid]=$galid_att;
		}
		
		$col_medid=$colobj->add_collection(tbl_collection_media::tblname, $colatt, tbl_collection_media::col_col_mediaid,$colmedid);
		
		
		if($suid!=$userobj->uid)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to create the Thread! Please logout and try again!" );
			die(json_encode($data));
		}
		
		$msgid=$msgobj->sendmsg($suid,$msg,$tid);
		$notcontent=$userobj->fname." ".$userobj->mname." ".$userobj->lname." has sent you message(s)";
		$noticon=$msgobj->get_threadpic($tid);
		$notlink="/dash_conversations.php?tid=".$tid."&msgid=".$msgid;
		$scriptres='';
		if($msgid!=FAILURE)
		{
			$msgobj->addattachment($col_medid,$tid,$msgid);
			
			$colures=$msgobj->get_thread_audience($tid);
			for($i=0;$i<count($colures);$i++)
			{
				$uid=$colures[$i][changesqlquote(tbl_collection_users::col_uid,"")];
				if($uid==$userobj->uid)continue;
				$extrajson='\"tid\":\"'.$tid.'\",\"msgid\":\"'.$msgid.'\"';
				$scriptres.=$socialobj->sendnotification($uid, $notcontent,"2",$noticon, $notlink,"2",$extrajson,"",$tid);
			}
			
			$data = array( 'returnval' =>1, 'message' =>"Message Sent Successfully!",'execscript'=>$scriptres);
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to send Message!" );
			die(json_encode($data));
		}
		
	}
	
	public function tbx_thread_delete()
	{
		global $mkey,$userobj;
		
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		
		$tid=$_POST["threadid"];
		
		if($msgobj->deletethread($tid,$userobj->uid)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Thread deleted successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete the Thread!" );
			die(json_encode($data));
		}
	}
	
	public function tbx_post_new()
	{
		global $mkey,$userobj;
		
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$audobj=new ta_audience();
		$colobj=new ta_collection();
		$galleryobj=new ta_galleryoperations();
		$socialobj=new ta_socialoperations();
		$logobj=new ta_logs();
		
		if(isset($_POST["audid"])&&$_POST["audid"]!=""&&$_POST["audid"]!="undefined")
		{
			$audid=$_POST["audid"];
		}
		else
		{
			$audid=$audobj->audience_create();
		}
		$thepost=$_POST["thepost"];
		$subject=$_POST["subject"];
		$mentions=$_POST["mentions"];
		
		if(!isset($_POST["ptype"]))
		{
			if(isset($_POST["gpid"]))
			{
				$ptype="5";
				$gpid=$_POST["gpid"];
				if(!$socialobj->group_user_check($gpid,$userobj->uid))
				{
					$ptype="4";
				}
			}
			else
			{
				$ptype="4";
			}
		}
		else
		{
			$ptype=$_POST["ptype"];
		}
		
		$mentions=json_decode(stripslashes($_POST["mentions"]));
		
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		if(isset($_POST["colmedid"])&&$_POST["colmedid"]!="undefined")
		{
			$colmedid=$_POST["colmedid"];
		}
		else
		{
			$colmedid="";
		}
		
		$galid_att=$galleryobj->get_galid_special($userobj->uid,"16");
		
		$colatt=Array();
		$attcount=count($attachments);
		for($i=0;$i<$attcount;$i++)
		{
			$colatt[$i][tbl_collection_media::col_mediaid]=$attachments[$i];
			$colatt[$i][tbl_collection_media::col_galid]=$galid_att;
		}
		
		$tid=$msgobj->thread_create($userobj->uid,$ptype,$subject);
		$msgobj->thread_add_audienceid($tid,$audid);
		$msgid=$msgobj->sendmsg($userobj->uid,$thepost,$tid);
		
		if($ptype=="5")
		{
			$socialobj->attachthreadtogroup($gpid, $tid);
		}
		else
		if($ptype=="6")
		{
			$socialobj->attachthreadtohelp($tid);
		}
		
		if($attcount!=0)
		{
			$col_medid=$colobj->add_collection(tbl_collection_media::tblname, $colatt, tbl_collection_media::col_col_mediaid,$colmedid);
			$msgobj->addattachment($col_medid,$tid,$msgid);
		}
		$msgobj->thread_add_comthread($tid);
		$scriptres='';
		$tagarray=Array();
		for($i=0;$i<count($mentions);$i++)
		{
			$tag_uid=$mentions[$i]->uid;
			$tag_pos=$mentions[$i]->pos;
			$tag_name=$mentions[$i]->name;
			
			$tagarray[$i]["tagname"]=$tag_name;
			$tagarray[$i]["uid"]=$tag_uid;
			
			$extrajson='\"tid\":\"'.$tid.'\",\"msgid\":\"'.$msgid.'\"';
			$notcontent="You have been tagged in a post by ".$userobj->fname." ".$userobj->mname." ".$userobj->lname;
			$notlink="/post_display.php?tid=".$tid;
			$noticon=$msgobj->get_threadpic($tid);
			if($noticon=="")$noticon=$userobj->getprofpic($userobj->uid);
			$scriptres.=$socialobj->sendnotification($tag_uid, $notcontent,"2",$noticon, $notlink,"2",$extrajson,"",$tid);
		}
		$tagid=$socialobj->tag_user_add($tagarray,$userobj->uid,"1","");
		
		$msgobj->editmsg($tid, $msgid, tbl_message_content::col_tagid, $tagid);
		
		if(isset($_POST["tagcolid"])&&$_POST["tagcolid"]!="")
		{
			$msgobj->tagpost_linkposttag($tid,$_POST["tagcolid"]);
		}
		
		
		$data = array( 'returnval' =>1, 'message' =>"Post successfully made!",'execscript'=>$scriptres);
		echo json_encode($data);
	}
	
	public function tbx_comment_new()
	{
		global $mkey,$userobj;
		
		$msgobj=new ta_messageoperations();
		$utilityobj=new ta_utilitymaster();
		$audobj=new ta_audience();
		$colobj=new ta_collection();
		$galleryobj=new ta_galleryoperations();
		$socialobj=new ta_socialoperations();
		
		$thecomment=$_POST["thecomment"];
		$mtid=$_POST["tid"];
		
		$mainres=$msgobj->getthreadoutline($mtid);
		$posteruid=$mainres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		
		$comtres=$msgobj->thread_get_comthread($mtid);
		
		if(count($comtres)==0)
		{
			$comtid=$msgobj->thread_add_comthread($mtid);
		}
		else
		{
			$comtid=$comtres[0][changesqlquote(tbl_thread_comments::col_ctid,"")];
		}
		
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		if(isset($_POST["colmedid"])&&$_POST["colmedid"]!="undefined")
		{
			$colmedid=$_POST["colmedid"];
		}
		else
		{
			$colmedid="";
		}
		
		$galid_att=$galleryobj->get_galid_special($userobj->uid,"16");
		
		$colatt=Array();
		$attcount=count($attachments);
		for($i=0;$i<$attcount;$i++)
		{
			$colatt[$i][tbl_collection_media::col_mediaid]=$attachments[$i];
			$colatt[$i][tbl_collection_media::col_galid]=$galid_att;
		}
		
		$msgid=$msgobj->sendmsg($userobj->uid,$thecomment,$comtid);
		if($attcount!=0)
		{
			$col_medid=$colobj->add_collection(tbl_collection_media::tblname, $colatt, tbl_collection_media::col_col_mediaid,$colmedid);
			$msgobj->addattachment($col_medid,$comtid,$msgid);
		}
		
		if(isset($_POST["repto"])&&$_POST["repto"]!=""&&$_POST["repto"]!="undefined")
		{
			$mres=$msgobj->getmsg($comtid,"","",$msgid);
			if(count($mres)!=0)
			{
				$msgobj->editmsg($comtid, $msgid, tbl_message_content::col_replyto,$_POST["repto"]);
			}
		}
		
		$scriptres='';
		
		if($posteruid!=$userobj->uid)
		{
			$extrajson='\"tid\":\"'.$mtid.'\"';
			$notcontent='People have commented in your post';
			$notlink="/post_display.php?tid=".$mtid;
			$noticon=$msgobj->get_threadpic($mtid);
			$scriptres.=$socialobj->sendnotification($posteruid,$notcontent,"12",$noticon, $notlink,"2",$extrajson,"",$mtid);
		}
		
		$data = array( 'returnval' =>1, 'message' =>"Comment successfully made!",'execscript'=>$scriptres);
		echo json_encode($data);
	}
	
	public function abx_addaudience()
	{
		global $mkey,$userobj;
		
		if(!isset($_POST["reselem"])&&!isset($_POST["autoset"]))
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to add the Audience!" );
			die(json_encode($data));
		}
		
		$gender=$_POST["gender"];
		$weducatid=$_POST["workeducat"];
		$minage=$_POST["minage"];
		$maxage=$_POST["maxage"];
		$repmin=$_POST["abx_repmin"];
		$repmax=$_POST["abx_repmax"];
		$loginreq=$_POST["loginreq"];
		$subinc=$_POST["subinc"];
		$lists=$_POST["lists"];
		$label=$_POST["abx_label"];
		$country=$_POST["country_input"];
		$state=$_POST["state_input"];
		if(isset($_POST["globalsender"]))
		{
			$participants=$_POST["globalsender"];
			$participants = explode(',', $participants);
		}
		else
		{
			$participants=Array();
		}
		$reselem=$_POST["reselem"];
		
		$audobj=new ta_audience();
		$colobj=new ta_collection();
		
		$userarr=Array();
		for($i=0;$i<count($participants);$i++)
		{
			$userarr[$i][changesqlquote(tbl_collection_users::col_uid,"")]=$participants[$i];
		}
		
		$agearr=Array();
		$agearr[0][tbl_collection_age::col_minage]=$minage;
		$agearr[0][tbl_collection_age::col_maxage]=$maxage;
		
		$weducatarr=Array();
		$weducatarr[0][tbl_collection_workedu::col_typeid]=$weducatid;
		
		$repptarr=Array();
		$repptarr[0][tbl_collection_reppoints::col_minrep]=$repmin;
		$repptarr[0][tbl_collection_reppoints::col_maxrep]=$repmax;
		
		$flistidarr=Array();
		$flistidarr[0][tbl_collection_lists::col_listid]=$lists;
		
		$countryarr=Array();
		$countryarr[0][tbl_collection_countries::col_csname]=$country;
		
		$statearr=Array();
		$statearr[0][tbl_collection_states::col_sid]=$state;
		
		$audid=$audobj->audience_create();
		$audobj->audience_edit($audid,tbl_audience_target::col_gender,$gender);
		$audobj->audience_edit($audid,tbl_audience_target::col_loginreq,$loginreq);
		$audobj->audience_edit($audid,tbl_audience_target::col_audlabel,$label);
		$audobj->audience_edit($audid,tbl_audience_target::col_cuid,$userobj->uid);
		$audobj->audience_edit($audid,tbl_audience_target::col_subscribers,$subinc);
		
		if($minage!="-1"||$maxage!="-1")
		{
			$audobj->audience_add_col_age($audid,$agearr);
		}
		if($weducatid!="-1")
		{
			$audobj->audience_add_col_weducat($audid,$weducatarr);
		}
		if($repmin!=""||$repmax!="")
		{
			$audobj->audience_add_col_reppoints($audid,$repptarr);
		}
		if($lists!="-1")
		{
			$audobj->audience_add_col_lists($audid,$flistidarr);
		}
		
		if($country!="-1")
		{
			$audobj->audience_add_col_country($audid,$countryarr);
		}
		
		if($state!="-1")
		{
			$audobj->audience_add_col_state($audid,$statearr);
		}
		
		if(!empty($userarr)&&!empty($participants))
		{
			$audobj->audience_add_col_users($audid,$userarr);
		}
		
		$data = array( 'returnval' =>1, 'message' =>'<script type="text/javascript">
				$(".alert-text").html("The audience has been set successfully");
				$(".alert-dismissable").show();
				$(".abx-audresult").html("The audience has been set successfully");
				</script>','execscript'=>'' );
		if($reselem!="-1")
		{
			$data = array( 'returnval' =>1, 'message' =>'<script type="text/javascript">
				$("'.$reselem.'").attr("data-audid","'.$audid.'");
				$(".alert-text").html("The audience has been set successfully");
				$(".alert-dismissable").show();
				$(".abx-audresult").html("The audience has been set successfully");
				</script>','execscript'=>'' );
		}
		else
		{
			if(isset($_POST["pelem"]))
			{
				$setobj=new ta_settings();
				$pelem=$_POST["pelem"];
				$elname=$_POST["elname"];
				$elemid=$_POST["elemid"];
				switch($pelem)
				{
					case "prof_elem":
						$setres=$setobj->setting_get($userobj->uid,$elname,$elemid,"2");
						if(count($setres)!=0)
						{
							$audid_old=$setres[0][changesqlquote(tbl_user_settings::col_setval,"")];
							$audobj->audience_remove($audid_old);
							$setobj->setting_remove($userobj->uid,$elname,$elemid,"2");
						}
						$setobj->setting_add($userobj->uid,$elname,$elemid,"2",$audid);
						break;
					case "post_audience":
						$tid=$elemid;
						$msgobj=new ta_messageoperations();
						$tres=$msgobj->getthreadoutline($tid);
						$audid_old=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
						$audobj->audience_remove($audid_old);
						$msgobj->thread_edit_outline($tid,tbl_message_outline::col_audienceid,$audid);
						break;
					case "galmed_audience":
						$galid=$elname;
						$mediaid=$elemid;
						$galobj=new ta_galleryoperations();
						$medres=$galobj->media_get_info($mediaid);
						$audid_old=$medres[0][changesqlquote(tbl_galdb::col_audienceid,"")];
						$audobj->audience_remove($audid_old);
						$galobj->editmediainfo($mediaid,$galid,tbl_galdb::col_audienceid,$audid);
						break;
					case "cust_audience":
						$dbobj=new ta_dboperations();
						$dbobj->dbupdate("UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_cfeedaudid."='' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'",tbl_user_extras::dbname);
						$dbobj->dbupdate("UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_cfeedaudid."='$audid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'",tbl_user_extras::dbname);
						break;
				}
				
				$data = array( 'returnval' =>1, 'message' =>'<script type="text/javascript">
				$(".alert-text").html("The privacy setting has been set successfully");
				$(".alert-dismissable").show();
				$(".abx-audresult").html("The audience has been set successfully");
				</script>','execscript'=>'' );
			}
		}
		
			echo json_encode($data);
	}
	
	public function tbx_upvote()
	{
		global $mkey,$userobj;
		
		$tid=$_POST["threadid"];
		$msgid=$_POST["msgid"];
		
		$socialobj=new ta_socialoperations();
		$msgobj=new ta_messageoperations();
		
		$msgres=$msgobj->getmsg($tid,"","",$msgid);
		
		$rateid=$msgres[0][changesqlquote(tbl_message_content::col_rateid,"")];
		$fuid=$msgres[0][changesqlquote(tbl_message_content::col_fuid,"")];
		
		if($socialobj->rating_up($userobj->uid,$rateid,"11")==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to rate up!" );
			die(json_encode($data));
		}
		else
		{
			$scriptres='';
			
			if($userobj->uid!=$fuid)
			{
				$extrajson='\"tid\":\"'.$tid.'\"';
				$notcontent='People have upvoted your post';
				$notlink="/post_display.php?tid=".$tid;
				$noticon=$msgobj->get_threadpic($tid);
				$scriptres.=$socialobj->sendnotification($fuid,$notcontent,"13",$noticon, $notlink,"2",$extrajson,"",$tid);
			}
			
			$curup=$socialobj->rating_get_upvotes($rateid);
			$curdown=$socialobj->rating_get_downvotes($rateid);
			$views=$msgobj->get_total_views($tid, $msgid);
			
			$scriptres.='
				<script type="text/javascript">
				$(".pd_upv_'.$tid.'").html("'.abs($curup).'");
				$(".pd_dv_'.$tid.'").html("'.abs($curdown).'");
				$(".pd_views_'.$tid.'").html("'.abs($views).'");
				</script>
				';
			
			$data = array( 'returnval' =>1, 'message' =>"Successfully Rated Up!",'execscript'=>$scriptres);
			echo json_encode($data);
		}
	}
	
	public function tbx_downvote()
	{
		global $mkey,$userobj;
	
		$tid=$_POST["threadid"];
		$msgid=$_POST["msgid"];
	
		$socialobj=new ta_socialoperations();
		$msgobj=new ta_messageoperations();
	
		$msgres=$msgobj->getmsg($tid,"","",$msgid);
	
		$rateid=$msgres[0][changesqlquote(tbl_message_content::col_rateid,"")];
	
		$scriptres='';
		
		if($socialobj->rating_down($userobj->uid,$rateid,"11")==FAILURE)
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to rate down!" );
			die(json_encode($data));
		}
		else
		{
			
			$curup=$socialobj->rating_get_upvotes($rateid);
			$curdown=$socialobj->rating_get_downvotes($rateid);
			$views=$msgobj->get_total_views($tid, $msgid);
			$scriptres.='
				<script type="text/javascript">
				$(".pd_upv_'.$tid.'").html("'.abs($curup).'");
				$(".pd_dv_'.$tid.'").html("'.abs($curdown).'");
				$(".pd_views_'.$tid.'").html("'.abs($views).'");
				</script>
				';
			
			$data = array( 'returnval' =>1, 'message' =>"Successfully Rated Down!",'execscript'=>$scriptres);
			echo json_encode($data);
		}
	}
	
	public function tbx_post_view()
	{
		global $mkey,$userobj;
		
		$tid=$_POST["tid"];
		$msgid=$_POST["msgid"];
		$msgobj=new ta_messageoperations();
		if($msgobj->assignreadstatus($userobj->uid,$tid,$msgid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Assigned Read Status successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to assign Read Status!" );
			echo json_encode($data);
		}
	}
	
	public function tbx_sharepost()
	{
		global $mkey,$userobj;
		
		$tid=$_POST["threadid"];
		$msgid=$_POST["msgid"];
		$content_new=$_POST["thread_extra"];
		$subject_new=$_POST["thread_lbl"];
		
		$msgobj=new ta_messageoperations();
		$audobj=new ta_audience();
		
		$audid_new=$audobj->audience_create();
		
		if($msgobj->thread_share($tid,$msgid,$userobj->uid,$audid_new, $subject_new, $content_new)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Post Shared successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to Share Post!" );
			die(json_encode($data));
		}
	}
	
	public function tbx_ptag()
	{
		global $mkey,$userobj;
		
		$tags=$_POST["globalsender"];
		$reselem=$_POST["reselem"];		
		$tags = explode(',', $tags);
		
		$msgobj=new ta_messageoperations();
		$colid=$msgobj->tagpost_addcol($tags,"");
		if($colid!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>'
				<script type="text/javascript">
				$("'.$reselem.'").attr("data-tagcol","'.$colid.'");
				$(".alert-text").html("The tags has been added successfully to the Post");
				$(".alert-dismissable").show();
				</script>
			' );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to add Tag to Post!" );
			die(json_encode($data));
		}
	}
	
	public function nbx_delnot()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$nid=$_POST["nid"];
		
		if($socialobj->deletenotification($nid)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Notification deleted successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to delete notification!" );
			die(json_encode($data));
		}
	}
	
	public function gp_new()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpname=$_POST["gp-name"];
		$gpdesc=$_POST["gp-desc"];
		$gpvis=$_POST["gp-vis"];
		$gpapproval=$_POST["gp-approval"];
		
		if($socialobj->creategroup($gpname, $userobj->uid,$gpvis,$gpdesc,"","","1",$gpapproval)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Group created successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-3, 'message' =>"Unable to create Group!" );
			die(json_encode($data));
		}
	}
	
	public function gpbx_apreq()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpid=$_POST["gpid"];
		$memuid=$_POST["memuid"];
		
		$scriptres='';
		$gpres=$socialobj->groups_get($gpid);
		$memtype=$gpres[0][changesqlquote(tbl_groups_info::col_gpmemtype,"")];
		$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
		switch($memtype)
		{
			case "2":
				if($socialobj->group_user_check_admin($gpid,$userobj->uid))
				{
					$socialobj->groups_mem_edit($gpid,$memuid,tbl_members_attached::col_memflag,"1");
					
					$notcontent="You request to join the Group ".$gpname." has been accepted by the admin";
					$notlink="/social_groups.php?gpid=".$gpid;
					$noticon="/master/securedir/m_images/img_icons/group_forum.png";
					$scriptres.=$socialobj->sendnotification($memuid, $notcontent,"10",$noticon, $notlink,"2","","",$gpid);
					
					$data = array( 'returnval' =>1, 'message' =>"Request Approved!",'execscript'=>$scriptres);
					echo json_encode($data);
				}
				else
				{
					$data = array( 'returnval' =>-1, 'message' =>"Unable to approve request!" );
					echo json_encode($data);
				}
				break;
			case "3":
				if($socialobj->group_user_check($gpid,$userobj->uid))
				{
					$socialobj->groups_mem_edit($gpid,$memuid,tbl_members_attached::col_memflag,"1");
					
					$acceptorname=$userobj->user_getfullname($userobj->uid);
					
					$notcontent="You request to join the Group ".$gpname." has been accepted by ".$acceptorname;
					$notlink="/social_groups.php?gpid=".$gpid;
					$noticon="/master/securedir/m_images/img_icons/group_forum.png";
					$scriptres.=$socialobj->sendnotification($memuid, $notcontent,"10",$noticon, $notlink,"2","","",$gpid);
					
					$data = array( 'returnval' =>1, 'message' =>"Request Approved!",'execscript'=>$scriptres);
					echo json_encode($data);
				}
				else
				{
					$data = array( 'returnval' =>-1, 'message' =>"Unable to approve request!" );
					echo json_encode($data);
				}
				break;
			case "4":
				$data = array( 'returnval' =>-1, 'message' =>"Unable to approve request!" );
				echo json_encode($data);
				break;
		}
	}
	
	public function gpbx_blockreq()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpid=$_POST["gpid"];
		$memuid=$_POST["memuid"];
		
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if(!$socialobj->group_user_check_creator($gpid,$memuid))
			{
				$socialobj->groups_mem_edit($gpid,$memuid,tbl_members_attached::col_memflag,"3");
				$data = array( 'returnval' =>1, 'message' =>"User Blocked from this group!" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Unable to block user since he/she is the creator of this group!" );
				echo json_encode($data);
			}
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Unable to block request!" );
			echo json_encode($data);
		}
	}
	
	public function gpbx_unblockreq()
	{
		global $mkey,$userobj;
	
		$socialobj=new ta_socialoperations();
	
		$gpid=$_POST["gpid"];
		$memuid=$_POST["memuid"];
	
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if(!$socialobj->group_user_check_creator($gpid,$memuid))
			{
				$socialobj->groups_mem_edit($gpid,$memuid,tbl_members_attached::col_memflag,"1");
				$data = array( 'returnval' =>1, 'message' =>"User is unblocked from this group!" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Unable to unblock user!" );
				echo json_encode($data);
			}
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Unable to unblock user!" );
			echo json_encode($data);
		}
	}
	
	public function s_toggroup()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpid=$_POST["gpid"];
		$gpres=$socialobj->groups_get($gpid);
		$memtype=$gpres[0][changesqlquote(tbl_groups_info::col_gpmemtype,"")];
		$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
		
		
		$uid=$userobj->uid;
		$scriptres='';
		if(!$socialobj->group_user_check_creator($gpid, $uid))
		{
			if(!$socialobj->group_user_check($gpid,$uid))
			{
				if($socialobj->group_user_check_processing($gpid, $uid))
				{
					$socialobj->deletegpmember($gpid,$uid);
					$data = array( 'returnval' =>1, 'message' =>'We feel upset about you leaving this group :( Do come back again!','execscript'=>$scriptres);
					echo json_encode($data);
					return ;
				}
				else
				if($socialobj->addgpmember($gpid, $uid)==SUCCESS)
				{
					switch ($memtype)
					{
						case "1":
							$adminres=$socialobj->group_get_admins($gpid);
							for($i=0;$i<count($adminres);$i++)
							{
								$adminuid=$adminres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
								$notcontent="You have new member(s) in your Group (".$gpname.")";
								$notlink="/social_groups.php?gpid=".$gpid;
								$noticon="/master/securedir/m_images/img_icons/group_forum.png";
								$scriptres.=$socialobj->sendnotification($adminuid, $notcontent,"4",$noticon, $notlink,"2","","",$gpid);
							}
							break;
						case "2":
							$adminres=$socialobj->group_get_admins($gpid);
							for($i=0;$i<count($adminres);$i++)
							{
								$adminuid=$adminres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
								$notcontent="People have requested to join your Group (".$gpname.")";
								$notlink="/social_groups.php?gpid=".$gpid;
								$noticon="/master/securedir/m_images/img_icons/group_forum.png";
								$scriptres.=$socialobj->sendnotification($adminuid, $notcontent,"4",$noticon, $notlink,"2","","",$gpid);
							}
							break;
						case "3":
							$adminres=$socialobj->group_get_mem($gpid,"","");
							for($i=0;$i<count($adminres);$i++)
							{
								$adminuid=$adminres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
								$notcontent="People have requested to join a Group (".$gpname.")";
								$notlink="/social_groups.php?gpid=".$gpid;
								$noticon="/master/securedir/m_images/img_icons/group_forum.png";
								$scriptres.=$socialobj->sendnotification($adminuid, $notcontent,"4",$noticon, $notlink,"2","","",$gpid);
							}
							break;
						case "4":
							$adminres=$socialobj->group_get_admins($gpid);
							for($i=0;$i<count($adminres);$i++)
							{
								$adminuid=$adminres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
								$notcontent="People have been blocked from joining your Group (".$gpname.")";
								$notlink="/social_groups.php?gpid=".$gpid;
								$noticon="/master/securedir/m_images/img_icons/group_forum.png";
								$scriptres.=$socialobj->sendnotification($adminuid, $notcontent,"4",$noticon, $notlink,"2","","",$gpid);
							}
							break;
						default:
							$adminres=$socialobj->group_get_mem($gpid,"","");
							for($i=0;$i<count($adminres);$i++)
							{
								$adminuid=$adminres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
								$notcontent="People have requested to join a Group (".$gpname.")";
								$notlink="/social_groups.php?gpid=".$gpid;
								$noticon="/master/securedir/m_images/img_icons/group_forum.png";
								$scriptres.=$socialobj->sendnotification($adminuid, $notcontent,"4",$noticon, $notlink,"2","","",$gpid);
							}
							break;
					}
				}
				if($memtype!="1")
				{
					$data = array( 'returnval' =>1, 'message' =>'Your request to Join this Group is currently processed! Thank you!','execscript'=>$scriptres);
					echo json_encode($data);
				}
				else
				{
					$data = array( 'returnval' =>1, 'message' =>'Your request has been accepted! Welcome to the group!','execscript'=>$scriptres);
					echo json_encode($data);
				}
			}
			else
			{
				$socialobj->deletegpmember($gpid,$uid);
				$data = array( 'returnval' =>1, 'message' =>'We feel upset about you leaving this group :( Do come back again!','execscript'=>$scriptres);
				echo json_encode($data);
			}
		}
	}
	
	public function tbx_postdel()
	{
		global $mkey,$userobj;
		
		$msgobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		
		$tid=$_POST["threadid"];
		$tres=$msgobj->getthreadoutline($tid);
		$fuid=$tres[0][changesqlquote(tbl_message_outline::col_fid,"")];
		$msgtype=$tres[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
		if($msgtype=="5")
		{
			$gtres=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_tid."='$tid' LIMIT 0,1", tbl_threads_attached::dbname);
			$gpid=$gtres[0][changesqlquote(tbl_threads_attached::col_gpid,"")];
		}
		if(($userobj->uid==$fuid)||($msgtype=="5"&&$socialobj->group_user_check_admin($gpid, $userobj->uid)))
		{
			$socialobj->attachedthread_remove($gpid, $tid);
			if($msgobj->deletethread($tid, $userobj->uid)!=SUCCESS)
			{
				$data = array( 'returnval' =>-1, 'message' =>"Unable to delete Post!" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>1, 'message' =>"Post deleted successfully!" );
				echo json_encode($data);
			}
		}
		else
		{
			$data = array( 'returnval' =>-2, 'message' =>"Unable to delete Post since you did not post it!" );
			echo json_encode($data);
		}
	}
	
	public function gpbx_makeadmin()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpid=$_POST["gpid"];
		$memuid=$_POST["memuid"];
		if($socialobj->group_user_check_admin($gpid, $userobj->uid))
		{
			$socialobj->groups_mem_edit($gpid, $memuid, tbl_members_attached::col_memrole,"2");
			$data = array( 'returnval' =>1, 'message' =>"The user has been made admin successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Only admins are allowed to make other users as admins!" );
			echo json_encode($data);
		}
	}
	
	public function gpbx_remadmin()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$gpid=$_POST["gpid"];
		$memuid=$_POST["memuid"];
		if($socialobj->group_user_check_admin($gpid, $userobj->uid)&&(!$socialobj->group_user_check_creator($gpid, $memuid)))
		{
			$socialobj->groups_mem_edit($gpid, $memuid, tbl_members_attached::col_memrole,"1");
			$data = array( 'returnval' =>1, 'message' =>"The user has been removed from admin position successfully!" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"The admin position of this user cannot be removed!" );
			echo json_encode($data);
		}
	}
	
	public function gp_edit_vis()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		
		$name=$_POST["name"];
		$val=$_POST["value"];
		$gpid=$_POST["pk"];
		
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if($socialobj->group_edit($gpid,tbl_groups_info::col_gpprivacy,$val)==SUCCESS)
			{
				$data = array( 'returnval' =>1, 'message' =>"Success" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Failure" );
				echo json_encode($data);
			}
		}
	}
	
	public function gp_edit_desc()
	{
		global $mkey,$userobj;
	
		$socialobj=new ta_socialoperations();
	
		$name=$_POST["name"];
		$val=$_POST["value"];
		$gpid=$_POST["pk"];
	
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if($socialobj->group_edit($gpid,tbl_groups_info::col_gpdesc,$val)==SUCCESS)
			{
				$data = array( 'returnval' =>1, 'message' =>"Success" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Failure" );
				echo json_encode($data);
			}
		}
	}
	
	public function gp_edit_gpname()
	{
		global $mkey,$userobj;
	
		$socialobj=new ta_socialoperations();
	
		$name=$_POST["name"];
		$val=$_POST["value"];
		$gpid=$_POST["pk"];
	
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if($socialobj->group_edit($gpid,tbl_groups_info::col_gpname,$val)==SUCCESS)
			{
				$data = array( 'returnval' =>1, 'message' =>"Success" );
				echo json_encode($data);
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Failure" );
				echo json_encode($data);
			}
		}
	}
	
	public function gp_cvrpic_set()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		$gpid=$_POST["gpid"];
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
			if(count($attachments)!=0)
			{
				if($socialobj->group_edit($gpid, tbl_groups_info::col_gppic, $attachments[0])==SUCCESS)
				{
					$data = array( 'returnval' =>1, 'message' =>"Success" );
					echo json_encode($data);
				}
				else
				{
					$data = array( 'returnval' =>-1, 'message' =>"Failure" );
					echo json_encode($data);
				}
			}
			else
			{
				$data = array( 'returnval' =>-1, 'message' =>"Failure" );
				echo json_encode($data);
			}
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure" );
			echo json_encode($data);
		}
	}
	
	public function gp_edit_set()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		$gpid=$_POST["gpid"];
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		if($socialobj->group_user_check_admin($gpid,$userobj->uid))
		{
				$socialobj->group_edit($gpid, tbl_groups_info::col_gpmemtype,$_POST["gp-approval"]);
				$data = array( 'returnval' =>1, 'message' =>"Success" );
				echo json_encode($data);
				
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure" );
			echo json_encode($data);
		}
	}
	
	public function gp_deactivate()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		$gpid=$_POST["gpid"];
		
		if($socialobj->group_user_check_creator($gpid,$userobj->uid))
		{
			$socialobj->group_edit($gpid, tbl_groups_info::col_gpflag, "3");
			$data = array( 'returnval' =>1, 'message' =>"The Group has been successfully deactivated" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure Deactivating the group");
			echo json_encode($data);
		}
	}
	
	public function gp_activate()
	{
		global $mkey,$userobj;
		
		$socialobj=new ta_socialoperations();
		$gpid=$_POST["gpid"];
		
		if($socialobj->group_user_check_creator($gpid,$userobj->uid))
		{
			$socialobj->group_edit($gpid, tbl_groups_info::col_gpflag, "1");
			$data = array( 'returnval' =>1, 'message' =>"The Group has been successfully activated" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure Activating the group");
			echo json_encode($data);
		}
	}
	
	public function intouch_fbpgadd()
	{
		global $mkey,$userobj;
		
		$inobj=new ta_intouch();
		$in_pgurl=$_POST["in_fb_pgurl"];
		$in_fb_pglbl=$_POST["in_fb_pglbl"];
		$jsondata='{"pgurl":"'.$in_pgurl.'","pglbl":"'.$in_fb_pglbl.'"}';
		if($inobj->intouch_add_element($userobj->uid,"1","1", $jsondata)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"The Facebook Page has been successfully added" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure adding the Facebook Page");
			echo json_encode($data);
		}
	}
	
	public function set_edit_proflink()
	{
		global $mkey,$userobj;
		
		$setobj=new ta_settings();
		$dbobj=new ta_dboperations();
		
		$name=$_POST["name"];
		$proflink=$_POST["value"];
		$usrid=$_POST["pk"];
		
		if(count($dbobj->dbquery("SELECT * FROM ".tbl_user_settings::tblname." WHERE ".tbl_user_settings::col_mainkey."='set_acct' AND ".tbl_user_settings::col_subkey."='proflink' AND ".tbl_user_settings::col_setval."='$proflink'", tbl_user_settings::dbname))!=0)
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure! Someone else has already chosen this Profile Link" );
			echo json_encode($data);
			return;
		}
		$setobj->setting_remove($userobj->uid,"set_acct","proflink");
		if($setobj->setting_add($userobj->uid,"set_acct","proflink","1",$proflink)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure" );
			echo json_encode($data);
		}
	}
	
	public function set_edit_comemail()
	{
		global $mkey,$userobj;
		
		$setobj=new ta_settings();
		
		$name=$_POST["name"];
		$emailcom=$_POST["value"];
		$usrid=$_POST["pk"];
		
		$setobj->setting_remove($userobj->uid,"set_acct","email_com");
		if($setobj->setting_add($userobj->uid,"set_acct","email_com","1",$emailcom)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure" );
			echo json_encode($data);
		}
	}
	
	public function set_edit_uname()
	{
		global $mkey,$userobj;
	
		$name=$_POST["name"];
		$newuname=$_POST["value"];
		$usrid=$_POST["pk"];
	
		if($userobj->user_editinfo($userobj->uid, tbl_user_info::col_unm,$newuname)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure! May be someone else has chosen this user name already!" );
			echo json_encode($data);
		}
	}
	
	public function media_edit_desc()
	{
		global $mkey,$userobj;
	
		$galobj=new ta_galleryoperations();
		
		$name=$_POST["name"];
		$description=$_POST["value"];
		$mediaid=$_POST["pk"];
	
		$medres=$galobj->media_get_info($mediaid);
		$mediauid=$medres[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		$galid=$medres[0][changesqlquote(tbl_galdb::col_galid,"")];
		
		if($mediauid!=$userobj->uid)
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure! You are not allowed to edit this description!" );
			echo json_encode($data);
		}
		
		if($galobj->editmediainfo($mediaid, $galid,tbl_galdb::col_mediadesc,$description)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!" );
			echo json_encode($data);
		}
	}
	
	public function rec_ignore() 
	{
		global $mkey,$userobj;
		
		$fuid=$_POST["fuid"];
		$colobj=new ta_collection();
		$dbobj=new ta_dboperations();
		$col_uid=$userobj->extras->recommendations;
		
		$colarr=Array();
		$colarr[0][tbl_collection_users::col_uid]=$fuid;
		
		if($col_uid=="")
		{
			$col_uid=$colobj->add_collection(tbl_collection_users::tblname,$colarr,tbl_collection_users::col_col_uid,"");
			$dbobj->dbupdate("UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_recommendations."='$col_uid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'", tbl_user_extras::dbname);
			
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
			return;
		}
		
		if(!$colobj->check_collection_item(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_uid,tbl_collection_users::col_uid,$fuid))
		{
			$colobj->add_collection(tbl_collection_users::tblname,$colarr,tbl_collection_users::col_col_uid,$col_uid);
		}
		
		$data = array( 'returnval' =>1, 'message' =>"Success" );
		echo json_encode($data);
	}
	
	public function rec_ignore_gp()
	{
		global $mkey,$userobj;
		
		$gpid=$_POST["gpid"];
		$colobj=new ta_collection();
		$dbobj=new ta_dboperations();
		$col_gpid=$userobj->extras->rec_gp;
		
		$colarr=Array();
		$colarr[0][tbl_collection_groups::col_gpid]=$gpid;
		
		if($col_gpid=="")
		{
			$col_gpid=$colobj->add_collection(tbl_collection_groups::tblname,$colarr,tbl_collection_groups::col_col_gpid,"");
			$dbobj->dbupdate("UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_rec_groups."='$col_gpid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'", tbl_user_extras::dbname);
				
			$data = array( 'returnval' =>1, 'message' =>"Success" );
			echo json_encode($data);
			return;
		}
		
		if(!$colobj->check_collection_item(tbl_collection_groups::tblname,tbl_collection_groups::col_col_gpid,$col_gpid,tbl_collection_groups::col_gpid,$gpid))
		{
			$colobj->add_collection(tbl_collection_groups::tblname,$colarr,tbl_collection_groups::col_col_gpid,$col_gpid);
		}
		
		$data = array( 'returnval' =>1, 'message' =>"Success" );
		echo json_encode($data);
	}
	
	public function fdbk_submit()
	{
		global $mkey,$userobj;
		
		$url=$_POST["relurl"];
		$fdbk=$_POST["fdbk_request"];
		$fdbk_nature=$_POST["fdbk_nature"];
		$emailaddr=$_POST["fdbk_mail"];
		$perfobj=new ta_performance();
		$feedbackid=$perfobj->feedbackcomplaints_send("00000",$userobj->uid,$url,$fdbk,$fdbk_nature,"",$emailaddr,"");
		if($feedbackid!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Successfully sent! Keep this reference no. for future reference:".$feedbackid );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"OOPS! An error occured in sending the data!");
			echo json_encode($data);
		}
	}
	
	public function cntct_submit()
	{
		global $mkey,$userobj;
		
		$url=$_POST["relurl"];
		$cnt=$_POST["cntct_request"];
		$cntct_nature=4;
		$emailaddr=$_POST["fdbk_mail"];
		$perfobj=new ta_performance();
		$feedbackid=$perfobj->feedbackcomplaints_send("00000",$userobj->uid,$url,$cnt,$cntct_nature,"",$emailaddr,"");
		if($feedbackid!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Successfully sent! Keep this reference no. for future reference:".$feedbackid );
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"OOPS! An error occured in sending the data!");
			echo json_encode($data);
		}
	}
	
	public function prof_files()
	{
		global $mkey,$userobj;
		
		$dbobj=new ta_dboperations();
		$galleryobj=new ta_galleryoperations();
		
		$profkey=$_POST["profkey"];
		$attachments=$_POST["attachments"];
		
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		$galid_att=$galleryobj->get_galid_special($userobj->uid,"16");
		
		$colatt=Array();
		$colatt[0][tbl_collection_media::col_mediaid]=$attachments[0];
		$colatt[0][tbl_collection_media::col_galid]=$galid_att;
		
		$medid=$colatt[0][tbl_collection_media::col_mediaid];
		
		//$col_medid=$colobj->add_collection(tbl_collection_media::tblname, $colatt, tbl_collection_media::col_col_mediaid,$colmedid);
		
		switch($profkey)
		{
			case "resume":
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_resume."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
			case "coverletter":
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_coverletter."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
			case "recommendations":
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_recommendations."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
			case "biodata":
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_biodata."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
			case "prof_bgpic":
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_bgprofpic."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
			default:
				$sql="UPDATE ".tbl_user_extras::tblname." SET ".tbl_user_extras::col_resume."='$medid' WHERE ".tbl_user_extras::col_uid."='$userobj->uid'";
				break;
		}
		
		if($dbobj->dbupdate($sql, tbl_user_extras::dbname)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success");
			echo json_encode($data);
		}
	}
	
	public function tbx_threadpic()
	{
		global $mkey,$userobj;
		
		$dbobj=new ta_dboperations();
		$galleryobj=new ta_galleryoperations();
		$msgobj=new ta_messageoperations();
		
		$tid=$_POST["tid"];
		$attachments=$_POST["attachments"];
		
		$attachments=json_decode(stripslashes($_POST["attachments"]));
		
		$galid_att=$galleryobj->get_galid_special($userobj->uid,"16");
		
		$colatt=Array();
		$colatt[0][tbl_collection_media::col_mediaid]=$attachments[0];
		$colatt[0][tbl_collection_media::col_galid]=$galid_att;
		
		$medid=$colatt[0][tbl_collection_media::col_mediaid];
		if($msgobj->thread_edit_outline($tid,tbl_message_outline::col_threadpic,$medid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success");
			echo json_encode($data);
		}
	}
	
	public function prof_lang_edit()
	{
		global $mkey,$userobj;
		
		$colobj=new ta_collection();
		$uiobj=new ta_uifriend();
		$logobj=new ta_logs();
		
		$lang=$_POST["lang"];
		
		$lang=stripslashes($lang);
		$lang=str_replace("[","",$lang);
		$lang=str_replace("]","",$lang);
		$lang=str_replace('"',"",$lang);
		
		$langarr = explode(',', $lang);
		
		$col_lid=$userobj->col_langid;
		if($col_lid=="")
		{
			$col_lid=$uiobj->randomstring(35,tadb::db_collection,tbl_collection_languages::tblname,tbl_collection_languages::col_collangid);
			$userobj->user_editinfo($userobj->uid,tbl_user_info::col_col_ulangid,$col_lid);
		}
		else
		{
			$colobj->remove_collection_complete(tbl_collection_languages::tblname,tbl_collection_languages::col_collangid,$col_lid);
		}
		$colarr=Array();
		for($i=0;$i<count($langarr);$i++)
		{
			$lid=$langarr[$i];
			$logobj->store_templogs("LOOP".$lid);
			$colarr[$i][tbl_collection_languages::col_langid]=$lid;
		}
		
		$colobj->add_collection(tbl_collection_languages::tblname,$colarr,tbl_collection_languages::col_collangid,$col_lid);
		
		$data = array( 'returnval' =>1, 'message' =>"Success!");
		echo json_encode($data);
	}
	
	public function prof_country_edit()
	{
		global $mkey,$userobj;
		
		$cnt=$_POST["cnt"];
		if($userobj->user_editinfo($userobj->uid,tbl_user_info::col_ucountry,$cnt)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success!");
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!");
			echo json_encode($data);
		}
	}
	
	public function prof_state_edit()
	{
		global $mkey,$userobj;
	
		$state=$_POST["state"];
		if($userobj->user_editinfo($userobj->uid,tbl_user_info::col_ustate,$state)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success!");
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!");
			echo json_encode($data);
		}
	}
	
	public function userstat_update()
	{
		global $mkey,$userobj;
		
		$loginstat=$_POST["stat"];
		$userobj->user_editinfo($userobj->uid,tbl_user_info::col_uloginstatus,$loginstat);
	}
	
	public function comnt_del()
	{
		global $mkey,$userobj;
		
		$tid=$_POST["threadid"];
		$msgid=$_POST["msgid"];
		
		$msgobj=new ta_messageoperations();
		if($msgobj->deletemsg($tid,$userobj->uid,$msgid)==SUCCESS)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success!");
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!");
			echo json_encode($data);
		}
	}
	
	public function tagcr_new()
	{
		global $mkey,$userobj;
		
		$newtag=$_POST["tag_new"];
		$desc=$_POST["tag_desc"];
		
		$msgobj=new ta_messageoperations();
		if($msgobj->tagpost_add($newtag,$userobj->uid,$desc)!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success!");
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!");
			echo json_encode($data);
		}
	}
	
	public function tbx_editlbl()
	{
		global $mkey,$userobj;
		
		$colobj=new ta_collection();
		$msgobj=new ta_messageoperations();
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		
		$tagid=$_POST["tagid"];
		$tid=$_POST["tid"];

		$tagres=$dbobj->dbquery("SELECT * FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_tid."='$tid' LIMIT 0,1",tbl_message_tags::dbname);
		$colid=$tagres[0][changesqlquote(tbl_message_tags::col_col_ptagid)];
		
		if($colid=="")
		{
			$colid=$uiobj->randomstring(35,tbl_collection_tagpost::dbname,tbl_collection_tagpost::tblname,tbl_collection_tagpost::col_col_tagid);
		}
		
		$colobj->remove_collection_complete(tbl_collection_tagpost::tblname,tbl_collection_tagpost::col_col_tagid,$colid);
		
		$dbobj->dbdelete("DELETE FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_tid."='$tid'",tbl_message_tags::dbname);
		
		$tagidarr=stripslashes($tagid);
		$tagidarr=str_replace("[","",$tagidarr);
		$tagidarr=str_replace("]","",$tagidarr);
		$tagidarr=str_replace('"',"",$tagidarr);
		
		$tagidarr = explode(',', $tagidarr);
		
		$msgobj->tagpost_addcol($tagidarr,$colid);
		$dbobj->dbinsert("INSERT INTO ".tbl_message_tags::tblname." (".tbl_message_tags::col_tid.",".tbl_message_tags::col_col_ptagid.") VALUES ('$tid','$colid')", tbl_message_tags::dbname);
		
		$data = array( 'returnval' =>1, 'message' =>"Success!");
		echo json_encode($data);
	}
	
	public function tbx_getlbl()
	{
		global $mkey,$userobj;
		
		$colobj=new ta_collection();
		$msgobj=new ta_messageoperations();
		$uiobj=new ta_uifriend();
		$dbobj=new ta_dboperations();
		
		$tid=$_POST["tid"];		
		$tagres=$dbobj->dbquery("SELECT * FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_tid."='$tid' LIMIT 0,1",tbl_message_tags::dbname);
		$colid=$tagres[0][changesqlquote(tbl_message_tags::col_col_ptagid)];
		
		$retarr=Array();
		if($colid=="")
		{
			return json_encode(Array());
		}
		else
		{
			$colres=$colobj->get_collection_complete_info(tbl_collection_tagpost::tblname,tbl_collection_tagpost::col_col_tagid,$colid);
			for($i=0;$i<count($colres);$i++)
			{
				$tagid=$colres[$i][changesqlquote(tbl_collection_tagpost::col_tagid,"")];
				array_push($retarr,$tagid);
			}
		}
		return json_encode($retarr);
	}
	
	public function load_cbx_lcont()
	{
		global $mkey,$userobj;
		
		$logobj=new ta_logs();
		$ores='';
		$tagid=$_POST["tagid"];
		$execscript='';
		if(isset($_POST["st"]))
		{
			$st=$_POST["st"];
		}
		else
		{
			$st=0;
		}
		
		if(isset($_POST["tot"]))
		{
			$tot=$_POST["tot"];
		}
		else
		{
			$tot=15;
		}
		
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		$logobj=new ta_logs();
		
		$inctid=Array();
		
		if($tagid=="-1")
		{
			$res=$msgobj->getuserthreadoutline($userobj->uid,$st,$tot,"1");
			$logobj->store_templogs(print_r($res,true));
			if($res==FAILURE||count($res)==0)
			{
				$initid=FAILURE;
				$ores.= "Looks empty here! Nothing more to show.<br>";
				$execscript.='<script type="text/javascript">
						$(".ldmore_cbx_lcont").prop("disabled",true);
						</script>';
			}
			else
			{
				$st=$st+$tot;
				$initid=$res[0][changesqlquote(tbl_message_incoming::col_tid,"")];
				for($i=0;$i<count($res);$i++)
				{
					$tid=$res[$i][changesqlquote(tbl_message_incoming::col_tid,"")];
					$latestupdate=$res[$i][changesqlquote(tbl_message_incoming::col_rtime,"")];
					$res1=$msgobj->getthreadoutline($tid);
					$mtype=$res1[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
					if($mtype!="1"&&$mtype!="2")
					{
						if($initid==$tid)$initid="";
						continue;
					}
					if($initid=="")$initid=$tid;
					$no_msg_unread=$msgobj->get_no_unread_messages($userobj->uid,$tid);
					$res2=$msgobj->get_thread_audience($tid);
					$audno=count($res2);
					if($no_msg_unread==0)
					{
						$tico='<span class="convbx_convstaticon badge"><i class="fa fa-check"></i></span>';
					}
					else
					{
						$tico='<span class="convbx_convstaticon badge"><i class="fa fa-reply"></i></span>';
					}
					$tpicurl=$msgobj->get_threadpic($tid);
					$ores.=
					'
				<li class="list-group-item convbx_overview" data-threadid="'.$tid.'">
					<small class="convbx_convtime">'.$latestupdate.'</small>
					<kbd class="convbx_newbadge">'.$no_msg_unread.'</kbd>
					'.$tico.'
					<img alt="" src="'.$tpicurl.'" width="50" height="50" class="convbx_thumbimg">
					<small>'.$res1[0][changesqlquote(tbl_message_outline::col_subject,"")].'</small>
				</li>
			';
				}
			}
		}
		else
		{
			$colres=$dbobj->dbquery("SELECT * FROM ".tbl_collection_tagpost::tblname." WHERE ".tbl_collection_tagpost::col_tagid."='$tagid' LIMIT $st,$tot", tbl_collection_tagpost::dbname);
			for($i=0;$i<count($colres);$i++)
			{
				$col_tagid=$colres[$i][changesqlquote(tbl_collection_tagpost::col_col_tagid,"")];
				$tres=$dbobj->dbquery("SELECT * FROM ".tbl_message_tags::tblname." WHERE ".tbl_message_tags::col_col_ptagid."='$col_tagid' LIMIT 0,1", tbl_message_tags::dbname);
				$tid=$tres[0][changesqlquote(tbl_message_tags::col_tid,"")];
				if($tid=="")continue;
				array_push($inctid,$tid);
			}
			
			if(count($inctid)==0)
			{
				$ores.= "Looks empty here! Nothing more to show.";
				$execscript.='<script type="text/javascript">
						$(".ldmore_cbx_lcont").prop("disabled",true);
						</script>';
			}
			else
			{
				$st=$st+$tot;
			}
			
			for($i=0;$i<count($inctid);$i++)
			{
				$th=$msgobj->getthreadoutline($inctid[$i]);
				$latestupdate=$th[0][changesqlquote(tbl_message_outline::col_lastupdate,"")];
					
				$mtype=$th[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
				$no_msg_unread=$msgobj->get_no_unread_messages($userobj->uid,$inctid[$i]);
				$res2=$msgobj->get_thread_audience($inctid[$i]);
				$audno=count($res2);
				if($no_msg_unread==0)
				{
					$tico='<span class="convbx_convstaticon badge"><i class="fa fa-check"></i></span>';
				}
				else
				{
					$tico='<span class="convbx_convstaticon badge"><i class="fa fa-reply"></i></span>';
				}
				$tpicurl=$msgobj->get_threadpic($inctid[$i]);
					
					
					
					
				$ores.='<li class="list-group-item convbx_overview" data-threadid="'.$inctid[$i].'">
					<small class="convbx_convtime">'.$latestupdate.'</small>
					<kbd class="convbx_newbadge">'.$no_msg_unread.'</kbd>
					'.$tico.'
					<img alt="" src="'.$tpicurl.'" width="50" height="50" class="convbx_thumbimg">
					<small>'.$th[0][changesqlquote(tbl_message_outline::col_subject,"")].'</small>
				</li>';
			}
		}
		
		return json_encode(Array('op'=>$ores,'st'=>$st,'execscript'=>$execscript));
	}
	
	public function list_new_cr($ltype="4")
	{
		global $mkey,$userobj;
		
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$listname=$_POST["list-name"];
		$listdesc=$_POST["list-desc"];
		
		if($socialobj->createlist($listname,$userobj->uid,$ltype,$listdesc,"1")!=FAILURE)
		{
			$data = array( 'returnval' =>1, 'message' =>"Success!");
			echo json_encode($data);
		}
		else
		{
			$data = array( 'returnval' =>-1, 'message' =>"Failure!");
			echo json_encode($data);
		}
	}
	
}

?>