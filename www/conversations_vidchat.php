<div align="center">

<p class="bg-success convbx_videoconf_status">
Streaming to Public Audience.. 
</p>

<div class="btn-group convbx_videoconf_vidop">
  <button type="button" class="btn btn-default"><i class="fa fa-wifi"></i> <span class="hidden-md  hidden-sm hidden-xs">Stream</span></button>
  <button type="button" class="btn btn-default"><i class="fa fa-desktop"></i> <span class="hidden-md  hidden-sm hidden-xs">Screen Share</span></button>
  <button type="button" class="btn btn-default" id="convbx_videconf_snapit"><i class="fa fa-camera"></i> <span class="hidden-md  hidden-sm hidden-xs">Snap</span></button>
  <button type="button" class="btn btn-default"><i class="fa fa-circle"></i> <span class="hidden-md  hidden-sm hidden-xs">Record</span></button>
  <button type="button" class="btn btn-default"><i class="fa fa-magic"></i> <span class="hidden-md  hidden-sm hidden-xs">Effects</span></button>
  <button type="button" class="btn btn-default"><i class="fa fa-paint-brush"></i> <span class="hidden-md  hidden-sm hidden-xs">Draw</span></button>
  
  <span class="dropdown">
  	<button type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-default"><i class="fa fa-cogs"></i> <span class="hidden-md hidden-sm hidden-xs">Stream Settings</span></button>
    <ul class="dropdown-menu">
      <li><a href="#"><i class="fa fa-signal"></i> Manage Bandwidth</a></li>
      <li><a href="#"><i class="fa fa-camera-retro"></i> Manage Capture Devices</a></li>
      <li><a href="#"><i class="fa fa-eye"></i> Manage Privacy</a></li>
      <li><a href="#"><i class="fa fa-file"></i> Share Files</a></li>
    </ul>
   </span>
  
  
</div>



<div class="btn-group pull-right convbx_videoconf_vidop">
	<button type="button" class="btn btn-primary ico-toggle" title="Toggle Audio"><i class="fa fa-microphone" data-togico="fa fa-microphone-slash" data-togico1="fa fa-microphone"></i></button>
	<button type="button" class="btn btn-primary ico-toggle" title="Toggle Video"><i class="fa fa-video-camera"></i></button>
	<button type="button" class="btn btn-primary ico-toggle" title="Hang Up"><i class="fa fa-phone"></i></button>
</div>

<div style="clear: both;"></div>
</div>

<br>
        	<div id="convbx_webcam" class="expose" align="center"><!-- Please allow access to your Webcam & Microphone from your browser to start the Video Conferencing session. --></div>
        	
        	<div id="convbx_videconf_snaps"></div>
        	
        	<div id="convbx_videoconf_cams"></div>
        	<div id="convbx_videoconf_status"></div>
        	
<script type="text/javascript">
	var vidconfobj=new JS_MEDIA();
	vidconfobj.videoconf({width:640,height:480,parentdiv:"convbx_videconf_snaps"});
	$("#convbx_webcam video:first").css("height","100%");
	$("#convbx_webcam video:first").css("width","100%");

	listenevent($('#convbx_videconf_snapit'),'click',function(){
		var mediaobj=new JS_MEDIA();
		var vidid=$("#convbx_webcam video:first").attr("id");
		mediaobj.videoconf_snapshot("convbx_videconf_snaps",vidid);
	});
</script>