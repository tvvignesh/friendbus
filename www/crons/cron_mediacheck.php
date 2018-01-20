#!/usr/local/bin/php
<?php 
$_SERVER['DOCUMENT_ROOT']="/var/www";
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$galobj=new ta_galleryoperations();
$fileobj=new ta_fileoperations();
$logobj=new ta_logs();
$sysobj=new ta_system();

//$logobj->store_templogs("CRON RUN AT ".time());

//$sysobj->cron_mediacheck();
?>