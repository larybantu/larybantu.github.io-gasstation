<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Edit Payment Entries</title>
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
    <?php 
			$sel_payment = $_GET['payment_id'];
			$result=mysql_query("SELECT * FROM payment_transaction WHERE payment_transaction_id=$sel_payment");
			
			//query the database for the rest of the data
			$editpayment = mysql_fetch_array($result);
			$printdate = date('m/d/Y', strtotime($editpayment['date_of_payment']));
			$printpayment_no = $editpayment['payment_no'];
			$printparticulars= $editpayment['particulars'];
			$printcustomer_name = $editpayment['customer_name'];
			$printcustomer_code = $editpayment['customer_code'];
			$printtype = $editpayment['payment_type'];
			$printamount = $editpayment['amount'];
		?>
    <form  id="formID" name="payment" method="post" action="<?php echo "update_payment.php?payment_id=". urlencode($editpayment['payment_transaction_id']) .""?>">
    <table width="645">
      <tr>
      <td width="116">Date:</td>
        <td width="200"><input type="text" name="date" id="datepicker" value="<?php echo $printdate ?>"/> </td>
        <td width="127">Receipt/Cheque No:</td>
       <td width="182"><input type="text" id="paymnt_no" name="payment_no" value="<?php echo $printpayment_no ?>"/></td>
       </tr>
      <tr>   
       <td>Particulars:</td>
        <td><input type="text" name="particulars" id="particulars" class="validate[required]" value="<?php echo $printparticulars ?>"/></td>
         <td>Customer Name:</td>
        <td><input type="text" name="cname" id="name" value="<?php echo $printcustomer_name ?>"/></td>
        </tr>
        <tr>  
          <td>Customer Account:</td>
           <td><select name="cacc" class="btn">
               <option value="<?php 
			   $queryacc = mysql_query("SELECT * FROM customers WHERE customer_code = '{$printcustomer_code}'");
			   $currentcust = mysql_fetch_array($queryacc);
			   echo $printcustomer_code ?>">
			   <?php echo $currentcust["customer_name"] ." ". $currentcust["customer_code"] ?></option>
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
           <td><input type="text" name="amount" id="amount" class="validate[required]" value="<?php echo $printamount ?>"/></td>
            <td><hr /></td>
           <td><hr /> </td>
           </tr>
         <tr>
         <td>Payment Type:</td>
         <td><select name="payment_type" class="btn">
               <?php 
			   if($printtype == 'Cash'){
               echo "<option value='Cash'>-Cash At Hand-</option>";
			   }elseif($printtype == 'Cheque'){
               echo "<option value='Cheque'>-Cheque-</option>";
			   }
			   ?>
                 <option value="Cash">Cash At Hand</option>
                   <option value="Cheque">Cheque</option>
             </select></td>
              <td class="del"><a href="<?php 
			 	echo "delete_payment.php?payment_id=". urlencode($editpayment['payment_transaction_id']) ."";
			 ?>" onclick="return confirm('Are you sure you want to delete this Payment Entry?');">Delete Entry</a></td>
             <td><input type="submit" class="btn" name="post" value="Update Entry" /></td>
          </tr>
       </table>
                        
    </form>
    <div id="results"><br />
    Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
	?><br />
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
    <?php include("../includes/footer.php");?>   
  </div>
 <?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
