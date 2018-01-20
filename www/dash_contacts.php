<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$uiobj=new ta_uifriend();
$assetobj=new ta_assetloader();

if(!$userobj->checklogin())
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/dash_contacts.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
}

$themeobj->template_load_left();

if(isset($_GET["tabopen"]))
{
	switch($_GET["tabopen"])
	{
		case "intouch":
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			echo '<script type="text/javascript">window.location.assign("/dash_contacts.php#cbx-tabs-intouch");</script>';return;
			break;
	}
}

?>

<?php require_once 'pop_contacts.php';?>

<div id="template_content_body">

<button class="btn btn-default contactbox_add" data-toggle="modal" data-target="#contacts_newbook"><span class="glyphicon glyphicon-plus-sign"></span> New Contactbook</button>
<a href="/dash_import.php"><button class="btn btn-default contactbox_searchfrnd"><i class="fa fa-user-plus"></i> Invite People</button></a> 
<br><br>

<div id="cbx-tabcont" class="jquitabs">
	    <ul>
	  	<li><a href="#cbx-tabs-contacts"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Your Contacts</span></a></li>
	  	<!--<li><a href="#cbx-tabs-find"><i class="fa fa-search"></i> <span class="hidden-xs hidden-sm hidden-md">Find People</span></a></li>-->
	    <li><a href="#cbx-tabs-suggestions"><i class="fa fa-comment"></i> <span class="hidden-xs hidden-sm hidden-md">Suggestions</span></a></li>
	    <li><a href="#cbx-tabs-intouch"><i class="fa fa-cloud"></i> <span class="hidden-xs hidden-sm hidden-md">In Touch</span></a></li>
	    <li><a href="#cbx-tabs-following"><i class="fa fa-rss"></i> <span class="hidden-xs hidden-sm hidden-md">Following</span></a></li>
	    <li><a href="#cbx-tabs-followers"><i class="fa fa-fire"></i> <span class="hidden-xs hidden-sm hidden-md">Followers</span></a></li>
	    <li><a href="#cbx-tabs-invites"><i class="fa fa-user-plus"></i> <span class="hidden-xs hidden-sm hidden-md">Invites & Requests</span></a></li>
	</ul>			
	
	<div id="cbx-tabs-contacts" class="cbx-tabs-cont" data-taburl="contacts_main.php">
	<?php require_once 'contacts_main.php';?>
	</div>
	
	<!-- <div id="cbx-tabs-find" class="cbx-tabs-cont" data-taburl="contacts_find.php"></div>-->
	<div id="cbx-tabs-suggestions" class="cbx-tabs-cont" data-taburl="contacts_suggestions.php"></div>
	<div id="cbx-tabs-intouch" class="cbx-tabs-cont" data-taburl="contacts_intouch.php"></div>
	<div id="cbx-tabs-following" class="cbx-tabs-cont" data-taburl="contacts_following.php"></div>
	<div id="cbx-tabs-followers" class="cbx-tabs-cont" data-taburl="contacts_followers.php"></div>
	<div id="cbx-tabs-invites" class="cbx-tabs-cont" data-taburl="contacts_invites.php"></div>

</div>
</div>
<?php
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
$(document).ready(function(){

	listenevent_future(".contacts_all",$("body"),"click",function(e){
		var predata=$(this).data();

		$("li.contacts_all").removeClass("active");
		$(this).addClass("active");
		ajax_sender(e,predata,function(){
			rebind_popovers();
		});
	});

	listenevent_future(".contacts_all[data-elem!='all']",$("body"),"mouseenter",function(e){
		var elem=$(this).attr("data-elem");
		if($(this).find(".cbxlistcog-dpdwn").length==0)
		{
			$(this).prepend('<div class="dropdown cbxlistcog-dpdwn pull-right"><button class="btn btn-xs btn-default btn-cbxlistcog" data-toggle="dropdown"><i class="fa fa-cog"></i></button><ul class="dropdown-menu"><li><a class="ajax-btn" data-mkey="s_remflist" data-elem="'+elem+'" data-eltarget="-1"><i class="fa fa-trash"></i> Remove List</a></li><li><a><i class="fa fa-pencil"></i> Edit List</a></li></ul></div></div>');
		}
		listenevent($(this),"mouseleave",function(){
			$(this).find(".dropdown").removeClass('open');
			$(this).find(".cbxlistcog-dpdwn").remove();
		});
	});

	listenevent_future(".usr_flvl",$("body"),"change",function(e){
		var predata=$(this).data();
		predata.flvl=$(this).val();
		ajax_sender(e,predata);
	});

	listenevent_future(".frnd_nick",$("body"),"click",function(e){
		var predata=$(this).data();
		var f_fname=predata.fnick;
		var elhtml=$(predata.eltarget).html();
		if(elhtml!=""){f_fname=elhtml;}
		var fnick=prompt("Please Enter the Nickname you wish to assign",f_fname);
		if(fnick!=null)
		{
			predata.nick=fnick;
			ajax_sender(e,predata);
		}
	});
	
	listenevent_future(".pop_flist_dpn",$("body"),"click",function(){
		$(this).parents(".popover:first").popover('hide');
		$("#contacts_newbook").modal('toggle');
	});
	
	listenevent_future(".pop_flist_dplbl",$("body"),"change",function(e){
		var predata=$(this).data();
		ajax_sender(e,predata);
	});
	
});
</script>
