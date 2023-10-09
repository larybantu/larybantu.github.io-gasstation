<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");?>
<?php require_once("../includes/formvalidator.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Nominal Account Query</title>
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
    <div class="menuhead">QUERY NORMINAL ACCOUNTS</div><br />
    NORMINAL ACCOUNTS ANALYSIS
     <form  id="formID" name="invoice" method="post" action="nominal_accounts.php">
    <table width="419">
    <tr>   
       <td height="40" class="rightalign"><strong>Nominal Account:</strong></td>
        <td><select name="product" class="btn2">
               <option value="">--Select Account--</option>
                 <option value="PMS">PMS-Credit Sales</option>
                   <option value="AGO">AGO-Credit Sales</option>
                      <option value="BIK">BIK-Credit Sales</option>
              </select>
          </td>
        </tr><br />
        <tr>
        <td  class="rightalign" height="45">From:</td><td><input type="text" name="fromdate" id="datepicker" /></td></tr>
       <tr> 
          <td  class="rightalign" height="47">To:</td>
    <td><input type="text" name="todate" id="datepicker2" /></td>
            
       
         <tr>
         <td>&nbsp;</td>
         <td><input type="submit" class="btn" name="post" value="Generate" /></td>
             
        </tr>
      </table>                 
    </form>
    
   <div id="results">
      <?php	
	if (isset($_POST['post'])){
		if($_POST['product'] != NULL && 
		   $_POST['fromdate'] != NULL &&
		   $_POST['todate'] != NULL){
		global $m,$d,$y;
		 $product = trim(mysql_prep($_POST['product']));
		 list($m,$d,$y) = explode("/",$_POST['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_POST['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d ");

	//ACTIONS TO SEARCH DB
	if($product == 'PMS'){
		echo "
		<div id='printer'>
    <a href='printnominal.php?product=".$product."fromdate=".$fromdate."todate=".$todate."' target='_blank' >Printer Friendly Version</a></div><br /><br />
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
			}else{
				echo "Result Unknown";
				}
		
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
