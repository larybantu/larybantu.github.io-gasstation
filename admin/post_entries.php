<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");
	  require_once("../includes/formvalidator.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Posted Entries</title>
<link href="../css/modify.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="Shortcut icon" href="../img/newfav.ico" />

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script  type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker();
	$("#datepicker2").datepicker();
  });
</script>
</head>

<body>
<div id="wrapper">
    <?php require("../includes/header_inner.php");?>
  <div id="display">
    <div class="menuhead">Post Entries</div>
    <hr />
    SELECT WHICH CATEGORY TO POST HERE BELOW<br /><br />
     <form  id="formID" name="invoice" method="post" action="post_entries.php">
    <table width="502">
    <tr>   
       <td class="rightalign">Post :</td>
        <td>
          <select name="type" class="btn2">
               <option value="">--Select Entries to Post--</option>
                <option value="invoice">Invoices</option>
                 <option value="payment"> Payments</option>
                 <option value="service"> Service Invoice</option>
             
          </select></td>
      </tr>
      <tr>   
       <td class="rightalign">Post by:</td>
        <td>
          <select name="by" class="btn2">
               <option value="">--Select Post Type--</option>
               	<option value="dateot"> Date of transaction</option>
                 <option value="dateoe">Date of Entry</option>
                 
             
          </select></td><td> <font size="-2">Hint: Use the date of Transaction</font></td>
      </tr>
      <tr>
      <td width="270" height="44" class="rightalign">From:</td>
        <td width="220"><input type="text" name="fromdate" id="datepicker" /> To: </td>
           <td><input type="text" name="todate" id="datepicker2" /></td>
     </tr>
           <tr>
           <td></td>
        <td height="40"><input type="submit" class="btn" name="post" value="Post" onclick="return confirm('Are you sure you have checked all the entries?');" /></td>
            <td>&nbsp;</td>
           </tr>
  </table>
  </form>
    <font size="-1">
    <hr />
    <font color='#FF0000'>NB: BY CLICKING THIS BUTTON ABOVE YOU AGREE THAT ALL DATA IS CHECKED AND CORRECT</font>
    </font>
    
        <div id="results">
      <?php	
	//check for posted information
	if (isset($_POST['post'])){
		if($_POST['type'] != NULL &&  $_POST['by'] != NULL && $_POST['fromdate'] != NULL &&  $_POST['todate'] != NULL){
			   
		      //Declaration of variables
		global $m,$d,$y;
		 $post_type = trim(mysql_prep($_POST['type']));
		 $post_by = trim(mysql_prep($_POST['by']));
		 list($m,$d,$y) = explode("/",$_POST['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_POST['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d ");
		 
		 if($post_type == 'invoice' && $post_by == 'dateoe'){//invoice by date of entry
		 
		 	$query = "INSERT INTO display_data (invoice_transaction_id, date, ref, customer_name, customer_code, vehicle_no, ltrs, debit, acc_type, product_code) SELECT invoice_transaction_id, date_of_invoice, invoice_no, customer_name, customer_code, vehicle_no, litres, amount, acc_type, product_code FROM invoice_transaction WHERE date_of_entry BETWEEN '{$fromdate}' AND '{$todate}' AND NOT EXISTS(SELECT * FROM display_data WHERE(invoice_transaction.invoice_transaction_id = display_data.invoice_transaction_id))";
				
				if(mysql_query($query)){
					//showing what has been posted
				echo "All entries of type: <font color=\"#438787\">Invoice</font> <br />By: <font color=\"#438787\">Date of Entry</font> <br />From:<font color=\"#438787\"> ". $fromdate ."</font><br />To:<font color=\"#438787\">  ". $todate ."</font> <br />have been successfully posted<br /><font color=\"#438787\"> You can now check the accounts to review the updated balances.</font>";
				
				}else{
					echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}//ends else failure to post 
		 
			 }elseif($post_type == 'invoice' && $post_by == 'dateot'){//invoice by date of transaction
			 		
				$query = "INSERT INTO display_data (invoice_transaction_id, date, ref, customer_name, customer_code, vehicle_no, ltrs, debit, acc_type, product_code) SELECT invoice_transaction_id, date_of_invoice, invoice_no, customer_name, customer_code, vehicle_no, litres, amount, acc_type, product_code FROM invoice_transaction WHERE date_of_invoice BETWEEN '{$fromdate}' AND '{$todate}' AND NOT EXISTS(SELECT * FROM display_data WHERE(invoice_transaction.invoice_transaction_id = display_data.invoice_transaction_id))";
				
				if(mysql_query($query)){
					//showing what has been posted
				echo "All entries of type: <font color=\"#438787\">Invoice</font> <br />By: <font color=\"#438787\">Date of Transaction</font> <br />From:<font color=\"#438787\"> ". $fromdate ."</font><br />To:<font color=\"#438787\">  ". $todate ."</font> <br />have been successfully posted<br /><font color=\"#438787\"> You can now check the accounts to review the updated balances.</font>";
				
				}else{
					echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}//ends else failure to post
						
			 		}elseif($post_type == 'payment' && $post_by == 'dateoe'){//payment by date of entry 
							
							$query = "INSERT INTO display_data 
								(payment_transaction_id, date, ref, 	customer_name,  customer_code, vehicle_no, credit, acc_type) SELECT payment_transaction_id, date_of_payment, payment_no, customer_name, customer_code, particulars, amount, acc_type FROM payment_transaction WHERE date_of_entry BETWEEN '{$fromdate}' AND '{$todate}' AND NOT EXISTS(SELECT * FROM display_data WHERE(payment_transaction.payment_transaction_id = display_data.payment_transaction_id))";
								
								if(mysql_query($query)){
					//showing what has been posted
				echo "All entries of type: <font color=\"#438787\">Payment</font> <br />By: <font color=\"#438787\">Date of Entry</font> <br />From:<font color=\"#438787\"> ". $fromdate ."</font><br />To:<font color=\"#438787\">  ". $todate ."</font> <br />have been successfully posted<br /><font color=\"#438787\"> You can now check the accounts to review the updated balances.</font>";
				
				}else{
					echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}//ends else failure to post
		 

						}elseif($post_type == 'payment' && $post_by == 'dateot'){//payment by date of transaction 
						
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
								FROM payment_transaction WHERE date_of_payment BETWEEN '{$fromdate}' AND '{$todate}' AND NOT EXISTS(SELECT * FROM display_data WHERE(payment_transaction.payment_transaction_id = display_data.payment_transaction_id))";
								
								if(mysql_query($query)){
					//showing what has been posted
				echo "All entries of type: <font color=\"#438787\">Payment</font> <br />By: <font color=\"#438787\">Date of Payment</font> <br />From:<font color=\"#438787\"> ". $fromdate ."</font><br />To:<font color=\"#438787\">  ". $todate ."</font> <br />have been successfully posted<br /><font color=\"#438787\"> You can now check the accounts to review the updated balances.</font>";
				
				}else{
					echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}//ends else failure to post
		 
							
			 				}elseif($post_type == 'service' && $post_by == 'dateoe'){//service by date of entry
								
								echo "Not posted still under construction";
								
			 					}elseif($post_type == 'service' && $post_by == 'dateot'){//service by date of transaction
				 
				 					echo "Not posted still under construction contact Hillary";
									 }
		   }else{//end of if values are not NULL
			   
			  echo "<br /><font color='#FF0000'>Some Field(s) above are Empty, Please Check again before Posting</font>"; 
		   }
			   }
	?>
    </div>
    <?php include("../includes/footer.php");?>
     <br />
 </div>
<?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
<?php 
close();
?>
