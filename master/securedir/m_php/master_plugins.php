<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO PLUGINS
 * @author T.V.VIGNESH
 *
 */
class ta_plugins
{
	public function load_css_plugins_all()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(MASTER_CSS_HOST."/plugins_all.css");
	}

	public function load_js_plugins_all()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_js(MASTER_JS_HOST."/mainjs/plugins_all.js");
	}

	public function init_item_comment_thread($rateid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_message_outline::tblname." WHERE ".tbl_message_outline::col_rateid."='$rateid' AND ".tbl_message_outline::col_msgtype."='7'",tbl_message_outline::dbname);
		if(count($res)==0)
		{
			$messageobj=new ta_messageoperations();
			$tid=$messageobj->thread_create("000000", "7", $GLOBALS["appid"],"Comment Thread to Item with Rate ID: ".$rateid,"ta_com_thread_".$rateid,"","1",$rateid);
		}
	}

	public function display_comments($rateid,$start=0,$increment=2)
	{
		$messageobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();
		$audienceobj=new ta_audience();
		$userobj=new ta_userinfo();

		$com_tid=$messageobj->get_item_comthreadid($rateid);
		$comments=$messageobj->getcomments($com_tid,$start,$increment);

		$audienceid=$messageobj->get_thread_audienceid($com_tid);
		if(!$userobj->checklogin())
		{
			$flag_follow=0;
		}
		else
		{
			$flag_follow=1;
		}

		echo '<div class="pluginitem_pastcommentsbox" rid="'.$rateid.'" totcomments="2">';

		for($i=0;$i<count($comments);$i++)
		{
			$comid=$dbobj->colval($comments,tbl_message_content::col_msgid,$i);
			$com_cont=$dbobj->colval($comments,tbl_message_content::col_msg,$i);
			$com_poster=$dbobj->colval($comments,tbl_message_content::col_fuid,$i);
			$com_flag=$dbobj->colval($comments,tbl_message_content::col_msgflag,$i);
			$com_posttime=$dbobj->colval($comments,tbl_message_content::col_msgtime,$i);
			$com_replyto=$dbobj->colval($comments,tbl_message_content::col_replyto,$i);
			$com_tagid=$dbobj->colval($comments,tbl_message_content::col_tagid,$i);
			$com_attachid=$dbobj->colval($comments,tbl_message_content::col_attachid,$i);

			$uobj=new ta_userinfo();
			$uobj->user_initialize_data($com_poster);

			echo '
			<div class="clear_float"></div>
			<div class="pluginitem_pastcommentsbox_comcont" comid="'.$comid.'" rid="'.$rateid.'">
				<div class="pluginitem_pastcommentsbox_comcont_poster">
					<img src="'.$uobj->compprofpic2.'" width="50" height="50">
				</div>
				<div class="pluginitem_pastcommentsbox_comcont_content">
				'.$com_cont.'
				</div>
				<div class="clear_float"></div>
			</div>
						 ';
		}

		echo '
				<div class="pluginitem_pastcommentsbox_morecomments" limit_start="'.$start.'" limit_inc="'.$increment.'" rid="'.$rateid.'" title="Click to Load more Comments to this item">View More Comments</div>
				<div class="pluginitem_pastcommentsbox_togglecomments" rid="'.$rateid.'" title="Click to Hide all Comments">Hide all Comments</div>

				<div class="clear_float"></div>
				</div>
			  ';
	}

	public function display_pluginbox($rateid)
	{
		$socialobj=new ta_socialoperations();
		$pluginobj=new ta_plugins();
		$messageobj=new ta_messageoperations();
		$dbobj=new ta_dboperations();

		$pluginobj->init_item_comment_thread($rateid);
		if(!($socialobj->rating_check_rateid_exists($rateid)))
		{
			$socialobj->rating_init(1,$rateid);
		}
		$currating=$socialobj->rating_current($rateid);

		echo '<div class="pluginbox" rid="'.$rateid.'">
				<div class="pluginitem_enclose" rid="'.$rateid.'">
					<div class="pluginitem_currating" rid="'.$rateid.'" title="The Current Rating of this item">'.$currating.'</div>
					<div class="pluginitem_rateup" rid="'.$rateid.'" title="Click to Rate this Post Up">Rate Up</div>
					<div class="pluginitem_ratedown" rid="'.$rateid.'" title="Click to Rate this Post Down">Rate Down</div>
					<div class="pluginitem_comment" rid="'.$rateid.'" title="Click to Comment on this item">Comment</div>
					<div class="pluginitem_share" rid="'.$rateid.'" title="Share this item anywhere with anyone you want">Share</div>
					<div class="pluginitem_follow" rid="'.$rateid.'" title="Follow this item to get regular updates regarding any improvements">Follow</div>
					<div class="pluginitem_date" rid="'.$rateid.'" title="See when this item was posted/created">Date</div>
					<div class="pluginitem_meta" rid="'.$rateid.'" title="A complete information regarding the outline of this item">Meta</div>
					<div class="pluginitem_more" rid="'.$rateid.'" title="Delete, Report, Edit, Favorites, Audience, Privacy Settings, Revisions, Tags, Viewers, Permalink and more...">More</div>
					<div class="clear_float"></div>
				</div>

				<div class="clear_float"></div>';

				echo '<div class="pluginitem_statusbox" rid="'.$rateid.'"></div>
					  <div class="clear_float"></div>
					';

					$pluginobj->display_comments($rateid,0,2);

					echo '<div class="clear_float"></div>

				<div class="pluginitem_commentbox" rid="'.$rateid.'">
					<textarea rows="3" cols="71" class="pluginitem_commentbox_comment" placeholder="Comment on this item" rid="'.$rateid.'"></textarea>
					<br>
					<input type="button" value="Post this Comment" class="btn_style1 plugin_send_item_comment" style="float:right;" rid="'.$rateid.'">
					<div class="clear_float"></div>
				</div>
				<div class="clear_float"></div>
			  </div>

			<div class="pluginitem_sharebox" rid="'.$rateid.'">

					<div class="pluginitem_sharebox_friends" rid="'.$rateid.'" title="Share this with the friends you have at Tech Ahoy">
						<span class="ui-icon ui-icon-person" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this with your friends
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_group" rid="'.$rateid.'" title="Share this item on a group. All the people in the group can view this if the owner has given permission to view it.">
						<span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this on a group
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_email" rid="'.$rateid.'" title="Share this item via Email. The people whom you share this with need not have a Tech Ahoy account to view this item unless explicitly specified by the owner.">
						<span class="ui-icon ui-icon-mail-closed" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this via Email
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_facebook" rid="'.$rateid.'" title="Share this item on Facebook">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this on Facebook
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_twitter" rid="'.$rateid.'" title="Share this item on Twitter">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this on Twitter
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_gplus" rid="'.$rateid.'" title="Share this item on Google+">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this on Google+
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_linkedin" rid="'.$rateid.'" title="Share this item on LinkedIn">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this on LinkedIn
						<div class="clear_float"></div>
					</div>

					<div class="pluginitem_sharebox_mobile" rid="'.$rateid.'" title="Share this item via Mobile Messages. DND & Other rules Apply.">
						<span class="ui-icon ui-icon-comment" style="float: left; margin: 0 7px 20px 0;"></span>
						Share this via SMS
						<div class="clear_float"></div>
					</div>

			</div>


			<div class="pluginitem_metabox" rid="'.$rateid.'">
				<div class="pluginitem_metabox_datecreated">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Date <span class="dialog_bolded">Created</span>: <span class="pluginitem_metabox_datecreated_content">00/00/0000</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_commentno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of <span class="dialog_bolded">Comments</span> to this Item: <span class="pluginitem_metabox_commentno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_audienceno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of <span class="dialog_bolded">Audiences</span> to this Item: <span class="pluginitem_metabox_audienceno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_raterno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who <span class="dialog_bolded">rated</span> this Item: <span class="pluginitem_metabox_raterno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_sharesno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who <span class="dialog_bolded">shared</span> this Item: <span class="pluginitem_metabox_sharesno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_revisionsno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of <span class="dialog_bolded">revisions</span> made to this Item: <span class="pluginitem_metabox_revisionsno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_tagsno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of <span class="dialog_bolded">tags</span> in this Item: <span class="pluginitem_metabox_tagsno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_favsno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who marked this as <span class="dialog_bolded">Favorite</span>: <span class="pluginitem_metabox_favsno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_viewsno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who <span class="dialog_bolded">viewed</span> this item: <span class="pluginitem_metabox_viewsno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_unfollowno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who <span class="dialog_bolded">unfollowed</span> this item: <span class="pluginitem_metabox_unfollowno_content">3</span>
						<div class="clear_float"></div>
				</div>

				<div class="pluginitem_metabox_reportno">
						<span class="ui-icon ui-icon-script" style="float: left; margin: 0 7px 20px 0;"></span>
						Number of people who <span class="dialog_bolded">reported</span> this item: <span class="pluginitem_metabox_reportno_content">3</span>
						<div class="clear_float"></div>
				</div>
			</div>



			<div class="pluginitem_morebox" rid="'.$rateid.'">
				<div class="pluginitem_morebox_delete" rid="'.$rateid.'" title="Remove this item">
						<span class="ui-icon ui-icon-trash" style="float: left; margin: 0 7px 20px 0;"></span>
						Remove this item
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_edit" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-pencil" style="float: left; margin: 0 7px 20px 0;"></span>
						Edit this item
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_report" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-flag" style="float: left; margin: 0 7px 20px 0;"></span>
						Report this item
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_markfav" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-bookmark" style="float: left; margin: 0 7px 20px 0;"></span>
						Mark as Favorite
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_viewers" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-person" style="float: left; margin: 0 7px 20px 0;"></span>
						See People who viewed this
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_audience" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-signal-diag" style="float: left; margin: 0 7px 20px 0;"></span>
						See the Audience for this item
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_privacy" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>
						Privacy Settings
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_tags" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-tag" style="float: left; margin: 0 7px 20px 0;"></span>
						See the people tagged or mentioned
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_revisions" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-disk" style="float: left; margin: 0 7px 20px 0;"></span>
						See the revisions made to this item
					<div class="clear_float"></div>
				</div>

				<div class="pluginitem_morebox_permalink" rid="'.$rateid.'">
						<span class="ui-icon ui-icon-link" style="float: left; margin: 0 7px 20px 0;"></span>
						Permalink to this item
					<div class="clear_float"></div>
				</div>
			</div>
			';
	}

	public function display_gallery($galid)
	{
		$galobj=new ta_galleryoperations();
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();

// 		$result=$utilityobj->send_request_post(HOST_SECURE."/m_process/process_comments_item.php");
// 		echo "RESULT:".$result;


		echo
		'
			<div class="galleryview_enclose" id="'.$galid.'">
		';

		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galinfo::tblname." WHERE ".tbl_galinfo::col_galid."='$galid' LIMIT 0,1",tbl_galinfo::dbname);

		if(count($res)==0)
		{
			echo "OOPS! No such Gallery Exists or the gallery might have been deleted by the user!";
			return FAILURE;
		}

		$parentgalid=$dbobj->colval($res,tbl_galinfo::col_parentgalid,0);
		$uid_owner=$dbobj->colval($res,tbl_galinfo::col_uid,0);
		$rateid=$dbobj->colval($res,tbl_galinfo::col_rateid,0);
		$audienceid=$dbobj->colval($res,tbl_galinfo::col_audienceid,0);
		$galtype=$dbobj->colval($res,tbl_galinfo::col_galtype,0);
		$tagid=$dbobj->colval($res,tbl_galinfo::col_tagid,0);
		$galflag=$dbobj->colval($res,tbl_galinfo::col_galflag,0);
		$galtime=$dbobj->colval($res,tbl_galinfo::col_galtime,0);
		$galpic=$dbobj->colval($res,tbl_galinfo::col_galpic,0);
		$galdesc=$dbobj->colval($res,tbl_galinfo::col_galdesc,0);
		$galname=$dbobj->colval($res,tbl_galinfo::col_galname,0);

		echo '<div class="galleryview_galname">'.$galname.'</div>';

		$collection_gallery=$galobj->get_children_gallery($galid);
		$collection_media=$galobj->get_children_media($galid);

		for($i=0;$i<count($collection_gallery);$i++)
		{
			$c_galname=$dbobj->colval($collection_gallery,tbl_galinfo::col_galname,$i);
			$c_galid=$dbobj->colval($collection_gallery,tbl_galinfo::col_galid,$i);
			$c_galpic=$dbobj->colval($collection_gallery,tbl_galinfo::col_galpic,$i);
			$c_galtime=$dbobj->colval($collection_gallery,tbl_galinfo::col_galtime,$i);

			echo
			'
				<div class="galleryview_child_enclose" id="'.$c_galid.'" itype="1">
				<div class="galleryview_child_gallery" id="'.$c_galid.'">
					<img src="'.$c_galpic.'" height="140">
				</div>

				<div class="galleryview_label">
				'.$c_galname.' abcd dhdhdhd  dhdhdhd  dhdhdhd djdjdj <br>
				</div>
				<div class="galleryview_label_time">
					('.$c_galtime.')
				</div>

				</div>
			';
		}

		for($i=0;$i<count($collection_media);$i++)
		{
			$c_m_mediaid=$dbobj->colval($collection_media,tbl_galdb::col_mediaid,$i);
			$c_m_mediatitle=$dbobj->colval($collection_media,tbl_galdb::col_mediatitle,$i);
			$c_m_mediatype=$dbobj->colval($collection_media,tbl_galdb::col_mediatype,$i);
			$c_m_mediaurl=$dbobj->colval($collection_media,tbl_galdb::col_mediaurl,$i);
			$c_m_audienceid=$dbobj->colval($collection_media,tbl_galdb::col_audienceid,$i);
			$c_m_mediaflag=$dbobj->colval($collection_media,tbl_galdb::col_mediaflag,$i);
			$c_m_time=$dbobj->colval($collection_media,tbl_galdb::col_mediatime,$i);

			echo
			'
				<div class="galleryview_child_enclose" id="'.$c_m_mediaid.'" itype="2">
				<div class="galleryview_child_gallery" id="'.$c_m_mediaid.'">
					'.$c_m_mediatype.'
				</div>
				'.$c_m_mediatitle.' ('.$c_m_time.')
				</div>
			';
		}

		if((count($collection_gallery)==0)&&(count($collection_media)==0))
		{
			echo "Sorry! There are currently no items to show!";
		}
		echo '<div class="clear_float"></div></div>';
	}

	public function display_media($galid,$mediaid)
	{

	}
}
?>