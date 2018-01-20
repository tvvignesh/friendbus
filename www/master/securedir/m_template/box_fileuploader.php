	<?php 
		require_once $_SERVER['DOCUMENT_ROOT'].'/adjustment.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
		$utilityobj=new ta_utilitymaster();
		$userobj=new ta_userinfo();
		$logobj=new ta_logs();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		if(!$userobj->checklogin())
		{
			die("Please Login to do any file upload!");
		}
		$uptype="-1";
		if(isset($_GET["uptype"]))$uptype=$_GET["uptype"];
		$uptime=time();
	?>

      <div class="flow-error">
        Your browser, unfortunately, is not supported by Flow.js. The library requires support for <a href="http://www.w3.org/TR/FileAPI/">the HTML5 File API</a> along with <a href="http://www.w3.org/TR/FileAPI/#normalization-of-params">file slicing</a>.
      </div>

      <div class="flow-drop" ondragenter="jQuery(this).addClass('flow-dragover');" ondragend="jQuery(this).removeClass('flow-dragover');" ondrop="jQuery(this).removeClass('flow-dragover');">
        Drop files here to upload or <a class="flow-browse-folder" style="cursor:pointer;"><u>select folder</u></a> or <a class="flow-browse" style="cursor:pointer;"><u>select from your computer</u></a> or <a class="flow-browse-image" style="cursor:pointer;"><u>select images</u></a> or <a class="flow-browse-video" style="cursor:pointer;"><u>select videos</u></a>
      </div>
      
      <div class="flow-progress">
        <table>
          <tr>
            <td width="100%"><div class="progress-container"><div class="progress-bar"></div></div></td>
            <td class="progress-text" nowrap="nowrap"></td>
            <td class="progress-pause" nowrap="nowrap">
              <a href="#" onclick="r.upload(); return(false);" class="progress-resume-link"><img src="/master/securedir/m_images/img_icons/resume.png" title="Resume upload" /></a>
              <a href="#" onclick="r.pause(); return(false);" class="progress-pause-link"><img src="/master/securedir/m_images/img_icons/pause.png" title="Pause upload" /></a>
              <a href="#" onclick="r.cancel(); return(false);" class="progress-cancel-link"><img src="/master/securedir/m_images/img_icons/cancel.png" title="Cancel upload" /></a>
            </td>
          </tr>
        </table>
      </div>
      
      <ul class="flow-list"></ul>

	<script type="text/javascript">
	window.mediaidarr=[];
	var uptype=<?php echo $uptype;?>;
	function req_get(name)
	{
		   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
		      return decodeURIComponent(name[1]);
	}
	
	function flowloaded(){
        var r = new Flow({
          target: '/upload/index.php',
          chunkSize: 1024*1024,
          query:{galid:"<?php echo $_GET["galid"];?>",uploadtype:"<?php echo $uptype;?>",uptime:"<?php echo $uptime;?>"},
          testChunks:false
        });
        if (!r.support) {
          $('.flow-error').show();
          return ;
        }
        $('.flow-drop').show();
        r.assignDrop($('.flow-drop')[0]);
        r.assignBrowse($('.flow-browse')[0]);
        r.assignBrowse($('.flow-browse-folder')[0], true);
        r.assignBrowse($('.flow-browse-image')[0], false, false, {accept: 'image/*'});
        r.assignBrowse($('.flow-browse-video')[0], false, false, {accept: 'video/*'});

        r.on('fileAdded', function(file){
          $('.flow-progress, .flow-list').show();

/*
             '<a href="" class="flow-file-download" target="_blank">' +
            'Download' +
            '</a> ' +
 */
          
          $('.flow-list').append(
            '<li class="flow-file flow-file-'+file.uniqueIdentifier+'">' +
            'Uploading <span class="flow-file-name"></span> ' +
            '<span class="flow-file-size"></span> ' +
            '<span class="flow-file-progress"></span> ' +

            '<span class="flow-file-pause">' +
            ' <img src="/master/securedir/m_images/img_icons/pause.png" title="Pause upload" />' +
            '</span>' +
            '<span class="flow-file-resume">' +
            ' <img src="/master/securedir/m_images/img_icons/resume.png" title="Resume upload" />' +
            '</span>' +
            '<span class="flow-file-cancel">' +
            ' <img src="/master/securedir/m_images/img_icons/cancel.png" title="Cancel upload" />' +
            '</span>'
          );
          var $self = $('.flow-file-'+file.uniqueIdentifier);
          $self.find('.flow-file-name').text(file.name);
          $self.find('.flow-file-size').text(readablizeBytes(file.size));
          /*$self.find('.flow-file-download').attr('href', '/download/' + file.uniqueIdentifier).hide();*/
          $self.find('.flow-file-pause').on('click', function () {
            file.pause();
            $self.find('.flow-file-pause').hide();
            $self.find('.flow-file-resume').show();
          });
          $self.find('.flow-file-resume').on('click', function () {
            file.resume();
            $self.find('.flow-file-pause').show();
            $self.find('.flow-file-resume').hide();
          });
          $self.find('.flow-file-cancel').on('click', function () {
            file.cancel();
            $self.remove();
          });
        });
        r.on('filesSubmitted', function(file) {
        	$(".mod_upld_close").prop('disabled', true);
          r.upload();
        });
        r.on('complete', function(){
          $('.flow-progress .progress-resume-link, .flow-progress .progress-pause-link').hide();
          console.log(window.mediaidarr);
          $(".mod_upld_close").prop('disabled',false);
          <?php 
          	if(isset($_GET["afterupload"]))
          	{
          		echo 'window["'.$_GET["afterupload"].'"](window.mediaidarr);';
          	}
          ?>
        });
        r.on('fileSuccess', function(file,message,chunk){
          /*console.log("SERVER RET:"+message);*/
          window.mediaidarr.push(message);
          var $self = $('.flow-file-'+file.uniqueIdentifier);

          $self.find('.flow-file-progress').text('(completed)');
          $self.find('.flow-file-pause, .flow-file-resume').remove();
          /*$self.find('.flow-file-download').attr('href', '/download/' + file.uniqueIdentifier).show();*/
        });
        r.on('fileError', function(file, message){
        	$(".mod_upld_close").prop('disabled',false);
          $('.flow-file-'+file.uniqueIdentifier+' .flow-file-progress').html('(file could not be uploaded: '+message+')');
        });
        r.on('fileProgress', function(file){
          $('.flow-file-'+file.uniqueIdentifier+' .flow-file-progress')
            .html(Math.floor(file.progress()*100) + '% '
              + readablizeBytes(file.averageSpeed) + '/s '
              + secondsToStr(file.timeRemaining()) + ' remaining') ;
          $('.progress-bar').css({width:Math.floor(r.progress()*100) + '%'});
        });
        r.on('uploadStart', function(){
          $('.flow-progress .progress-resume-link').hide();
          $('.flow-progress .progress-pause-link').show();
        });
        r.on('catchAll', function() {
          console.log.apply(console, arguments);
        });
        window.r = {
          pause: function () {
            r.pause();
            $('.flow-file-resume').show();
            $('.flow-file-pause').hide();
            $('.flow-progress .progress-resume-link').show();
            $('.flow-progress .progress-pause-link').hide();
          },
          cancel: function() {
            r.cancel();
            $('.flow-file').remove();
          },
          upload: function() {
            $('.flow-file-pause').show();
            $('.flow-file-resume').hide();
            r.resume();
          },
          flow: r
        };

};

function readablizeBytes(bytes) {
var s = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'];
var e = Math.floor(Math.log(bytes) / Math.log(1024));
return (bytes / Math.pow(1024, e)).toFixed(2) + " " + s[e];
}
function secondsToStr (temp) {
function numberEnding (number) {
  return (number > 1) ? 's' : '';
}
var years = Math.floor(temp / 31536000);
if (years) {
  return years + ' year' + numberEnding(years);
}
var days = Math.floor((temp %= 31536000) / 86400);
if (days) {
  return days + ' day' + numberEnding(days);
}
var hours = Math.floor((temp %= 86400) / 3600);
if (hours) {
  return hours + ' hour' + numberEnding(hours);
}
var minutes = Math.floor((temp %= 3600) / 60);
if (minutes) {
  return minutes + ' minute' + numberEnding(minutes);
}
var seconds = temp % 60;
return seconds + ' second' + numberEnding(seconds);
}

var loadobj=new JS_LOADER();
loadobj.jsload_flowjs(flowloaded);
	</script>