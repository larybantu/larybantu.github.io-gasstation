<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
// check if the 'id' variable is set in URL, and check that it is valid
	$sel_invoice = mysql_prep($_GET['invoice_id']); 
	$result=mysql_query("SELECT * FROM invoice_transaction WHERE invoice_transaction_id=$sel_invoice");
	$editinvoice = mysql_fetch_array($result);
	 if ($sel_invoice == $editinvoice['invoice_transaction_id'])
	 {
	 // delete the entry
	 $result = mysql_query("DELETE FROM invoice_transaction WHERE invoice_transaction_id=$sel_invoice")
	 or die(mysql_error()); 
	 
	 // redirect back to the view page
	if($_SESSION['user_id'] == 1000){
		  redirect_to("invoice_form_admin.php");
		 }else{
			redirect_to("../front/invoice_form.php");
			 }
	 }
	 else
	 // if id isn't set, or isn't valid, redirect back to view page
	 {
	 echo "Failed to Delete, Contact Hillary for Help";
	 }	

?>