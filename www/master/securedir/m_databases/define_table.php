<?php
class tbl_activity_index extends DB_ACTIVITY
{
	const tblname=parent::tbl_activity_index;

	const col_activityid="activityid";
	const col_activityname="activityname";
	const col_activitydesc="activitydesc";
	const col_appid="appid";
}

class tbl_admin_roles extends DB_ADMIN
{
	const tblname=parent::tbl_admin_roles;

	const col_sno="sno";
	const col_uid="sno";
	const col_roleflag="roleflag";
	const col_roletitle="roletitle";
	const col_permflag="permflag";
	const col_roletime="roletime";
}

class tbl_ad_actions extends DB_ADS
{
	const tblname=parent::tbl_ad_actions;

	const col_adid="adid";
	const col_containerid="containerid";
	const col_actiontype="actiontype";
	const col_uid="uid";
	const col_actionip="actionip";
	const col_actiontime="actiontime";
}

class tbl_ad_views extends DB_ADS
{
	const tblname=parent::tbl_ad_views;

	const col_adid="adid";
	const col_containerid="containerid";
	const col_uid="uid";
	const col_viewip="viewip";
	const col_viewtime="viewtime";
}

class tbl_ad_clicks extends DB_ADS
{
	const tblname=parent::tbl_ad_clicks;

	const col_adid="adid";
	const col_containerid="containerid";
	const col_uid="uid";
	const col_clickip="clickip";
	const col_clicktime="clicktime";
}

class tbl_ad_containers extends DB_ADS
{
	const tblname=parent::tbl_ad_containers;

	const col_prodid="prodid";
	const col_cid="cid";
	const col_cheight="cheight";
	const col_cwidth="cwidth";
	const col_ccost="ccost";
	const col_cnote="cnote";
	const col_cflag="cflag";
	const col_crtime="crtime";
	const col_ctype="ctype";
	const col_cextflag="cextflag";
}

class tbl_ad_closings extends DB_ADS
{
	const tblname=parent::tbl_ad_closings;

	const col_adid="adid";
	const col_closingprice="closingprice";
	const col_paystatus="paystatus";
	const col_paymode="paymode";
	const col_paycurrencyid="paycurrencyid";
	const col_paylocid="paylocid";
	const col_paidamt="paidamt";
}

class tbl_ad_publishers extends DB_ADS
{
	const tblname=parent::tbl_ad_publishers;

	const col_extid="extid";
	const col_uid="uid";
	const col_col_urlid="col_urlid";
	const col_payid="payid";
	const col_flag="flag";
}

class tbl_ad_web extends DB_ADS
{
	const tblname=parent::tbl_ad_web;

	const col_adid="adid";
	const col_col_containerid="col_containerid";
	const col_mediatype="mediatype";
	const col_adcontent="adcontent";
	const col_timeid="col_timeid";
	const col_adflag="adflag";
	const col_adtype="adtype";
	const col_adcost="adcost";
	const col_crtime="crtime";
	const col_adstyle="adstyle";
	const col_earntype="earntype";
	const col_socialact="socialact";
	const col_incometarget="incometarget";
	const col_views="views";
	const col_clicks="clicks";
	const col_actions="actions";
	const col_campaignname="campaignname";
	const col_campbudgetpd="campbudgetpd";
	const col_col_jobtargetid="col_jobtargetid";
}

class tbl_notifications extends DB_ALERTS
{
	const tblname=parent::tbl_notifications;

	const col_notifyid="notifyid";
	const col_uid="uid";
	const col_notifytype="notifytype";
	const col_notifytext="notifytext";
	const col_notifyicon="notifyicon";
	const col_notifystatus="notifystatus";
	const col_notifytime="notifytime";
	const col_notifylink="notifylink";
	const col_jsonid="jsonid";
	const col_elid="elid";
	const col_cnt="cnt";
}

class tbl_subalerts_products extends DB_ALERTS
{
	const tblname=parent::tbl_subalerts_products;

	const col_sno="sno";
	const col_pid="pid";
	const col_uid="uid";
	const col_subtime="subtime";
	const col_itemid="itemid";
}

class tbl_subalerts_users extends DB_ALERTS
{
	const tblname=parent::tbl_subalerts_users;

	const col_sno="sno";
	const col_uid="uid";
	const col_suid="suid";
	const col_stime="stime";
	const col_sflag="sflag";
}

class tbl_app_info extends DB_APPS
{
	const tblname=parent::tbl_app_info;

	const col_appid="appid";
	const col_appkey="appkey";
	const col_appname="appname";
	const col_appdesc="appdesc";
	const col_devid="devid";
	const col_appflag="appflag";
	const col_apptype="apptype";
}

class tbl_app_developers extends DB_APPS
{
	const tblname=parent::tbl_app_developers;

	const col_devid="devid";
	const col_uid="uid";
	const col_devflag="devflag";
	const col_devtype="devtype";
	const col_devtotrating="devtotrating";
	const col_devnoofrating="devnoofrating";
}

