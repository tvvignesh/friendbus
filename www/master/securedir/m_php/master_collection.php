<?php
/**
 *
* CONTAINS ALL COLLECTION RELATED FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_collection
{
	/**
	 *
	 * ADDS ELEMENTS TO COLLECTIONS
	 * @param unknown $tblname The name of the table where the collection has to be created
	 * @param unknown $colarray A collection array having key value pairs columnname=>value
	 * @param unknown $colidcolname Column Name of the collection ID
	 * @param string $colid Defaults to "" which means generate new collection ID
	 * @return string The collection ID
	 */
	public function add_collection($tblname,$colarray,$colidcolname,$colid="")
	{
		if(!is_array($colarray))
		{
			return FAILURE;
		}

		if($colid=="")
		{
			$uiobj=new ta_uifriend();
			$colid=$uiobj->randomstring(35,tadb::db_collection,$tblname,$colidcolname);
		}

		$dbobj=new ta_dboperations();
		
		for($i=0;$i<count($colarray);$i++)
		{
			$flag=0;
			$query="INSERT IGNORE INTO ".$tblname." (";
			foreach($colarray[$i] as $key=>$value)
			{
				
				//$dbobj->dbquery("SELECT * FROM ".$tblname." WHERE ".$colidcolname."='$colid' AND ".$key."='$value'", tadb::db_collection);
				
				if($flag==1)
				{
				$query.=",";
				}
					$query.=$key;
					$flag=1;
			}
			$query.=",".$colidcolname;
				$query.=") VALUES (";
				$flag=0;
				foreach($colarray[$i] as $key=>$value)
				{
				if($flag==1)
				{
				$query.=",";
				}
				$query.="'".$value."'";
				$flag=1;
				}
				$query.=",'$colid')";
				$dbobj->dbinsert($query,tadb::db_collection);
		}
		return $colid;
		}

	/**
	 *
	 * GET COLLECTION INFORMATION
	 * @param unknown $tblname The name of the table to be checked
	 * @param unknown $colidcolname The name of the collection id column to be matched
	 * @param unknown $colvalue The value to be matched
	 * @return Ambigous <string, NULL, unknown> A DB Array having all results on success, FAILURE on failure
	 */
	public function get_collection_complete_info($tblname,$colidcolname,$colvalue)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".$tblname." WHERE ".$colidcolname."='$colvalue'",tadb::db_collection);
	}

	/**
	 *
	 * GET INFORMATION ABOUT AN ITEM IN THE COLLECTION
	 * @param unknown $tblname The name of the table to be checked
	 * @param unknown $colidcolname The name of the collection ID column
	 * @param unknown $colvalue The collection ID
	 * @param unknown $itemcolname The name of the item ID column
	 * @param unknown $itemid The item ID
	 * @return Ambigous <string, unknown> A DB Array having all results on success, FAILURE on failure
	 */
	public function get_collection_item_info($tblname,$colidcolname,$colvalue,$itemcolname,$itemid)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".$tblname." WHERE ".$colidcolname."='$colvalue' AND ".$itemcolname."='$itemid'",tadb::db_collection);
	}

	/**
	 *
	 * CHECK IF AN ITEM ID EXISTS IN THE COLLECTION
	 * @param unknown $tblname The name of the table to be checked
	 * @param unknown $colidcolname The name of the collection ID column
	 * @param unknown $collectionid The ID of the collection to be checked
	 * @param unknown $colname The name of the column
	 * @param unknown $itemid The ID of the item which has to be checked for match
	 * @return string BOOL_SUCCESS on successful find, BOOL_FAILURE if not found
	 */
	public function check_collection_item($tblname,$colidcolname,$collectionid,$colname,$itemid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".$tblname." WHERE ".$colidcolname."='$collectionid' AND ".$colname."='$itemid'",tadb::db_collection);
		if ($res==FAILURE||$res==EMPTY_RESULT)
		{
			return BOOL_FAILURE;
		}
		$flag=0;
		for($i=0;$i<count($res);$i++)
		{
			if($res[$i][changesqlquote($colname,"")]==$itemid)
			{
				$flag=1;break;
			}
		}
		if($flag==1)
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * CHECK IF A COLLECTION EXISTS
	 * @param unknown $tblname The name of the table to be checked
	 * @param unknown $colidcolname The name of the collection ID column
	 * @param unknown $collectionid The ID of the collection to be checked
	 * @return string BOOL_SUCCESS on successful find, BOOL_FAILURE if not found
	 */
	public function check_collection($tblname,$colidcolname,$collectionid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".$tblname." WHERE ".$colidcolname."='$collectionid'",tadb::db_collection);
		if($res==FAILURE||$res==EMPTY_RESULT)
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}

	/**
	 *
	 * REMOVE AN ITEM FROM THE COLLECTION
	 * @param unknown $tblname The name of the table where the item exists
	 * @param unknown $colidcolname The name of the column where the collection ID exists
	 * @param unknown $collectionid The ID of the collection where the item exists
	 * @param unknown $colname The name of the column
	 * @param unknown $itemid The ID of the item which has to be removed
	 * @return string BOOL_SUCCESS on successful find, BOOL_FAILURE if not found
	 */
	public function remove_collection_item($tblname,$colidcolname,$collectionid,$colname,$itemid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbdelete("DELETE FROM ".$tblname." WHERE (".$colname."='$itemid' AND ".$colidcolname."='$collectionid')",tadb::db_collection);
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * REMOVE AN ENTIRE COLLECTION
	 * @param unknown $tblname The name of the table where the collection exists
	 * @param unknown $colidcolname The name of the column having the collection id
	 * @param unknown $collectionid The collection ID which is to be removed
	 * @return string SUCCESS on success, FAILURE on failure
	 */
	public function remove_collection_complete($tblname,$colidcolname,$collectionid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbdelete("DELETE FROM ".$tblname." WHERE (".$colidcolname."='$collectionid')",tadb::db_collection);
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * EDIT A COLUMN OF AN ITEM IN A COLLECTION
	 * @param unknown $tblname The table name where the item exists
	 * @param unknown $colidcolname The column name having collection ID
	 * @param unknown $collectionid The collection ID of the item
	 * @param unknown $itemcolname The column name having the itemid
	 * @param unknown $itemid The item ID
	 * @param unknown $editcolname The name of the column to be edited
	 * @param unknown $value The value which the column has to be updated with
	 * @return Ambigous <string/object, string, void> SUCCESS on successful update, FAILURE on failure
	 */
	public function edit_collection_item($tblname,$colidcolname,$collectionid,$itemcolname,$itemid,$editcolname,$value)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbupdate("UPDATE ".$tblname." SET ".$editcolname."='$value' WHERE ".$colidcolname."='$collectionid' AND ".$itemcolname."='$itemid'",tadb::db_collection);
	}
}