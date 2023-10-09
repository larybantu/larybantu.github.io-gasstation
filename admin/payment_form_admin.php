<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Payment Entry</title>
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
    <div class="menuhead"> Payment Entry</div>
    <form  id="formID" name="invoice" method="post" action="payment_form_admin.php">
    <table width="645">
      <tr>
      <td width="116">Date:</td>
        <td width="200"><input type="text" name="date" id="datepicker"/> </td>
        <td width="127">Receipt/Cheque No:</td>
       <td width="182"><input type="text" id="litres" name="payment_no"/></td>
       </tr>
      <tr>   
       <td>Particulars:</td>
        <td><input type="text" name="particulars" id="vehicle" class="validate[required]"/></td>
         <td>Customer Name:</td>
        <td><input type="text" name="cname" id="name"/></td>
        </tr>
        <tr>  
          <td>Customer Account:</td>
           <td><select name="cacc" class="btn">
               <option value="">--Select Account--</option>
               <?php 
			   		$queryacc = mysql_query("SELECT * FROM customers ORDER BY customer_name");
					while($customer = mysql_fetch_array($queryacc)){
						echo "<option value='{$customer["customer_code"]}'>{$customer["customer_name"]} " . $customer['customer_code'] . "</option>";
					}
			 ?>
              </select></td>
            <td>&nbsp;</td>
           <td></td>
        </tr>
        <tr> 
          <td>Amount:</td>
           <td><input type="text" name="amount" id="amount" class="validate[required]"/></td>
            <td>&nbsp;</td>
           <td>&nbsp; </td>
           </tr>
         <tr>
         <td>Payment Type:</td>
         <td><select name="payment_type" class="btn">
               <option value="">-Payment Type-</option>
                 <option value="Cash">Cash At Hand</option>
                   <option value="Cheque">Cheque</option>
             </select></td>
             <td></td>
             <td><input type="submit" class="btn" name="post" value="Post" /></td>
          </tr>
       </table>
                        
    </form>
    <div id="results">
    Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
	if ($_POST){
		global $m,$d,$y;
		list($m,$d,$y) = explode("/",$_POST['date']);
	/*1*/ $date = mysql_real_escape_string("$y-$m-$d ");
	/*2*/ $payment_type = trim(mysql_prep($_POST['payment_type'])) ;
	/*3*/ $particulars = trim(mysql_prep($_POST['particulars']));
	/*4*/ $customer_name = trim(mysql_prep($_POST['cname']));
	/*5*/ $customer_code = trim(mysql_prep($_POST['cacc']));
	/*6*/ $payment_no = trim(mysql_prep($_POST['payment_no']));
	/*7*/ $amount = trim(mysql_prep($_POST['amount'])) ; 
	/*8*/ $payment_no = trim(mysql_prep($_POST['payment_no']));
	
	
	//validation of entries to eliminate duplicate entries
	$queryinsert = mysql_query("SELECT payment_no FROM payment_transaction");
	$already_exists = mysql_fetch_array($queryinsert);
	if($already_exists['payment_no']!= $_POST['payment_no']){
	//continue with insert
	$query = "INSERT INTO payment_transaction (date_of_payment, date_of_entry, payment_type, customer_name, customer_code, particulars, amount, payment_no) VALUES ('{$date}',CURDATE(),'{$payment_type}','{$customer_name}','{$customer_code}','{$particulars}',{$amount},{$payment_no})";
	
	//make the qquery
	if(mysql_query($query)){
		 	echo " &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font color=\"#438787\">Entry Added Successfully</font>";
		}else{
			echo "&nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<font color='#FF0000'>Failed: " . mysql_error() . "</font>";			 
			 }
	}else{
		echo "&nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<font color='#FF0000'>".$_POST['payment_type']." Number Already Exists </font>";
			}
		}	
	?> <br />
    <table  class="displaytb" width="800">
        <tr class="tablehead">
          <td class="celltb" width="79" height="38">Date</td>
          <td class="celltb" width="87">Rec/Chq No.</td>
          <td class="celltb" width="56">Account</td>
          <td class="celltb" width="196">Name</td>
          <td class="celltb" width="128">Particlars</td>
          <td class="celltb" width="119">Amount</td>
          <td width="50">Action</td>
        </tr>
     <?php
			 $result = mysql_query("SELECT * FROM payment_transaction WHERE date_of_entry = CURDATE() AND NOT EXISTS(SELECT * FROM display_data WHERE(payment_transaction.payment_transaction_id = display_data.payment_transaction_id))");

		while($row = mysql_fetch_array($result)){
			echo "<tr>
			  <td class='celltb' width='79' height='35'>". date('d/M/Y', strtotime($row['date_of_payment'])) ."</td>
			  <td class='celltb' width='87'>{$row["payment_no"]}</td>
			  <td class='celltb' width='56'>{$row["customer_code"]}</td>
			  <td class='celltb' width='196'>{$row["customer_name"]}</td>
			  <td class='celltb' width='128'>{$row["particulars"]}</td>
			  <td class='celltb' width='119'>{$row["amount"]}</td>
			  <td width='30'><a href='editentryp.php?payment_id=". urlencode($row['payment_transaction_id']) ."'>Edit</a></td>
			</tr>";
		  }
			?>
            </table>
    </div>
  </div>
   <?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