class tbl_block_info extends DB_BLACKLISTS
{
	const tblname=parent::tbl_block_info;

	const col_blockid="blockid";
	const col_blockdesc="blockdesc";
	const col_blocktime="blocktime";
	const col_blockuid="blockuid";
	const col_blocktype="blocktype";
	const col_itemid="itemid";
	const col_itemtype="itemtype";
}

class tbl_reports_info extends DB_BLACKLISTS
{
	const tblname=parent::tbl_reports_info;

	const col_itemid="itemid";
	const col_reportid="reportid";
	const col_repuid="repuid";
	const col_reason="reason";
	const col_reptime="reptime";
	const col_refurl="refurl";
	const col_itemtype="itemtype";
	const col_reasontype="reasontype";
}

class tbl_currency_mainunits extends DB_CATEGORY
{
	const tblname=parent::tbl_currency_mainunits;

	const col_currencyid="currencyid";
	const col_curname="curname";
	const col_curval="curval";
	const col_cursymbol="cursymbol";
	const col_locationid="locationid";
}

class tbl_currency_subunits extends DB_CATEGORY
{
	const tblname=parent::tbl_currency_subunits;

	const col_subcurid="subcurid";
	const col_currencyid="currencyid";
	const col_subcurval="subcurval";
	const col_subcurname="subcurname";
}

class tbl_life_routine extends DB_CATEGORY
{
	const tblname=parent::tbl_life_routine;

	const col_actid="actid";
	const col_pactid="pactid";
	const col_actname="actname";
	const col_actpic="actpic";
	const col_actdesc="actdesc";
	const col_acturl="acturl";
	const col_actflag="actflag";
}

class tbl_measure_subunits extends DB_CATEGORY
{
	const tblname=parent::tbl_measure_subunits;

	const col_subunitid="subunitid";
	const col_subunitname="subunitname";
	const col_convvalue="convvalue";
	const col_subunitsymbol="subunitsymbol";
	const col_mainunitid="mainunitid";
}

class tbl_measure_types extends DB_CATEGORY
{
	const tblname=parent::tbl_measure_types;

	const col_measureid="measureid";
	const col_measurename="measurename";
	const col_measuredef="measuredef";
}

class tbl_measure_units extends DB_CATEGORY
{
	const tblname=parent::tbl_measure_units;

	const col_unitid="unitid";
	const col_fullunit="fullunit";
	const col_symbol="symbol";
	const col_dimension="dimension";
	const col_measureid="measureid";
	const col_unitdefinition="unitdefinition";
}

class tbl_program_cats extends DB_CATEGORY
{
	const tblname=parent::tbl_program_cats;

	const col_progcatid="progcatid";
	const col_catname="catname";
	const col_parcatid="parcatid";
}

class tbl_workedu extends DB_CATEGORY
{
	const tblname=parent::tbl_workedu;

	const col_typeid="typeid";
	const col_name="name";
	const col_parid="parid";
	const col_notes="notes";
}

class tbl_languages extends DB_CATEGORY
{
	const tblname=parent::tbl_languages;
	
	const col_langcode="langcode";
	const col_language="language";
	const col_dicturl="dicturl";
}

class tbl_religion extends DB_CATEGORY
{
	const tblname=parent::tbl_religion;

	const col_religid="religid";
	const col_label="label";
	const col_religpic="religpic";
	const col_religmeta="religmeta";
}

class tbl_skills extends DB_CATEGORY
{
	const tblname=parent::tbl_skills;

	const col_skillid="skillid";
	const col_parskillid="parskillid";
	const col_label="label";
	const col_skillmeta="skillmeta";
	const col_skillico="skillico";
}

class tbl_tags_post extends DB_CATEGORY
{
	const tblname=parent::tbl_tags_post;

	const col_tagid="tagid";
	const col_tagname="tagname";
	const col_details="details";
	const col_addtime="addtime";
	const col_usrid="usrid";
	const col_tagpic="tagpic";
}

class tbl_collection_activitylog extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_activitylog;

	const col_col_activityid="col_activityid";
	const col_activityid="activityid";
}

class tbl_collection_adcontainers extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_adcontainers;

	const col_col_contid="col_contid";
	const col_contid="contid";
}

class tbl_collection_age extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_age;

	const col_col_ageid="col_ageid";
	const col_minage="minage";
	const col_maxage="maxage";
}

class tbl_collection_languages extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_languages;

	const col_collangid="collangid";
	const col_langid="langid";
}

class tbl_collection_lists extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_lists;

	const col_col_listid="col_listid";
	const col_listid="listid";
}

class tbl_collection_location extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_location;

	const col_col_locationid="col_locationid";
	const col_locationid="locationid";
	const col_label="label";
}

class tbl_collection_programs extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_programs;

	const col_col_progid="col_progid";
	const col_progid="progid";
}

class tbl_collection_relationship extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_relationship;

	const col_colrelid="colrelid";
	const col_relflag="relflag";
}

