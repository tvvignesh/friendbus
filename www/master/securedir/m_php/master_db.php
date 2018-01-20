<?php
/**
 *
 * CONTAINS ALL DATABASE OPERATION FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_dboperations
{
	public $mysqli;
	function __construct($dbname=tbl_user_info::dbname) 
	{
		return $this->dbconnect($dbname);
	}
	
	/**
	 *
	 * CONNECTS TO THE DATABASE
	 *
	 * @param string $dbname Database Name
	 * @param string $username User Name
	 * @param string $password Password for the DB
	 * @param string $host Host of the database
	 * @return void Does not return anything
	 */
	public function dbconnect($dbname,$username="vignesh_admin",$password="#vicrosoft_pista@123#",$host="192.168.1.4")
	{
		if (isset($GLOBALS["mysql_connection"])) {
			if($GLOBALS["mysql_connection"]->ping())
			{
				$this->mysqli=$GLOBALS["mysql_connection"];
				return $this->mysqli;
			}
		}
			$this->mysqli = new mysqli($host, $username, $password, $dbname);
			if ($this->mysqli->connect_errno)
			{
				$errobj=new ta_errorhandle();
				$errobj->senderror("OOPS! Could not connect to the database!",$this->mysqli->connect_errno(),$this->mysqli->connect_error);
			}
			$GLOBALS["mysql_connection"]=$this->mysqli;
			return $this->mysqli;
	}

	/**
	 *
	 * FUNCTION TO GET THE MYSQLI OBJECT
	 * @param $dbname unknown The database to connect to
	 * @return The MySQLI object
	 */
	public function getmysqliobj($dbname)
	{
		$dbobj=new ta_dboperations();
		$mysqli=$dbobj->dbconnect($dbname);
		return $mysqli;
	}

	/**
	 *
	 * EXECUTES SQL QUERIES IN A DB (FETCH)
	 *
	 * @param string $query Query to be executed
	 * @param string $dbname Database Name (Default DB NAME)
	 * @return string NULL if no rows returned, 2d array if rows available
	 */
	public function dbquery($query,$dbname,$dbclose="-1")
	{
		$logobj=new ta_logs();
		$logobj->store_templogs($query);
		
			$this->mysqli->select_db($dbname);
			$GLOBALS["dbr_total"]++;$GLOBALS["dbr_query"]++;
		if ($result = $this->mysqli->query($query))
		{
			$GLOBALS["dbretry"]=0;
			$finfo = $result->fetch_fields();
			$c=0;
			foreach ($finfo as $val)
			{
				$colarr[$c]=$val->name;//get all colum names in this array
				$c++;
			}
			$co=0;
			while($obj = $result->fetch_object())
			{
				for($k=0;$k<count($colarr);$k++)
				{
				$tares[$co][$colarr[$k]]=$obj->$colarr[$k];
				}
				$co++;
			}
			if($co==0)
			{
				$GLOBALS["dbretry"]=0;
				if($dbclose!="-1"){$this->dbclose();}
				return EMPTY_RESULT;
			}
		}
		else
		{
			if($GLOBALS["dbretry"]>3)
			{
				$GLOBALS["dbretry"]=0;
				$errmsg=$this->mysqli->error;
				$errno=$this->mysqli->errno;
				if($dbclose!="-1"){$this->dbclose();}
				$errobj=new ta_errorhandle();
				$errobj->senderror("OOPS! Could Not process your query!".$errmsg,$errno,"1");
			}
			else
			{
				$GLOBALS["dbretry"]++;
				$this->dbquery($query,$dbname);
			}
		}
			//QUERY DONE
			if($dbclose!="-1"){$this->dbclose();$result->close();}
			unset($obj);unset($finfo);unset($query);unset($result);unset($colarr);unset($c);unset($co);
			return $tares;
	}

	/**
	 *
	 * EXECUTES SQL QUERIES IN A DB (INSERT)
	 *
	 * @param string $query Query to be executed
	 * @param string $dbname Database Name (Default DB NAME)
	 * @return string/object Object if error occured,"SUCCESS" if successful
	 */
	public function dbinsert($query,$dbname,$dbclose="-1")
	{
		$logobj=new ta_logs();
		$logobj->store_templogs($query);
		
		$this->mysqli->select_db($dbname);
		$GLOBALS["dbr_total"]++;;$GLOBALS["dbr_insert"]++;
		if (!$this->mysqli->query($query))
		{
			$errmsg=$this->mysqli->error;
			$errno=$this->mysqli->errno;
	
			die("<br><br>".$errmsg."<br><br>".$errno);
	
			if($GLOBALS["dbretry"]>3)
			{
				$GLOBALS["dbretry"]=0;
				$logobj=new ta_logs();
				$logobj->store_templogs("PROBLEM EXECUTING QUERY:".$query." ON ".$dbname);
				return $this->mysqli;
			}
			else
			{
				$GLOBALS["dbretry"]++;
				$this->dbinsert($query,$dbname);
			}
		}
		else
		{
			$GLOBALS["dbretry"]=0;
		}
		if($dbclose!="-1"){$this->dbclose();}
		return SUCCESS;
	}

	/**
	 *
	 * EXECUTES SQL QUERIES IN A DB (UPDATE)
	 *
	 * @param string $query Query to be executed
	 * @param string $dbname Database Name (Default DB NAME)
	 * @return string/object Object if error occured,"SUCCESS" if successful
	 */
	public function dbupdate($query,$dbname,$dbclose="-1")
	{
		$logobj=new ta_logs();
		//$logobj->store_templogs($query);
		
		$this->mysqli->select_db($dbname);
		$GLOBALS["dbr_total"]++;$GLOBALS["dbr_update"]++;
	if (!$this->mysqli->query($query))
		{
			if($GLOBALS["dbretry"]>3)
			{
				$GLOBALS["dbretry"]=0;
				return $this->mysqli;
			}
			else
			{
				$GLOBALS["dbretry"]++;
				$this->dbupdate($query,$dbname);
			}
		}
		else
		{
			$GLOBALS["dbretry"]=0;
		}
		if($dbclose!="-1"){$this->dbclose();}
		return SUCCESS;
	}

	/**
	 *
	 * EXECUTES SQL QUERIES IN A DB (DELETE)
	 *
	 * @param string $query Query to be executed
	 * @param string $dbname Database Name (Default DB NAME)
	 * @return string/object Object if error occured,"SUCCESS" if successful
	 */
	public function dbdelete($query,$dbname,$dbclose="-1")
	{
		$logobj=new ta_logs();
		$logobj->store_templogs($query);
		
		$this->mysqli->select_db($dbname);
		$GLOBALS["dbr_total"]++;$GLOBALS["dbr_del"]++;
	if (!$this->mysqli->query($query))
		{
			if($GLOBALS["dbretry"]>3)
			{
				$GLOBALS["dbretry"]=0;
				return $this->mysqli;
			}
			else
			{
				$GLOBALS["dbretry"]++;
				$this->dbdelete($query,$dbname);
			}
		}
		else
		{
			$GLOBALS["dbretry"]=0;
		}
		if($dbclose!="-1"){$this->dbclose();}
		return SUCCESS;
	}

	/**
	 * 
	 * CLOSE A DB CONNECTION
	 */
	public function dbclose()
	{
		mysqli_kill($this->mysqli,$this->mysqli->thread_id);
		mysqli_close($this->mysqli);
	}
	
	/**
	 * 
	 * START A TRANSACTION
	 */
	public function transaction_start()
	{
		return $this->mysqli->autocommit(FALSE);
	}
	
	/**
	 * 
	 * COMMIT UPDATES TO DB
	 */
	public function commit()
	{
		return $this->mysqli->autocommit(TRUE);
		//return $this->mysqli->commit();
	}
	
	/**
	 *
	 * TURN OFF AUTOCOMMIT
	 */
	public function commit_off()
	{
		return $this->mysqli->autocommit(FALSE);
	}

	/**
	 *
	 * TURN ON COMMIT
	 */
	public function commit_on()
	{
		return $this->mysqli->autocommit(TRUE);
	}

	/**
	 *
	 * ROLLBACK DATABASE OPERATIONS PERFORMED WITH COMMIT
	 */
	public function rollback()
	{
		return $this->mysqli->rollback();
	}

	/**
	 *
	 * GET A COLUMN VALUE FROM A DB Array
	 * @param unknown $res The DB Array to be processed
	 * @param unknown $colname The name of the column
	 * @param unknown $i The index of the array
	 * @return unknown The value
	 */
	public function colval($res,$colname,$i)
	{
		return $res[$i][changesqlquote($colname,"")];
	}

	/**
	 *
	 * ESCAPE A STRING's QUOTES
	 * @param unknown $str The string to be escaped
	 * @return string The escaped string
	 */
	public function escapestr($str)
	{
		return $this->mysqli->real_escape_string($str);
	}
}
?>
