<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	$utilityobj=new ta_utilitymaster();
	$userobj=new ta_userinfo();
	$socialobj=new ta_socialoperations();
	$uiobj=new ta_uifriend();
	$galobj=new ta_galleryoperations();
	$vuobj=new ta_userinfo();
	
	if(!$userobj->checklogin())
	{
	
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		setcookie("returnpath",HOST_SERVER."/dash_gallery.php",0,'/');
	}
	else
	{
		$userobj->userinit();
		$uid=$userobj->uid;
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
	}
	
	if(isset($_GET["uid"]))
	{
		$vuid=$_GET["uid"];
		if($vuid==$userobj->uid)
		{
			$vuid=$userobj->uid;
			$GLOBALS["iscr"]=true;
		}
		else
		{
			$GLOBALS["iscr"]=false;
		}
	}
	else
	{
		if(isset($GLOBALS["vuid"]))
		{
			$vuid=$GLOBALS["vuid"];
			if($userobj->uid==$vuid)
			{
				$GLOBALS["iscr"]=true;
			}
			else
			{
				$GLOBALS["iscr"]=false;
			}
		}
		else
		{
			$GLOBALS["iscr"]=true;
			$vuid=$userobj->uid;
		}
	}
	$vuobj->user_initialize_data($vuid);
?>

<div id="gv-tabs-download"></div>

<script type="text/javascript">
  	var predata={};
  	predata.galid=$("#galbox_vidcontainer").attr("data-galid");
  	predata.mediaid=$("#galbox_vidcontainer").attr("data-mediaid");
  	predata.mkey="gbx_vid_infodownload";
  	predata.eltarget="#gv-tabs-download";
  	predata.dtype="html";
  	predata.ddemand="html";
	ajax_sender(undefined,predata);
</script>
