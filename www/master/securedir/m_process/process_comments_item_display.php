<?php
require_once 'adjustment.php';

ob_start();
header("content-type:text/xml");
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';

if((isset($_POST["rateid"]))&&(isset($_POST["start"]))&&(isset($_POST["increment"])))
{
	$rateid=$_POST["rateid"];
	$start=$_POST["start"];
	$increment=$_POST["increment"];
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
$messageobj=new ta_messageoperations();
$pluginobj=new ta_plugins();
$dbobj=new ta_dboperations();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "<document>";

$tid=$messageobj->get_item_comthreadid($rateid);
$audienceid=$messageobj->get_thread_audienceid($tid);
//$audienceobj->audience_edit($audienceid,tbl_audience_target::col_loginreq,"2");

if((!($userobj->checklogin()))&&$audienceid!=""&&(!($audienceobj->audience_check_public($audienceid))))
{
	echo "<statusmsg>Not Logged In</statusmsg>";
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

$comments=$messageobj->getcomments($tid,$start+$increment,$increment);

echo '<comment_body_enclose rid="'.$rateid.'">';
for($i=0;$i<count($comments);$i++)
{
	$comid=$dbobj->colval($comments,tbl_message_content::col_msgid,$i);
	$com_cont=$dbobj->colval($comments,tbl_message_content::col_msg,$i);
	$com_poster=$dbobj->colval($comments,tbl_message_content::col_fuid,$i);
	$com_flag=$dbobj->colval($comments,tbl_message_content::col_msgflag,$i);
	$com_posttime=$dbobj->colval($comments,tbl_message_content::col_msgtime,$i);
	$com_replyto=$dbobj->colval($comments,tbl_message_content::col_replyto,$i);
	$com_tagid=$dbobj->colval($comments,tbl_message_content::col_tagid,$i);
	$com_attachid=$dbobj->colval($comments,tbl_message_content::col_attachid,$i);

	echo '<comment_body>';
		echo '<comid>'.$comid.'</comid>';
		echo '<com_cont>'.$com_cont.'</com_cont>';
		echo '<com_poster>'.$com_poster.'</com_poster>';
		echo '<com_posttime>'.$com_posttime.'</com_posttime>';
		echo '<com_replyto>'.$com_poster.'</com_replyto>';
		echo '<com_tagid>'.$com_tagid.'</com_tagid>';
		echo '<com_attachid>'.$com_attachid.'</com_attachid>';
		echo '<com_flag>'.$com_flag.'</com_flag>';
	echo '</comment_body>';

}

echo '</comment_body_enclose>';

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