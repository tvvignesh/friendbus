<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$vuobj=new ta_userinfo();
if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
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
	$GLOBALS["iscr"]=true;
	$vuid=$userobj->uid;
}
$vuobj->user_initialize_data($vuid);

$GLOBALS["vuid"]=$vuid;

$assetobj=new ta_assetloader();
$themeobj->template_load_left();

require_once 'gallery_navbars.php';	
?>

<script type="text/javascript">
function load_navbar_gpinfo()
{
	var gid=$(".galbox_picshow_info_icon:first").attr("galid");
	var mid=$(".galbox_picshow_info_icon:first").attr("mediaid");
	var loadobj=new JS_LOADER();
	loadobj.ajax_call({
		  url:"/item_getter.php",
		  method:"POST",
		  dataType:"json",
		  data:{key:"galpic_info",galid:gid,mediaid:mid},
		  cache:false,
		  success:function(data){
			  $(".gpi_name").html(data.medtitle);
			  $(".gpi_desc").html(data.meddesc);
			  $(".gpi_size").html(data.medsize);
			  $(".gpi_crtime").html(data.medtime);
			  $(".gpi_dimensions").html(data.medwidth+"x"+data.medheight);
			  $(".gpi_mime").html(data.medmime);
			  $(".gpi_owner").html(data.meduname);
			  $("#ta-rmenu_gpic_orig").attr("href",data.medurl)
		  }
	});
}
</script>

<div id="template_content_body">
	<div class="mainhead_text">Gallery</div>
	<div class="mainhead_cont">
<?php
	if(!$userobj->checklogin())
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_js_final();
		die('Sorry! You Must <a href="/index.php">Login to Tech Ahoy</a> to view your Gallery');
	}
?>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <p class="description"></p>
    <a class="prev"><i class="fa fa-arrow-left"></i></a>
    <a class="next"><i class="fa fa-arrow-right"></i></a>
    <a class="close"><span class="glyphicon glyphicon-remove-circle"></span></a>
    <a class="galbox_picshow_info_icon toggle-menu menu-right" id="navbar_toggle_gpinfobox" onclick="load_navbar_gpinfo();" data-epropagate="-1" data-target="#ta-rmenu_galpic"><span class="glyphicon glyphicon-info-sign"></span></a>
    <!-- <a class="galbox_picshow_settings_icon toggle-menu menu-right" id="navbar_toggle_gpsetbox" data-target="#ta-rmenu_galpicset"><span class="glyphicon glyphicon-cog"></span></a>-->
    <a class="play-pause"></a>
    <a class="galbox_picshow_thumbpreview_icon"><span class="glyphicon glyphicon-th"></span></a>
    <a class="galbox_picshow_thumbpreviewclose"><span class="glyphicon glyphicon-remove"></span></a>
    <ol class="indicator"></ol>
</div>

<div id="gal_pdf_viewmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="PdfGalModal">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body" align="center">
        <canvas id="gal_pdf_viewcanvas" style="border:1px  solid black"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="gal_pdfview_prev" class="btn btn-primary">Previous Page</button>
        <button type="button" id="gal_pdfview_next" class="btn btn-primary">Next Page</button>
      </div>
    </div>
  </div>
</div>



<ul class="nav nav-tabs" id="galbox_cont_tabs">
	<li class="active"><a href="#galbox_tab_pic"><span class="glyphicon glyphicon-picture"></span><span class="hidden-xs hidden-sm hidden-md"> Pictures</span></a></li>
	<li><a href="#galbox_tab_vid"><span class="glyphicon glyphicon-facetime-video"></span><span class="hidden-xs hidden-sm hidden-md"> Videos</span></a></li>
	<li><a href="#galbox_tab_doc"><span class="glyphicon glyphicon-file"></span><span class="hidden-xs hidden-sm hidden-md"> Documents</span></a></li>
	<li><a href="#galbox_tab_music"><span class="glyphicon glyphicon-music"></span><span class="hidden-xs hidden-sm hidden-md"> Music</span></a></li>
	<?php 
    	if($GLOBALS["iscr"])
    	{
    		echo '<li><a href="#galbox_tab_vault"><span class="glyphicon glyphicon-lock"></span><span class="hidden-xs hidden-sm hidden-md"> Vault</span></a></li>
				<li><a href="#galbox_tab_sandbox"><span class="glyphicon glyphicon-certificate"></span><span class="hidden-xs hidden-sm hidden-md"> Sandbox</span></a></li>
				';
    	}
	?>
	<!-- <li><a href="#galbox_tab_trash"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> Trash</span></a></li>-->
  <!-- <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">More
    <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li><a href="#">Import Files</a></li>
      <li><a href="#">Take Backups</a></li>
      <li><a href="#">Manage Privacy</a></li>
      <li><a href="#">Share Files</a></li>
      <li><a href="#">Upgrade Space</a></li>
      <li><a href="#">Manage Settings</a></li>
    </ul>
  </li>-->
