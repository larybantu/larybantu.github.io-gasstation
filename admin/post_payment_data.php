<<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Post Payment Entry</title>
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
  <div class="menuhead"> Post Payment Feedback</div>
  <br />
  <br />
  <br />
  &nbsp; &nbsp; &nbsp; &nbsp;
<?php 
				
//make the awesome query
	 /*1	data_id	int(40)			No	None	AUTO_INCREMENT	 	 	 	 	 	 		
	 2	invoice_transaction_id	int(40)			Yes	NULL		 	 	 	 		 		
	 3	payment_transaction_id	int(40)			Yes	NULL		 	 	 	 		 		
	 4	date	date			No	None		 	 	 	 	 	 		
	 5	ref	int(11)			No	None		 	 	 	 	 	 		
	 6	customer_name	varchar(40)	utf8_general_ci		No	None		 	 	 	 	 	 		
	 7	vehicle_no	varchar(50)	utf8_general_ci		No	None		 	 	 	 	 	 		
	 8	product_code	varchar(3)	utf8_general_ci		Yes	NULL		 	 	 	 	 	 		
	 9	ltrs	decimal(10,2)			No	None		 	 	 	 	 	 		
	 10	debit	bigint(40)			Yes	NULL		 	 	 	 	 	 		
	 11	credit	bigint(40)			Yes	NULL		 	 	 	 	 	 		
	 12	balance	bigint(40)			Yes	NULL		 	 	 	 	 	 		
	 13	customer_code	varchar(10)	utf8_general_ci		No	None		 	 	 	 	 	 		
	 14	acc_type*/

	$query = "INSERT INTO display_data 
	(payment_transaction_id, 
	date,
	ref, 
	customer_name, 
	customer_code, 
	vehicle_no, 
	credit, 
	acc_type) 
	SELECT
	 payment_transaction_id, 
	 date_of_payment, 
	 payment_no,
	 customer_name, 
	 customer_code,
	 particulars, 
	 amount,
	 acc_type 
	  FROM payment_transaction WHERE NOT EXISTS(SELECT * FROM display_data WHERE(payment_transaction.payment_transaction_id = display_data.payment_transaction_id))";
	
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