class tbl_collection_routine extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_routine;

	const col_col_routineid="col_routineid";
	const col_routineid="routineid";
}

class tbl_collection_time extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_time;

	const col_col_timeid="col_timeid";
	const col_timeid="timeid";
	const col_stime="stime";
	const col_etime="etime";
}

class tbl_collection_users extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_users;

	const col_col_uid="col_uid";
	const col_uid="uid";
}

class tbl_collection_workedu extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_workedu;

	const col_col_typeid="col_typeid";
	const col_typeid="typeid";
}

class tbl_collection_files extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_files;

	const col_col_fileid="col_fileid";
	const col_fileurl="fileurl";
}

class tbl_collection_reppoints extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_reppoints;

	const col_col_reppointid="col_reppointid";
	const col_minrep="minrep";
	const col_maxrep="maxrep";
}

class tbl_collection_contactno extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_contactno;

	const col_col_cnoid="col_cnoid";
	const col_cno="cno";
	const col_ctype="ctype";
	const col_clabel="clabel";
}

class tbl_collection_links extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_links;

	const col_col_linkid="col_linkid";
	const col_linkid="linkid";
}

class tbl_collection_skills extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_skills;

	const col_col_skillid="col_skillid";
	const col_skillid="skillid";
}

class tbl_collection_achievements extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_achievements;

	const col_col_achieveid="col_achieveid";
	const col_achievementid="achievementid";
	const col_label="label";
	const col_notes="notes";
	const col_achievetime="achievetime";
	const col_galid="galid";
}

class tbl_collection_media extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_media;

	const col_col_mediaid="col_mediaid";
	const col_galid="galid";
	const col_mediaid="mediaid";
}

class tbl_collection_tagpost extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_tagpost;

	const col_col_tagid="col_tagid";
	const col_tagid="tagid";	
}

class tbl_collection_groups extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_groups;

	const col_col_gpid="col_gpid";
	const col_gpid="gpid";
}

class tbl_collection_countries extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_countries;
	
	const col_col_cid="col_cid";
	const col_csname="csname";
}

class tbl_collection_states extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_states;
	
	const col_col_sid="col_sid";
	const col_sid="sid";
}

class tbl_message_content extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_content;

	const col_tid="tid";
	const col_msgid="msgid";
	const col_msg="msg";
	const col_fuid="fuid";
	const col_msgtime="msgtime";
	const col_col_mediaid="col_mediaid";
	const col_tagid="tagid";
	const col_msgmode="msgmode";
	const col_deststate="deststate";
	const col_msgflag="msgflag";
	const col_replyto="replyto";
	const col_rateid="rateid";	
}

class tbl_message_incoming extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_incoming;

	const col_tid="tid";
	const col_ruid="ruid";
	const col_rtime="rtime";
}

class tbl_message_filter extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_filter;

	const col_sno="sno";
	const col_filterid="filterid";
	const col_tid="tid";
	const col_uid="uid";
	const col_filterlabel="filterlabel";
	const col_filteremail="filteremail";
	const col_filtercontent="filtercontent";
	const col_filtersubject="filtersubject";
}

class tbl_message_outline extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_outline;

	const col_tid="tid";
	const col_subject="subject";
	const col_fid="fid";
	const col_audienceid="audienceid";
	const col_msgtype="msgtype";
	const col_threadpic="threadpic";
	const col_threadflag="threadflag";
	const col_appid="appid";
	const col_rateid="rateid";
	const col_lastupdate="lastupdate";
}

class tbl_message_readstatus extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_readstatus;

	const col_uid="uid";
	const col_threadid="threadid";
	const col_msgid="msgid";
	const col_readstatus="readstatus";
}

class tbl_message_spam extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_spam;

	const col_tid="tid";
	const col_msgid="msgid";
	const col_spamreason="spamreason";
	const col_repid="repid";
}

class tbl_thread_comments extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_thread_comments;

	const col_mtid="mtid";
	const col_ctid="ctid";
}

class tbl_message_shares extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_shares;

	const col_tid="tid";
	const col_ntid="ntid";
	const col_msgid="msgid";
	const col_nmsgid="nmsgid";
}

class tbl_message_tags extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_tags;

	const col_tid="tid";
	const col_col_ptagid="col_ptagid";
	const col_addtime="addtime";
}

class tbl_message_featured extends DB_CONVERSATIONS
{
	const tblname=parent::tbl_message_featured;
	
	const col_tid="tid";
	const col_addtime="addtime";
	const col_flag="flag";
	const col_flvl="flvl";
}

class tbl_errorcodes extends DB_ERRORS
{
	const tblname=parent::tbl_errorcodes;

	const col_sno="sno";
	const col_errname="errname";
	const col_errdesc="errdesc";
	const col_errcode="errcode";
	const col_errcallback1="errcallback1";
	const col_errcallbacktext1="errcallbacktext1";
	const col_errcallback2="errcallback2";
	const col_errcallbacktext2="errcallbacktext2";
	const col_errtitle="errtitle";
	const col_errpriority="errpriority";
	const col_appid="appid";
	const col_file="file";
}

