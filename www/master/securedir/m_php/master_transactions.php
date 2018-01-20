<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO PAYMENT & TRANSACTIONAL OPERATIONS
 * @author T.V.VIGNESH
 *
 */
class ta_transactions
{
	public function receivepayment($uid,$amount,$currency,$pid,$appid,$cardsecurearr,$paymakername,$payeename,$paymentnote,$detailsarray="")
	{

	}

	public function refundmoney($uid,$amount,$currency,$pid,$appid,$cardsecurearr,$paymakername,$payeename,$refundnote,$detailsarray="")
	{

	}

	public function makepayment($uid,$amount,$currency,$pid,$appid,$cardsecurearr,$paymakername,$payeename,$paymentnote,$detailsarray="")
	{

	}

	public function transfermoney($uid,$amount,$currency,$pid,$appid,$cardsecurearr,$paymakername,$payeename,$transfernote,$sourceacct,$destacct,$detailsarray="")
	{

	}

	public function paymenthistory($uid,$appid="")
	{

	}

	/**
	 *
	 * GET ALL THE TRANSACTIONS MADE BY A USER
	 * @param unknown $uid UID of the user who made the transaction
	 * @return array A DB Array having all the transactions made by the user
	 */
	public function get_transactions($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_transactions_money::tblname." WHERE ".tbl_transactions_money::col_uid."='$uid'",tbl_transactions_money::dbname);
		return $res;
	}

	/**
	 *
	 * GET THE NUMBER OF TRANSACTIONS MADE BY A USER
	 * @param unknown $uid User ID of the person who made the transaction
	 * @return number The number of transactions made
	 */
	public function get_no_transactions($uid)
	{
		$res=$this->get_transactions($uid);
		return count($res);
	}


}
?>