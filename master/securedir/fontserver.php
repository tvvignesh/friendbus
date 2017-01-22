<?php
header("Content-type: text/css", true);
header("expires: ".gmdate('D, d M Y H:i:s', time() + 31536000)." GMT");
if(isset($_GET["family"])&&isset($_GET["licence"]))
{
	$family=$_GET["family"];
	$licence=$_GET["licence"];
	$family=strtolower($family);
	$licence=strtolower($licence);
	if($family==""||$licence=="")
	{
		die("");
	}
	$GLOBALS["noecho"]="yes";
	$GLOBALS["noheaders"]="yes";
	require_once("adjustment.php");
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	$dir="";
	if($licence=="apache")
	{
		$dir="apache";
	}
	else
	if($licence=="ofl")
	{
		$dir="ofl";
	}
	
	$path=MASTER_CSS_ROOT."/fonts/fonts-master/".$dir."/".$family."/METADATA.json";
	$fpath=MASTER_CSS_HOST."/fonts/fonts-master/".$dir."/".$family."/";
	if(file_exists($path))
	{
		$contents=file_get_contents($path);
		$obj=json_decode($contents);
		//var_dump(json_decode($contents));
		for($i=0;$i<count($obj->{'fonts'});$i++)
		{
			echo
			"
				@font-face
				{
				  font-family: '".$obj->{'fonts'}[$i]->{'name'}."';
				  font-style: '".$obj->{'fonts'}[$i]->{'style'}."';
				  font-weight: '".$obj->{'fonts'}[$i]->{'weight'}."';
				  src:url(".$fpath.$obj->{'fonts'}[$i]->{'filename'}.");
				}
		";
		}
	}
	else
	{
		die("");
	}
}
?>