class tbl_linkdb extends DB_EXTCONNECT
{
	const tblname=parent::tbl_linkdb;

	const col_url="url";
	const col_linkid="linkid";
	const col_linkvisits="linkvisits";
	const col_linkflag="linkflag";
	const col_linktype="linktype";
	const col_linkaddtime="linkaddtime";
	const col_label="label";
	const col_favico="favico";
}

class tbl_referraldb extends DB_EXTCONNECT
{
	const tblname=parent::tbl_referraldb;

	const col_referralid="referralid";
	const col_referraltype="referraltype";
	const col_referralweburl="referralweburl";
	const col_referralvisits="referralvisits";
	const col_referraltime="referraltime";
	const col_referralname="referralname";
	const col_referraldesc="referraldesc";
	const col_referraluid="referraluid";
}

class tbl_shorturldb extends DB_EXTCONNECT
{
	const tblname=parent::tbl_shorturldb;

	const col_url="url";
	const col_linkflag="linkflag";
	const col_linkvisits="linkvisits";
	const col_linkkey="linkkey";
	const col_uid="uid";
	const col_createtime="createtime";
}

class tbl_extensiondb extends DB_GALLERY
{
	const tblname=parent::tbl_extensiondb;

	const col_extid="extid";
	const col_ext="ext";
	const col_extico="extico";
	const col_col_progid="col_progid";
}

class tbl_galdb extends DB_GALLERY
{
	const tblname=parent::tbl_galdb;

	const col_galid="galid";
	const col_mediaurl="mediaurl";
	const col_audienceid="audienceid";
	const col_mediadesc="mediadesc";
	const col_mediaid="mediaid";
	const col_tagid="tagid";
	const col_mediatype="mediatype";
	const col_mediatitle="mediatitle";
	const col_mediaflag="mediaflag";
	const col_mediatime="mediatime";
	const col_mediathumb="mediathumb";
	const col_jsonid="jsonid";
	const col_fext="fext";
	const col_fname="fname";
	const col_serverid="serverid";
	const col_mediauid="mediauid";
}

class tbl_galinfo extends DB_GALLERY
{
	const tblname=parent::tbl_galinfo;

	const col_sno="sno";
	const col_galid="galid";
	const col_galname="galname";
	const col_galdesc="galdesc";
	const col_galpic="galpic";
	const col_audienceid="audienceid";
	const col_galtype="galtype";
	const col_galflag="galflag";
	const col_galtime="galtime";
	const col_uid="uid";
	const col_tagid="tagid";
	const col_parentgalid="parentgalid";
	const col_rateid="rateid";
	const col_jsonid="jsonid";
}

class tbl_notesdb extends DB_GALLERY
{
	const tblname=parent::tbl_notesdb;

	const col_uid="uid";
	const col_notetext="notetext";
	const col_createtime="createtime";
	const col_noteid="noteid";
	const col_notepriority="notepriority";
	const col_notetype="notetype";
	const col_noteshare="noteshare";
}

class tbl_programdb extends DB_GALLERY
{
	const tblname=parent::tbl_programdb;

	const col_progid="progid";
	const col_progname="progname";
	const col_progpath="progpath";
	const col_progdesc="progdesc";
	const col_progcatid="progcatid";
}

class tbl_gallery_attached extends DB_GROUPS
{
	const tblname=parent::tbl_gallery_attached;

	const col_gpid="gpid";
	const col_galid="galid";
}

class tbl_groups_info extends DB_GROUPS
{
	const tblname=parent::tbl_groups_info;

	const col_gpid="gpid";
	const col_gpdesc="gpdesc";
	const col_gpname="gpname";
	const col_gpprivacy="gpprivacy";
	const col_gptime="gptime";
	const col_gppic="gppic";
	const col_gpflag="gpflag";
	const col_gprating="gprating";
	const col_gpemail="gpemail";
	const col_gpmemtype="gpmemtype";
}

class tbl_members_attached extends DB_GROUPS
{
	const tblname=parent::tbl_members_attached;

	const col_gpid="gpid";
	const col_uid="uid";
	const col_jointime="jointime";
	const col_memrole="memrole";
	const col_addby="addby";
	const col_memflag="memflag";
}

class tbl_threads_attached extends DB_GROUPS
{
	const tblname=parent::tbl_threads_attached;

	const col_gpid="gpid";
	const col_tid="tid";
	const col_attachtime="attachtime";
}

class tbl_groups_featured extends DB_GROUPS
{
	const tblname=parent::tbl_groups_featured;
	
	const col_gpid="gpid";
	const col_flvl="flvl";
	const col_ftime="ftime";
}

class tbl_support_customer extends DB_HELP
{
	const tblname=parent::tbl_support_customer;

