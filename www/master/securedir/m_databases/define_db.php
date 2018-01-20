<?php
class DB_ACTIVITY extends tadb
{
	const dbname=parent::db_activity;

	const tbl_activity_index="`activity_index`";
}

class DB_ADMIN extends tadb
{
	const dbname=parent::db_admin;

	const tbl_admin_roles="`admin_roles`";
}

class DB_ADS extends tadb
{
	const dbname=parent::db_ads;

	const tbl_ad_actions="`ad_actions`";
	const tbl_ad_views="`ad_views`";
	const tbl_ad_clicks="`ad_clicks`";
	const tbl_ad_containers="`ad_containers`";
	const tbl_ad_closings="`ad_closings`";
	const tbl_ad_publishers="`ad_publishers`";
	const tbl_ad_web="`ad_web`";
}

class DB_ALERTS extends tadb
{
	const dbname=parent::db_alerts;

	const tbl_notifications="`notifications`";
	const tbl_subalerts_users="`subalerts_users`";
	const tbl_subalerts_products="`subalerts_products`";
}

class DB_APPS extends tadb
{
	const dbname=parent::db_apps;

	const tbl_app_info="`app_info`";
	const tbl_app_developers="`app_developers`";
}

class DB_BLACKLISTS extends tadb
{
	const dbname=parent::db_blacklists;

	const tbl_block_info="`block_info`";
	const tbl_reports_info="`reports_info`";
}

class DB_CATEGORY extends tadb
{
	const dbname=parent::db_category;

	const tbl_currency_mainunits="`currency_mainunits`";
	const tbl_currency_subunits="`currency_subunits`";
	const tbl_life_routine="`life_routine`";
	const tbl_measure_subunits="`measure_subunits`";
	const tbl_measure_types="`measure_types`";
	const tbl_measure_units="`measure_units`";
	const tbl_program_cats="`program_cats`";
	const tbl_workedu="`workedu`";
	const tbl_languages="`languages`";
	const tbl_religion="`religion`";
	const tbl_skills="`skills`";
	const tbl_tags_post="`tags_post`";
}

class DB_COLLECTION extends tadb
{
	const dbname=parent::db_collection;

	const tbl_collection_activitylog="`activitylog`";
	const tbl_collection_adcontainers="`adcontainers`";
	const tbl_collection_age="`age`";
	const tbl_collection_languages="`languages`";
	const tbl_collection_lists="`lists`";
	const tbl_collection_location="`location`";
	const tbl_collection_programs="`programs`";
	const tbl_collection_relationship="`relationship`";
	const tbl_collection_routine="`routine`";
	const tbl_collection_time="`time`";
	const tbl_collection_users="`users`";
	const tbl_collection_workedu="`workedu`";
	const tbl_collection_email="`email`";
	const tbl_collection_files="`files`";
	const tbl_collection_p_sb_mailcats="`p_sb_mailcats`";
	const tbl_collection_p_sb_subscriptions="`p_sb_subscriptions`";
	const tbl_collection_reppoints="`reppoints`";
	const tbl_collection_contactno="`contactno`";
	const tbl_collection_links="`links`";
	const tbl_collection_skills="`skills`";
	const tbl_collection_achievements="`achievements`";
	const tbl_collection_audience="`audience`";
	const tbl_collection_media="`media`";
	const tbl_collection_tagpost="`tagpost`";
	const tbl_collection_groups="`groups`";
	const tbl_collection_countries="`countries`";
	const tbl_collection_states="`states`";
}

class DB_CONVERSATIONS extends tadb
{
	const dbname=parent::db_conversations;

	const tbl_message_content="`message_content`";
	const tbl_message_filter="`message_filter`";
	const tbl_message_outline="`message_outline`";
	const tbl_message_readstatus="`message_readstatus`";
	const tbl_message_spam="`message_spam`";
	const tbl_message_incoming="`message_incoming`";
	const tbl_thread_comments="`thread_comments`";
	const tbl_message_shares="`message_shares`";
	const tbl_message_tags="`message_tags`";
	const tbl_message_featured="`message_featured`";
}

class DB_ERRORS extends tadb
{
	const dbname=parent::db_errors;

	const tbl_errorcodes="`errorcodes`";
}

class DB_EXTCONNECT extends tadb
{
	const dbname=parent::db_extconnect;

	const tbl_linkdb="`linkdb`";
	const tbl_referraldb="`referraldb`";
	const tbl_shorturldb="`shorturldb`";
}

class DB_GALLERY extends tadb
{
	const dbname=parent::db_gallery;

