<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
// check if the 'id' variable is set in URL, and check that it is valid
	$sel_customer = mysql_prep($_GET['customer_id']); 
	$result=mysql_query("SELECT * FROM customers WHERE customer_id=$sel_customer");
	$editcustomer = mysql_fetch_array($result);
	 if ($sel_customer == $editcustomer['customer_id'])
	 {
	 // delete the entry
	 $result = mysql_query("DELETE FROM customers WHERE customer_id=$sel_customer")
	 or die(mysql_error()); 
	 
	 // redirect back to the view page
	 redirect_to("addacc.php");
	 }
	 else
	 // if id isn't set, or isn't valid, redirect back to view page
	 {
	 echo "Failed to Delete, Contact Hillary for Help";
	 }	

?>