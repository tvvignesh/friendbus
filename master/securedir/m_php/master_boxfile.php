<?php
/**
 *
* CONTAINS FUNCTIONS RELATED TO BOX
* @author T.V.VIGNESH
*
*/
class ta_box
{
	public function box_report($itemid,$itemurl="",$type="1")
	{
	
		$lbl="User";
		if($type=="9")$lbl="Post";
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Report '.$lbl,'modalbody' =>'
				<form id="ta-repform">
					<b>Reason for Reporting:</b>
					<select class="form-control" name="rep_rtype">
						<option value="1">SPAM</option>
						<option value="2">Explicit Content</option>
						<option value="3">Illegal Activities</option>
						<option value="4">Copyright Violations</option>
						<option value="5">Mocking Individuals</option>
						<option value="6">Spreading Virus</option>
						<option value="7">Duplicate Content</option>
						<option value="8">Fake Account/Content</option>
						<option value="9">Inappropriate Content</option>
						<option value="10">Others</option>
					</select>
					<br><b>Please Elaborate the Reason:</b>
					<br><textarea name="rep_reason" class="form-control"></textarea>
					<br><b>Reference URL (if any):</b>
					<input type="url" class="form-control" name="rep_url" placeholder="Enter URL" value="'.$itemurl.'">
				</form>
				','modalfooter' =>'<span class="report-status pull-left"></span><div style="clear:both;"></div> <button class="btn btn-primary ajax-btn" data-mkey="s_repsubmit" data-sform="#ta-repform" data-eltarget=".report-status" data-dtype="json" data-ddemand="json" data-itemtype="1" data-itemid="'.$itemid.'">Report</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		');
	
		return json_encode($data);
	}
	
