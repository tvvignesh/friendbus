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

if(isset($_GET["lvl"]))
{
	$lvl=$_GET["lvl"];
}
else
{
	$lvl=0;
	$GLOBALS["myl1"]=$lvl;
}

if(isset($_GET["st"]))
{
	$start=$_GET["st"];
}
else
{
	$start=0;
	$GLOBALS["mys1"]=$start;
}

if(isset($_GET["tot"]))
{
	$tot=$_GET["tot"];
}
else
{
	$tot=2;
	$GLOBALS["mye1"]=$tot;
}

$totcol=2;
$mypres_1=$mypres_2='';

$c=0;
$resarr1=Array();
$GLOBALS["myresarr1"]=Array();
$myres=$socialobj->feed_getcustom($userobj->uid,$lvl,$start,$tot,$resarr1,$c,$tot,$userobj->extras->cfeedaudid);

$myresarr=$GLOBALS["myresarr1"];

	$i=0;
	foreach($myresarr as $tid=>$feed)
	{
		$mytid=$tid;
		
		$contres=$msgobj->getmsg($tid,"0","1");
		$msgid=$contres[0][changesqlquote(tbl_message_content::col_msgid,"")];
		$mymsgid=$msgid;
		
		$pres=require 'post_display.php';
		
		if($totcol==2)
		{
			if($i%2==0)
			{
				$mypres_1.=$pres;
			}
			else
			{
				$mypres_2.=$pres;
			}
		}
		$i++;
	}
/*}*/
	
$fdarr=Array('col1'=>$mypres_1,'col2'=>$mypres_2,'st'=>$GLOBALS["mys1"],'lvl'=>$GLOBALS["myl1"]);

if(!isset($ajaxchk))
{
	echo json_encode($fdarr);
}
	
?>