	const col_sno="sno";
	const col_ticketid="ticketid";
	const col_threadid="threadid";
	const col_uid="uid";
	const col_rating="rating";
	const col_solvedflag="solvedflag";
	const col_comments="comments";
	const co_starttime="starttime";
}

class tbl_ratings extends DB_LIKES
{
	const tblname=parent::tbl_ratings;

	const col_rateid="rateid";
	const col_rateuid="rateuid";
	const col_ratetime="ratetime";
	const col_ratestat="ratestat";
	const col_ratetype="ratetype";
}

class tbl_url_bookmarks extends DB_LIKES
{
	const tblname=parent::tbl_url_bookmarks;

	const col_sno="sno";
	const col_uid="uid";
	const col_favid="favid";
	const col_favurl="favurl";
	const col_favname="favname";
	const col_favdesc="favdesc";
	const col_favtime="favtime";
	const col_galid="galid";
}

class tbl_user_interests extends DB_LIKES
{
	const tblname=parent::tbl_user_interests;

	const col_uid="uid";
	const col_actid="actid";
	const col_acttime="acttime";
	const col_actnotes="actnotes";
}

class tbl_referral_activity extends DB_LOGS
{
	const tblname=parent::tbl_referral_activity;

	const col_instanceid="instanceid";
	const col_description="description";
	const col_referralid="referralid";
	const col_referreduid="referreduid";
}

class tbl_user_activity extends DB_LOGS
{
	const tblname=parent::tbl_user_activity;

	const col_uid="uid";
	const col_activityid="activityid";
	const col_activitytime="activitytime";
	const col_ipaddr="ipaddr";
	const col_platforminfo="platforminfo";
	const col_instanceid="instanceid";
	const col_activitydesc="activitydesc";
}

class tbl_audience_target extends DB_PEOPLE
{
	const tblname=parent::tbl_audience_target;

	const col_audienceid="audienceid";
	const col_col_locationid="col_locationid";
	const col_col_routineid="col_routineid";
	const col_col_ageid="col_ageid";
	const col_gender="gender";
	const col_interestedin="interestedin";
	const col_col_langid="col_langid";
	const col_col_relstatid="col_relstatid";
	const col_col_weid="col_weid";
	const col_col_users="col_users";
	const col_col_groups="col_groups";
	const col_spread_status="spread_status";
	const col_spread_uid="spread_uid";
	const col_col_reppointid="col_reppointid";
	const col_col_religionid="col_religionid";
	const col_col_politicsid="col_politicsid";
	const col_col_celebid="col_celebid";
	const col_col_hierarchyid="col_hierarchyid";
	const col_col_bookid="col_bookid";
	const col_col_movseriesid="col_movseriesid";
	const col_subscribers="subscribers";
	const col_col_musicid="col_musicid";
	const col_col_prodid="col_prodid";
	const col_col_eventid="col_eventid";
	const col_col_healthid="col_healthid";
	const col_col_incomeexpid="col_incomeexpid";
	const col_col_foodid="col_foodid";
	const col_col_scheduleid="col_scheduleid";
	const col_col_speciesid="col_speciesid";
	const col_col_skillid="col_skillid";
	const col_col_cbookid="col_cbookid";
	const col_col_listid="col_listid";
	const col_col_sportid="col_sportid";
	const col_col_buildingid="col_buildingid";
	const col_col_inspirationid="col_inspirationid";
	const col_col_audienceid_or="col_audienceid_or";
	const col_col_audienceid_and="col_audienceid_and";
	const col_col_audienceid_not="col_audienceid_not";
	const col_loginreq="loginreq";
	const col_audlabel="audlabel";
	const col_cuid="cuid";
	const col_col_country="col_country";
	const col_col_state="col_state";
}

class tbl_user_info extends DB_PEOPLE
{
	const tblname=parent::tbl_user_info;

	const col_usrid="usrid";
	const col_unm="unm";
	const col_upass="upass";
	const col_uemail="uemail";
	const col_ugender="ugender";
	const col_ufname="ufname";
	const col_umname="umname";
	const col_ulname="ulname";
	const col_udob="udob";
	const col_umobno="umobno";
	const col_ucountry="ucountry";
	const col_ustate="ustate";
	const col_upincode="upincode";
	const col_uaddress="uaddress";
	const col_uaggreementaccept="uaggreementaccept";
	const col_usubscribe="usubscribe";
	const col_uuserexp="uuserexp";
	const col_usignuptime="usignuptime";
	const col_uipaddr="uipaddr";
	const col_usessionid="usessionid";
	const col_uloginstatus="uloginstatus";
	const col_profpicurl="profpicurl";
	const col_cprofpic1="cprofpic1";
	const col_cprofpic2="cprofpic2";
	const col_uverifyid="uverifyid";
	const col_uflag="uflag";
	const col_docroot="docroot";
	const col_col_ulangid="col_ulangid";
	const col_col_userlocationid="col_userlocationid";
	const col_uphone="uphone";
}

class tbl_user_health extends DB_PEOPLE
{
	const tblname=parent::tbl_user_health;

