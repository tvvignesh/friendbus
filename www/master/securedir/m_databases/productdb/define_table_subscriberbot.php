<?php
class tbl_p_sb_cats_mail extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_cats_mail;

	const col_catid="catid";
	const col_pcatid="pcatid";
	const col_catname="catname";
	const col_catdesc="catdesc";
	const col_totalrating="totalrating";
	const col_noofrates="noofrates";
	const col_cattype="cattype";
}

class tbl_p_sb_subscriptions_public extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_public;

	const col_subid="subid";
	const col_col_emailid="col_emailid";
	const col_subname="subname";
	const col_subnote="subnote";
	const col_subwebsite="subwebsite";
	const col_col_sb_mailcatid="col_sb_mailcatid";
	const col_addtime="addtime";
	const col_avgfrequency="avgfrequency";
	const col_subflag="subflag";
	const col_subtype="subtype";
	const col_featuredflag="featuredflag";
}

class tbl_p_sb_submail_comments extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_submail_comments;

	const col_submailid="submailid";
	const col_subid="subid";
	const col_threadid="threadid";
}

class tbl_p_sb_submails extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_submails;

	const col_submailid="submailid";
	const col_subid="subid";
	const col_subject="subject";
	const col_body="body";
	const col_headers="headers";
	const col_col_subbot_mailattachid="col_subbot_mailattachid";
	const col_flag="flag";
	const col_addtime="addtime";
	const col_scanstatus="scanstatus";
	const col_time_sent="time_sent";
	const col_mailtype="mailtype";
	const col_mailformat="mailformat";
	const col_emailaddr="emailaddr";
}

class tbl_p_sb_submail_rating extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_rating;

	const col_subid="subid";
	const col_submailid="submailid";
	const col_rateid="rateid";
}

class tbl_p_sb_submail_readlist extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_submail_readlist;

	const col_readlistid="readlistid";
	const col_uid="uid";
	const col_submailid="submailid";
	const col_readstatus="readstatus";
	const col_readtime="readtime";
	const col_subid="subid";
}

class tbl_p_sb_subscriptions_alias extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_alias;

	const col_uid="uid";
	const col_subid="subid";
	const col_alias="alias";
}

class tbl_p_sb_subscriptions_comments extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_comments;

	const col_subid="subid";
	const col_threadid="threadid";
}

class tbl_p_sb_subscriptions_rating extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_rating;

	const col_subid="subid";
	const col_rateid="rateid";
}

class tbl_p_sb_subscriptions_requests extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_subscriptions_requests;

	const col_requestid="requestid";
	const col_uid="uid";
	const col_requestnote="requestnote";
	const col_requesturl="requesturl";
	const col_requesttime="requesttime";
	const col_emailaddr="emailaddr";
}

class tbl_p_sb_user extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_user;

	const col_uid="uid";
	const col_digestfrequency="digestfrequency";
	const col_lastdigestsent="lastdigestsent";
	const col_col_catid="col_catid";
	const col_col_digest_subid="col_digest_subid";
	const col_digestmail="digestmail";
}

class tbl_p_sb_user_favs extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_user_favs;

	const col_uid="uid";
	const col_id="id";
	const col_itemtype="itemtype";
	const col_addtime="addtime";
}

class tbl_p_sb_user_mailcats extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_user_mailcats;

	const col_cid="cid";
	const col_pcid="pcid";
	const col_cname="cname";
	const col_col_subid="col_subid";
	const col_cdesc="cdesc";
	const col_createtime="createtime";
	const col_uid_creator="uid_creator";
}

class tbl_p_sb_views extends DB_P_SUBSCRIBERBOT
{
	const tblname=parent::tbl_p_sb_views;

	const col_id="id";
	const col_ip="ip";
	const col_uid="uid";
	const col_viewtime="viewtime";
	const col_itemtype="itemtype";
}
?>