<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_contacts.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}

$inobj=new ta_intouch();
echo $inobj->facebook_load_sdk();
?>
		  		
		  		<div class="row">
  			<div class="col-sm-6 col-md-4 col-lg-2">
		  		 <ul class="list-group">
				  <li class="list-group-item active">All Networks</li>
				  <!-- <li class="list-group-item"><i class="fa fa-facebook-official"></i> Facebook</li>
				  <li class="list-group-item"><i class="fa fa-google-plus-square"></i> Google Plus</li>-->
				</ul>
			</div>
			<div class="col-sm-6 col-md-8 col-lg-10">		  		 
		  		 
		  		 
		  		 <div class="panel panel-default">
				  <div class="panel-heading">
				  
				  <div class="dropdown pull-right ta-lmargin">
                		<button class="btn btn-default" title="More" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus"></i> Add</button>
                		<ul class="dropdown-menu">
					      <li><a class="box-tog" data-toggle="modal" data-mkey="intouch_fb_addpage"><i class="fa fa-file"></i> New Page</a></li>
					    </ul>
				</div>
				
				<button class="btn btn-primary btn-sm grant_fp_perm pull-right" title="Grant Friendbus the permission to access your Facebook account details">Grant Permission</button>
				  
				  
				    <h3 class="panel-title"><i class="fa fa-facebook-official"></i> Facebook </h3>
				    <div style="clear:both;"></div>
				  </div>
				<div class="panel-body">
				  	
				  	<h4>Pages</h4>
				  	
				<?php 
					$inres_fbpg=$inobj->facebook_get_pages($userobj->uid);
					
					for($i=0;$i<count($inres_fbpg);$i++)
					{
						$intouchid=$inres_fbpg[$i][changesqlquote(tbl_intouchdb::col_intouchid,"")];
						$jsonid=$inres_fbpg[$i][changesqlquote(tbl_intouchdb::col_jsonid,"")];
						$jsonobj=$utilityobj->jsondata_get($jsonid);
						
						$pgurl=$jsonobj->pgurl;
						$pglbl=$jsonobj->pglbl;
						
						echo '<div class="fb-page" data-href="'.$pgurl.'" data-tabs="timeline,events,messages" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="'.$pgurl.'"><a href="'.$pgurl.'">'.$pglbl.'</a></blockquote></div></div>';
					}
				?>
				
				<hr>
				<h4>Contacts</h4>
				
				<!-- <div class="fb-page" data-href="https://www.facebook.com/manipalthetalk.net" data-tabs="timeline,events,messages" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/manipalthetalk.net"><a href="https://www.facebook.com/manipalthetalk.net">Manipal The Talk.Net</a></blockquote></div></div>
				
				<div class="fb-page" data-href="https://www.facebook.com/manipalthetalk.net" data-tabs="timeline,events,messages" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/manipalthetalk.net"><a href="https://www.facebook.com/manipalthetalk.net">Manipal The Talk.Net</a></blockquote></div></div>-->
				
				
				
				
					<div class="row">					  
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					</div>
				
				
				</div>						
						<div class="panel-footer">200 Contacts in total, Filter Applied:Genre=Rock</div>
				  </div>
				  
				  
				  
				   <div class="panel panel-default">
				  <div class="panel-heading">
				  	<span class="pull-right"><input type="text" class="form-control" placeholder="Search"></span>
				    <h3 class="panel-title"><i class="fa fa-google-plus-square"></i> Google Plus</h3>
				    <div style="clear:both;"></div>
				  </div>
				<div class="panel-body">
				  	
				
					<div class="row">
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Lives in Karnataka, INDIA
					  </div>
					</div>
					  </div>
					  
					</div>
				
				
				</div>						
						<div class="panel-footer">200 Contacts in total, Filter Applied:Genre=Rock</div>
				  </div>
				  
				  
				</div>		  		 
			</div>
			
			<script type="text/javascript">
				listenevent($(".grant_fp_perm"), "click", function(){
					<?php 
						echo $inobj->facebook_request_permission("email,user_birthday,user_friends");
					?>
				});
			</script>