<?php 
header('Content-Type: application/json');
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
$socialobj=new ta_socialoperations();
$logobj=new ta_logs();
$dbobj=new ta_dboperations();
$boxobj=new ta_box();
$reqobj=new ta_requests();

$mkey=$_POST["mkey"];

if(!$userobj->checklogin())
{
	$data = array( 'returnval' =>-1, 'message' =>"You must be logged in to do this operation!" );
	die(json_encode($data));
}

if($userobj->checklogin())
{
	$userobj->userinit();
}

switch($mkey)
{
	case "box_gal_newpic":$galid=$_POST["galid"];echo $boxobj->box_gal_newpic($galid);break;
	case "box_fileupload":$galid=$_POST["galid"];if(isset($_POST["uptype"])){$uptype=$_POST["uptype"];}else{$uptype="-1";} echo $boxobj->box_fileupload($galid,$uptype,$userobj->uid);break;;
	case "box_gal_new":if(isset($_POST["galtype"])){$galtype=$_POST["galtype"];}else{$galtype="";} echo $boxobj->box_gal_new($galtype);break;
	case "s_reportfrnd":$fuid=$_POST["fuid"];$link="https://www.friendbus.com/users.php?uid=".$fuid;echo $boxobj->box_report($fuid,$link,"1");break;
	case "box_audience":echo $boxobj->box_audience();break;
	case "box_thread_new":echo $boxobj->box_thread_new();break;
	case "box_participants_add":echo $boxobj->box_th_participants_add();break;
	case "tbx_share":echo $boxobj->tbx_share();break;
	case "box_smileys":echo $boxobj->box_smileys();break;
	case "box_ptag":echo $boxobj->box_ptag($userobj->uid);break;
	case "tbx_getlink":echo $boxobj->tbx_getlink();break;
	case "box_viewgpmem":echo $boxobj->box_viewgpmem($userobj->uid);break;
	case "box_viewgpadmins":echo $boxobj->box_viewgpadmins($userobj->uid);break;
	case "box_viewgpreq":echo $boxobj->box_viewgpreq($userobj->uid);break;
	case "box_viewgpblkusers":echo $boxobj->box_viewgpblkusers($userobj->uid);break;
	case "box_gp_inviteppl";echo $boxobj->box_gp_inviteppl($userobj->uid);break;
	case "box_question_ask":echo $boxobj->box_question_ask($userobj->uid);break;
	case "box_gp_edit_set":echo $boxobj->box_gp_edit_set($userobj->uid);break;
	case "intouch_fb_addpage":echo $boxobj->intouch_fb_addpage($userobj->uid);break;
	case "box_medviewer":echo $boxobj->box_medviewer();break;
	case "tbx_reportpost":$tid=$_POST["threadid"];$link="https://www.friendbus.com/post_display.php?tid=".$tid;echo $boxobj->box_report($tid,$link,"9");break;
	case "box_viewupvoters":echo $boxobj->box_viewupvoters();break;
	case "box_tagcreate":echo $boxobj->box_tagcreate();break;
	case "box_lists_new":echo $boxobj->box_lists_new();break;
	
	case "contacts_newbook":$reqobj->r_contacts_newbook();break;
	case "contact_flvl":$reqobj->r_contact_flvl();break;
	case "contact_fnick":$reqobj->r_contact_fnick();break;
	case "contact_flisttog":$reqobj->r_contact_flisttog();break;
	case "contacts_togfollow":$reqobj->r_contacts_togfollow();break;
	case "contacts_load":$elem=$_POST["elem"];require_once 'contacts_all.php';break;
	case "s_togfriend":$reqobj->r_s_togfriend();break;
	case "s_removefriend":$reqobj->r_s_removefriend();break;
	case "s_togblockfriend":$reqobj->r_s_togblockfriend();break;
	case "s_repsubmit":$reqobj->r_s_repsubmit();break;
	case "s_remflist":$reqobj->r_s_remflist();break;
	case "gbx_newgal":$reqobj->gbx_newgal();break;
	case "gbx_galopen_pic":$reqobj->gbx_galopen_pic();break;
	case "gbx_galpic_upload":$reqobj->gbx_galpic_upload();break;
	case "gbx_pic_remove":$reqobj->gbx_pic_remove();break;
	case "gbx_pic_galdel":
	case "gbx_vid_galdel":
	case "gbx_doc_galdel":
	case "gbx_sbx_del":
			$reqobj->gbx_galdel();break;
	case "gbx_galopen_vid":$reqobj->gbx_galopen_vid();break;
	case "gbx_galopen_doc":$reqobj->gbx_galopen_doc();break;
	case "gbx_vid_infoload":$reqobj->gbx_vid_infoload();break;
	case "gbx_vid_infodownload":$reqobj->gbx_vid_infodownload();break;
	case "gbx_vid_del":$reqobj->gbx_vid_del();break;
	case "gbx_doc_del":$reqobj->gbx_doc_del();break;
	case "gbx_aud_del":$reqobj->gbx_aud_del();break;
	case "gbx_va_del":$reqobj->gbx_va_del();break;
	case "tbx_newthread":$reqobj->tbx_thread_new();break;
	case "tbx_threadmsg":$reqobj->tbx_thread_sendmsg();break;
	case "tbx_delthread":$reqobj->tbx_thread_delete();break;
	case "tbx_addparticipants":$reqobj->tbx_thread_addparticipants();break;
	case "tbx_newpost":$reqobj->tbx_post_new();break;
	case "tbx_newcomment":$reqobj->tbx_comment_new();break;
	case "abx_addaudience":$reqobj->abx_addaudience();break;
	case "thread_upvote":$reqobj->tbx_upvote();break;
	case "thread_downvote":$reqobj->tbx_downvote();break;
	case "tbx_postview":$reqobj->tbx_post_view();break;
	case "tbx_sharepost":$reqobj->tbx_sharepost();break;
	case "tbx_ptag":$reqobj->tbx_ptag();break;
	case "nbx_delnot":$reqobj->nbx_delnot();break;
	case "gp_create":$reqobj->gp_new();break;
	case "gpbx_apreq":$reqobj->gpbx_apreq();break;
	case "gpbx_blockreq":$reqobj->gpbx_blockreq();break;
	case "gpbx_unblockreq":$reqobj->gpbx_unblockreq();break;
	case "s_toggroup":$reqobj->s_toggroup();break;
	case "tbx_postdel":$reqobj->tbx_postdel();break;
	case "gpbx_makeadmin":$reqobj->gpbx_makeadmin();break;
	case "gpbx_remadmin":$reqobj->gpbx_remadmin();break;
	case "gp_edit_vis":$reqobj->gp_edit_vis();break;
	case "gp_edit_desc":$reqobj->gp_edit_desc();break;
	case "gp_edit_gpname":$reqobj->gp_edit_gpname();break;
	case "ta_gp_invitemem":$reqobj->ta_gp_invitemem();break;
	case "gp_cvrpic_set":$reqobj->gp_cvrpic_set();break;
	case "gp_edit_set":$reqobj->gp_edit_set();break;
	case "gp_deactivate":$reqobj->gp_deactivate();break;
	case "gp_activate":$reqobj->gp_activate();break;
	case "intouch_fbpgadd":$reqobj->intouch_fbpgadd();break;
	case "set_edit_proflink":$reqobj->set_edit_proflink();break;
	case "set_edit_comemail":$reqobj->set_edit_comemail();break;
	case "set_edit_uname":$reqobj->set_edit_uname();break;
	case "media_edit_desc":$reqobj->media_edit_desc();break;
	case "rec_ignore":$reqobj->rec_ignore();break;
	case "rec_ignore_gp":$reqobj->rec_ignore_gp();break;
	case "fdbk_submit":$reqobj->fdbk_submit();break;
	case "cntct_submit":$reqobj->cntct_submit();break;
	case "prof_files":$reqobj->prof_files();break;
	case "prof_lang_edit":$reqobj->prof_lang_edit();break;
	case "prof_country_edit":$reqobj->prof_country_edit();break;
	case "prof_state_edit":$reqobj->prof_state_edit();break;
	case "userstat_update":$reqobj->userstat_update();break;
	case "comnt_del":$reqobj->comnt_del();break;
	case "tagcr_new":$reqobj->tagcr_new();break;
	case "tbx_editlbl":$reqobj->tbx_editlbl();break;
	case "tbx_getlbl":echo $reqobj->tbx_getlbl();break;
	case "load_cbx_lcont":echo $reqobj->load_cbx_lcont();break;
	case "tbx_threadpic":$reqobj->tbx_threadpic();break;
	case "list_new_cr":$reqobj->list_new_cr();break;
	default:
		$data = array( 'returnval' =>-10, 'message' =>"OOPS! Unidentified Request!" );
		die(json_encode($data));
	break;
		
}

?>