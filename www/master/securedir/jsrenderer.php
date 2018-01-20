<?php
ob_start("ob_gzhandler");
header("Content-type: text/javascript", true);
header("expires: ".gmdate('D, d M Y H:i:s', time() + 31536000)." GMT");

$url=$_GET["url"];
$serverurl=$_SERVER['DOCUMENT_ROOT'].$url;

// if(preg_match('/\//',$url) or !preg_match('/.css/',$url))
// {
// 	die('OOPS! Only local CSS files are allowed!');
// }
$contents=file_get_contents($serverurl);
echo $contents;
ob_end_flush();
?>