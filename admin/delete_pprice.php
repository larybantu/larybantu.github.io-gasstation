<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
// check if the 'id' variable is set in URL, and check that it is valid
	$sel_pprice = mysql_prep($_GET['pprice_id']); 
	$result=mysql_query("SELECT * FROM pump_price WHERE pump_price_id=$sel_pprice");
	$editpprice = mysql_fetch_array($result);
	 if ($sel_pprice == $editpprice['pump_price_id'])
	 {
	 // delete the entry
	 $result = mysql_query("DELETE FROM pump_price WHERE pump_price_id=$sel_pprice")
	 or die(mysql_error());
	 
	 // redirect back to the view page
	 redirect_to("change_price_admin.php");
	 }
	 else
	 // if id isn't set, or isn't valid, redirect back to view page
	 {
		 //sel_pprice is not equal to editpprice['pump_price_id']
	 echo "Failed to Delete, Contact Hillary for Help 0774026685";
	 }	

?>