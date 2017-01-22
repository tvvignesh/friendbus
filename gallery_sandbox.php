<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
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

$galid_sbx=$galobj->get_galid_special($userobj->uid,"15");
$galid_rep=$galobj->get_galid_special($userobj->uid,"12");
$medres=$galobj->get_children_media($galid_rep);
?>

<?php 
	echo ' <button class="btn btn-primary box-tog pull-left ta-lmargin gbx_sbx_chunkupload" data-mkey="box_fileupload" data-uptype="1" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid_sbx.'"><i class="fa fa-plus"></i> Upload to Sandbox</button>';
?>
	
	
	
	
	<br><br>
	
	<div class="panel panel-primary" id="galbox_tab_sandbox_status">
		  <div class="panel-body">
		  		Upload files to the sandbox to scan files for potential viruses and infected content. Empty Report means that your file has not been scanned.
		  		<br><br>
		  		Scan Reports
		  		<ul class="list-group-item">
		  		<?php 
		  			for($i=0;$i<count($medres);$i++)
		  			{
		  				$medid=$medres[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
		  				$medurl=$medres[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
		  				$medtime=$medres[$i][changesqlquote(tbl_galdb::col_mediatime,"")];
		  				echo '<li class="list-group-item gbx-sbx-rep-'.$medid.'"><a href="'.$utilityobj->pathtourl($medurl).'">Viw Report Generated on '.$medtime.'</a>
								<div class="dropdown pull-right">
									<button class="btn btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
									<ul class="dropdown-menu">
										<li><a class="ajax-btn" data-mkey="gbx_sbx_del" data-mediaid="'.$medid.'" data-galid="'.$galid_rep.'" data-suchide=".gbx-sbx-rep-'.$medid.'"><i class="fa fa-trash"></i> Delete Report</a></li>
									</ul>
								</div>
							<div style="clear:both;"></div>
							</li>';
		  			}
		  			
		  			if(count($medres)==0)
		  			{
		  				echo 'Looks like there is nothing here to show.. Upload files here to get it scanned for possible viruses..';
		  			}
		  		?>
		  		</ul>
		    </div>
		</div>