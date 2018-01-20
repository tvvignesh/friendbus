#!/usr/local/bin/php
<?php 
$_SERVER['DOCUMENT_ROOT']="/var/www/html";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$galobj=new ta_galleryoperations();
$fileobj=new ta_fileoperations();
$logobj=new ta_logs();
$sysobj=new ta_system();

$mediaid=$argv[1];
$format=$argv[2];
$fpath=$argv[3];
$mkey=$argv[4];
$logobj->store_templogs("PROCESS AUDIO.. MEDIA ID:".$mediaid);
$logobj->store_templogs(print_r($argv,true));
$sysobj->process_audio($mediaid,$format,$fpath,$mkey);
?>