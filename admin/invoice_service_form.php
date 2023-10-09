<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Service Invoice Entry</title>
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
    <div class="menuhead"> Service Invoice Entry</div>
    <form  id="formID" name="invoice" method="post" action="../front/invoice_form.php">
    <table width="645">
      <tr>
      <td width="116">Date:</td>
        <td width="200"><input type="text" name="date" id="datepicker"/> </td>
        <td width="110"> Invoice No:</td>
       <td width="199"><input type="text" id="invoice_no" name="invoice_no" class="validate[required]" /></td>
       </tr>
      <tr>   
       <td>Vehicle No:</td>
        <td><input type="text" name="vehicle_no" id="vehicle" class="validate[required]"/></td>
         <td>Customer Name:</td>
        <td><input type="text" name="cname" id="name"/></td>
        </tr>
        <tr>  
          <td>Customer Account:</td>
           <td><select name="cacc" class="btn">
               <option value="">--Select Account--</option>
               <?php 
			   		$queryacc = mysql_query("SELECT * FROM customers");
					while($customer = mysql_fetch_array($queryacc)){
						echo "<option value='{$customer["customer_code"]}'>{$customer["customer_name"]} " . $customer['customer_code'] . "</option>";
						}
			   
                 
			 ?>
              </select></td>
            <td>Litres(2dp):</td>
           <td><input type="text" id="litres" name="ltrs"/></td>
          </tr>
        <tr> 
          <td>Amount:</td>
           <td><input type="text" name="amount" id="amount" class="validate[required]"/></td>
            <td>Product Price</td>
           <td> &nbsp; &nbsp; <?php echo 3200; ?></td>
           </tr>
         <tr>
         <td>Product:</td>
         <td><select name="product" class="btn">
               <option value="">--Choose Product--</option>
                 <option value="PMS">PMS</option>
                   <option value="AGO">AGO</option>
                      <option value="BIK">BIK</option>
                      <option value="SERVICE">SERVICE</option>
             </select></td>
             <td></td>
             <td><input type="submit" class="btn" name="post" value="Post" /></td>
          </tr>
       </table>
                        
    </form>
    <div id="results"><br />
    Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
	if ($_POST){
		global $m,$d,$y;
		list($m,$d,$y) = explode("/",$_POST['date']);
	/*1*/ $date = mysql_real_escape_string("$y-$m-$d ");
	/*2*/ $invoice_no = trim(mysql_prep($_POST['invoice_no']));
	/*3*/ $vehicle = trim(mysql_prep($_POST['vehicle_no']));
	/*4*/ $customer_name = trim(mysql_prep($_POST['cname']));
	/*5*/ $customer_code = trim(mysql_prep($_POST['cacc']));
	/*6*/ $ltrs = trim(mysql_prep($_POST['ltrs']));
	/*7*/ $amount = trim(mysql_prep($_POST['amount'])) ; 
	/*8*/ $product = trim(mysql_prep($_POST['product'])) ;
	
	//validation of entries 

	//to eliminate duplicate entries	
	$queryinsert = mysql_query("SELECT invoice_no FROM invoice_transaction");
	$already_exists = mysql_fetch_array($queryinsert);
	if($already_exists['invoice_no']!== $_POST['invoice_no']){
		$query = "INSERT INTO invoice_transaction (invoice_no, date_of_invoice, date_of_entry, vehicle_no, product_code, customer_name, customer_code, litres, amount) VALUES ({$invoice_no},'{$date}',CURDATE(),'{$vehicle}','{$product}','{$customer_name}','{$customer_code}',{$ltrs},{$amount})";
		
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
    <table  class="displaytb" width="747">
        <tr class="tablehead">
          <td class="celltb" width="72" height="38">Date</td>
          <td class="celltb" width="68">Invoice No.</td>
          <td class="celltb" width="58">Account</td>
          <td class="celltb" width="196">Name</td>
          <td class="celltb" width="59">Veh. No.</td>
          <td class="celltb" width="59">Product</td>
          <td class="celltb" width="47">Litres</td>
          <td class="celltb" width="134">Amount</td>
          <td width="10">Action</td>
        </tr>
     <?php
			$result = mysql_query("SELECT * FROM invoice_transaction WHERE date_of_entry = CURDATE()");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' width='70' height='35'>". date('d/M/Y', strtotime($row['date_of_invoice'])) ."</td>
			  <td class='celltb' width='68'>{$row["invoice_no"]}</td>
			  <td class='celltb' width='55'>{$row["customer_code"]}</td>
			  <td class='celltb' width='196'>{$row["customer_name"]}</td>
			  <td class='celltb' width='59'>{$row["vehicle_no"]}</td>
			  <td class='celltb' width='59'>{$row["product_code"]}</td>
			  <td class='celltb' width='47'>{$row["litres"]}</td>
			  <td class='celltb' width='134'>{$row["amount"]}</td>
			  <td width='30'><a href='editentryi.php'>Edit</a></td>
			</tr>
		";
		  }
			?>
            </table>
    </div>
  </div>
 <?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
