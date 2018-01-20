<?php 
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	if(isset($_POST["feed_pgno"]))
	{
		$pgno=$_POST["feed_pgno"];
	}
	else
	{
		$pgno=1;
	}
	
	$dbobj=new ta_dboperations();
	$socialobj=new ta_socialoperations();
	$userobj=new ta_userinfo();
	$uobj=new ta_userinfo();
	$cuobj=new ta_userinfo();
	$ruobj=new ta_userinfo();
	$msgobj=new ta_messageoperations();
	$utilityobj=new ta_utilitymaster();
	$colobj=new ta_collection();
	$galobj=new ta_galleryoperations();
	$logobj=new ta_logs();
	$audobj=new ta_audience();
	
	$userobj->userinit();
	
	$GLOBALS["feed_curcount"]=0;
	$fres=$socialobj->getfriends($userobj->uid,"","");
	$totfriend=count($fres);
	$level=0;
	$totfinish=0;
	
	$ulvlarr=Array();
	
	$totload=8;
	$totcol=2;
	
	$mypres_1=$mypres_2='';
do
{
	for($i=0;$i<$totfriend;$i++)
	{
		$fuid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
		if(isset($ulvlarr[$fuid]["last"])&&$ulvlarr[$fuid]["last"]==1)continue;
		$audid="";
		$tlevel=$level;
		if(!isset($ulvlarr[$fuid]))
		{
			$ulvlarr[$fuid]["lvl"]=0;
		}
		do
		{
			$postres=$msgobj->get_recentpost($fuid,$ulvlarr[$fuid]["lvl"]);
			if(count($postres)==0||$postres==EMPTY_RESULT)
			{
				$ulvlarr[$fuid]["last"]=1;
				$totfinish++;
				break;
			}
			else
			{
				$ulvlarr[$fuid]["last"]=0;
			}
			$audid=$postres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
			$ulvlarr[$fuid]["lvl"]++;
			$audstat=$audobj->audience_check_user($audid,$userobj->uid);
		}while(!$audstat);
		
		if(count($postres)==0)continue;
		$mytid=$postres[0][changesqlquote(tbl_message_outline::col_tid,"")];
		$tid=$mytid;
		
		$contres=$msgobj->getmsg($tid,"0","1");
		$msgid=$contres[0][changesqlquote(tbl_message_content::col_msgid,"")];
		$mymsgid=$msgid;
		
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
		if($GLOBALS["feed_curcount"]==$totload)break;
	}
	if($GLOBALS["feed_curcount"]==$totload)break;
		
}while($totfinish!=$totfriend);

echo '<div class="col-lg-6 col-md-4 col-sm-6">'.$mypres_1.'</div>';
echo '<div class="col-lg-6 col-md-4 col-sm-6">'.$mypres_2.'</div>';

if($GLOBALS["feed_curcount"]==0)
{
	echo 'Your Feed seems to be empty! Try Adding some friends! You will be able to see when they make posts.';
}
	
?>