<?php
require_once 'adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
echo '<link rel="shortcut icon" href="'.HOST_SERVER.'/favicon.ico" />';
$GLOBALS["product"]=PROD_FBUS;
$assetobj=new ta_assetloader();
$themeobj=new ta_template();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$assetobj->load_css_final();
$themeobj->template_load_top();
echo '<div style="clear:both;"></div>';
require_once 'pop_box.php';
?>