</ul>

<div class="tab-content">
	<div id="galbox_tab_pic" class="tab-pane active" data-taburl="gallery_pic.php?uid=<?php echo $vuid;?>" data-sucfunc="tab_gbx_pic">
		<?php require_once 'gallery_pic.php';?>
	</div>
	<div id="galbox_tab_vid" class="tab-pane" data-taburl="gallery_video.php?uid=<?php echo $vuid;?>">
	</div>
    <div id="galbox_tab_doc" class="tab-pane" data-taburl="gallery_doc.php?uid=<?php echo $vuid;?>">
    </div>
    <div id="galbox_tab_music" class="tab-pane" data-taburl="gallery_audio.php?uid=<?php echo $vuid;?>">
    </div>
    <?php 
    	if($GLOBALS["iscr"])
    	{
    		echo '<div id="galbox_tab_vault" class="tab-pane" data-taburl="gallery_vault.php?uid='.$vuid.'"></div>';
    	}
    ?>
    <div id="galbox_tab_vault" class="tab-pane" data-taburl="gallery_vault.php?uid=<?php echo $vuid;?>">
    </div>
    <?php 
    	if($GLOBALS["iscr"])
    	{
    		echo '<div id="galbox_tab_sandbox" class="tab-pane" data-taburl="gallery_sandbox.php?uid='.$vuid.'"></div>';
    	}
    ?>
    <!--<div id="galbox_tab_trash" class="tab-pane" data-taburl="gallery_trash.php?uid=<?php echo $vuid;?>">-->
    </div>
</div>
		
		
		
	</div>
	<br>

</div>


<?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
function tab_gbx_pic()
{
	var utilityobj=new JS_UTILITY();
	utilityobj.multiselect($('.galsel_pic'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true,
		disableIfEmpty: true,
        disabledText: 'None Selected'
	});
}

$(document).ready(function(){
	tab_gbx_pic();
	var utilityobj=new JS_UTILITY();
	var loadobj=new JS_LOADER();

		
	listenevent_future(".galsel_pic",$("#galbox_tab_pic"),"change",function(e){
		var galid=$(this).val();
		var predata=$(this).data();
		predata.galid=galid;
		ajax_sender(e,predata,function(){
			$(".gbx_pic_addstuff").attr("data-galid",galid);
			$(".gbx_pic_dpdwn_galdel").attr("data-galid",galid);
			$(".ld_gal_pics").attr("data-galid",galid);
			exec_pic_gal();
		});
	});

	listenevent_future(".galbox_viewpic_cont",$("#galbox_tab_pic"),"mouseenter",function(e){
		var galid=$(this).attr("data-galid");
		var mediaid=$(this).attr("data-imgid");
		if($(this).find(".gbx-pic-dpdwn").length==0)
		{
		<?php 
			if($GLOBALS["iscr"])
			{
				echo '
	$(this).prepend(\'<div class="dropdown gbx-pic-dpdwn pull-right"><button class="btn btn-sm btn-default btn-cbxlistcog" data-toggle="dropdown"><i class="fa fa-cog"></i></button><ul class="dropdown-menu"><li><a class="ajax-btn" data-mkey="gbx_pic_remove" data-prompt="1" data-galid="\'+galid+\'" data-mediaid="\'+mediaid+\'" data-suchide=".gbx_pic_\'+mediaid+\'" data-eltarget="-3"><i class="fa fa-trash"></i> Remove Picture</a></li><li><a class="box-tog" data-mkey="box_audience" data-toggle="modal" data-autoset="1" data-toggle="modal" data-pelem="galmed_audience" data-elname="\'+galid+\'" data-elemid="\'+mediaid+\'"><i class="fa fa-users"></i> Change Privacy</a></li></ul></div></div>\');
					';
			}
		?>
		}
		listenevent($(this),"mouseleave",function(){
			$(this).find(".dropdown").removeClass('open');
			$(this).find(".gbx-pic-dpdwn").remove();
		});
	});
				
	listenevent_future(".galbox_viewpic_cont [data-toggle=dropdown]",$("#galbox_tab_pic"),"click",function(e){
		   $(this).next('.dropdown-menu').toggle();
		   e.stopPropagation();
		   e.preventDefault();
	});

	listenevent_future(".galbox_viewpic_cont .dropdown-menu li a:not(.box-tog)",$("#galbox_tab_pic"),"click",function(e){
			var predata=$(this).data();
			ajax_sender(e,predata);
		   e.stopPropagation();
		   e.preventDefault();
	});
	
	listenevent_future("#navbar_toggle_gpinfobox",$("body"),"click",function(){
		var loaobj=new JS_LOADER();
		loadobj.ajax_call({
			  url:"/item_getter.php",
			  method:"POST",
			  data:{key:"galpic_info",galid:window.cp_galid,mediaid:window.cp_mediaid},
			  cache:false,
			  success:function(data){
				$(".galbox_imgdet").html(data);
			  }
		});
	});
});
</script>
