<?php 
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();

$userobj->userinit();

$galid_att=$galobj->get_galid_special($userobj->uid,"16");

if(!(isset($tid)))
{
	if(isset($_POST["tid"]))
	{
		$tid=$_POST["tid"];
	}
	else
	if(isset($_GET["tid"]))
	{
		$tid=$_GET["tid"];
	}
	else
	{
		echo "Please select a thread";return FAILURE;
	}
}

echo '<div class="convbx_convcont" data-threadid="'.$tid.'" data-vuid="'.$userobj->uid.'">';
	require_once 'conversations_message_cont.php';
echo '</div>';
?>
        	<hr class="convbx_linesep">
        	<div class="convbx_convinput" style="margin-top:5px;">
    
			    <div class="row">
			    
			    <div class="col-md-12">
    				<div class="widget-area no-padding blank">
						<div class="cbx-msgsendinput">
							<form>
								<div class="convbx_sendinput" contenteditable="true" data-placeholder="What would you like to say?"></div>
								<ul>
								
								<?php echo '<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Attach Documents" class="box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a></li>';?>
								<!-- <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
								<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
								<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
								<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Link"><i class="fa fa-link"></i></a></li>
								<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Poll"><i class="fa fa-bar-chart"></i></a></li>
								<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Location"><i class="fa fa-map-marker"></i></a></li>-->
								<li><a title="Insert Smileys" data-placement="bottom" data-original-title="Add Smileys" class="box-tog" data-mkey="box_smileys" data-toggle="modal" data-eltarget="-1" data-intarget=".convbx_sendinput"><i class="fa fa-smile-o"></i></a></li>
							</ul>
							<button type="button" class="btn btn-success th_sendmsgbtn pull-right"><i class="fa fa-share"></i> Send</button>
						</form>
					</div><!-- Status Upload  -->
				</div><!-- Widget Area -->
				</div>
			        
			    </div>
        	
        	
        	
        	
        	</div>

<?php
$assetobj=new ta_assetloader();
$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();
?>
        	
<script type="text/javascript">
window.mediaidarr=[];
function convbx_init_temp()
{
	var loadobj=new JS_LOADER();
	var comobj=new JS_COMMUNICATION();
	process_tarea($('.convbx_sendinput'));

	loadobj.jsload_emoticons(function(){
		$(".convbx_convcont").emoticonize();
	});
}

document.addEventListener("DOMContentLoaded",convbx_init_temp);
</script>