	const col_uid="uid";
	const col_col_disabilityid="col_disabilityid";
	const col_col_diseaseid="col_diseaseid";
	const col_col_medicineid="col_medicineid";
	const col_healthdate="healthdate";
	const col_col_medicalrecordid="col_medicalrecordid";
}

class tbl_user_settings extends DB_PEOPLE
{
	const tblname=parent::tbl_user_settings;

	const col_uid="uid";
	const col_mainkey="mainkey";
	const col_subkey="subkey";
	const col_settype="settype";
	const col_setval="setval";
}

class tbl_user_extras extends DB_PEOPLE
{
	const tblname=parent::tbl_user_extras;

	const col_uid="uid";
	const col_religid="religid";
	const col_politicalview="politicalview";
	const col_wishlistid="wishlistid";
	const col_watchlistid="watchlistid";
	const col_readlistid="readlistid";
	const col_col_routineid="col_routineid";
	const col_col_contactbookid="col_contactbookid";
	const col_celebstatus="celebstatus";
	const col_col_historyid="col_historyid";
	const col_col_lockers="col_lockers";
	const col_resume="resume";
	const col_coverletter="coverletter";
	const col_biodata="biodata";
	const col_recommendations="recommendations";
	const col_col_social="col_social";
	const col_col_skillid="col_skillid";
	const col_col_achievementid="col_achievementid";
	const col_aliases="aliases";
	const col_relstat="relstat";
	const col_scribbles="scribbles";
	const col_rec_groups="rec_groups";
	const col_cfeedaudid="cfeedaudid";
	const col_bgprofpic="bgprofpic";
}

class tbl_user_work extends DB_PEOPLE
{
	const tblname=parent::tbl_user_work;

	const col_uid="uid";
	const col_wid="wid";
	const col_stime="stime";
	const col_etime="etime";
	const col_role="role";
	const col_instname="instname";
	const col_listid="listid";
	const col_crtime="crtime";
	const col_notes="notes";
	const col_recprivacy="recprivacy";
	const col_typeid="typeid";
	const col_salarymin="salarymin";
	const col_salarymax="salarymax";
	const col_locid="locid";
	const col_galid="galid";
	const col_insturl="insturl";
}

class tbl_user_edu extends DB_PEOPLE
{
	const tblname=parent::tbl_user_edu;

	const col_uid="uid";
	const col_eduid="eduid";
	const col_stime="stime";
	const col_etime="etime";
	const col_instname="instname";
	const col_degree="degree";
	const col_listid="listid";
	const col_locid="locid";
	const col_galid="galid";
	const col_notes="notes";
	const col_crtime="crtime";
	const col_recprivacy="recprivacy";
	const col_insturl="insturl";
}

class tbl_user_devices extends DB_PEOPLE
{
	const tblname=parent::tbl_user_devices;

	const col_uid="uid";
	const col_did="did";
	const col_label="label";
	const col_ip="ip";
	const col_logid="logid";
	const col_adddate="adddate";
	const col_resolution="resolution";
	const col_lastlocid="lastlocid";
	const col_description="description";
	const col_devicetype="devicetype";
}

class tbl_user_import extends DB_PEOPLE
{
	const tblname=parent::tbl_user_import;

	const col_uid="uid";
	const col_prodid="prodid";
	const col_prodflag="prodflag";
	const col_email="email";
	const col_usrname="usrname";
	const col_jsonid="jsonid";
	const col_logid="logid";
	const col_importtime="importtime";
}

class tbl_feedback_user extends DB_PERFORMANCE
{
	const tblname=parent::tbl_feedback_user;

	const col_sno="sno";
	const col_uid="uid";
	const col_url="url";
	const col_feedback="feedback";
	const col_feedbackid="feedbackid";
	const col_ftime="ftime";
	const col_appid="appid";
	const col_emailaddr="emailaddr";
	const col_screenshot="screenshot";
	const col_suggestedsol="suggestedsol";
	const col_readstatus="readstatus";
	const col_moodtype="moodtype";
}

class tbl_location_info extends DB_PLACES
{
	const tblname=parent::tbl_location_info;

	const col_locid="locid";
	const col_locname="locname";
}

class tbl_weather_reports extends DB_PLACES
{
	const tblname=parent::tbl_weather_reports;

	const col_weatherid="weatherid";
	const col_locid="locid";
	const col_weathertime="weathertime";
	const col_precipitation="precipitation";
	const col_humidity="humidity";
	const col_label="label";
	const col_windspeed="windspeed";
	const col_uvindex="uvindex";
	const col_snowfall="snowfall";
	const col_sunset="sunset";
	const col_moonrise="moonrise";
	const col_temperature="temperature";
	const col_iconflag="iconflag";
	const col_recordedtime="recordedtime";
}

class tbl_countries extends DB_PLACES
{
	const tblname=parent::tbl_countries;
	
	const col_id="id";
	const col_sortname="sortname";
	const col_name="name";
}

class tbl_states extends DB_PLACES
{
	const tblname=parent::tbl_states;

