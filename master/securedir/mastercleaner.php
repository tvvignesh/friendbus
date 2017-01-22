<?php
require_once 'adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';

$assetobj=new ta_assetloader();
$themeobj=new ta_template();

$assetobj->load_css_theme_default();
$themeobj->template_load_top();
$themeobj->template_load_left();
$themeobj->template_load_right();
$dbobj=new ta_dboperations();
$dbobj->dbdelete("DELETE FROM ".tbl_user_info::tblname,tbl_user_info::dbname);
$dbobj->dbdelete("DELETE FROM ".tbl_user_reppoints::tblname,tbl_user_reppoints::dbname);
echo "<br><br><div align='center'>SUCCESS CLEANING THE DATABASE!</div>";
?>