	const tbl_extensiondb="`extensiondb`";
	const tbl_galdb="`galdb`";
	const tbl_galinfo="`galinfo`";
	const tbl_notesdb="`notesdb`";
	const tbl_programdb="`programdb`";
}

class DB_GROUPS extends tadb
{
	const dbname=parent::db_groups;

	const tbl_gallery_attached="`gallery_attached`";
	const tbl_groups_info="`groups_info`";
	const tbl_members_attached="`members_attached`";
	const tbl_threads_attached="`threads_attached`";
	const tbl_groups_featured="`groups_featured`";
}

class DB_HELP extends tadb
{
	const dbname=parent::db_help;

	const tbl_support_customer="`support_customer`";
	const tbl_support_help="`support_help`";
}

class DB_LIKES extends tadb
{
	const dbname=parent::db_likes;

	const tbl_ratings="`ratings`";
	const tbl_url_bookmarks="`url_bookmarks`";
	const tbl_user_interests="`user_interests`";
}

class DB_LOGS extends tadb
{
	const dbname=parent::db_logs;

	const tbl_referral_activity="`referral_activity`";
	const tbl_user_activity="`user_activity`";
	const tbl_user_profilehits="`user_profilehits`";
}

class DB_PEOPLE extends tadb
{
	const dbname=parent::db_people;

	const tbl_audience_target="`audience_target`";
	const tbl_user_info="`user_info`";
	const tbl_user_work="`user_work`";
	const tbl_user_settings="`user_settings`";
	const tbl_user_health="`user_health`";
	const tbl_user_extras="`user_extras`";
	const tbl_user_edu="`user_edu`";
	const tbl_user_devices="`user_devices`";
	const tbl_user_import="`user_import`";
}

class DB_PERFORMANCE extends tadb
{
	const dbname=parent::db_performance;

	const tbl_feedback_user="`feedback_user`";
}

class DB_PLACES extends tadb
{
	const dbname=parent::db_places;

	const tbl_location_info="`location_info`";
	const tbl_weather_reports="`weather_reports`";
	const tbl_countries="`countries`";
	const tbl_country="`country`";
	const tbl_states="`states`";
	const tbl_cities="`cities`";
}

class DB_PRICE extends tadb
{
	const dbname=parent::db_price;

	const tbl_jewelprice="`jewelprice`";
}

class DB_PRIVSECURITY extends tadb
{
	const dbname=parent::db_privsecurity;

	const tbl_incoming_requests="`incoming_requests`";
}

class DB_SOCIAL extends tadb
{
	const dbname=parent::db_social;

	const tbl_frienddb="`frienddb`";
	const tbl_listinfo="`listinfo`";
	const tbl_listsdb="`listsdb`";
	const tbl_tagdb="`tagdb`";
	const tbl_followdb="`followdb`";
	const tbl_jsondb="`jsondb`";
	const tbl_socketdb="`socketdb`";
	const tbl_intouchdb="`intouchdb`";
}

class DB_SPECIES extends tadb
{
	const dbname=parent::db_species;

	const tbl_classdb="`classdb`";
	const tbl_domaindb="`domaindb`";
	const tbl_familydb="`familydb`";
	const tbl_genusdb="`genusdb`";
	const tbl_kingdomdb="`kingdomdb`";
	const tbl_orderdb="`orderdb`";
	const tbl_phylumdb="`phylumdb`";
	const tbl_speciesdb="`speciesdb`";
}

class DB_TRANSACTIONS extends tadb
{
	const dbname=parent::db_transactions;

	const tbl_user_reppoints="`user_reppoints`";
	const tbl_transactions_money="`transactions_money`";
}

class DB_UI extends tadb
{
	const dbname=parent::db_ui;

	const tbl_icondb="`icondb`";
	const tbl_themedb="`themedb`";
}

class DB_PRODUCTS extends tadb
{
	const dbname=parent::db_products;

	const tbl_outline_products="`outline_products`";
	const tbl_products_books="`products_books`";
}

class DB_SYSTEM extends tadb
{
	const dbname=parent::db_system;

	const tbl_crondb="`crondb`";
	const tbl_serverdb="`serverdb`";
}

/**
 *
 * CHANGES A SQL QUOTE (`) TO ANY DEFINED VALUE (DEFAULTS TO ')
 * @param unknown_type $str STRING TO BE REPLACED
 * @param unknown_type $rep REPLACEMENT TO BE MADE
 * @return mixed REPLACED STRING
 */
function changesqlquote($str,$rep="'")
{
	return str_replace("`", $rep, $str);
}
?>