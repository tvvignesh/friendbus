<?php 
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
$socialobj=new ta_socialoperations();
$logobj=new ta_logs();
$dbobj=new ta_dboperations();
$boxobj=new ta_box();
$reqobj=new ta_requests();
$uiobj=new ta_uifriend();
$msgobj=new ta_messageoperations();

$mkey=$_GET["mkey"];

if(!$userobj->checklogin())
{
	echo 'You must be logged in to do this operation!';
}

if($userobj->checklogin())
{
	$userobj->userinit();
}

switch($mkey)
{
	case "ld_com_post":
		$comtid=$_GET["ctid"];
		$comstart=$_GET["cstart"];
		$comtot=$_GET["ctot"];
		$tid=$_GET["tid"];
		echo $uiobj->load_comments($tid,$comtid,$comstart,$comtot);
		break;
	case "ld_gal_pics":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		$galid=$_GET["galid"];
		$vuid=$_GET["vuid"];
		echo $uiobj->disp_gal_pic($vuid,$galid,$st,$tot);
		break;
	case "ld_gal_vids":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		$galid=$_GET["galid"];
		$vuid=$_GET["vuid"];
		echo $uiobj->disp_gal_vid($vuid,$galid,$st,$tot);
		break;
	case "ld_gal_docs":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		$galid=$_GET["galid"];
		$vuid=$_GET["vuid"];
		echo $uiobj->disp_gal_doc($vuid,$galid,$st,$tot);
		break;
	case "ld_notif_more":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		echo $uiobj->disp_notifications($userobj->uid,$st,$tot);
		break;
	case "gpmem_ldmore":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		$gpid=$_GET["gpid"];
		echo $uiobj->disp_group_mem($gpid,$st,$tot);
		break;
	case "ldmore_gppost":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		$gpid=$_GET["gpid"];
		echo json_encode($uiobj->disp_group_post($gpid,$st,$tot));
		break;
	case "fd_ldmore_gppost":
		$lvl=$_GET["lvl"];
		$gpres=$socialobj->getgroups($userobj->uid);
		$GLOBALS["feed_curcount"]=0;
		$totcol=2;
		$mypres_1=$mypres_2='';
		for($i=0;$i<count($gpres);$i++)
		{
			$gpid=$gpres[$i][changesqlquote(tbl_members_attached::col_gpid,"")];
			$gp=$socialobj->groups_get($gpid);
			$gpname=$gp[0][changesqlquote(tbl_groups_info::col_gpname,"")];
			$pres=$uiobj->disp_group_post($gpid,$lvl,"1");
		 	if($pres['col1']=="")continue;
		 	
		 	if($totcol==2)
		 	{
		 		if($GLOBALS["feed_curcount"]%2==0)
		 		{
		 			$mypres_1.='(<a href="/social_groups.php?gpid='.$gpid.'">'.$gpname.'</a>)'.$pres['col1'];
		 		}
		 		else
		 		{
		 			$mypres_2.='(<a href="/social_groups.php?gpid='.$gpid.'">'.$gpname.'</a>)'.$pres['col1'];
		 		}
		 		$GLOBALS["feed_curcount"]++;
		 	}
		}
		$lvl++;
		
		echo json_encode(Array('col1'=>$mypres_1,'col2'=>$mypres_2,'lvl'=>$lvl));
		break;
	case "fd_ldmore_wallpost":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		
		$st=intval($st);
		$GLOBALS["feed_curcount"]=0;
		$totcol=2;
		$mypres_1=$mypres_2='';
		
		$walres=$msgobj->get_userthreads($userobj->uid,"4",$st,$tot);
		for($i=0;$i<count($walres);$i++)
		{
			$mytid=$walres[$i][changesqlquote(tbl_message_outline::col_tid,"")];
		
			$pres=require 'post_display.php';
			if($totcol==2)
			{
				if($GLOBALS["feed_curcount"]%2==0)
				{
					$mypres_1.=$pres;
				}
				else
				{
					$mypres_2.=$pres;
				}
			}
		
			$GLOBALS["feed_curcount"]++;
		}
		
		if(count($walres)==0)
		{
			$mypres_1='Looks like there is nothing more to show..
					<script type="text/javascript">
					$(".fd_ldmore_wallpost").hide();
					</script>
					';
		}
		
		$st=$st+$tot;
		echo json_encode(Array('col1'=>$mypres_1,'col2'=>$mypres_2,'st'=>$st));
		
		break;
	case "fd_ldmore_featured":
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		
		$st=intval($st);
		$GLOBALS["feed_curcount"]=0;
		$totcol=2;
		$mypres_1=$mypres_2='';
		
		$fres=$msgobj->get_threads_featured($st,$tot);
		for($i=0;$i<count($fres);$i++)
		{
			$mytid=$fres[$i][changesqlquote(tbl_message_featured::col_tid,"")];
		
			$pres=require 'post_display.php';
			if($totcol==2)
			{
				if($GLOBALS["feed_curcount"]%2==0)
				{
					$mypres_1.=$pres;
				}
				else
				{
					$mypres_2.=$pres;
				}
			}
		
			$GLOBALS["feed_curcount"]++;
		}
		
		if(count($fres)==0)
		{
			$mypres_1='Looks like there is nothing more to show..
					<script type="text/javascript">
					$(".fd_ldmore_wallpost").hide();
					</script>
					';
		}
		
		$st=$st+$tot;
		echo json_encode(Array('col1'=>$mypres_1,'col2'=>$mypres_2,'st'=>$st));
		break;
	case "ldmore_upvtbox":
		$rateid=$_GET["rateid"];
		$st=$_GET["st"];
		$tot=$_GET["tot"];
		
		$ratres=$socialobj->rating_get_upvoters($rateid,$st,$tot);
		$upvoters='';
		for($i=0;$i<count($ratres);$i++)
		{
			$ratuid=$ratres[$i][changesqlquote(tbl_ratings::col_rateuid,"")];
			$fullname=$userobj->user_getfullname($ratuid);
			$pic=$utilityobj->pathtourl($userobj->getprofpic($ratuid));
			$upvoters.='<li class="list-group-item"> <img src="'.$pic.'" width="30" height="30" class="ta-rmargin"><a href="/users.php?uid='.$ratuid.'">'.$fullname.'</a></li>';
		}
		
		if(count($ratres)==0)
		{
			$upvoters='<br><br>Looks like there is nothing more to show..
					<script type="text/javascript">
					$(".ldmore_upvtbox").hide();
					</script>
					';
		}
		
		$st=$st+$tot;
		echo json_encode(Array('res'=>$upvoters,'st'=>$st,'tot'=>$tot));
		break;
		
		break;
	default:
		echo "";
		break;
}
?>