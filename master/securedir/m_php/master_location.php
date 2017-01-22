<?php
/**
 *
* CONTAINS ALL LOCATION AND PLACE RELATED FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_location
{
	public function location_add($locname,$parentlocid,$lat,$long,$imgurl="",$code="",$currencyid="",$area=-1,$address="",$infourl="",$notes="",$capitalid="",$locflag="1")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		$locid=$uiobj->randomstring(40,tbl_location_info::dbname,tbl_location_info::tblname,tbl_location_info::col_locid);
		//$dbobj->dbinsert($query,DB_MAIN);
	}
}
?>