	const col_id="id";
	const col_name="name";
	const col_country_id="country_id";
}

class tbl_cities extends DB_PLACES
{
	const tblname=parent::tbl_cities;

	const col_id="id";
	const col_name="name";
	const col_state_id="state_id";
}

class tbl_country extends DB_PLACES
{
	const tblname=parent::tbl_country;

	const col_id="id";
	const col_enabled="enabled";
	const col_code3l="code3l";
	const col_code2l="code2l";
	const col_name="name";
	const col_name_official="name_official";
	const col_flag_32="flag_32";
	const col_flag_128="flag_128";
	const col_latitude="latitude";
	const col_longitude="longitude";
	const col_zoom="zoom";
}

class tbl_jewelprice extends DB_PRICE
{
	const tblname=parent::tbl_jewelprice;

	const col_prodid="prodid";
	const col_priceid="priceid";
	const col_price="price";
	const col_prectime="prectime";
	const col_curtime="curtime";
	const col_weight="weight";
	const col_unitid="unitid";
	const col_purity="purity";
}

class tbl_incoming_requests extends DB_PRIVSECURITY
{
	const tblname=parent::tbl_incoming_requests;

	const col_sno="sno";
	const col_requestkey="requestkey";
	const col_method="method";
	const col_appid="appid";
	const col_email="email";
	const col_uname="uname";
	const col_url="url";
}

class tbl_frienddb extends DB_SOCIAL
{
	const tblname=parent::tbl_frienddb;

	const col_fuid="fuid";
	const col_tuid="tuid";
	const col_reldesc="reldesc";
	const col_flevel="flevel";
	const col_fmsg="fmsg";
	const col_ftime="ftime";
	const col_fflag="fflag";
	const col_nickname="nickname";
}

class tbl_listinfo extends DB_SOCIAL
{
	const tblname=parent::tbl_listinfo;

	const col_sno="sno";
	const col_listid="listid";
	const col_listname="listname";
	const col_listdesc="listdesc";
	const col_listpic="listpic";
	const col_listtime="listtime";
	const col_listflag="listflag";
	const col_listrating="listrating";
	const col_listprivacy="listprivacy";
	const col_listtype="listtype";
	const col_listuid="listuid";
	const col_parlistid="parlistid";
}

class tbl_listsdb extends DB_SOCIAL
{
	const tblname=parent::tbl_listsdb;

	const col_uid="uid";
	const col_itemid="itemid";
	const col_listid="listid";
	const col_listtime="listtime";
	const col_itemurl="itemurl";
	const col_itemtype="itemtype";
	const col_jsonid="jsonid";
}

class tbl_tagdb extends DB_SOCIAL
{
	const tblname=parent::tbl_tagdb;

	const col_tagid="tagid";
	const col_fuid="fuid";
	const col_tuid="tuid";
	const col_tagname="tagname";
	const col_tagtype="tagtype";
}

class tbl_followdb extends DB_SOCIAL
{
	const tblname=parent::tbl_followdb;
	
	const col_uid="uid";
	const col_fuid="fuid";
	const col_crtime="crtime";
}

class tbl_jsondb extends DB_SOCIAL
{
	const tblname=parent::tbl_jsondb;
	
	const col_jsonid="jsonid";
	const col_jsondata="jsondata";
}

class tbl_socketdb extends DB_SOCIAL
{
	const tblname=parent::tbl_socketdb;

	const col_uid="uid";
	const col_jsonid="jsonid";
	const col_crtime="crtime";
}

class tbl_intouchdb extends DB_SOCIAL
{
	const tblname=parent::tbl_intouchdb;
	
	const col_intouchid="intouchid";
	const col_uid="uid";
	const col_extflag="extflag";
	const col_exttype="exttype";
	const col_jsonid="jsonid";
	const col_addtime="addtime";
}

class tbl_classdb extends DB_SPECIES
{
	const tblname=parent::tbl_classdb;

