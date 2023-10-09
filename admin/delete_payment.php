<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
// check if the 'id' variable is set in URL, and check that it is valid
	$sel_payment = mysql_prep($_GET['payment_id']); 
	$result=mysql_query("SELECT * FROM payment_transaction WHERE payment_transaction_id=$sel_payment");
	$editpayment = mysql_fetch_array($result);
	 if ($sel_payment == $editpayment['payment_transaction_id'])
	 {
	 // delete the entry
	 $result = mysql_query("DELETE FROM payment_transaction WHERE payment_transaction_id=$sel_payment")
	 or die(mysql_error()); 
	 
	 // redirect back to the view page
	if($_SESSION['user_id'] == 1000){
		  redirect_to("payment_form_admin.php");
		 }else{
			redirect_to("../front/payment_form.php");
			 }
	 }
	 else
	 // if id isn't set, or isn't valid, redirect back to view page
	 {
	 echo "Failed to Delete, Contact Hillary for Help <br /> <a href='meter_readings.php'>Click here </a> to go Back to the Main Menu";
	 
	 }	

?>