<?php 
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$audobj=new ta_audience();
$msgobj=new ta_messageoperations();
$utilityobj=new ta_utilitymaster();
$galobj=new ta_galleryoperations();
$colobj=new ta_collection();

$userobj->userinit();

if(!(isset($tid)))
{
	if(isset($_POST["tid"]))
	{
		$tid=$_POST["tid"];
	}
	else
	if(isset($_GET["tid"]))
	{
		$tid=$_GET["tid"];
	}
	else
	{
		echo "Please select a thread";return FAILURE;
	}
}

$msgobj->get_thread_audienceid($tid);
$tres=$msgobj->getthreadoutline($tid);
$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
if(!$audobj->audience_check_user($audid,$userobj->uid))
{
	echo "Sorry! You don't have permission to access this thread";return FAILURE;
}

$mymsg=$msgobj->getmsg($tid);
$fulluname=$userobj->user_getfullname($userobj->uid);

for($i=count($mymsg)-1;$i>=0;$i--)
{
	$atttext='';
    $fuid=$mymsg[$i][changesqlquote(tbl_message_content::col_fuid,"")];
    $fmsg=$mymsg[$i][changesqlquote(tbl_message_content::col_msg,"")];
    $fmsgid=$mymsg[$i][changesqlquote(tbl_message_content::col_msgid,"")];
    $fmsgtime=$mymsg[$i][changesqlquote(tbl_message_content::col_msgtime,"")];
    $col_medid=$mymsg[$i][changesqlquote(tbl_message_content::col_col_mediaid,"")];
        			
    $colres=$colobj->get_collection_complete_info(tbl_collection_media::tblname, tbl_collection_media::col_col_mediaid, $col_medid);
    $totatt=count($colres);
        			
    for($a=0;$a<$totatt;$a++)
    {
    	$medid=$colres[$a][changesqlquote(tbl_collection_media::col_mediaid,"")];
    	$medgalid=$colres[$a][changesqlquote(tbl_collection_media::col_galid,"")];
    	$medres=$galobj->media_get_info($medid);
        $medtitle=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_mediatitle,"")]);
        $medurl=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_mediaurl,"")]);
        $atttext.='<a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$medid.'" data-galid="'.$medgalid.'">'.$medtitle.'</a><br>';
        
     }
        			
     if($totatt!=0)
     {
     	$atttext="<hr>Attached:<br>".$atttext;
     }
        			
     $fpic=$userobj->getprofpic($fuid);
     $fpic=$utilityobj->pathtourl($fpic);
     
     if($fuid==$userobj->uid)
     {
     	$cssclass="pull-right";
        $bub="convbx_bubble_right";
        $ful=$userobj->fname;
     }
     else
     {
        $cssclass="pull-left";
        $bub="convbx_bubble_left";
        $ful=$userobj->user_getfullname($fuid,"1");
     }
        			
     echo 
     '
     <div class="'.$cssclass.'">
	<img alt="" src="'.$fpic.'" width="50" height="50" class="convbx_cthumbimg">
	<br><br><div style="max-width:100px;"><a href="/users.php?uid='.$fuid.'">'.$ful.'</a></div>
	</div>
	<div class="'.$bub.' '.$cssclass.'">
	'.$fmsg.$atttext.'
	</div>
	<div style="clear:both;"></div>
	';
}
     if(count($mymsg)==0)
     {
     	echo "Its empty here! No messages yet! Try sending a message";
     }
 ?>