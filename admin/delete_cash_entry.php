<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
// check if the 'id' variable is set in URL, and check that it is valid
	$sel_cash = mysql_prep($_GET['cash_id']); 
	$result=mysql_query("SELECT * FROM cash_transaction WHERE cash_transaction_id=$sel_cash");
	$editcash = mysql_fetch_array($result);
	 if ($sel_cash == $editcash['cash_transaction_id'])
	 {
	 // delete the entry
	 $result = mysql_query("DELETE FROM cash_transaction WHERE cash_transaction_id=$sel_cash")
	 or die(mysql_error()); 
	 
	 // redirect back to the view page
	 redirect_to("cashentry.php");
	 }
	 else
	 // if id isn't set, or isn't valid, redirect back to view page
	 {
	 echo "Failed to Delete, Contact Hillary for Help";
	 }	

?>