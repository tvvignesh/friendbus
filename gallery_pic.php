<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$vuobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();

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
    	
    	<div class="well well-sm">
    		<div class="pull-left">
    		
    			<?php 
    			$res=$galobj->gallery_get_user($vuid);
    			$totgal=count($res);
    			echo '<select class="form-control galsel_pic" data-mkey="gbx_galopen_pic" data-eltarget=".galbox_viewpic_outcont" data-dtype="html" data-ddemand="html">';
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
    			$galid_init=$res[0][changesqlquote(tbl_galinfo::col_galid,"")];
    			?>
    		
    		</div>
    	
	    	<div class="galbox_viewpic_rcontrol">
	    	
	    	<div class="dropdown controlbtns">
			    	<a href="#" class="btn btn-default" id="galbox_picbtn_more" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      		<span class="glyphicon glyphicon-collapse-down"></span><span class="hidden-xs hidden-sm hidden-md"> More</span>
			   		</a>
			   		
			   		<ul class="dropdown-menu bullet" aria-labelledby="galbox_picbtn_more">
			   			<li><a id="galbox_view_slideshow">View Slideshow</a></li>
				        <?php
				        if($GLOBALS["iscr"])
				        {
				        	echo '<li><a class="gbx_pic_dpdwn_galdel ajax-btn" data-mkey="gbx_pic_galdel" data-eltarget="-1" data-prompt="1" data-galid="'.$galid_init.'">Delete Gallery</a></li>';
				        }
				        ?>
				  	</ul>
			 </div>
		    
		    <?php
		    if($GLOBALS["iscr"])
		    {	
		    	echo ' <button class="btn btn-default box-tog pull-left gbx_pic_addstuff" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid_init.'"><i class="fa fa-upload"></i><span class="hidden-xs hidden-sm hidden-md"> Upload Pictures</span></button>
						<button class="btn btn-primary box-tog ta-lmargin" data-mkey="box_gal_new" data-toggle="modal" data-eltarget="-1"><i class="fa fa-plus"></i><span class="hidden-xs hidden-sm hidden-md"> New Gallery</span></button>
				';
		    }
		    ?>
				
		    </div>
		    
		    <div style="clear:both;"></div> 
    	</div>
    
    <div class="row galbox_viewpic_outcont">
    	
    	<?php 
    		echo $uiobj->disp_gal_pic($vuid,$galid_init,"0","15");
    	?>
	
	</div>
	<div style="clear:both;"></div>
	
	<div align="center"><button class="btn btn-default ld_gal_pics" data-vuid="<?php echo $vuid;?>" data-st="15" data-tot="10" data-galid="<?php echo $galid_init;?>">Load More Pictures</button></div>
			
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",function(){
	exec_pic_gal();
});
</script>
