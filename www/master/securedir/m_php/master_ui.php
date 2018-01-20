<?php
/**
 *
 * CONTAINS FUNCTIONS WHICH HELP IN UI DESIGN
 * @author T.V.VIGNESH
 *
 */
class ta_uifriend
{
	/**
	 *
	 * GENERATES CONTACT LINK (WITH A HREF)
	 *
	 * @param string $keyval Contact Key name which specifies link to be given (Defaults to contact)
	 * @param string $msg Text to be displayed in the link (Defaults to Contact Us)
	 * @return string The complete html link
	 */
	public function contactfunc($keyval="contact",$msg="Contact Us")
	{
		$res=" <a href='";
		if($keyval=="webadmin")
		{
			$res.="http://www.techahoy.com/contact?webadmin=1";
		}
		if($keyval=="contact")
		{
			$res.="http://www.techahoy.com/contact?contact=1";
		}
		$res.="'>".$msg."</a> ";
		return $res;
	}

	/**
	 *
	 * GENERATES A RANDOM STRING OF GIVEN LENGTH
	 * @param unknown_type $length The length of string to be generated
	 * @param unknown_type $db If it should be checked for duplicate, the database name where match has to be checked
	 * @param unknown_type $table If it should be checked for duplicate, the table name where match has to be checked
	 * @param unknown_type $col If it should be checked for duplicate, the column name where match has to be checked
	 * @return string Returns the random string generated
	 */
	public function randomstring($length=15,$db="",$table="",$col="")
	{
		$randval=substr(md5(microtime()),rand(0,26),$length);
		if($db==""&&$table==""&&$col=="")
		{
			return $randval;
		}
		else
		{
			$dbobj=new ta_dboperations();
			do
			{
				$res=$dbobj->dbquery("SELECT * FROM ".$table." WHERE ".$col."='$randval'",$db);
			}while($res!=EMPTY_RESULT);
			return $randval;
		}
	}

