<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$vuobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$fileobj=new ta_fileoperations();

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

<div style="clear:both;"></div> 
<div id="galbox_docguidetab">
  <ul>
  	<li><a href="gallery_doc_suggest.php?uid=<?php echo $vuid;?>"><i class="fa fa-play-circle-o fa-2x"></i></a></li>
    <!-- <li><a href="gallery_doc_comment.php"><i class="fa fa-comments-o fa-2x"></i></a></li>
    <li><a href="gallery_doc_info.php"><i class="fa fa-info-circle fa-2x"></i></a></li>
    <li><a href="gallery_doc_share.php"><i class="fa fa-share-alt fa-2x"></i></a></li>
    <li><a href="gallery_doc_download.php"><i class="fa fa-download fa-2x"></i></a></li>-->
  </ul>
</div>


  
  <script type="text/javascript">
  $("#galbox_docguidetab").tabs({
		active:0,
		cache: false,
	    hide: {
	        effect: "slide",
	        duration: 500
	    },
	    show:{
			effect: "slide",
	        duration: 500
		}
	});
  
	listenevent_future('.galbox_pdfprevbtn,.thumb a .view-elem',$('body'),'click',function(e){
		var th=$(this);
		function load_elem_pdfrender()
		{
			
			/*var elem=th.parents(".thumbnail:first");*/
			var docsrc=th.parents(".thumbnail:first").attr("data-docsrc");
			var doctitle=th.parents(".thumbnail:first").find(".galbox_doctitle:first").text();
			/*var docsrc=elem.attr("data-docsrc");*/
			var myobject = JSON.parse(docsrc);
			if(myobject["application/pdf"]!==undefined)
			{
				var pdfurl=myobject["application/pdf"];
				
				window.pdfpgno=1;
				
				PDFJS.getDocument(pdfurl).then(function getPdfHelloWorld(pdf) {
					
					render_pdf(pdf,1,'gal_pdf_viewcanvas');
					$("#gal_pdf_viewmodal .modal-title").html("Preview of "+doctitle);
				    
				    listenevent($('#gal_pdfview_next'),'click',function(){
				    	if(window.pdfpgno==pdf.pdfInfo.numPages)
			    		{
				    		alert("You have reached the end of this document!");
				    		return;
			    		}
				    	window.pdfpgno++;
				    	render_pdf(pdf,window.pdfpgno,'gal_pdf_viewcanvas');
					});
				    
				    listenevent($('#gal_pdfview_prev'),'click',function(){
				    	if(window.pdfpgno==1)
			    		{
				    		alert("You are already in the First Page of this Document");
				    		return;
			    		}
				    	window.pdfpgno--;
				    	render_pdf(pdf,window.pdfpgno,'gal_pdf_viewcanvas');
					});
				    
				  });
				
			}
			
		}
		
		var loadobj=new JS_LOADER();
		loadobj.jsload_pdfjs(load_elem_pdfrender);
		
	});
  </script>