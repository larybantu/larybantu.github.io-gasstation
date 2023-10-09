<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");
	  require_once("../includes/formvalidator.php"); ?>
<?php confirm_logged_in();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nominal Statement-Account </title>
<link href="../css/print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css/print.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="Shortcut icon" href="../img/newfav.ico" />
</head>

<body>
<?php
// check if the 'date' variable is set in URL, and check that it is valid
if(isset($_GET['product']) && isset($_GET['fromdate']) && isset($_GET['todate']) ){
	global $m,$d,$y;
		 $product = trim(mysql_prep($_GET['product']));
		 list($m,$d,$y) = explode("/",$_GET['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_GET['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d ");
//ACTIONS TO SEARCH DB
	if($product == 'PMS'){
		echo "
		<div id='printer'>
    <a href='printmeters.php' target='_blank' >Printer Friendly Version</a></div><br /><br />
	<div id ='tableheading'><hr /> 
			Statement of Nominal Account <br />".$product." - CREDIT SALES<br /><strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='747'>
        <tr class='tablehead'>
          <td class='celltb' width='72' height='38'>Date</td>
          <td class='celltb' width='68'>Invoice No.</td>
          <td class='celltb' width='58'>Account</td>
          <td class='celltb' width='196'>Name</td>
          <td class='celltb' width='59'>Veh. No.</td>
          <td class='celltb' width='59'>Product</td>
          <td class='celltb' width='47'>Litres</td>
          <td class='celltb' width='134'>Amount</td>
          <td width='10'>Action</td>
        </tr>";
			$result = mysql_query("SELECT * FROM invoice_transaction WHERE product_code = '{$product}' AND date_of_invoice BETWEEN '{$fromdate}' AND '{$todate}' ORDER BY date_of_invoice, invoice_no ASC");
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
			  <td width='10'><a href='editentryi.php?invoice_id=". urlencode($row['invoice_transaction_id']) ."'>Edit</a></td>
			</tr>
		";
		  }
        echo"
            </table>";
			}elseif($product == 'AGO'){
		echo "
		<div id='printer'>
    <a href='printmeters.php' target='_blank' >Printer Friendly Version</a></div><br /><br />
	<div id ='tableheading'><hr /> 
			Statement of Nominal Account <br />".$product." - CREDIT SALES<br /><strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='747'>
        <tr class='tablehead'>
          <td class='celltb' width='72' height='38'>Date</td>
          <td class='celltb' width='68'>Invoice No.</td>
          <td class='celltb' width='58'>Account</td>
          <td class='celltb' width='196'>Name</td>
          <td class='celltb' width='59'>Veh. No.</td>
          <td class='celltb' width='59'>Product</td>
          <td class='celltb' width='47'>Litres</td>
          <td class='celltb' width='134'>Amount</td>
          <td width='10'>Action</td>
        </tr>";
			$result = mysql_query("SELECT * FROM invoice_transaction WHERE product_code = '{$product}' AND date_of_invoice BETWEEN '{$fromdate}' AND '{$todate}' ORDER BY date_of_invoice, invoice_no ASC");
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
			  <td width='10'><a href='editentryi.php?invoice_id=". urlencode($row['invoice_transaction_id']) ."'>Edit</a></td>
			</tr>
		";
		  }
        echo"
            </table>";
			}elseif($product == 'BIK'){
		echo "
		<div id='printer'>
    <a href='printmeters.php' target='_blank' >Printer Friendly Version</a></div><br /><br />
	<div id ='tableheading'><hr /> 
			Statement of Nominal Account <br />".$product." - CREDIT SALES<br /><strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='747'>
        <tr class='tablehead'>
          <td class='celltb' width='72' height='38'>Date</td>
          <td class='celltb' width='68'>Invoice No.</td>
          <td class='celltb' width='58'>Account</td>
          <td class='celltb' width='196'>Name</td>
          <td class='celltb' width='59'>Veh. No.</td>
          <td class='celltb' width='59'>Product</td>
          <td class='celltb' width='47'>Litres</td>
          <td class='celltb' width='134'>Amount</td>
          <td width='10'>Action</td>
        </tr>";
			$result = mysql_query("SELECT * FROM invoice_transaction WHERE product_code = '{$product}' AND date_of_invoice BETWEEN '{$fromdate}' AND '{$todate}' ORDER BY date_of_invoice, invoice_no ASC");
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
			  <td width='10'><a href='editentryi.php?invoice_id=". urlencode($row['invoice_transaction_id']) ."'>Edit</a></td>
			</tr>
		";
		  }
        echo"
            </table>";
			}
}else{
	echo "No content Available withon the GET Commands";
	}
			
			 ?>
</body>
</html>