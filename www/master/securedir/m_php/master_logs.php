<?php
/**
 *
* CONTAINS ALL LOGGING RELATED FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_logs
{
	public function record_profilehits($vuid,$uid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbinsert("INSERT INTO ".tbl_user_profilehits::tblname." (".tbl_user_profilehits::col_uid.",".tbl_user_profilehits::col_vuid.")
					VALUES ('$uid','$vuid')",tbl_user_profilehits::dbname);
	}

	public function get_profilehits($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_profilehits::tblname." WHERE ".tbl_user_profilehits::col_uid."='$uid'",tbl_user_profilehits::dbname);
		return $res;
	}

	public function get_no_profilehits($uid)
	{
		$res=$this->get_profilehits($uid);
		return count($res);
	}
	
	public function store_templogs($msg,$fpath=ROOT_SERVER."/myerrors.log")
	{
		error_log($msg."\n",3,$fpath);
	}
}
?>
