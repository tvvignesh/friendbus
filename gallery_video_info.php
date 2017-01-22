<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();
$vuobj=new ta_userinfo();

$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

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
  <div id="gv-tabs-info"></div>
  
  <script type="text/javascript">
  function sender_editable(params)
  {
  	console.log(params);
  	params.mkey=params.name;
      var d = new $.Deferred;
          $.ajax({
  	      method:"POST",
  		  url: "/request_process.php",
  		  data:params,
  		  dataType:"json",
  		}).done(function(data) {
  			if(data.returnval!=1)
  			{
  				return d.reject(data.message);
  			}
  			console.log(data);
  			d.resolve();
  		});
          return d.promise();
  }
  
  	var predata={};
  	predata.galid=$("#galbox_vidcontainer").attr("data-galid");
  	predata.mediaid=$("#galbox_vidcontainer").attr("data-mediaid");
  	predata.mkey="gbx_vid_infoload";
  	predata.eltarget="#gv-tabs-info";
  	predata.dtype="html";
  	predata.ddemand="html";
	ajax_sender(undefined,predata,function(){

		<?php 
			if($GLOBALS["iscr"])
			{
				echo
				'
				var utilityobj=new JS_UTILITY();
				utilityobj.editable($("#media_edit_desc"),{
					type: "textarea",
					pk: predata.mediaid,
					url:sender_editable,
					title: "Enter media description",
					mode:"inline",
					rows:3,
					name:"media_edit_desc"
				});
				';
			}
		?>
	});
  </script>
