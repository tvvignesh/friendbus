<?php 
$noecho="yes";
require_once 'adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';
$fileobj=new ta_fileoperations();
$fileobject=$_FILES["newpic"];
$filetype=$_POST["ftype"];
$imgurl=$fileobj->picupload($fileobject,$filetype,0);
echo $imgurl;
?>