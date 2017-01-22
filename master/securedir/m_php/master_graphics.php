<?php
/**
 *
 * CONTAINS ICONS, SMILEYS AND OTHER GRAPHICAL CONTENT
 * @author T.V.VIGNESH
 *
 */
class ta_graphics
{
	private $smile,$angry,$confused,$crying,$laughing,$kissing,$bye,$surprised,$sad,$tensed,$pray,$tired,$search,$sick;

	/**
	 * INITIALIZE ALL SMILEY PATHS
	 */
	public function initsmiley()
	{
		$smile="";
		$angry="";
		$confused="";
		$crying="";
		$laughing="";
		$kissing="";
		$bye="";
		$surprised="";
		$sad="";
		$tensed="";
		$pray="";
		$tired="";
		$search="";
		$sick="";
	}

	/**
	 *
	 * GET THE URL OF AN ICON FROM THE DATABASE
	 * @param unknown $key The Unique key of the icon
	 * @return string The path of the icon in the server on success, FAILURE on failure
	 */
	public function geticonurl($key)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_icondb::tblname." WHERE ".tbl_icondb::col_iconkey."='$key'",tbl_icondb::dbname);
		if($res==EMPTY_RESULT)
		{
			return FAILURE;
		}
		else
		{
			return $res[0][changesqlquote(tbl_icondb::col_iconurl,"")];
		}
	}
}