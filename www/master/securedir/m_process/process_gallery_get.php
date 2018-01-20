<?php
require_once 'adjustment.php';

ob_start();
header("content-type:text/xml");
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';

/**
 * dispstatus
 * 1-all
 * 2-all media
 * 3-all gallery
 * 4-parent details
 */
if((isset($_POST["galid"]))&&(isset($_POST["start"]))&&(isset($_POST["increment"]))&&(isset($_POST["dispstatus"])))
{
	$galid=$_POST["galid"];
	$start=$_POST["start"];
	$increment=$_POST["increment"];
	$dispstatus=$_POST["dispstatus"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

$userobj=new ta_userinfo();
$audienceobj=new ta_audience();
$pluginobj=new ta_plugins();
$dbobj=new ta_dboperations();
$galobj=new ta_galleryoperations();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "<document>";

$audienceid=$galobj->get_audience_gallery($galid);
//$audienceobj->audience_edit($audienceid,tbl_audience_target::col_loginreq,"2");

if(!($audienceobj->audience_check_exists($audienceid)))
{
	$audienceid="";
}

if((!($userobj->checklogin()))&&$audienceid!=""&&(!($audienceobj->audience_check_public($audienceid))))
{
	echo "<statusmsg>Not Logged In</statusmsg>";
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

echo "<dispstatus>".$dispstatus."</dispstatus>";

if($dispstatus=="2")
{
	$children_media=$galobj->get_children_media($galid);

	echo '<gallery_media_enclose galid="'.$galid.'">';
	for($i=0;$i<count($children_media);$i++)
	{
		$media_id=$dbobj->colval($children_media,tbl_galdb::col_mediaid,$i);
		$media_url=$dbobj->colval($children_media,tbl_galdb::col_mediaurl,$i);
		$media_name=$dbobj->colval($children_media,tbl_galdb::col_mediatitle,$i);
		$media_desc=$dbobj->colval($children_media,tbl_galdb::col_mediadesc,$i);
		$media_type=$dbobj->colval($children_media,tbl_galdb::col_mediatype,$i);
		$media_flag=$dbobj->colval($children_media,tbl_galdb::col_mediaflag,$i);
		$media_time=$dbobj->colval($children_media,tbl_galdb::col_mediatime,$i);
		$media_audienceid=$dbobj->colval($children_media,tbl_galdb::col_audienceid,$i);
		$media_tagid=$dbobj->colval($children_media,tbl_galdb::col_tagid,$i);

		echo '<gallery_media>';
		echo '<media_id>'.$media_id.'</media_id>';
		echo '<media_url>'.$media_url.'</media_url>';
		echo '<media_name>'.$media_name.'</media_name>';
		echo '<media_desc>'.$media_desc.'</media_desc>';
		echo '<media_type>'.$media_type.'</$media_type>';
		echo '<media_flag>'.$media_flag.'</media_flag>';
		echo '<media_time>'.$media_time.'</media_time>';
		echo '<media_audienceid>'.$media_audienceid.'</media_audienceid>';
		echo '<media_tagid>'.$media_tagid.'</media_tagid>';
		echo '</gallery_media>';
	}

	echo '</gallery_media_enclose>';
}
else
if($dispstatus=="3")
{
	$children_gallery=$galobj->get_children_gallery($galid);

	echo '<gallery_gallery_enclose galid="'.$galid.'">';
	for($i=0;$i<count($children_gallery);$i++)
	{
		$gal_id=$dbobj->colval($children_gallery,tbl_galinfo::col_galid,$i);
		$gal_audienceid=$dbobj->colval($children_gallery,tbl_galinfo::col_audienceid,$i);
		$gal_desc=$dbobj->colval($children_gallery,tbl_galinfo::col_galdesc,$i);
		$gal_flag=$dbobj->colval($children_gallery,tbl_galinfo::col_galflag,$i);
		$gal_name=$dbobj->colval($children_gallery,tbl_galinfo::col_galname,$i);
		$gal_pic=$dbobj->colval($children_gallery,tbl_galinfo::col_galpic,$i);
		$gal_time=$dbobj->colval($children_gallery,tbl_galinfo::col_galtime,$i);
		$gal_type=$dbobj->colval($children_gallery,tbl_galinfo::col_galtype,$i);
		$gal_parentgalid=$dbobj->colval($children_gallery,tbl_galinfo::col_parentgalid,$i);
		$gal_rateid=$dbobj->colval($children_gallery,tbl_galinfo::col_rateid,$i);
		$gal_tagid=$dbobj->colval($children_gallery,tbl_galinfo::col_tagid,$i);
		$gal_uid=$dbobj->colval($children_gallery,tbl_galinfo::col_uid,$i);

		echo '<gallery_media>';
		echo '<media_id>'.$media_id.'</media_id>';
		echo '<media_url>'.$media_url.'</media_url>';
		echo '<media_name>'.$media_name.'</media_name>';
		echo '<media_desc>'.$media_desc.'</media_desc>';
		echo '<media_type>'.$media_type.'</$media_type>';
		echo '<media_flag>'.$media_flag.'</media_flag>';
		echo '<media_time>'.$media_time.'</media_time>';
		echo '<media_audienceid>'.$media_audienceid.'</media_audienceid>';
		echo '<media_tagid>'.$media_tagid.'</media_tagid>';
		echo '</gallery_media>';
	}

	echo '</gallery_media_enclose>';
}

$comments=$messageobj->getcomments($tid,$start+$increment,$increment);

echo "</document>";
die('');

if(!($audienceobj->audience_check_user($audienceid,$userobj->uid)))
{
	echo "<statusmsg>Access Denied</statusmsg>";
	echo "<statuscode>5</statuscode>";
	echo "</document>";
	die('');
}

echo "</document>";

?>