<<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Post Invoice Entry</title>
<link href="../css/modify.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="Shortcut icon" href="../img/newfav.ico" />

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script  type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker();
  });
</script>
</head>

<body>
<div id="wrapper">
    <?php require("../includes/header_inner.php");?>
  <div id="display">
  <div class="menuhead"> Post Invoice Feedback</div>
  <br />
  <br />
  <br />
  &nbsp; &nbsp; &nbsp; &nbsp;
<?php 
				
//make the awesome query

	$query = "INSERT INTO display_data (invoice_transaction_id, date, ref, customer_name, customer_code, vehicle_no, ltrs, debit, acc_type, product_code) SELECT invoice_transaction_id, date_of_invoice, invoice_no, customer_name, customer_code, vehicle_no, litres, amount, acc_type, product_code FROM invoice_transaction WHERE NOT EXISTS(SELECT * FROM display_data WHERE(invoice_transaction.invoice_transaction_id = display_data.invoice_transaction_id))";
	
	if(mysql_query($query)){
		redirect_to("post_entries.php");
	}else{
		echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}
?>
   
    <?php include("../includes/footer.php");?>   
  </div>
 <?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
<?php 
close();
?>