	public function box_gal_new($galtype="")
	{
	
		$boxlbl='Gallery';
		switch ($galtype)
		{
			case "0":
			case "1":
			case "2":
			case "3":
			case "4":
				$boxlbl='Gallery';
				break;
			case "5":
				$boxlbl='Album';
				break;
			default:
				$boxlbl='Gallery';
				break;
		}
	
		$data = array( 'returnval' =>1, 'modaltitle' =>'New '.$boxlbl,'modalbody' =>'
				<form id="ta-gbxnewgal">
					<b>Name the '.$boxlbl.':</b>
					<input type="text" class="form-control" name="galtitle">
					<br><b>Description</b>
					<textarea class="form-control" name="galdesc"></textarea>
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn" data-mkey="gbx_newgal" data-sform="#ta-gbxnewgal" data-eltarget="-1" data-dtype="json" data-ddemand="json" data-galtype="'.$galtype.'">Create Gallery</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		');
	
		return json_encode($data);
	}
	
	public function box_gal_newpic($galid)
	{
	
		$data = array( 'returnval' =>1, 'modaltitle' =>'Upload Picture(s)','modalbody' =>'
				<form id="ta-gbxnewpic">
					<b>Upload New files</b>
					<input type="file" class="form-control" name="galbx_newpic_file[]" multiple="multiple">
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn" data-mkey="gbx_galpic_upload" data-galid="'.$galid.'" data-sform="#ta-gbxnewpic" data-eltarget="-1" data-dtype="json" data-ddemand="json">Start Upload</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		');
	
		return json_encode($data);
	}
	
	public function box_fileupload($galid,$uptype="-1",$uid)
	{
		$dbobj=new ta_dboperations();
		if(count($dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' AND ".tbl_galinfo::col_uid."='$uid'", tbl_galinfo::dbname))==0)
		{
			$data = array( 'returnval' =>-1,'message'=>'Failure', 'modaltitle' =>'Failure','modalbody' =>"You cannot upload since this is not your gallery",'modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
			return json_encode($data);
		}
		if(isset($_POST["rld"]))
		{
			if($_POST["rld"]=="1")
			$rl="window.location.reload();";
			else
			$rl="";
					
		}
		else
		{
			$rl="window.location.reload();";
		}
		
		$exreq='';
		if(isset($_POST["afterupload"]))
		{
			$exreq.='&afterupload='.$_POST["afterupload"];
		}
		
		$script='<div id="f_upload_cont">Loading..</div><script type="text/javascript">$("#f_upload_cont").load("/master/securedir/m_template/box_fileuploader.php?galid='.$galid.'&uptype='.$uptype.$exreq.'");</script>';
	
		$data = array( 'returnval' =>1, 'modaltitle' =>'Upload File(s)','modalbody' =>$script,'modalfooter' =>'<button class="btn btn-default mod_upld_close" data-dismiss="modal" onclick="'.$rl.'">Close</button>');
		return json_encode($data);
	}
	
	public function box_thread_new()
	{
		if(isset($_POST["tuid"])&&$_POST["tuid"]!="")
		{
			$tuid=$_POST["tuid"];
		}
		else
		{
			$tuid="";
		}
		$data = array( 'returnval' =>1, 'modaltitle' =>'New Message','modalbody' =>'
				<form id="ta-tbxnewthread">
					<b>Participant(s) of this conversation:</b>
					<select class="form-control theread_p_ip" name="thread_p[]" multiple="multiple" style="width: 100%"></select>
					<br><b>Label/Subject for the conversation</b>
					<input type="text" class="form-control" name="thread_lbl">
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn" data-mkey="tbx_newthread" data-sform="#ta-tbxnewthread" data-eltarget="-1" data-dtype="json" data-ddemand="json">Create Thread</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				
				<script type="text/javascript">
	
				var utilityobj=new JS_UTILITY();

				function formatValues(mydata) {
					if(typeof mydata.photo!=="undefined" && mydata.photo!="")
				    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
					else
					return mydata.text;
				}
				
				utilityobj.select2($(".theread_p_ip"),{			
					  ajax: {
					    url: "/item_getter.php",
					    dataType: "json",
						method:"POST",
					    data: function(params) {
					      return {
							key:"friends",query:params.term,page:params.page
					      };
					    },
					    processResults: function (data, params) {
							console.log(data);
					      params.page = params.page || 1;
					 
					      return {
					        results: data.results,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
    					cache: true
 					 }, 
					  minimumInputLength: 1,
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatValues,
					formatSelection: formatValues,
					maximumSelectionSize: 10
					  });
				
				$(".theread_p_ip").change(function(){
				  	window.globalsender = $(this).val();
				 });
				</script>
		');
	
		return json_encode($data);
	}
	
	public function box_th_participants_add()
	{
		if(!isset($_POST["threadid"]))
		{
			$data = array( 'returnval' =>-1, 'modaltitle' =>'Add Participants','modalbody' =>'Please select the thread first to add the participants');
			return json_encode($data);
		}
		$tid=$_POST["threadid"];
		
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Add Participants','modalbody' =>'
				<form id="ta-th_addpart">
					<b>Participant(s) of this conversation:</b>
					<select class="form-control theread_p_ip" name="thread_p[]" multiple="multiple" style="width: 100%"></select>
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn" data-mkey="tbx_addparticipants" data-threadid="'.$tid.'" data-sform="#ta-th_addpart" data-eltarget="-1" data-dtype="json" data-ddemand="json">Add Participants</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		
				<script type="text/javascript">
		
				var utilityobj=new JS_UTILITY();
		
				function formatValues(mydata) {
					if(typeof mydata.photo!=="undefined" && mydata.photo!="")
				    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
					else
					return mydata.text;
				}
		
				utilityobj.select2($(".theread_p_ip"),{
					  ajax: {
					    url: "/item_getter.php",
					    dataType: "json",
						method:"POST",
					    data: function(params) {
					      return {
							key:"friends",query:params.term,page:params.page
					      };
					    },
					    processResults: function (data, params) {
							console.log(data);
					      params.page = params.page || 1;
		
					      return {
					        results: data.results,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
    					cache: true
 					 },
					  minimumInputLength: 1,
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatValues,
					formatSelection: formatValues,
					maximumSelectionSize: 10
					  });
		
				$(".theread_p_ip").change(function(){
				  	window.globalsender = $(this).val();
				 });
				</script>
		');
		
		return json_encode($data);
	}
	
	public function box_gp_inviteppl($uid)
	{
		if(!isset($_POST["gpid"]))
		{
			$data = array( 'returnval' =>-1, 'modaltitle' =>'Add Participants','modalbody' =>'Please select the group first to add members');
			return json_encode($data);
		}
		$gpid=$_POST["gpid"];
		
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Invite People','modalbody' =>'
				<form id="ta-gp_addmem">
					<b>User(s) to be added:</b>
					<select class="form-control gp_in_ip" name="gp_in_ip[]" multiple="multiple" style="width: 100%"></select>
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn" data-mkey="ta_gp_invitemem" data-gpid="'.$gpid.'" data-sform="#ta-gp_addmem" data-eltarget="-1" data-dtype="json" data-ddemand="json">Invite</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		
				<script type="text/javascript">
		
				var utilityobj=new JS_UTILITY();
		
				function formatValues(mydata) {
					if(typeof mydata.photo!=="undefined" && mydata.photo!="")
				    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
					else
					return mydata.text;
				}
		
				utilityobj.select2($(".gp_in_ip"),{
					  ajax: {
					    url: "/item_getter.php",
					    dataType: "json",
						method:"POST",
					    data: function(params) {
					      return {
							key:"friends_notingp",query:params.term,page:params.page,gpid:"'.$gpid.'"
					      };
					    },
					    processResults: function (data, params) {
							console.log(data);
					      params.page = params.page || 1;
		
					      return {
					        results: data.results,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
    					cache: true
 					 },
					  minimumInputLength: 1,
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatValues,
					formatSelection: formatValues,
					maximumSelectionSize: 10
					  });
		
				$(".gp_in_ip").change(function(){
				  	window.globalsender = $(this).val();
				 });
				</script>
		');
		
		return json_encode($data);
	}
	
	public function box_audience()
	{
		$userobj=new ta_userinfo();
		$socialobj=new ta_socialoperations();
		
		$weducatres=$userobj->weducat_get_all();
		$flistres=$socialobj->get_list_info_all_user($userobj->uid,"1");
		
		if(isset($_POST["reselem"]))
		{
			$reselem=$_POST["reselem"];
		}
		else
		{
			$reselem="-1";
		}
		
		if(isset($_POST["autoset"]))
		{
			$aset="1";
		}
		else
		{
			$aset="-1";
		}
		
		if(isset($_POST["pelem"]))
		{
			$pelem=$_POST["pelem"];
		}
		else
		{
			$pelem="-1";
		}
		
		if(isset($_POST["elemid"]))
		{
			$elemid=$_POST["elemid"];
		}
		else
		{
			$elemid="-1";
		}
		
		if(isset($_POST["elname"]))
		{
			$elname=$_POST["elname"];
		}
		else
		{
			$elname="-1";
		}
		
		$ageopt='';
		$weducatopt='';
		$listsopt='';
		
		for($i=1;$i<150;$i++)
		{
			$ageopt.='<option value="'.$i.'">'.$i.'</option>';
		}
		
		for($j=0;$j<count($weducatres);$j++)
		{
			$weducatid=$weducatres[$j][changesqlquote(tbl_workedu::col_typeid,"")];
			$weducatlabel=$weducatres[$j][changesqlquote(tbl_workedu::col_name,"")];
			
			$weducatopt.='<option value="'.$weducatid.'">'.$weducatlabel.'</option>';
		}
		
		for($k=0;$k<count($flistres);$k++)
		{
			$flistid=$flistres[$k][changesqlquote(tbl_listinfo::col_listid,"")];
			$flistname=$flistres[$k][changesqlquote(tbl_listinfo::col_listname,"")];
			
			$listsopt.='<option value="'.$flistid.'">'.$flistname.'</option>';
		}
		
		//TODO ADD LOCATION TO AUDIENCE
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Choose Your Audience','modalbody' =>'
				<b>Note:</b> It is not necessary to edit each and every field in the below form. It has been given just incase deep filtering of Audience is required (You can leave it to Ignore or Empty field if you are not sure about a filter) 
				<br><br>
				<form id="ta-abx_addaud">
					<b>Label Audience</b>
					<input type="text" class="form-control abx_label" name="abx_label">
				
					<b>Friend(s)</b> (Will ignore all other criteria)
					<select class="form-control theread_p_ip1" name="thread_p[]" multiple="multiple" style="width: 100%"></select>
					
					<b>Minumum Age</b>
					<select class="form-control abx_minage" name="minage" style="width: 100%">
					<option value="-1">Ignore</option>
					'.$ageopt.'
					</select>
					
					<b>Maximum Age</b>
					<select class="form-control abx_maxage" name="maxage" style="width: 100%">
					<option value="-1">Ignore</option>
					'.$ageopt.'
					</select>
					
					<b>Gender</b>
					<select class="form-control abx_gender" name="gender" style="width: 100%">
						<option value="4">All</option>
						<option value="1">Male</option>
						<option value="2">Female</option>
						<option value="3">Others</option>
					</select>
					
					<b>Country</b>
					<select class="form-control country_input" name="country_input" style="width:100%;">
				       <option value="-1" selected="selected">Ignore</option>
				    </select>
				
					<b>State</b>
					<select class="form-control state_input" name="state_input" style="width:100%;">
						<option value="-1" selected="selected">Ignore</option>
					</select>
				
					<b>Category of Work</b>
					<select class="form-control abx_workcat" name="workeducat" style="width: 100%">
						<option value="-1">Ignore</option>
						'.$weducatopt.'
					</select>
				
					<b>Minimum Reputation Points</b>
					<input type="text" class="form-control abx_repmin" name="abx_repmin" placeholder="For eg. 1000">
				
					<b>Maximum Reputation Points</b>
					<input type="text" class="form-control abx_repmax" name="abx_repmax" placeholder="For eg. 5000">
				
					<b>Login Status</b>
					<select class="form-control abx_loginreq" name="loginreq" style="width: 100%">
						<option value="1">Require Login</option>
						<option value="2">Need not be logged in (not recommended)</option>
					</select>
				
					<b>Subscribers/Followers</b>
					<select class="form-control abx_subinc" name="subinc" style="width: 100%">
						<option value="1">Include Subscribers</option>
						<option value="2">Do not include Subscribers</option>
					</select>
				
					<b>Lists</b>
					<select class="form-control abx_lists" name="lists" style="width: 100%">
						<option value="-1">Ignore</option>
						'.$listsopt.'
					</select>
				
				</form>
				','modalfooter' =>'<span class="abx-audresult pull-left"></span><button class="btn btn-primary ajax-btn" data-mkey="abx_addaudience" data-sform="#ta-abx_addaud" data-reselem="'.$reselem.'" data-aset="'.$aset.'" data-elemid="'.$elemid.'" data-pelem="'.$pelem.'" data-elname="'.$elname.'" data-eltarget=".abx-audresult" data-dtype="json" data-ddemand="json">Add Audience</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				
				<script type="text/javascript">
		
				var utilityobj=new JS_UTILITY();
		
				function formatValues(mydata) {
					if(typeof mydata.photo!=="undefined" && mydata.photo!="")
				    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
					else
					return mydata.text;
				}
		
				country_init($(".country_input"));
				state_init($(".country_input"),$(".state_input"));
				
				utilityobj.select2($(".theread_p_ip1"),{
					  ajax: {
					    url: "/item_getter.php",
					    dataType: "json",
						method:"POST",
					    data: function(params) {
					      return {
							key:"friends",query:params.term,page:params.page
					      };
					    },
					    processResults: function (data, params) {
							console.log(data);
					      params.page = params.page || 1;
		
					      return {
					        results: data.results,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
    					cache: true
 					 },
					  minimumInputLength: 1,
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatValues,
					formatSelection: formatValues,
					maximumSelectionSize: 10
					  });
				
				$(".theread_p_ip1").change(function(){
				  	window.globalsender = $(this).val();
				 });
				
				</script>
		');
		
		return json_encode($data);
	}
	
	public function tbx_share()
	{
		
		$tid=$_POST["threadid"];
		$msgid=$_POST["msgid"];
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Share Post','modalbody' =>'
				<form id="ta-postshare">
					<b>Note:</b> <small>You can leave the fields empty if you don\'t want to add anything to the post</small>
					<br>
					<b>Label/Subject for the Share</b>
					<input type="text" class="form-control" name="thread_lbl">
					<b>Add something to the Post:</b>
					<textarea class="form-control" name="thread_extra" rows="5" cols="50"></textarea>
				</form>
				','modalfooter' =>'<button class="btn btn-primary ajax-btn share-postbtn" data-mkey="tbx_sharepost" data-sform="#ta-postshare" data-eltarget="-1" data-dtype="json" data-ddemand="json" data-threadid="'.$tid.'" data-msgid="'.$msgid.'">Share this Post</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		');
		
		return json_encode($data);
	}
	
	public function box_smileys()
	{
		$intarget=$_POST["intarget"];
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Add Smileys & Stickers','modalbody' =>'
				<ul class="list-group smiley-ul" data-intarget="'.$intarget.'">
					<li class="list-group-item">:-) &nbsp;&nbsp;&nbsp;&nbsp;<span class="no-emoticons ta-lmargin">:-)</span> <button class="btn btn-xs btn-default smiley-addbtn pull-right" data-smiley=":-)">Add</button></li>
					<li class="list-group-item">:) &nbsp;&nbsp;&nbsp;&nbsp;<span class="no-emoticons ta-lmargin">:)</span> <button class="btn btn-xs btn-default smiley-addbtn pull-right" data-smiley=":)">Add</button></li>
				</ul>
				<script type="text/javascript">
					var loadobj=new JS_LOADER();
					loadobj.jsload_emoticons(function(){
						$(".smiley-ul li").emoticonize();
					});
				</script>
				','modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		
		return json_encode($data);
	}
	
	public function box_ptag()
	{		
		$reselem=$_POST["reselem"];
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Add a Label','modalbody' =>'
				
				<form id="ta-ptag">
					<b>Note:</b> <small>This refers to tagging a Post/Message, Tagging friends can be done by using "@" symbol in your post</small>
					<br>
					<b>Label for the Tag</b>
					<select class="form-control p_tag" name="p_tag[]" multiple="multiple" style="width: 100%"></select>
				</form>
	
				<script type="text/javascript">
		
				var utilityobj=new JS_UTILITY();
		
				function formatValues(mydata) {
					if(typeof mydata.photo!=="undefined" && mydata.photo!="")
				    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
					else
					return mydata.text;
				}
		
				utilityobj.select2($(".p_tag"),{
					  ajax: {
					    url: "/item_getter.php",
					    dataType: "json",
						method:"POST",
					    data: function(params) {
					      return {
							key:"post_tags",query:params.term,page:params.page
					      };
					    },
					    processResults: function (data, params) {
							console.log(data);
					      params.page = params.page || 1;
		
					      return {
					        results: data.results,
					        pagination: {
					          more: (params.page * 30) < data.total_count
					        }
					      };
					    },
    					cache: true
 					 },
					minimumInputLength: 1,
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatValues,
					formatSelection: formatValues,
					maximumSelectionSize: 10
					  });
		
				$(".p_tag").change(function(){
				  	window.globalsender = $(this).val();
				 });
				</script>
				','modalfooter' =>'
				<span class="tbx-ptagres"></span>
				<button class="btn btn-primary ajax-btn" data-mkey="tbx_ptag" data-sform="#ta-ptag" data-reselem="'.$reselem.'" data-eltarget=".tbx-ptagres" data-dtype="json" data-ddemand="json">Add Tag(s)</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>');
		
		return json_encode($data);
	}
	
	public function tbx_getlink()
	{
		$tid=$_POST["threadid"];
		$data = array( 'returnval' =>1, 'modaltitle' =>'Link to this Post','modalbody' =>'<form>
				<input type="text" class="form-control tbx-linkval" value="https://www.friendbus.com/post_display.php?tid='.$tid.'">
		</form>','modalfooter' =>'<button class="btn btn-primary cpy-tbxlink">Copy Link</button><button class="btn btn-default" data-dismiss="modal">Close</button>
				<script type="text/javascript">
				listenevent($(".cpy-tbxlink"),"click",function(){
					var linkval=$(".tbx-linkval:first").val();
					var copytbx = document.querySelector(".tbx-linkval");
  					copytbx.select();
					var successful = document.execCommand("copy");
					if(successful)
					{
						alert("The link has been copied to the clipboard. You may now paste it anywhere you like");
					}
					else
					{
						alert("Looks like your browser do not support this feature! You may copy it manually by pressing Ctrl+C after selecting the link");
					}
				});				
				</script>
				
		');
		return json_encode($data);
	}
	
	public function box_viewgpmem($uid)
	{
		$gpid=$_POST["gpid"];
		
		$uiobj=new ta_uifriend();
		$memdisp=$uiobj->disp_group_mem($gpid,"0","15");
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Members in this Group','modalbody' =>'
				<ul class="list-group gpmem_list">'.$memdisp.'</ul>
				<button class="btn btn-default gpmem_ldmore" data-st="15" data-tot="15" data-gpid="'.$gpid.'">Load More</button>
				','modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_viewgpadmins($uid)
	{
		$gpid=$_POST["gpid"];
	
		$socialobj=new ta_socialoperations();
		$uobj=new ta_userinfo();
		$memres=$socialobj->group_get_admins($gpid);
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
						
					$memdisp.='<li class="list-group-item gpmem_uid_'.$memuid.'"><img src="'.$mempic.'" width="32" height="32"> '.$memname.'<span class="pull-right">'.$memrdisp.'<b>Join Time</b>: <small><em>'.$memtime.'</em></small></span>';
					if($socialobj->group_user_check_admin($gpid, $uid))
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
	
						if($memrole!="3"&&$memuid!=$uid)
						{
							$memdisp.='<button class="btn btn-default ajax-btn" data-mkey="gpbx_blockreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-prompt="1" data-suchide=".gpmem_uid_'.$memuid.'">Block</button>';
						}
						$memdisp.='</div>
						<div style="clear:both;"></div>
				';
					}
					$memdisp.='</li>';
		}
	
		$data = array( 'returnval' =>1, 'modaltitle' =>'Admins of this Group','modalbody' =>'
				<ul class="list-group">'.$memdisp.'</ul>
				','modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_viewgpreq($uid)
	{
		$bres='';		
		$gpid=$_POST["gpid"];
		$socialobj=new ta_socialoperations();
		$muobj=new ta_userinfo();
		$memreq=$socialobj->group_get_requests($gpid,"0","3");
		if(count($memreq)==0)
		{
			$bres.='There are no requests received as of now!';
		}
		$bres.= '<ul class="list-group">';
		for($i=0;$i<count($memreq);$i++)
		{
			$memuid=$memreq[$i][changesqlquote(tbl_members_attached::col_uid,"")];
			$mempic=$muobj->getprofpic($memuid);
			$memname=$muobj->user_getfullname($memuid);
			$bres.='<li class="list-group-item gpreq_mem_'.$memuid.'"><img src="'.$mempic.'" width="32" height="32"> <a href="/users.php?uid='.$memuid.'">'.$memname.'</a>
								<div class="btn-group pull-right">
								<button class="btn btn-primary btn-sm ajax-btn" data-mkey="gpbx_apreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-suchide=".gpreq_mem_'.$memuid.'"><i class="fa fa-check"></i></button>
							';
				
			if($socialobj->group_user_check_admin($gpid,$uid))
			{
				$bres.='<button class="btn btn-default btn-sm ajax-btn" data-mkey="gpbx_blockreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-suchide=".gpreq_mem_'.$memuid.'" data-prompt="1"><i class="fa fa-ban"></i></button>';
			}
		
			$bres.='
							</div><div style="clear:both;"></div></li>';
		}
		 $bres.= '</ul>';
		 
		 $data = array( 'returnval' =>1, 'modaltitle' =>'Requests received','modalbody' =>$bres,'modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		 return json_encode($data);
	}
	
	public function box_viewgpblkusers($uid)
	{
		$bres='';
		$gpid=$_POST["gpid"];
		$socialobj=new ta_socialoperations();
		$muobj=new ta_userinfo();
		$blkreq=$socialobj->group_get_blockedusers($gpid);
		if(count($blkreq)==0)
		{
			$bres.='There are no users blocked as of now!';
		}
		$bres.= '<ul class="list-group">';
		for($i=0;$i<count($blkreq);$i++)
		{
			$memuid=$blkreq[$i][changesqlquote(tbl_members_attached::col_uid,"")];
			$mempic=$muobj->getprofpic($memuid);
			$memname=$muobj->user_getfullname($memuid);
			$bres.='<li class="list-group-item gpreq_mem_'.$memuid.'"><img src="'.$mempic.'" width="32" height="32"> <a href="/users.php?uid='.$memuid.'">'.$memname.'</a>
					<div class="btn-group pull-right">
					';
		
			if($socialobj->group_user_check_admin($gpid,$uid))
			{
				$bres.='<button class="btn btn-default btn-sm ajax-btn" data-mkey="gpbx_unblockreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-suchide=".gpreq_mem_'.$memuid.'" data-prompt="1"><i class="fa fa-key"></i></button>';
			}
		
			$bres.='</div><div style="clear:both;"></div></li>';
		}
		$bres.= '</ul>';
			
		$data = array( 'returnval' =>1, 'modaltitle' =>'Blocked Users','modalbody' =>$bres,'modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_question_ask($uid)
	{
		$galobj=new ta_galleryoperations();
		
		$gpid=$_POST["appid"];

		$galid_att=$galobj->get_galid_special($uid,"16");
		
		$qres='<b>Note:</b> Please make sure you search the help center for existing questions before asking a new one<br>
				
				<input type="text" class="form-control status-subject" placeholder="Subject of your post">
    						<br>
    						<div class="widget-area no-padding blank">
								<div class="status-upload">
									<form>
										<div class="statusinput mentions" contenteditable="true" data-placeholder="What would you like to ask?"></div>
										<ul>
											<li><a title="" data-placement="bottom" data-original-title="Attach Documents" class="box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a></li>
											<li><a title="Insert Smileys" data-placement="bottom" data-original-title="Add Smileys" class="box-tog" data-mkey="box_smileys" data-toggle="modal" data-eltarget="-1" data-intarget=".statusinput"><i class="fa fa-smile-o"></i></a></li>
										</ul>
										<div class="pull-right">
											<button type="button" class="btn btn-sm btn-success status-post"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md">Post</span></button>
										</div>
										<div style="clear:both;"></div>
									</form>
								</div><!-- Status Upload  -->
						</div><!-- Widget Area -->
													
													
						<div style="clear:both;"></div>							
				
													
													
				<script type="text/javascript">
window.mediaidarr=[];
	process_tarea($(".statusinput,.status-c-input"));

	var utilityobj=new JS_UTILITY();
	var loadobj=new JS_LOADER();

	 $(".tbar_items").removeClass("active");
	 $(".tbar_i_feeds").addClass("active");

	loadobj.jsload_mentions(function(){
		$(".statusinput").mentionsInput({source: "/item_getter.php?key=tagfriend",showAtCaret: true});
	});
	 
	/*utilityobj.mentions($(".statusinput"), {source: "/item_getter.php?key=tagfriend"});*/

	 
	listenevent_future(".status-post",$("body"),"click", function(){
		var mentions=$(".statusinput").mentionsInput("getMentions");		
		
		var statussubject=$(".status-subject").val();
		if(statussubject=="")
		{
			var statussubject=prompt("The subject field is empty! Are you sure you want to continue posting?")
			if(!statussubject){return;}
		}
		
		var mystatus=$(".statusinput").html();
		var audid=$(".status-audienceset").attr("data-audid");
		var tagcolid=$(".status-ptag").attr("data-tagcol");
		var attvar=JSON.stringify(window.mediaidarr);
		var mentions=JSON.stringify(mentions);
		loadobj.ajax_call({
			url:"/request_process.php",
			  method:"POST",
			  data:{mkey:"tbx_newpost",thepost:mystatus,audid:audid,subject:statussubject,attachments:attvar,tagcolid:tagcolid,mentions:mentions,ptype:"6"},
			  cache:false,
			  success:function(data){
				  $(".alert-text").html("The post has been made successfully");
				  $(".alert-dismissable").css("display","block");
				  $(".statusinput").html("");
				  $(".status-subject").val("");
				  $(".mentions-input .highlighter").remove();
				  $(".statusinput").css("background-color","white");
			  }
		});
	});
	
rebind_all();

</script>
				';
			
		$data = array( 'returnval' =>1, 'modaltitle' =>'Ask a Question','modalbody' =>$qres,'modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_gp_edit_set()
	{
		$gpid=$_POST["gpid"];
		
		$socialobj=new ta_socialoperations();
		
		//selected="selected"
		$gpres=$socialobj->groups_get($gpid);
		$gpvis=$gpres[0][changesqlquote(tbl_groups_info::col_gpprivacy,"")];
		$gpmemtype=$gpres[0][changesqlquote(tbl_groups_info::col_gpmemtype,"")];
		
		$gpsetres='<form id="ta_gp_editset">
					<b>Approval Method:</b> 
					<select class="form-control" name="gp-approval">
						<option value="1">Automatically approve all requests</option>
						<option value="2">Require Admin\'s approval</option>
						<option value="3">Require approval from any existing member</option>
						<option value="4">Disapprove all requests</option>
					</select>
					<br>
				</form>';
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Edit Group Settings','modalbody' =>$gpsetres,'modalfooter' =>'
				<span class="edit-status"></span>
				<button class="btn btn-primary ajax-btn" data-mkey="gp_edit_set" data-sform="#ta_gp_editset" data-eltarget=".edit-status" data-dtype="json" data-ddemand="json" data-gpid="'.$gpid.'">Make Changes</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				
		');
		return json_encode($data);
	}
	
	public function intouch_fb_addpage($uid)
	{
		$inres='<form id="ta_intouch_fbpgadd">
					<b>Facebook Page URL:</b>
					<input type="url" class="form-control" name="in_fb_pgurl">
					<b>Label:</b>
					<input type="text" class="form-control" name="in_fb_pglbl">
				</form>';
		$data = array( 'returnval' =>1, 'modaltitle' =>'Intouch - Add a Facebook Page','modalbody' =>$inres,'modalfooter' =>'
				<span class="in-add-status"></span>
				<button class="btn btn-primary ajax-btn" data-mkey="intouch_fbpgadd" data-sform="#ta_intouch_fbpgadd" data-eltarget=".in-add-status" data-dtype="json" data-ddemand="json">Add Page</button>
				<button class="btn btn-default" data-dismiss="modal">Close</button>
		
		');
		return json_encode($data);
	}
	
	public function box_medviewer()
	{
		$medid=$_POST["mediaid"];
		$galid=$_POST["galid"];
		
		$galobj=new ta_galleryoperations();
		$utilityobj=new ta_utilitymaster();
		$fileobj=new ta_fileoperations();
		
		$medres=$galobj->media_get_info($medid);
		$galres=$galobj->get_gallery_info($galid);
		
		if(count($medres)==0||count($galres)==0)
		{
			$data = array( 'returnval' =>-1, 'message'=>'OOPS! Either the gallery or media selected does not exist in our database!','modaltitle' =>'','modalbody' =>'','modalfooter' =>'');
			return json_encode($data);
		}
		
		$boxres='';
		
		$medtype=$medres[0][changesqlquote(tbl_galdb::col_mediatype,"")];
		$medfname=$medres[0][changesqlquote(tbl_galdb::col_fname,"")];
		$medtitle=$medres[0][changesqlquote(tbl_galdb::col_mediatitle,"")];
		$medurl=$medres[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
		$medthumb=$medres[0][changesqlquote(tbl_galdb::col_mediathumb,"")];
		$medflag=$medres[0][changesqlquote(tbl_galdb::col_mediaflag,"")];
		$meddesc=$medres[0][changesqlquote(tbl_galdb::col_mediadesc,"")];
		$meduid=$medres[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		$medtime=$medres[0][changesqlquote(tbl_galdb::col_mediatime,"")];
		$medext=$medres[0][changesqlquote(tbl_galdb::col_fext,"")];
		
		$medtypename='';
		switch($medtype)
		{
			case "1":
				$boxres.='<img src="'.$utilityobj->pathtourl($medurl).'" style="width:100%;">
						<br><br>
						<b>'.$meddesc.'</b>';$medtypename='Image';
				break;
			case "2":
				$boxres.='<a href="'.$utilityobj->pathtourl($medurl).'"><img src="'.$utilityobj->pathtourl($medthumb).'" style="width:100%;"></a>
						<br><br>
						<b>'.$meddesc.'</b>';$medtypename='Document';
				break;
			case "3":
				$jsonid=$medres[0][changesqlquote(tbl_galdb::col_jsonid,"")];
				$jsonobj=$utilityobj->jsondata_get($jsonid);
				
				$srctext='';
				
				if(isset($jsonobj->formats))
				{
					$co=0;
					if(isset($jsonobj->formats->webm))
					{
						$mediaid=$jsonobj->formats->webm;
						$mediaurl=$galobj->geturl_media("",$mediaid,"3");
						$mediaext=$fileobj->fileinfo($mediaurl,"3");
						$srctext.='<source src="'.$utilityobj->pathtourl($mediaurl).'" type="'.$fileobj->contenttype($mediaext).'" />';
					}
					if(isset($jsonobj->formats->ogv))
					{
						$mediaid=$jsonobj->formats->ogv;
						$mediaurl=$galobj->geturl_media("",$mediaid,"3");
						$mediaext=$fileobj->fileinfo($mediaurl,"3");
						$srctext.='<source src="'.$utilityobj->pathtourl($mediaurl).'" type="'.$fileobj->contenttype($mediaext).'" />';
					}
					foreach($jsonobj->formats as $key => $value)
					{
						if($key=="webm"||$key=="ogv")continue;
						$mediaid=$value;
						$mediaurl=$galobj->geturl_media("",$mediaid,"3");
						$mediaext=$fileobj->fileinfo($mediaurl,"3");
						$srctext.='<source src="'.$utilityobj->pathtourl($mediaurl).'" type="'.$fileobj->contenttype($mediaext).'" />';
					}
				}
				
				$srctext.='<source src="'.$utilityobj->pathtourl($medurl).'" type="'.$fileobj->contenttype($medext).'" />';
				
				$boxres.='<video controls="controls" poster="'.$utilityobj->pathtourl($medthumb).'" class="img img-responsive video-js vjs-default-skin vjs-big-play-centered" preload="auto" id="galbox_videlement" height="360">
						'.$srctext.'
						</video>
						<br><br>
						<b>'.$meddesc.'</b>';$medtypename='Video';
				break;
			case "4":
				$boxres.='<audio controls><source src="'.$utilityobj->pathtourl($medurl).'" type="audio/mpeg">Your browser does not support the audio element.</audio>
						<br><br>
						<b>'.$meddesc.'</b>';$medtypename='Audio';
				break;
			default:
				$boxres.='<a href="'.$utilityobj->pathtourl($medurl).'"><img src="'.$utilityobj->pathtourl($medthumb).'" style="width:100%;"></a>
						<br><br>
						<b>'.$meddesc.'</b>';$medtypename='Misc';
				break;
		}
		
		$boxtitle=$medtitle.' ('.$medfname.') [Type:'.$medtypename.']';
		$boxfooter='Time Added: '.$medtime.' <a href="'.$utilityobj->pathtourl($medurl).'">[Download]</a>
				<button class="btn btn-default ta-lmargin" data-dismiss="modal">Close</button>
				';
		
		$data = array( 'returnval' =>1, 'modaltitle' =>$boxtitle,'modalbody' =>$boxres,'modalfooter' =>$boxfooter);
		return json_encode($data);
	}
	
	public function box_viewupvoters()
	{
		$msgobj=new ta_messageoperations();
		$socialobj=new ta_socialoperations();
		$dbobj=new ta_dboperations();
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		
		$rateid=$_POST["rateid"];
		
		$ratres=$socialobj->rating_get_upvoters($rateid);
		$upvoters='<ul class="list-group ul_upvbx">';
		for($i=0;$i<count($ratres);$i++)
		{
			$ratuid=$ratres[$i][changesqlquote(tbl_ratings::col_rateuid,"")];
			$fullname=$userobj->user_getfullname($ratuid);
			$pic=$utilityobj->pathtourl($userobj->getprofpic($ratuid));
			$upvoters.='<li class="list-group-item"> <img src="'.$pic.'" width="30" height="30" class="ta-rmargin"><a href="/users.php?uid='.$ratuid.'">'.$fullname.'</a></li>';
		}
		if(count($ratres)==0)
		{
			$upvoters.='Looks like there is nothing here to show..';
		}
		$upvoters.='</ul><br><br>';
		
		$upvoters.='<div align="center"><button class="btn btn-default ldmore_upvtbox" data-rateid="'.$rateid.'" data-st="10" data-tot="10">Load More</button></div>';
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'People who upvoted this','modalbody' =>$upvoters,'modalfooter' =>'<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_tagcreate()
	{
		$dbobj=new ta_dboperations();
		$msgobj=new ta_messageoperations();
		
		$res='<form id="ta-tgcrform">
				<b>Label:</b>
				<input class="form-control" name="tag_new">
				<b>Description:</b>
				<textarea class="form-control" name="tag_desc"></textarea>
			</form>
			<span class="tagc-status"></span>';
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Create a new Label','modalbody' =>$res,'modalfooter' =>'
		<button class="btn btn-primary ajax-btn" data-mkey="tagcr_new" data-sform="#ta-tgcrform" data-eltarget=".tagc-status" data-dtype="json" data-ddemand="json">Add</button>
		<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
	public function box_lists_new()
	{
		
		$res='
			<form id="ta-newlistform">
				<b>List Name:</b>
				<input type="text" class="form-control" name="list-name">
				<b>List Description:</b>
				<input type="text" class="form-control" name="list-desc">
			</form>
			';
		
		$data = array( 'returnval' =>1, 'modaltitle' =>'Create a new List','modalbody' =>$res,'modalfooter' =>'
		<span class="list-status pull-left"></span><button class="btn btn-primary ajax-btn" data-mkey="list_new_cr" data-sform="#ta-newlistform" data-eltarget=".list-status" data-dtype="json" data-ddemand="json">Create</button>
		<button class="btn btn-default" data-dismiss="modal">Close</button>');
		return json_encode($data);
	}
	
}
?>