	const col_phylumid="phylumid";
	const col_classid="classid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_domaindb extends DB_SPECIES
{
	const tblname=parent::tbl_domaindb;

	const col_domainid="domainid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_familydb extends DB_SPECIES
{
	const tblname=parent::tbl_familydb;

	const col_orderid="orderid";
	const col_familyid="familyid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_genusdb extends DB_SPECIES
{
	const tblname=parent::tbl_genusdb;

	const col_familyid="familyid";
	const col_genusid="genusid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_kingdomdb extends DB_SPECIES
{
	const tblname=parent::tbl_kingdomdb;

	const col_kingdomid="kingdomid";
	const col_domainid="domainid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_orderdb extends DB_SPECIES
{
	const tblname=parent::tbl_orderdb;

	const col_classid="classid";
	const col_orderid="orderid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_phylumdb extends DB_SPECIES
{
	const tblname=parent::tbl_phylumdb;

	const col_kingdomid="kingdomid";
	const col_phylumid="phylumid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_speciesdb extends DB_SPECIES
{
	const tblname=parent::tbl_speciesdb;

	const col_genusid="genusid";
	const col_speciesid="speciesid";
	const col_name="name";
	const col_desc="desc";
	const col_infourl="infourl";
}

class tbl_user_reppoints extends DB_TRANSACTIONS
{
	const tblname=parent::tbl_user_reppoints;

	const col_sno="sno";
	const col_uid="uid";
	const col_points="points";
	const col_reason="reason";
	const col_appid="appid";
	const col_addtime="addtime";
}

class tbl_icondb extends DB_UI
{
	const tblname=parent::tbl_icondb;

	const col_iconid="iconid";
	const col_iconurl="iconurl";
	const col_iconname="iconname";
	const col_height="height";
	const col_width="width";
	const col_iconkey="iconkey";
	const col_icontype="icontype";
}

class tbl_themedb extends DB_UI
{
	const tblname=parent::tbl_themedb;

	const col_themeid="themeid";
	const col_themename="themename";
	const col_themecss="themecss";
	const col_themedesc="themedesc";
	const col_themeuid="themeuid";
	const col_themeflag="themeflag";
	const col_totalrates="totalrates";
	const col_noofrates="noofrates";
	const col_prodflag="prodflag";
	const col_themecreatetime="themecreatetime";
	const col_galid="galid";
	const col_themeprivacy="themeprivacy";
}

class tbl_collection_email extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_email;

	const col_col_emailid="col_emailid";
	const col_emailaddr="emailaddr";
}

class tbl_collection_audience extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_audience;
	
	const col_col_audienceid="col_audienceid";
	const col_audienceid="audienceid";
}

class tbl_support_help extends DB_HELP
{
	const tblname=parent::tbl_support_help;

	const col_appid="appid";
	const col_threadid="threadid";
	const col_asktime="asktime";
}

class tbl_outline_products extends DB_PRODUCTS
{
	const tblname=parent::tbl_outline_products;

	const col_prodid="prodid";
	const col_total="total";
	const col_sold="sold";
	const col_unsold="unsold";
	const col_returned="returned";
	const col_claims="claims";
	const col_preorders="preorders";
	const col_col_priceid="col_priceid";
	const col_wishlistno="wishlistno";
	const col_pop_locationid="pop_locationid";
	const col_reports="reports";
	const col_flag="flag";
	const col_galid="galid";
	const col_col_dealerid="col_dealerid";
	const col_rateid="rateid";
	const col_threadid="threadid";
	const col_addtime="addtime";
}

class tbl_products_books extends DB_PRODUCTS
{
	const tblname=parent::tbl_products_books;

	const col_prodid="prodid";
	const col_bookid="bookid";
	const col_bookname="bookname";
	const col_col_authorid="col_authorid";
	const col_bookdesc="bookdesc";
	const col_bookcat="bookcat";
	const col_col_ageid="col_ageid";
	const col_col_publication="col_publication";
	const col_edition="edition";
	const col_editionyear="editionyear";
	const col_printedat="printedat";
	const col_totpages="totpages";
	const col_typesetat="typesetat";
}

class tbl_collection_p_sb_mailcats extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_p_sb_mailcats;

	const col_col_mailcatid="col_mailcatid";
	const col_mailcatid="mailcatid";
}

class tbl_collection_p_sb_subscriptions extends DB_COLLECTION
{
	const tblname=parent::tbl_collection_p_sb_subscriptions;

	const col_col_subid="col_subid";
	const col_subid="subid";
}

class tbl_user_profilehits extends DB_LOGS
{
	const tblname=parent::tbl_user_profilehits;

	const col_uid="uid";
	const col_vuid="vuid";
	const col_vtime="vtime";
}

class tbl_transactions_money extends DB_TRANSACTIONS
{
	const tblname=parent::tbl_transactions_money;

	const col_uid="uid";
	const col_payamount_total="payamount_total";
	const col_actualamount="actualamount";
	const col_taxamount="taxamount";
	const col_transactiontime="transactiontime";
	const col_transactionip="transactionip";
	const col_address="address";
	const col_ruid="ruid";
	const col_facctno="facctno";
	const col_transactiondesc="transactiondesc";
	const col_transactionbank="transactionbank";
	const col_transactionid="transactionid";
	const col_transactiontype="transactiontype";
	const col_locid="locid";
	const col_servicetype="servicetype";
	const col_paystatus="paystatus";
	const col_tacctno="tacctno";
}

class tbl_crondb extends DB_SYSTEM
{
	const tblname=parent::tbl_crondb;

	const col_cronid="cronid";
	const col_cmd="cmd";
	const col_jsonid="jsonid";
	const col_lastrun="lastrun";
}

class tbl_serverdb extends DB_SYSTEM
{
	const tblname=parent::tbl_serverdb;

	const col_serverid="serverid";
	const col_par_serverid="par_serverid";
	const col_serverip="serverip";
	const col_addtime="addtime";
	const col_serverstatus="serverstatus";
	const col_jsonid="jsonid";
}
?>