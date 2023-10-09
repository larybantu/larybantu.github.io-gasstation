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

<title>Query Customer</title>
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
<script language="javascript" src="highlight.js"></script>
</head>

<body>
<div id="wrapper">
    <?php require("../includes/header_inner.php");?>
     <?php	
	if (isset($_POST['post'])){
		if($_POST['cacc'] != NULL && 
		   $_POST['fromdate'] != NULL &&
		   $_POST['balancebfd'] != NULL && 
		   $_POST['todate'] != NULL){
			   
			   //Declaration of variables
		global $m,$d,$y;
		 $c_account = trim(mysql_prep($_POST['cacc']));
		 $balancebfd = trim(mysql_prep($_POST['balancebfd']));
		 list($m,$d,$y) = explode("/",$_POST['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_POST['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d "); ?>
         
  <div id="display">
    <div class="menuhead">Customer Statement Options</div><br />
    SUBLEDGER ACCOUNT ANALYSIS
    
    
    <form  id="formID" name="invoice" method="post" action="customer_statements.php">
    
    <table width="502">
    <tr>   
       <td class="rightalign">Customer Account:</td>
        <td>
          <select name="cacc" class="btn2">
               <option value=""><?php echo $c_account ?></option>
               <?php 
			   $queryacc = mysql_query("SELECT * FROM customers");
					while($customer = mysql_fetch_array($queryacc)){
						echo "<option value='{$customer["customer_code"]}'>{$customer["customer_name"]} " . $customer['customer_code'] . "</option>";
						} ?>
                        
              </select></td>
              <td><?php $customer = mysql_fetch_array($queryacc);
			  echo "<strong>" . $customer['customer_name'] - $customer['customer_code'] ."</strong>" ;?></td>
        </tr>
      <tr>
      <td width="270" height="44" class="rightalign">From:</td>
        <td width="220"><input type="text" name="fromdate" id="datepicker" /> </td>
        
       
      
          <td class="rightalign">To:</td>
           <td><input type="text" name="todate" id="datepicker2" /></td>
     </tr>
           <tr> 
          <td height="44" class="rightalign">Balance B/F:</td>
          <td><select name="balancebfd" class="btn3">
               <option value="">--Select Option--</option>
                <option value="1">YES</option>
                   <option value="0">NO</option>
        </select></td>
            
           </tr>
           <tr>
           <td>&nbsp;</td>
        </tr>
         <tr><td></td>
         <td><input type="submit" class="btn" name="post" value="Generate" /></td>
        </tr>
       </table>
                        
    </form>
    
      <div id="results">
     <?php

	//ACTIONS TO SEARCH DB FOR SELECTED RESULTS
	if($balancebfd == 0){
		echo "
		<div id='printer'>
		    <a href='print_account_statement.php' target='_blank' >Printer Friendly Version</a></div><br />
		<div id ='tableheading'><hr /> ";
		$queryacc2 = mysql_query("SELECT * FROM customers");
		$result = mysql_fetch_array($queryacc2);
		if($result["customer_code"]== $c_account ){
			echo "<strong> {$result["customer_code"]} - ". $result["customer_name"] ."</strong><br />";
			}
					
		 echo"
			 Account Statement <br /> From <strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='800'>
        <tr class='tablehead'>
          <td class='celltb' width='81' height='40'>Date</td>
          <td class='celltb' width='80'>Ref</td>
          <td class='celltb' width='120'>Account Name</td>
          <td class='celltb' width='100'>Vehicle No.</td>
          <td class='celltb' width='57'>Litres</td>
          <td class='celltb' width='95'>Debit</td>
		  <td class='celltb' width='95'>Credit</td>
		  <td class='celltb' width='95'>Balance</td>
         
        </tr>";
		//what we need to arrange
		$bal = 0;
		//1 Select account......... the account exists since its selected from the drop down menu so no need to verify
		//3 select invoices................ 
		/*BACKUP SELECT STATEMENT */
		
		$invoice_data = mysql_query("
		SELECT *
		FROM invoice_transaction
		LEFT JOIN customers ON invoice_transaction.customer_code = customers.customer_code 
		WHERE customers.customer_code = '{$c_account}'
		AND invoice_transaction.date_of_invoice BETWEEN '{$fromdate}' AND '{$todate}'  
		ORDER BY invoice_transaction.date_of_invoice ASC"); 
		
		while($row = mysql_fetch_array($invoice_data)){
		//4 display the results for the invoice
		
		echo "<tr>
          <td class='celltb' width='81' height='40'>". date('d/M/Y', strtotime($row["date_of_invoice"]))."</td>
          <td class='celltb' width='80'>".$row['invoice_no']."</td>
          <td class='celltb' width='120'>{$row["customer_name"]}</td>
          <td class='celltb' width='100'>{$row["vehicle_no"]}</td>
          <td class='celltb' width='57'>{$row["litres"]}</td>
          <td class='celltb' width='95'>{$row["amount"]}</td>
		  <td class='celltb' width='95'>0.00</td>";
		  
		  //4 calculate the total Debit...........
		  $bal = $bal + $row['amount'];
		  echo"
		  <td class='celltb' width='95'>". $bal ."</td>";
		  echo"
        </tr>";
		}
		
		/*//Payments rows
		//3 select payments................
		$payment_data = mysql_query("
		SELECT * FROM payment_transaction
		LEFT JOIN customers ON payment_transaction.customer_code = customers.customer_code 
		WHERE customers.customer_code = '{$c_account}'
		AND payment_transaction.date_of_payment BETWEEN '{$fromdate}' AND '{$todate}'  
		ORDER BY payment_transaction.date_of_payment ASC");
	
		while($row = mysql_fetch_array($payment_data)){
		//4 display the results
		echo "<tr class='payment'>
          <td class='celltb' width='81' height='40'>". date('d/M/Y', strtotime($row["date_of_payment"]))."</td>
          <td class='celltb' width='98'>".$row['payment_no']."</td>
          <td class='celltb' width='101'>{$row["customer_name"]}</td>
          <td class='celltb' width='127'>{$row["payment_type"]}</td>
          <td class='celltb' width='57'></td>
          <td class='celltb' width='95'>0.00</td>
		  <td class='celltb' width='95'>{$row["amount"]}</td>";
		  
		  //4 calculate the balance removing the payments from the payments...........
		  echo"
		  <td class='celltb' width='95'>Balance</td>
        </tr>";
		}*/
        echo"
            </table>";
		
		}
	}else{ 
		echo "<br /><font color='#FF0000'>Some Field(s) above are Empty, Please Check again before Posting</font>"; 
		}
	}
	?>
    
    </div>
<?php include("../includes/footer.php");?>
   </div>
<?php include("../includes/mainmenu.php");?>
    
</div>
</body>
</html>
<?php 
close();
?>
