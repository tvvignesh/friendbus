<?php
   require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
   $utilityobj=new ta_utilitymaster();
   $userobj=new ta_userinfo();
   $socialobj=new ta_socialoperations();
   $uiobj=new ta_uifriend();
   $galobj=new ta_galleryoperations();
   $fileobj=new ta_fileoperations();
   $logobj=new ta_logs();
   $vuobj=new ta_userinfo();
   
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
	
	$res=$galobj->gallery_get_user($vuid,"0");
	$totgal=count($res);
    ?>
  		
  		<div id="ga-tabs-own" class="ga-tabs-cont">
  		
  		<!-- <button class="btn btn-primary btn-sm gbx_aud_newalbum box-tog" data-mkey="box_gal_new" data-galtype="5" data-toggle="modal" data-eltarget="-1" title='Create a New Album'>
	     	<i class="fa fa-plus"></i> New Album
      	</button>-->
      	<br><br>
  		
  		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
		  		 
		  		 <div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Audio</h3>
				  </div>
				  <div class="panel-body">
				    	
		
		<?php
			for($i=0;$i<$totgal;$i++)
			{
				$opres='';
				$galid=$res[$i][changesqlquote(tbl_galinfo::col_galid,"")];
				$galthumb=$res[$i][changesqlquote(tbl_galinfo::col_galpic,"")];
				$galname=$res[$i][changesqlquote(tbl_galinfo::col_galname,"")];
				$galdesc=$res[$i][changesqlquote(tbl_galinfo::col_galdesc,"")];
				$jsonid=$res[$i][changesqlquote(tbl_galinfo::col_jsonid,"")];
				
				$res1=$galobj->get_children_media($galid,"4");
				$totmed=count($res1);
				$galauthor="";
				if($jsonid!="")
				{
					$jsonobj=$utilityobj->jsondata_get($jsonid);
					if(isset($jsonobj->galauthor)){$galauthor=$jsonobj->galauthor;}
				}
				if($galauthor!="")
				{$authlbl="Playlist by ".$galauthor;}
				else
				{$authlbl="";}
				
				$opres.='<div class="ga_album_cont" data-toggle="collapse" data-target="#ga_custalbum_'.$galid.'" aria-expanded="false" aria-controls="ga_custalbum_'.$galid.'">
   						<div class="panel panel-default">
				  <div class="panel-body">
				  		<img src="'.$utilityobj->pathtourl($galthumb).'" class="pull-left ga_list_img">
				  		<span class="glyphicon glyphicon-play-circle pull-right ga_play_audio"></span>
				  		<strong>'.$galname.'</strong> ['.$totmed.' tracks]
				  		<div class="ga_list_artist">'.$authlbl.'</div>
				  		<div>Description: '.$galdesc.'</div>
				  		<br>
				  		<div>
				  			
				  			<div class="btn-group ga_audio_controls">
				  			<button type="button" class="btn btn-default box-tog ga_upload_audio" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid.'" title="Upload an Audio Track to add it to this Album"><i class="fa fa-upload"></i> Upload</button>
			  				<button type="button" class="btn btn-default ga_share_audio" data-galid="'.$galid.'"><span class="glyphicon glyphicon-share-alt"></span> Share</button>
			  				<button type="button" class="btn btn-default ga_download_audio" data-galid="'.$galid.'"><span class="glyphicon glyphicon-download-alt"></span> Download</button>
			  				<!--<div class="btn-group">
							    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></button>
							    <ul class="dropdown-menu" role="menu">
							      <li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Add to Playlist</a></li>
							      <li><a href="#"><i class="fa fa-wifi"></i> Add to Stream</a></li>
							      <li><a href="#"><span class="glyphicon glyphicon-heart"></span> Favorite</a></li>
							      <li><a href="#"><span class="glyphicon glyphicon-minus-sign"></span> Delete</a></li>
							    </ul>
			  				</div>-->
						</div>
				  		
				  		</div>
				    </div>
				  </div>
	    	 	
	    	 </div>
   		
   					';
				
				if($totmed!=0)
				{
					$opres.='<div class="collapse well" id="ga_custalbum_'.$galid.'">';
					
					for($j=0;$j<$totmed;$j++)
					{
						$mediatype=$res1[$j][changesqlquote(tbl_galdb::col_mediatype,"")];
						if($mediatype!="4")continue;
						$mediaid=$res1[$j][changesqlquote(tbl_galdb::col_mediaid,"")];
						$jsonid_med=$res1[$j][changesqlquote(tbl_galdb::col_jsonid,"")];
						$mediatitle=$res1[$j][changesqlquote(tbl_galdb::col_mediatitle,"")];
						$mediathumb=$res1[$j][changesqlquote(tbl_galdb::col_mediathumb,"")];
						$mediadesc=$res1[$j][changesqlquote(tbl_galdb::col_mediadesc,"")];
						$mediatime=$res1[$j][changesqlquote(tbl_galdb::col_mediatime,"")];
						$mediaurl=$res1[$j][changesqlquote(tbl_galdb::col_mediaurl,"")];
						$uid=$res1[$j][changesqlquote(tbl_galdb::col_mediauid,"")];
						
						$fext=$fileobj->fileinfo($mediaurl,"3");
						
						$release=$genre=$duration=$metadata_med=$artist_med=$audsrc='';
						$downloadobj=new stdClass;
						if($jsonid_med!="")
						{
							$jsonobj_med=$utilityobj->jsondata_get($jsonid_med);
							if(isset($jsonobj_med->formats))
							{
								foreach ($jsonobj_med->formats as $key=>$value)
								{
									$medid=$value;
									$format=$key;
									$medurl=$galobj->geturl_media($galid,$medid,"3");
									$downloadobj->$format=$utilityobj->pathtourl($medurl);
									$audsrc.='data-audsrc-'.$format.'="'.$utilityobj->pathtourl($medurl).'"';
								}
							}
							else
							{
								$audsrc.='data-audsrc-'.$fext.'="'.$utilityobj->pathtourl($mediaurl).'"';
								$downloadobj->$fext=$utilityobj->pathtourl($medurl);
							}
							
							if(isset($jsonobj_med->author))
							{
								$artist_med=$jsonobj_med->author;
							}
							
							if(isset($jsonobj_med->metadata))
							{
								$metadata_medobj=$jsonobj_med->metadata;
								$metadata_med=$utilityobj->object_to_json($metadata_medobj);
								
								if(isset($jsonobj_med->metadata->genre))
								{
									$genre=$jsonobj_med->metadata->genre;
								}
								
								if(isset($jsonobj_med->metadata->release))
								{
									$release=$jsonobj_med->metadata->release;
								}
							}							
							
							if(isset($jsonobj_med->duration))
							{
								$duration=$jsonobj_med->duration;
							}
						}
						else
						{
							$audsrc.='data-audsrc-'.$fext.'="'.$utilityobj->pathtourl($mediaurl).'"';
							$downloadobj->$fext=$utilityobj->pathtourl($medurl);
						}
						
						$opres.='
		    	<div class="panel panel-default" id="#gbx-audmed-'.$mediaid.'" '.$audsrc.' data-audid="'.$mediaid.'" data-audtitle="'.$mediatitle.'" data-audartist="'.$artist_med.'" data-audposter="'.$mediathumb.'" data-audmetadata=\''.$metadata_med.'\'>
				  <div class="panel-body">
				  		<img src="'.$mediathumb.'" class="pull-left ga_list_img">
				  		<span class="glyphicon glyphicon-play-circle pull-right ga_play_audio"></span>
				  		<strong>'.$mediatitle.'</strong> ['.$galname.']
				  		<div class="ga_list_artist">by '.$artist_med.'</div>
				  		<div>Duration: '.$duration.'</div>
				  		<div>Genre: '.$genre.'</div>
				  		<div>Released: '.$release.'</div>
				  		<br>
				  		<div>
				 
				  			<div class="btn-group ga_audio_controls">
			  				<button type="button" data-mediaid="'.$mediaid.'" class="btn btn-default ga_share_audio"><span class="glyphicon glyphicon-share-alt"></span> Share</button>
			  				
						<div class="btn-group">
							<button type="button" data-mediaid="'.$mediaid.'" class="btn btn-default dropdown-toggle ga_download_audio" data-toggle="dropdown"><span class="glyphicon glyphicon-download-alt"></span> Download</button>
							<ul class="dropdown-menu" role="menu">';
							      
								foreach ($downloadobj as $key=>$value)
								{
									$dformat=$key;$durl=$value;
									$opres.='<li><a href="'.$durl.'" data-mediaid="'.$mediaid.'">'.$dformat.'</a></li>';
								}
							      
							$opres.='</ul>
						</div>
			  				
										
										
							<div class="btn-group">
							    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></button>
							    <ul class="dropdown-menu" role="menu">
							      <!--<li><a href="#" data-mediaid="'.$mediaid.'"><span class="glyphicon glyphicon-list-alt"></span> Add to Playlist</a></li>
							      <li><a href="#" data-mediaid="'.$mediaid.'"><i class="fa fa-wifi"></i> Add to Stream</a></li>
							      <li><a href="#" data-mediaid="'.$mediaid.'"><span class="glyphicon glyphicon-heart"></span> Favorite</a></li>-->
				  				
							      <li><a class="ajax-btn" data-mkey="gbx_aud_del" data-prompt="1" data-suchide="#gbx-audmed-'.$mediaid.'" data-mediaid="'.$mediaid.'" data-galid="'.$galid.'"><span class="glyphicon glyphicon-minus-sign"></span> Delete</a></li>
							    </ul>
			  				</div>
						</div>
						
				  		</div>
				    </div>
				  </div>';
					}
					$opres.='</div>';
				}
				
				echo $opres;
			}
		
		?>
		
	  
						</div>
						
						<div class="panel-footer">Section under construction</div>
				  </div>
				</div>
		  		 
			</div>
			</div>
			
<script type="text/javascript">
var utilityobj=new JS_UTILITY();

utilityobj.checkbox($('input[type="checkbox"]'), {
	checkedClass: 'glyphicon glyphicon-ok'
});
</script>