	/**
	 *
	 * REDIRECT USER TO A URL USING JAVASCRIPT
	 * @param unknown_type $url The URL to which redirection is made
	 */
	public function redirectjs($url)
	{
		echo "Please wait.. Redirecting..";
		ob_end_flush();
		die('
				<script type="text/javascript">
					window.location.assign("'.$url.'");
				</script>
		');
	}

	/**
	 *
	 * ASSIGN ADMIN ROLE
	 * @param unknown_type $uid User ID of person for which role is to be assigned
	 * @param unknown_type $roletype Flag Value (1-admin,2-moderator)
	 * @param unknown_type $roletitle Role title/Position of person
	 * @param unknown_type $permflag Permission (1-allowed,2-under review,3-blocked)
	 * @return string SUCCESS on successful assign, "" on failure
	 */
	public function assignadminroles($uid,$roletype,$roletitle,$permflag="1")
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbinsert("INSERT INTO ".tbl_admin_roles::tblname." (".tbl_admin_roles::col_uid.",".tbl_admin_roles::col_roleflag.",".tbl_admin_roles::col_roletitle.",".tbl_admin_roles::col_permflag.") VALUES
				('$uid','$roletype','$roletitle','$permflag')",tbl_admin_roles::dbname)=="SUCCESS")
				{
					return SUCCESS;
				}
				else
				{
					return FAILURE;
				}
	}

	/**
	 *
	 * EDIT ADMIN ROLE
	 * @param unknown_type $uid User ID of person for which admin role is to be edited
	 * @param unknown_type $colflag Flag Value of item to be edited (1-role flag,2-role title,3-perm flag)
	 * @param unknown_type $value Value to be inserted for the respective column
	 * @return string SUCCESS on successful editing, "" on failure
	 */
	public function editadminrole($uid,$colflag,$value)
	{
		$dbobj=new ta_dboperations();
		/*
		 * colflag
		* 1-edit role flag
		* 2-edit role title
		* 3-edit perm flag
		*/
		switch ($colflag)
		{
			case "1":$res=$dbobj->dbupdate("UPDATE ".tbl_admin_roles::tblname." SET ".tbl_admin_roles::col_roleflag."='$value' WHERE ".tbl_admin_roles::col_uid."='$uid'",tbl_admin_roles::dbname);break;
			case "2":$res=$dbobj->dbupdate("UPDATE ".tbl_admin_roles::tblname." SET ".tbl_admin_roles::col_roletitle."='$value' WHERE ".tbl_admin_roles::col_uid."='$uid'",tbl_admin_roles::dbname);break;
			case "3":$res=$dbobj->dbupdate("UPDATE ".tbl_admin_roles::tblname." SET ".tbl_admin_roles::col_permflag."='$value' WHERE ".tbl_admin_roles::col_uid."='$uid'",tbl_admin_roles::dbname);break;
			default:return FAILURE;
		}
		if($res=="SUCCESS")
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * CHECK ADMIN ROLE OF THE USER
	 * @param unknown_type $uid User ID of the person for whom role is checked
	 * @return string The role flag of the USER (1-admin,2-moderator)
	 */
	public function checkadminrole($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_admin_roles::tblname." WHERE ".tbl_admin_roles::col_uid."='$uid' AND ".tbl_admin_roles::col_permflag."='1' LIMIT 0,1",tbl_admin_roles::dbname);
		return $res[0][changesqlquote(tbl_admin_roles::col_roleflag,"")];
	}

	/**
	 *
	 * EVAL ALL GLOBAL VARIABLES WHEN CALLED (DONT USE IT MUCH)
	 * @return string EVAL CODE OF GLOBAL VARIABLES
	 */
	public function globals()
	{
		$vars = array();
		foreach($GLOBALS as $k => $v){
			$vars[] = "$".$k;
		}
		return "global ".  join(",", $vars).";";
	}

	/**
	 *
	 * REDIRECT TO THE RESPECTIVE PAGE AFTER AN OPERATION
	 * @param unknown_type $path Path of the page to redirect to (Defaults to RETURNPATH)
	 */
	public function returnpath($path="")
	{
		if($path=="")
		{
			$path=$GLOBALS["returnpath"];
		}
		echo
		'
				<script type="text/javascript">
					window.location.assign("'.$path.'");
				</script>
			';
	}

	/**
	 *
	 * EXTERNAL FONT TO BE INCLUDED
	 * @param unknown $fontfamily Font Family Name which will be used
	 * @param unknown $url URL Where the font is located in the server (Defaults to "" which means take the font from google font api else take from the server)
	 * @param string $local1 The Name of the Local font
	 * @param string $local2 The Name of the Local font for MAC
	 */
	public function includefont($fontfamily,$url="",$local1="",$local2="")
	{
		if($url=="")
		{
			$uiobj=new ta_uifriend();
			$uiobj->includegooglefont($fontfamily);
			return;
		}
		if($local1!="")
		{
			echo '
					@font-face{
						font-family:"'.$fontfamily.'";
						src:
							local("'.$local1.'"),
							local("'.$local2.'"),
							url("'.$url.'")
					}
				';
		}
		else
		{
			echo '
					@font-face{
						font-family:"'.$fontfamily.'";
						src:url("'.$url.'")
					}
				';
		}
	}

	/**
	 *
	 * INCLUDE FONTS THROUGH GOOGLE APIs
	 * @param unknown $family Font family Name
	 * @param unknown $effect Optional Effect to be added to the font from Google Font API
	 * @return unknown The font family or concatenated font family as a string
	 */
	public function includegooglefont($family,$effect="")
	{
		$extra='';
		if($effect!="")
		{
			$extra.="&effect=".$effect;
		}
		if(is_array($family))
		{
			$concat='';
			foreach ($family as $str)
			{
				$concat.=$str."|";
			}
			echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.$concat.$extra.'">';
			return $concat;
		}
		echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.$family.$extra.'">';
		return $family;
	}
	
	public function userpop_toggle($uid,$uid_friend)
	{
		$socialobj=new ta_socialoperations();
		$userobj=new ta_userinfo();
		$blacklistobj=new ta_blacklists();
		
		$listidarr=Array();
		$disp_nick="";
		$displabel='';
		
		$fres=$userobj->user_getinfo($uid_friend);
		
		$fname_friend=$fres["ufname"];
		$mname_friend=$fres["umname"];
		$lname_friend=$fres["ulname"];
		$profpic_friend=$fres["cprofpic2"];
		
		$res3=$socialobj->get_mutualfriends($uid,$uid_friend);
		$mutcount=count($res3);
		
		$follabel="Follow";
		if($socialobj->follower_check($uid,$uid_friend))
		{$follabel="Unsubscribe";}
			
		if($blacklistobj->block_check($uid,$uid_friend,"1"))
		{$disp_block='<i class="fa fa-unlock"></i> Unblock';}
		else
		{$disp_block='<i class="fa fa-ban"></i> Block';}
		
		$isfrnd="-1";
		if($socialobj->friend_check_mutual($uid, $uid_friend))
		{
			$isfrnd="1";
			$fdisp='<i class="fa fa-times"></i> Remove Friend';
			$res=$socialobj->friend_get_info($uid,$uid_friend);
			$flvl_friend=$res[0][changesqlquote(tbl_frienddb::col_flevel,"")];
			$nick_friend=$res[0][changesqlquote(tbl_frienddb::col_nickname,"")];
			
			$res1=$socialobj->get_belonginglist_friend($uid_friend,$uid);
			$listcount=count($res1);
			
			$res_listall=$socialobj->get_list_info_all_user($uid,"1");
			$listall_count=count($res_listall);
			if($nick_friend!=""){$disp_nick='(<span class="fr_fn_'.$uid_friend.'">'.$nick_friend.'</span>)';}
			
			for($m=0;$m<$listcount;$m++)
			{
			$listidarr[$m]=$res1[$m][changesqlquote(tbl_listsdb::col_listid)];
			}
			
			for($j=0;$j<$listcount;$j++)
			{
				$listid=$res1[$j][changesqlquote(tbl_listsdb::col_listid,"")];
				$res2=$socialobj->get_listinfo($listid);
				$listlabel=$res2[changesqlquote(tbl_listinfo::col_listname,"")];
				$listdesc=$res2[changesqlquote(tbl_listinfo::col_listdesc,"")];
				
				$displabel.='<span class="label label-success contacts_lists" data-elemid="'.$listid.'" title="'.$listdesc.'">'.$listlabel.'</span> ';
			}
		}
		else
			if($socialobj->friend_check($uid,$uid_friend))
			{$fdisp='<i class="fa fa-times"></i> Cancel Request';}
		else
			if($socialobj->friend_check($uid_friend,$userobj->uid))
			{$fdisp='<i class="fa fa-check"></i> Accept Request';}
		else
			{$fdisp='<i class="fa fa-user-plus"></i> Add Friend';}
		
		$result='
		<div id="p_cnt_uid_'.$uid_friend.'_a" class="popover_html">
			<img class="media-object pull-left" src="'.$profpic_friend.'" width="50" height="50">
			<div class="pull-left cbx-tabs-contfr_rbx">
				<b><a href="/users.php?uid='.$uid_friend.'">'.$fname_friend.' '.$mname_friend.' '.$lname_friend.'</a> '.$disp_nick.'</b>
				<br>'.$mutcount.' Mutual Contacts<br>
				';
		if($isfrnd!="-1")
		{
				$result.='<br>Friendship: <span class="d_flvl_'.$uid_friend.'">'.$socialobj->friend_leveltotext($flvl_friend).'</span>
				<input class="usr_flvl" type="range" data-mkey="contact_flvl" data-eltarget=".d_flvl_'.$uid_friend.'" data-fid="'.$uid_friend.'" value="'.$flvl_friend.'" min="0" max="10" step="1"/>';
		}
				
				$result.='
				<div class="usr_flbadge_'.$uid_friend.'">'.$displabel.'</div>
			</div>
			<div style="clear:both;"></div><br>

			<button class="btn btn-default btn-sm ajax-btn" data-mkey="s_togfriend" data-fuid="'.$uid_friend.'" data-eltarget=".s_fshipstat_'.$uid_friend.'"><span class="s_fshipstat_'.$uid_friend.'">'.$fdisp.'</span></button>';
		
				
		if($isfrnd!="-1")
		{
			$result.='<div class="dropdown div-inline">
					<button class="btn btn-default btn-sm frnd_addlist" data-fuid="'.$uid_friend.'" data-toggle="dropdown"><i class="fa fa-list"></i> <span class="frnd_listcnt">'.$listcount.'</span> Lists</button>
		
			<ul class="dropdown-menu dropdown-menu-left" role="menu" aria-expanded="true" data-fuid="'.$uid_friend.'">';
				
			if($listall_count!=0)
			{
				for($k=0;$k<$listall_count;$k++)
				{
				$l1_id=$res_listall[$k][changesqlquote(tbl_listinfo::col_listid,"")];
				$l1_name=$res_listall[$k][changesqlquote(tbl_listinfo::col_listname,"")];
				$l1_desc=$res_listall[$k][changesqlquote(tbl_listinfo::col_listdesc,"")];
				$l1_pic=$res_listall[$k][changesqlquote(tbl_listinfo::col_listpic,"")];
					
				$checkstat="";
				if(in_array($l1_id,$listidarr)){$checkstat=" checked";}
			
				$result.='<li><a><label class="pop_flist_dplbl" data-mkey="contact_flisttog" data-fuid="'.$uid_friend.'" data-eltarget=".usr_flbadge_'.$uid_friend.'" data-elpropstop="1" data-flid="'.$l1_id.'" title="'.$l1_desc.'"><input type="checkbox"'.$checkstat.'> '.$l1_name.'</label></a></li>';
				}
			}
				
			$result.='<li><a class="pop_flist_dpn"><i class="fa fa-plus"></i> Create New List</a></li>
				</ul>
				</div>
				
				<div class="btn-group">
					<button class="btn btn-default btn-sm" title="Message"><i class="fa fa-comments"></i></button>
					<button class="btn btn-default btn-sm" title="Video Call"><i class="fa fa-video-camera"></i></button>
					<button class="btn btn-default btn-sm" title="Audio Call"><i class="fa fa-phone"></i></button>
				</div>
					';
		}
		
		$result.='
				<div class="dropdown div-inline">
					<button class="btn btn-default btn-sm" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
					<ul class="dropdown-menu dropdown-menu-left">
						<li><a class="ajax-btn" data-mkey="contacts_togfollow" data-fuid="'.$uid_friend.'" data-eltarget=".usr_folstat_'.$uid_friend.'" data-elpropstop="1"><i class="fa fa-rss"></i>
								<span class="usr_folstat_'.$uid_friend.'">'.$follabel.'</span>
							</a></li>
				';
		if($isfrnd!="-1")
		{
			$result.='
						<li><a data-mkey="contact_fnick" data-fuid="'.$uid_friend.'" data-fnick="'.$fname_friend.'" data-eltarget=".fr_fn_'.$uid_friend.'" class="frnd_nick"><i class="fa fa-tag"></i> Assign a Nick</a></li>
						<li><a href="#"><i class="fa fa-sticky-note"></i> Recent Activity</a></li>
					';
		}
			$result.='
						<li><a href="#" class="box-tog" data-mkey="s_reportfrnd" data-toggle="modal" data-fuid="'.$uid_friend.'"><i class="fa fa-flag"></i> Report</a></li>
						<li><a href="#" class="s_blkfrnd_'.$uid_friend.' ajax-btn" data-mkey="s_togblockfriend" data-fuid="'.$uid_friend.'" data-eltarget=".s_blkfrnd_'.$uid_friend.'" data-elpropstop="1">'.$disp_block.'</a></li>
					</ul>
				</div>
						</div>';
		
		return $result;
	}
	
	public function disp_gal_pic($uid,$galid,$start="0",$tot="15")
	{
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$audobj=new ta_audience();
		$userobj=new ta_userinfo();
		$userobj->userinit();
		
		$res=$galobj->gallery_get_user($uid);
		$totgal=count($res);
		$result='';
		if($totgal==0)
		{
			$result.="Please create a gallery to start adding pictures to it!";
		}
		else
		{
			$galres=$galobj->gallery_getmedia($galid,"1",$start,$tot);
			for($i=0;$i<count($galres);$i++)
			{
				$mediaid=$galres[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
				$mediatitle=$galres[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
				$mediadesc=$galres[$i][changesqlquote(tbl_galdb::col_mediadesc,"")];
				$mediaurl=$galres[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
				$mediatime=$galres[$i][changesqlquote(tbl_galdb::col_mediatime,"")];
				$mediathumb=$galres[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
				$mediaaud=$galres[$i][changesqlquote(tbl_galdb::col_audienceid,"")];
				
				if(!$audobj->audience_check_user($mediaaud,$userobj->uid))continue;
			
				if($mediathumb=="")
				{
					$mediathumb=$mediaurl;
				}
				
				$result.='<div class="galbox_viewpic_cont col-xs-12 col-sm-6 col-md-4 col-lg-2 gbx_pic_'.$mediaid.'" style=\'background-image:url("'.$utilityobj->pathtourl($mediathumb).'");\' data-orig="'.$utilityobj->pathtourl($mediaurl).'" data-imgtitle="'.$mediatitle.'" data-desc="'.$mediadesc.'" data-imgid="'.$mediaid.'" data-mediaid="'.$mediaid.'" data-galid="'.$galid.'"></div>';
			}
						
			if(count($galobj->gallery_getmedia($galid,"1"))==0)
			{
				$result.='The gallery is currently empty!';
			}
		}
		return $result;
	}
	
	public function disp_gal_vid($uid,$galid,$start="0",$tot="15")
	{
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		$fileobj=new ta_fileoperations();
		$utilityobj=new ta_utilitymaster();
		$userobj=new ta_userinfo();
		$audobj=new ta_audience();
		
		$userobj->userinit();
		
		$res=$galobj->gallery_get_user($uid);
		$totgal=count($res);
		$result='';
		if($totgal==0)
		{
			$result.="Please create a gallery to start adding videos to it!";
		}
		else
		{
			$galid_vidpro=$galobj->get_galid_special($uid,"9");
			$res1=$galobj->gallery_getmedia($galid,"3",$start,$tot);
			for($i=0;$i<count($res1);$i++)
			{
				$mediaid=$res1[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
				$mediaurl=$res1[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
				$mediathumb=$res1[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
				$mediatime=$res1[$i][changesqlquote(tbl_galdb::col_mediatime,"")];
				$mediatitle=$res1[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
				$mediadesc=$res1[$i][changesqlquote(tbl_galdb::col_mediadesc,"")];
				$jsonid=$res1[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
				$mediauid=$res1[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
				$mediagalid=$res1[$i][changesqlquote(tbl_galdb::col_galid,"")];
				$mediaaud=$res1[$i][changesqlquote(tbl_galdb::col_audienceid,"")];
				
				if(!$audobj->audience_check_user($mediaaud,$userobj->uid))continue;
				
				$jsonobj=$utilityobj->jsondata_get($jsonid);
				
				if($mediatitle==""){$mediatitle="Unnamed";}
				
				$ext=$fileobj->fileinfo(ROOT_SERVER.$mediaurl,"3");
				 
				$result.='
			
				<div class="col-sm-6 col-md-4 col-lg-2 galbox_vidsuggest gbx-vid-thumb-'.$mediaid.'">
				<div class="thumbnail" data-galid="'.$galid.'" data-mediaid="'.$mediaid.'" data-vidsrc=\'{';
				
				
				if(isset($jsonobj->formats))
				{
					$co=0;
					if(isset($jsonobj->formats->webm))
					{
						if($co!=0){$result.=',';}
						$medid=$jsonobj->formats->webm;
						$medurl=$galobj->geturl_media($galid_vidpro,$medid,"3");
						$medext=$fileobj->fileinfo($medurl,"3");
						$result.='"'.$fileobj->contenttype($medext).'": "'.$utilityobj->pathtourl($medurl).'"';
						$co++;
					}
					if(isset($jsonobj->formats->ogv))
					{
						if($co!=0){$result.=',';}
						$medid=$jsonobj->formats->ogv;
						$medurl=$galobj->geturl_media($galid_vidpro,$medid,"3");
						$medext=$fileobj->fileinfo($medurl,"3");
						$result.='"'.$fileobj->contenttype($medext).'": "'.$utilityobj->pathtourl($medurl).'"';
						$co++;
					}
					foreach($jsonobj->formats as $key => $value) 
					{
						if($key=="webm"||$key=="ogv")continue;
						if($co!=0){$result.=',';}
						$medid=$value;
						$medurl=$galobj->geturl_media($galid_vidpro,$medid,"3");
						$medext=$fileobj->fileinfo($medurl,"3");
						$result.='"'.$fileobj->contenttype($medext).'": "'.$utilityobj->pathtourl($medurl).'"';
						$co++;
					}
				}
				else
				{
					$result.='"'.$fileobj->contenttype($ext).'": "'.$utilityobj->pathtourl($mediaurl).'"';
				}
						
				$result.='}\' data-vidposter=\'{ "normal": "'.$utilityobj->pathtourl($mediathumb).'"}\'>
			      <div class="thumb">
						<a href="#"><span class="play">&#9658;</span><div class="overlay"></div></a>
						
						<img src="'.$utilityobj->pathtourl($mediathumb).'" width="160" height="130">
								
				</div>
			      <div class="caption">
			        <h5 class="galbox_vidtitle">'.$mediatitle.'</h5>
			
			        <div class="galbox_vidsuggestbtn">
				        <div class="btn-group">
			        		
			        		<div class="pull-left">
				  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> Manage</button>
				        		<ul class="dropdown-menu" role="menu">
			        			';
				        		
				if($userobj->uid==$mediauid)
				{
					$result.='<li><a class="ajax-btn" data-mkey="gbx_vid_del" data-mediaid="'.$mediaid.'" data-galid="'.$galid.'" data-suchide=".gbx-vid-thumb-'.$mediaid.'"><i class="fa fa-trash"></i> Delete Video</a></li>
							<li><a class="box-tog" data-mkey="box_audience" data-toggle="modal" data-autoset="1" data-toggle="modal" data-pelem="galmed_audience" data-elname="'.$mediagalid.'" data-elemid="'.$mediaid.'"><i class="fa fa-users"></i> Change Privacy</a></li>
							';
				}
				
				    $result.='</ul>
			        		</div>
				  			
			        		<!--<div class="pull-left ta-lmargin">
								    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Add to List <i class="fa fa-caret-down"></i></button>
								    <ul class="dropdown-menu" role="menu">
								      <li><a href="#"><span class="glyphicon glyphicon-time"></span> Watch Later</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-ok"></span> Watched</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-minus-sign"></span> Ignore</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-heart"></span> Favorite</a></li>
								    </ul>
				  			</div>-->
			        		<div style="clear:both;"></div>
			        		
						</div>
				   
				   
			        </div>
			      </div>
			    </div>
			  </div>
			
						';
		  	}
		
			if(count($galobj->gallery_getmedia($galid,"3"))==0)
			{
				$result.='The gallery is currently empty!';
			}
		}
		echo  $result;
	}
	
	
	
	public function disp_gal_doc($uid,$galid,$start="0",$tot="15")
	{
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		$fileobj=new ta_fileoperations();
		$utilityobj=new ta_utilitymaster();
		$userobj=new ta_userinfo();
		$audobj=new ta_audience();
		
		$userobj->userinit();
	
		$res=$galobj->gallery_get_user($uid);
		$totgal=count($res);
		$result='';
		if($totgal==0)
		{
			$result.="Please create a gallery to start adding documents to it!";
		}
		else
		{
			$galid_docpro=$galobj->get_galid_special($uid,"10");
			$res1=$galobj->gallery_getmedia($galid,"2",$start,$tot);
			for($i=0;$i<count($res1);$i++)
			{
				$mediaid=$res1[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
				$mediaurl=$res1[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
				$mediathumb=$res1[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
				$mediatime=$res1[$i][changesqlquote(tbl_galdb::col_mediatime,"")];
				$mediatitle=$res1[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
				$mediadesc=$res1[$i][changesqlquote(tbl_galdb::col_mediadesc,"")];
				$jsonid=$res1[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
				$mediauid=$res1[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
				$mediagalid=$res1[$i][changesqlquote(tbl_galdb::col_galid,"")];
				$mediaaud=$res1[$i][changesqlquote(tbl_galdb::col_audienceid,"")];
	
				if(!$audobj->audience_check_user($mediaaud,$userobj->uid))continue;
				
				$jsonobj=$utilityobj->jsondata_get($jsonid);
	
				if($mediatitle==""){$mediatitle="Unnamed";}
	
				$ext=$fileobj->fileinfo(ROOT_SERVER.$mediaurl,"3");
					
				$result.='
		
				<div class="col-sm-6 col-md-4 col-lg-2 galbox_docsuggest gbx-doc-thumb-'.$mediaid.'">
				<div class="thumbnail" data-galid="'.$galid.'" data-mediaid="'.$mediaid.'" data-docsrc=\'{';
	
	
				if(isset($jsonobj->formats))
				{
					$result.='"'.$fileobj->contenttype($ext).'": "'.$utilityobj->pathtourl($mediaurl).'"';
					foreach($jsonobj->formats as $key => $value)
					{
						$result.=',';
						$medid=$value;
						$medurl=$galobj->geturl_media($galid_docpro,$medid,"3");
						$medext=$fileobj->fileinfo($medurl,"3");
						$result.='"'.$fileobj->contenttype($medext).'": "'.$utilityobj->pathtourl($medurl).'"';
					}
				}
				else
				{
					$result.='"'.$fileobj->contenttype($ext).'": "'.$utilityobj->pathtourl($mediaurl).'"';
				}
	
				$result.='}\' data-docposter=\'{ "normal": "'.$utilityobj->pathtourl($mediathumb).'"}\'>
			      <div class="thumb">
						<a href="#"><span class="view-elem" data-toggle="modal" data-target="#gal_pdf_viewmodal">&#9658;</span><div class="overlay"></div></a>
	
						<img src="'.$utilityobj->pathtourl($mediathumb).'" width="160" height="130">
	
				</div>
			      <div class="caption">
			        <h5 class="galbox_doctitle">'.$mediatitle.'</h5>
		
			        <div class="galbox_docsuggestbtn">
				        <div class="btn-group">
			    
			        		<div class="pull-left">
				  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> Manage</button>
				        		<ul class="dropdown-menu" role="menu">
			        		';
				 
				if($userobj->uid==$mediauid)
				{
					$result.='<li><a class="ajax-btn" data-mkey="gbx_doc_del" data-mediaid="'.$mediaid.'" data-galid="'.$galid.'" data-suchide=".gbx-doc-thumb-'.$mediaid.'"><i class="fa fa-trash"></i> Delete Document</a></li>
							<li><a class="box-tog" data-mkey="box_audience" data-toggle="modal" data-autoset="1" data-toggle="modal" data-pelem="galmed_audience" data-elname="'.$mediagalid.'" data-elemid="'.$mediaid.'"><i class="fa fa-users"></i> Change Privacy</a></li>
							';
				}
				
				 $result.='</ul>
			        		</div>
				 
			        		<div class="pull-left ta-lmargin">
								    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Add to List <i class="fa fa-caret-down"></i></button>
								    <ul class="dropdown-menu" role="menu">
								      <li><a href="#"><span class="glyphicon glyphicon-time"></span> Watch Later</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-ok"></span> Watched</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-minus-sign"></span> Ignore</a></li>
								      <li><a href="#"><span class="glyphicon glyphicon-heart"></span> Favorite</a></li>
								    </ul>
				  			</div>
			        		<div style="clear:both;"></div>
			    
						</div>
				
				
			        </div>
			      </div>
			    </div>
			  </div>
		
						';
			}
	
			if(count($galobj->gallery_getmedia($galid,"2"))==0)
			{
				$result.='The gallery is currently empty!';
			}
		}
		echo  $result;
	}
	
	public function load_comments($tid,$comtid,$comstart,$comtot)
	{
		$msgobj=new ta_messageoperations();
		$colobj=new ta_collection();
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		$cuobj=new ta_userinfo();
		$ruobj=new ta_userinfo();
		$userobj=new ta_userinfo();
		
		$userobj->userinit();
		
		$presult='';
			$comtres=$msgobj->getmsg($comtid,$comstart,$comtot,"","2");
			for($c=0;$c<count($comtres);$c++)
			{
				$comment=$comtres[$c][changesqlquote(tbl_message_content::col_msg,"")];
				$comfuid=$comtres[$c][changesqlquote(tbl_message_content::col_fuid,"")];
				$comtime=$comtres[$c][changesqlquote(tbl_message_content::col_msgtime,"")];
				$comcoldmedid=$comtres[$c][changesqlquote(tbl_message_content::col_col_mediaid,"")];
				$comrepto=$comtres[$c][changesqlquote(tbl_message_content::col_replyto,"")];
				$commsgid=$comtres[$c][changesqlquote(tbl_message_content::col_msgid,"")];
				$comcolmedid=$comtres[$c][changesqlquote(tbl_message_content::col_col_mediaid,"")];
				
				if($comrepto!="")continue;
		
				$colres1=$colobj->get_collection_complete_info(tbl_collection_media::tblname, tbl_collection_media::col_col_mediaid, $comcolmedid);
		
				$atttext1='';
				$totatt1=count($colres1);
				for($b=0;$b<$totatt1;$b++)
				{
					$medid1=$colres1[$b][changesqlquote(tbl_collection_media::col_mediaid,"")];
					$medres1=$galobj->media_get_info($medid1);
					$medtitle1=$utilityobj->pathtourl($medres1[0][changesqlquote(tbl_galdb::col_mediatitle,"")]);
					$medurl1=$utilityobj->pathtourl($medres1[0][changesqlquote(tbl_galdb::col_mediaurl,"")]);
		
					$atttext1.='<a href="'.$medurl1.'">'.$medtitle1.'</a><br>';
				}
		
				if($totatt1!=0)
				{
					$atttext1="<hr>Attached files:<br>".$atttext1;
				}
		
		
		
				$cuobj->user_initialize_data($comfuid);
				$comfpic=$utilityobj->pathtourl($cuobj->compprofpic2);
		
				$repres=$msgobj->thread_get_replies($commsgid);
		
				$presult.= '
					<li class="list-group-item" id="ta_cmnt_'.$commsgid.'">
 					<img src="'.$comfpic.'" class="pull-left comp-commentimg" width="30" height="30">
 					<span class="comp-comment">
 						<span class="comp-commentuname"><a href="#">'.$cuobj->fname.'</a></span>:
 						'.$comment.$atttext1;
				
				if($userobj->uid==$comfuid)
				{
					$presult.='<a style="cursor:pointer;" class="ajax-btn pull-right ta-rmargin" data-mkey="comnt_del" data-threadid="'.$comtid.'" data-msgid="'.$commsgid.'" data-prompt="1" data-suchide="#ta_cmnt_'.$commsgid.'">Delete</a>';
				}
				
 				$presult.='<span class="pull-right ta-rmargin"><a class="reply-comment" data-threadid="'.$tid.'" data-msgid="'.$commsgid.'" data-replyto="'.$cuobj->fname.'">Reply</a></span></span>
 					<div style="clear: both;"></div><small class="pull-right" style="color:grey;">['.$comtime.']</small><div style="clear: both;">';
		
				if(count($repres)!=0)
				{
					$presult.= '<ul class="list-group">';
		
					for($p=0;$p<count($repres);$p++)
					{
		
						$reply=$repres[$p][changesqlquote(tbl_message_content::col_msg,"")];
						$comfuid=$repres[$p][changesqlquote(tbl_message_content::col_fuid,"")];
						$comtime=$repres[$p][changesqlquote(tbl_message_content::col_msgtime,"")];
						$comcoldmedid=$repres[$p][changesqlquote(tbl_message_content::col_col_mediaid,"")];
						$comrepto=$repres[$p][changesqlquote(tbl_message_content::col_replyto,"")];
						$commsgid1=$repres[$p][changesqlquote(tbl_message_content::col_msgid,"")];
						$comtid1=$repres[$p][changesqlquote(tbl_message_content::col_tid,"")];
		
						$ruobj->user_initialize_data($comfuid);
						$repfpic=$utilityobj->pathtourl($ruobj->compprofpic2);
		
						$presult.= '
 					<li class="list-group-item" id="ta_cmnt_'.$commsgid1.'">
 					<img src="'.$repfpic.'" class="pull-left comp-commentimg" width="30" height="30">
 					<span class="comp-comment">
 					<span class="comp-commentuname"><a href="#">'.$ruobj->fname.'</a></span>:
 					'.$reply;
 					
 					if($userobj->uid==$comfuid)
 					{
 						$presult.='<a style="cursor:pointer;" class="ajax-btn pull-right ta-rmargin" data-mkey="comnt_del" data-threadid="'.$comtid1.'" data-msgid="'.$commsgid1.'" data-prompt="1" data-suchide="#ta_cmnt_'.$commsgid1.'">Delete</a>';
 					}
						
 					$presult.='
 					<span class="pull-right comp-commentreplink ta-rmargin"><a class="reply-comment" data-threadid="'.$tid.'" data-msgid="'.$commsgid.'" data-replyto="'.$ruobj->fname.'">Reply</a></span></span>
 					<div style="clear: both;"></div><small class="pull-right" style="color:grey;">['.$comtime.']</small><div style="clear: both;">
 					</li>';
					}
		
					$presult.= '</ul>';
				}
					
		
				$presult.= '</li>';
			}
			
			if(count($comtres)==0)
			{
				$presult='';
			}
		return $presult;
	}
	
	public function disp_notifications($uid,$start="0",$tot="15")
	{
		$utilityobj=new ta_utilitymaster();
		$socialobj=new ta_socialoperations();
		$userobj=new ta_userinfo();
		
		$result='';
		$n_tot_unread=$socialobj->notifications_getcount($uid);
		$n_tot_all=$socialobj->notifications_getcount($uid,"3");
		$nres=$socialobj->readnotifications($uid,"3",$start,$tot);
		for($i=0;$i<count($nres);$i++)
		{
			$nid=$nres[$i][changesqlquote(tbl_notifications::col_notifyid,"")];
			$nicon=$nres[$i][changesqlquote(tbl_notifications::col_notifyicon,"")];
			$nlink=$nres[$i][changesqlquote(tbl_notifications::col_notifylink,"")];
			$nstatus=$nres[$i][changesqlquote(tbl_notifications::col_notifystatus,"")];
			$ncont=$nres[$i][changesqlquote(tbl_notifications::col_notifytext,"")];
			$ntime=$nres[$i][changesqlquote(tbl_notifications::col_notifytime,"")];
			$ntype=$nres[$i][changesqlquote(tbl_notifications::col_notifytype,"")];
			$jsonid=$nres[$i][changesqlquote(tbl_notifications::col_jsonid,"")];
		
			if($jsonid!="")
			{
				$jsonobj=$utilityobj->jsondata_get($jsonid);
			}
		
			switch ($ntype)
			{
				case "2":
					$result.='<li class="list-group-item" data-nid="'.$nid.'">
			<i class="fa fa-times pull-right notif-remove"></i><a href="'.$nlink.'">
			<img src="'.$nicon.'" style="height:inherit;" width="32" height="32" class="pull-left">
			<span class="pull-right" style="width:70%;">'.ucfirst($ncont).'</span></a>
			<br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'.$ntime.'</em></span>
			<div style="clear:both;"></div></li>';
					break;
				case "3":
					if(!$socialobj->friend_check_mutual($uid,$jsonobj->suid)&&$socialobj->friend_check($jsonobj->suid,$uid))
					{
						$result.='<span class="s_fshipstat_'.$jsonobj->suid.'" style="display:none;"></span><li class="list-group-item cbx-frcont-'.$jsonobj->suid.'" data-nid="'.$nid.'">
				<i class="fa fa-times pull-right notif-remove"></i><a href="'.$nlink.'">
				<img src="'.$userobj->getprofpic($jsonobj->suid).'" style="height:inherit;" width="32" height="32" class="pull-left">
				<span class="pull-right" style="width:70%;">'.ucfirst($ncont).'</span></a>
				<br>
				<span class="pull-right"><i class="fa fa-info-circle"></i> <em>'.$ntime.'</em></span>
				<div class="btn-group pull-right">
				<button class="btn btn-default ajax-btn" data-mkey="s_togfriend" data-fuid="'.$jsonobj->suid.'" data-suchide=".cbx-frcont-'.$jsonobj->suid.'" data-sucfunc="notif_read" data-eltarget=".s_fshipstat_'.$jsonobj->suid.'">
				<i class="fa fa-check-circle"></i> Accept</button>
				<button class="btn btn-default ajax-btn" data-mkey="s_removefriend" data-fuid="'.$jsonobj->suid.'" data-suchide=".cbx-frcont-'.$jsonobj->suid.'" data-sucfunc="notif_read" data-eltarget=".s_fshipstat_'.$jsonobj->suid.'">
				<i class="fa fa-times-circle"></i> Decline</button></div><div style="clear:both;"></div></li>
				';
					}
					else
						if((!$socialobj->friend_check($jsonobj->suid,$uid))||($socialobj->friend_check_mutual($uid,$jsonobj->suid)))
						{
							$socialobj->deletenotification($nid);
							$result.='';
						}
					else
					{
						$result.='';
					}
					break;
		
				case "8":
					$result.='<li class="list-group-item" data-nid="'.$nid.'">
				<i class="fa fa-times pull-right notif-remove"></i><a href="'.$nlink.'">
				<img src="'.$userobj->getprofpic($jsonobj->suid).'" style="height:inherit;" width="32" height="32" class="pull-left">
				<span class="pull-right" style="width:70%;">'.ucfirst($ncont).'</span></a>
				<br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'.$ntime.'</em></span>
				<div style="clear:both;"></div></li>
				';break;
				case "9":
					$result.='<li class="list-group-item" data-nid="'.$nid.'">
				<i class="fa fa-times pull-right notif-remove"></i><a href="'.$nlink.'">
				<img src="'.$nicon.'" style="height:inherit;" width="32" height="32" class="pull-left">
				<span class="pull-right" style="width:70%;">'.ucfirst($ncont).'</span></a>
				<br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'.$ntime.'</em></span>
				<div style="clear:both;"></div></li>';
					break;
				default:
					$result.='<li class="list-group-item" data-nid="'.$nid.'">
				<i class="fa fa-times pull-right notif-remove"></i><a href="'.$nlink.'">
				<img src="'.$nicon.'" style="height:inherit;" width="32" height="32" class="pull-left">
				<span class="pull-right" style="width:70%;">'.ucfirst($ncont).'</span></a>
				<br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'.$ntime.'</em></span>
				<div style="clear:both;"></div></li>';
					break;
			}
		
			$result.='
				<script type="text/javascript">
					window.notifarr.push("'.$nid.'");
				</script>
			';
		}
		
		if($n_tot_all==0)
		{
			$result.='<span class="notification-none">All caught Up! You have not received any notifications yet.</span>';
		}
		
		
		
		return $result;
	}
	
	public function disp_group_mem($gpid,$start="0",$tot="15")
	{
		$socialobj=new ta_socialoperations();
		$uobj=new ta_userinfo();
		$uobj->userinit();
		$utilityobj=new ta_utilitymaster();
		$memres=$socialobj->group_get_mem($gpid,$start,$tot);
		$memdisp='';
		for($i=0;$i<count($memres);$i++)
		{
			$memuid=$memres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
			$memtime=$memres[$i][changesqlquote(tbl_members_attached::col_jointime,"")];
			$memrole=$memres[$i][changesqlquote(tbl_members_attached::col_memrole,"")];
			$mempic=$uobj->getprofpic($memuid);
			$memname=$uobj->user_getfullname($memuid);
				
			if($memrole=="2")$memrdisp=" (Admin)";
			else
				if($memrole=="3")$memrdisp=" (Founder)";
				else
					$memrdisp="";
						
					$memdisp.='<li class="list-group-item gpmem_uid_'.$memuid.'"><img src="'.$utilityobj->pathtourl($mempic).'" width="32" height="32"> '.$memname.'<span class="pull-right">'.$memrdisp.'<b>Join Time</b>: <small><em>'.$memtime.'</em></small></span>';
					if($socialobj->group_user_check_admin($gpid, $uobj->uid))
					{
						$memdisp.='<br>
						<div class="btn-group pull-right">
						';
						if($memrole=="1")
						{
							$memdisp.='<button class="btn btn-default ajax-btn" data-mkey="gpbx_makeadmin" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-prompt="1">Make Admin</button>';
						}
						else
							if($memrole!="3")
							{
								$memdisp.='<button class="btn btn-default ajax-btn" data-mkey="gpbx_remadmin" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-prompt="1">Remove Admin</button>';
							}
		
						if($memrole!="3"&&$memuid!=$uobj->uid)
						{
							$memdisp.='<button class="btn btn-default ajax-btn" data-mkey="gpbx_blockreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-prompt="1" data-suchide=".gpmem_uid_'.$memuid.'">Block</button>';
						}
						$memdisp.='</div>
						<div style="clear:both;"></div>
				';
					}
					$memdisp.='</li>';
		}
		return $memdisp;
	}
	
	public function disp_group_post($gpid,$start="0",$tot="15")
	{
		$totload=8;
		$totcol=2;
		$mypres_1=$mypres_2='';
		
		$cnt=0;
		
		$socialobj=new ta_socialoperations();
		$gptres=$socialobj->readgrouptids($gpid,$start,$tot);
		
		for($i=0;$i<count($gptres);$i++)
		{
			$mytid=$gptres[$i][changesqlquote(tbl_threads_attached::col_tid,"")];
		
			$pres=require 'post_display.php';
			if($pres=="")continue;
			if($totcol==2)
			{
				if($cnt%2==0)
				{
					$mypres_1.=$pres;
				}
				else
				{
					$mypres_2.=$pres;
				}
			}
		
			$cnt++;
			if($cnt==$totload)break;
		}
		
		if($start=="0"&&count($gptres)==0)
		{
			return FAILURE;
		}
		
		return Array('col1'=>$mypres_1,'col2'=>$mypres_2); 
	}
	
	public function disp_lists($uid,$start="0",$tot="15")
	{
		$socialobj=new ta_socialoperations();
		$lres=$socialobj->get_list_user($uid,"4",$start,$tot);
		$ores='';
		$initlid="";
		for($i=0;$i<count($lres);$i++)
		{
			$lname=$lres[$i][changesqlquote(tbl_listinfo::col_listname,"")];
			$lid=$lres[$i][changesqlquote(tbl_listinfo::col_listid,"")];
			$ldesc=$lres[$i][changesqlquote(tbl_listinfo::col_listdesc,"")];
			if($i==0)$initlid=$lid;
			$ores.='<li class="list-group-item" data-listid="'.$lid.'" title="'.$ldesc.'">'.$lname.'</li>';
		}
		
		return json_encode(Array('res'=>$ores,'st'=>$start,'initlid'=>$initlid));
	}
	
	public function disp_list_container($uid,$listid,$start="0",$tot="15")
	{
		$socialobj=new ta_socialoperations();
		$msgobj=new ta_messageoperations();
		$galobj=new ta_galleryoperations();
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		
		$ores='';
		$listcont=$socialobj->get_listcontents($listid,$uid,$start,$tot);
		for($i=0;$i<count($listcont);$i++)
		{
			$itemid=$listcont[$i][changesqlquote(tbl_listsdb::col_itemid,"")];
			$itemurl=$listcont[$i][changesqlquote(tbl_listsdb::col_itemurl,"")];
			$itemtype=$listcont[$i][changesqlquote(tbl_listsdb::col_itemtype,"")];
			$listtime=$listcont[$i][changesqlquote(tbl_listsdb::col_listtime,"")];
			$listuid=$listcont[$i][changesqlquote(tbl_listsdb::col_uid,"")];
			
			if($itemtype=="1")
			{
				$tres=$msgobj->getthreadoutline($itemid);
				$itemname=$tres[0][changesqlquote(tbl_message_outline::col_subject,"")];
				if($itemurl=="")
				{
					$itemurl="/post_display.php?tid=".$itemid;
				}
				$itempic=$msgobj->get_threadpic($itemid);
				$itemttext="Post/Message";
			}
			
			$ores.=' <h3>'.$itemname.'</h3><div><p><img src="'.$itempic.'" height="200" class="talist-floatedimg">
					<ul class="list-group pull-left">
						<li class="list-group-item"><b>Type:</b> '.$itemttext.'</li>
				    	<li class="list-group-item"><b>Time Added:</b> '.$listtime.'</li>
				    	<li class="list-group-item"><b>Link:</b> <a href="'.$itemurl.'">'.$itemurl.'</a></li>
				    </ul>
					';
			
			
			
			$ores.=' <h3>Avengers Age Of Ultron
								  </h3>
								  <div>
								    <p>
								    <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcTp0qlAoWcOOswIkL_qpjYzJqCCDmWXiBzCXiqbE43Obo8c0Z-s" height="200" class="talist-floatedimg">
								    <ul class="list-group pull-left">
								    	<li class="list-group-item"><b>Released:</b> 2015</li>
								    	<li class="list-group-item"><b>Cast:</b> Robert Downey Jr., Thor, Black Widow, Captain America</li>
								    	<li class="list-group-item"><b>Language:</b> English</li>
								    </ul>
								    <div style="clear: both;"></div>
								    <br>
								    <button class="btn btn-default"><i class="fa fa-eye"></i> View this Item</button>
								    <button class="btn btn-default"><i class="fa fa-trash"></i> Remove this item</button>
								    <button class="btn btn-default"><i class="fa fa-arrows"></i> Move this item</button>
								    </p>
								  </div>
								  <h3>Inside Out</h3>
								  <div>
								    <p>abc</p>
								  </div>
								  <h3>Terminator</h3>
								  <div>
								    <p>xyz</p>
								  </div>
								  <h3>Jurrasic World</h3>
								  <div>
								    <p>xyz</p>
								  </div>
								</div>
			
				
			';
		}
		
		return json_encode(Array('res'=>$ores,'st'=>$start));
	}

}//END OF CLASS
?>