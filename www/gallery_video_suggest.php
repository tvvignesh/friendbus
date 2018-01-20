<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$vuobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();
$fileobj=new ta_fileoperations();
$logobj=new ta_logs();

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

$res=$galobj->gallery_get_user($vuid);
$totgal=count($res);
$galid_init=$res[0][changesqlquote(tbl_galinfo::col_galid,"")];
?>
  
  <div id="gv-tabs-suggest">
    
    
    <?php
    if($GLOBALS["iscr"])
    {
    	echo '
		<button class="btn btn-primary box-tog pull-left" data-mkey="box_gal_new" data-toggle="modal" data-eltarget="-1"><i class="fa fa-plus"></i><span class="hidden-xs hidden-sm hidden-md"> New Gallery</span></button>
		<button class="btn btn-primary box-tog pull-left ta-lmargin gbx_vid_chunkupload" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid_init.'"><i class="fa fa-upload"></i><span class="hidden-xs hidden-sm hidden-md"> Upload Video(s)</span></button>
			';
    }
    ?>
    
    <div class="dropdown pull-left ta-lmargin">
			    	<button class="btn btn-primary" data-toggle="dropdown">
			      		<i class="fa fa-caret-down"></i><span class="hidden-xs hidden-sm hidden-md"> More</span>
			   		</button>
			   		
			   		<ul class="dropdown-menu" aria-labelledby="galbox_picbtn_more">
        <?php
        if($GLOBALS["iscr"])
        {
        	echo '<li><a class="gbx_vid_dpdwn_galdel ajax-btn" data-mkey="gbx_vid_galdel" data-eltarget="-1" data-prompt="1" data-galid="'.$galid_init.'">Delete Gallery</a></li>';
        }
        ?>
			  	</ul>
	</div>
    
    <div id="gv_sug_btns">
    
    	<div class="pull-right">
    		
    			<?php 
    			echo '<select class="form-control galsel_vid" data-mkey="gbx_galopen_vid" data-eltarget=".galbox_viewvid_outcont" data-dtype="html" data-ddemand="html">';
    				for($i=0;$i<$totgal;$i++)
    				{
	    				$galid=$res[$i][changesqlquote(tbl_galinfo::col_galid,"")];
	    				$galname=$res[$i][changesqlquote(tbl_galinfo::col_galname,"")];
	    				
	    				$galtype=$res[$i][changesqlquote(tbl_galinfo::col_galtype,"")];
	    				if($galtype=="14")
	    				{
	    					if(!$GLOBALS["iscr"])
	    					{
	    						continue;
	    					}
	    				}
	    				
	    				echo '<option value="'.$galid.'">'.$galname.'</option>';
    				}
    			echo '</select>';
    			?>
    </div>    	

		    
		    
    </div>
    
    <div style="clear:both;"></div>
    <br>
  <div class="row galbox_viewvid_outcont">
  
  	<?php 
		$uiobj->disp_gal_vid($vuid,$galid_init,"0","15");
  	?>
  
</div>
  
  <div align="center"><button class="btn btn-default ld_gal_vids" data-vuid="<?php echo $vuid;?>" data-st="15" data-tot="10" data-galid="<?php echo $galid_init;?>">Load More</button></div>
  
  </div>
  
  <script type="text/javascript">

	var utilityobj=new JS_UTILITY();
	utilityobj.multiselect($('.galsel_vid'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true,
		disableIfEmpty: true,
        disabledText: 'None Selected'
	});

	listenevent($(".galsel_vid"),"change",function(e){
		var galid=$(this).val();
		var predata=$(this).data();
		predata.galid=galid;
		ajax_sender(e,predata,function(){
			$(".gbx_vid_as_upload").attr("data-galid",galid);
			$(".gbx_vid_chunkupload").attr("data-galid",galid);
			$(".gbx_vid_dpdwn_galdel").attr("data-galid",galid);
			rebind_all();
		});
	});
	
  </script>
