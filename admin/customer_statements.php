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
  <div id="display">
    <div class="menuhead">Customer Statement Options</div><br />
    SUBLEDGER ACCOUNT ANALYSIS
    <form  id="formID" name="invoice" method="post" action="customer_statements.php">
    <table width="502">
    <tr>   
       <td class="rightalign">Customer Account:</td>
        <td>
          <select name="cacc" class="btn2">
               <option value="">--Select Account--</option>
               <?php 
			   		$queryacc = mysql_query("SELECT * FROM customers ORDER BY customer_name");
					while($customer = mysql_fetch_array($queryacc)){
						echo "<option value='{$customer["customer_code"]}'>{$customer["customer_name"]} " . $customer['customer_code'] . "</option>";
						}
			   
                 
			 ?>
              </select></td>
        </tr>
      <tr>
      <td width="270" height="44" class="rightalign">From:</td>
        <td width="220"><input type="text" name="fromdate" id="datepicker" /> To: </td>
           <td><input type="text" name="todate" id="datepicker2" /></td>
     </tr>
           <tr> 
          <td height="44" class="rightalign">Balance B/F:</td>
          <td><select name="balancebfd" class="btn3">
               <option value="">--Select Option--</option>
                <option value="1">YES</option>
                   <option value="0">NO</option>
        </select></td>
        <td><input type="submit" class="btn" name="post" value="Generate" /></td>
            
           </tr>
       </table>
                        
    </form>
    
      <div id="results">
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
		 $todate = mysql_real_escape_string("$y-$m-$d ");

	//ACTIONS TO SEARCH DB FOR SELECTED RESULTS
	if($balancebfd == 0){
		echo "
		<div id='printer'>
		    <a href='print_account_statement.php?c_acc=".urlencode($c_account)."&&from=".urlencode($fromdate)."&&to=".urlencode($todate)."&&bfd=".urlencode($balancebfd)."' target='_blank' >Printer Friendly Version</a></div><br />
		<div id ='tableheading'><hr /> ";
	$queryacc2 = mysql_query("SELECT * FROM customers WHERE customer_code = '{$c_account}' ");
		$result = mysql_fetch_array($queryacc2);
			echo "<strong> {$result['customer_code']} - ". $result["customer_name"] ."</strong><br />
			 Account Statement <br /> From <strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='800'>
        <tr class='tablehead'>
          <td class='celltb' width='81' height='40'>Date</td>
          <td class='celltb' width='98'>Ref</td>
          <td class='celltb' width='101'>Account Name</td>
		   <td class='celltb' width='65'>Pdt</td>
          <td class='celltb' width='127'>Vehicle No.</td>
          <td class='celltb' width='57'>Litres</td>
          <td class='celltb' width='95'>Debit</td>
		  <td class='celltb' width='95'>Credit</td>
		  <td class='celltb' width='95'>Balance</td>
         
        </tr>";
  //what we need to arrange
		$bal = 0;
		$ttltrs = 0;
		$ttdbt = 0;
		$ttcdt = 0;
		//1 Select account......... the account exists since its selected from the drop down menu so no need to verify
		//3 select invoices................ 
		/*BACKUP SELECT STATEMENT */
		
		$invoice_data = mysql_query("
		SELECT *
		FROM display_data
		WHERE customer_code = '{$c_account}'
		AND date BETWEEN '{$fromdate}' AND '{$todate}'  
		ORDER BY date ASC"); 
		
		
		while($row = mysql_fetch_array($invoice_data)){
		//4 display the results for the invoice
		if($row['acc_type'] == 'D'){
		echo "<tr>
          <td class='celltb' width='81' height='20'>". date('d/m/Y', strtotime($row["date"]))."</td>
          <td class='center' width='98'>".$row['ref']."</td>
          <td class='celltb' width='101'>{$row["customer_name"]}</td>
		   <td class='celltb' width='65'>{$row["product_code"]}</td>
          <td class='celltb' width='70'>{$row["vehicle_no"]}</td>
          <td class='center' width='57'>{$row["ltrs"]}</td>
          <td class='right' width='95'>".number_format($row["debit"])."</td>
		  <td class='right' width='95'>-</td>";
		  
		  //4 calculate the total Debit less the Credit...........
		  $bal = $bal + ($row['debit']);
		  //5 Summations for the last total row.......
		  $ttltrs = $ttltrs + $row['ltrs'];
		  $ttdbt = $ttdbt + $row['debit'];
		  echo"
		  <td class='right' width='95'>". number_format($bal) ."</td>";
		  echo"
        </tr>";
		//display the Credit Payments
		}elseif($row['acc_type'] == 'C'){
			echo "<tr class='payment'>
          <td class='celltb' width='81' height='20'>". date('d/m/Y', strtotime($row["date"]))."</td>
          <td class='center' width='98'>".$row['ref']."</td>
          <td class='celltb' width='101'>{$row["vehicle_no"]}</td>
          <td class='celltb' width='65'></td>
		   <td class='celltb' width='65'></td>
          <td class='center' width='57'>{$row["ltrs"]}</td>
          <td class='right' width='95'></td>
		  <td class='right' width='95'>".number_format(-1*($row["credit"]))."</td>";
		  
		  $bal = $bal - $row['credit'];
		  $ttcdt = $ttcdt + $row['credit'];
		  echo"
		  <td class='right' width='95'>". number_format($bal) ."</td>";
		  echo"
        </tr>";
		  
			}
		}
		echo "             
        <tr class='tablehead'>
          <td class='celltb' width='73' height='30'>TOTAL</td>
          <td class='center' width='83'></td>
          <td class='celltb' width='140'></td>
		   <td class='celltb' width='65'></td>
		   <td class='celltb' width='65'></td>
          <td class='center' width='63'>".$ttltrs."</td>
          <td class='right' width='86'>".number_format($ttdbt)."</td>
		  <td class='right' width='89'>".number_format($ttcdt)."</td>
		  <td class='right' width='100'>".number_format($bal)."</td>";
  
  }//end of balance bfd = 0
  
  elseif($balancebfd == 1){
		echo "
		<div id='printer'>
		    <a href='print_account_statement.php?c_acc=".urlencode($c_account)."&&from=".urlencode($fromdate)."&&to=".urlencode($todate)."&&bfd=".urlencode($balancebfd)."' target='_blank' >Printer Friendly Version</a></div><br />
		<div id ='tableheading'><hr /> ";
		$queryacc2 = mysql_query("SELECT * FROM customers WHERE customer_code = '{$c_account}' ");
		$result = mysql_fetch_array($queryacc2);
			echo "<strong> {$result['customer_code']} - ". $result["customer_name"] ."</strong><br />
			 Account Statement <br /> From <strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='800'>
        <tr class='tablehead'>
          <td class='celltb' width='81' height='40'>Date</td>
          <td class='celltb' width='98'>Ref</td>
          <td class='celltb' width='101'>Account Name</td>
		   <td class='celltb' width='65'>Pdt</td>
          <td class='celltb' width='127'>Vehicle No.</td>
          <td class='celltb' width='57'>Litres</td>
          <td class='celltb' width='95'>Debit</td>
		  <td class='celltb' width='95'>Credit</td>
		  <td class='celltb' width='95'>Balance</td>
         
        </tr>";
  
		
		//1 Select account......... the account exists since its selected from the drop down menu so no need to verify
		//3 select invoices................ 
		/*BACKUP SELECT STATEMENT */
		
		 
		
		$bfd_data = mysql_query("
		SELECT SUM(debit) AS totdbt, SUM(credit) AS totcdt
		FROM display_data
		WHERE customer_code = '{$c_account}'
		AND date < '{$fromdate}'");
		
		//Display the beginning Row of the Balance brought foward
		$bfd_info = mysql_fetch_array($bfd_data);
		$bal_bfd_include = $bfd_info["totdbt"] - $bfd_info["totcdt"];
		echo "             
        <tr class='bfd'>
          <td class='celltb' width='73' height='30'>". date('d/m/Y', strtotime($fromdate))."</td>
          <td class='center' width='83'>BFD</td>
          <td class='celltb' width='140'><i>Balance B/FD</i></td>
          <td class='celltb' width='70'>-</td>
          <td class='center' width='63'>-</td>
		  <td class='center' width='63'>-</td>
          <td class='right' width='86'>".number_format($bfd_info["totdbt"])."</td>
		  <td class='right' width='89'>".number_format($bfd_info["totcdt"])."</td>
		  <td class='right' width='100'>".number_format($bal_bfd_include)."</td>
		  </tr>";	
		
		//current standings of the balances
		$bal = $bal_bfd_include;
		$ttltrs = 0;
		$ttdbt = $bfd_info["totdbt"];
		$ttcdt = $bfd_info["totcdt"];
		
		$invoice_data = mysql_query("
		SELECT *
		FROM display_data
		WHERE customer_code = '{$c_account}'
		AND date BETWEEN '{$fromdate}' AND '{$todate}'  
		ORDER BY date ASC");
		
		while($row = mysql_fetch_array($invoice_data)){
		//4 display the results for the invoice
		if($row['acc_type'] == 'D'){
			//obtaining the balance bfd
		 
		
		
		echo "<tr>
          <td class='celltb' width='73' height='20'>". date('d/m/Y', strtotime($row["date"]))."</td>
          <td class='center' width='83'>".$row['ref']."</td>
          <td class='celltb' width='140'>{$row["customer_name"]}</td>
		  <td class='celltb' width='70'>{$row["product_code"]}</td>
          <td class='celltb' width='63'>{$row["vehicle_no"]}</td>
          <td class='center' width='63'>{$row["ltrs"]}</td>
          <td class='right' width='86'>".number_format($row["debit"])."</td>
		  <td class='right' width='89'>-</td>";
		  
		  //4 calculate the total Debit less the Credit...........
		  $bal = $bal + $row['debit'];
		  //5 Summations for the last total row.......
		  $ttltrs = $ttltrs + $row['ltrs'];
		  $ttdbt = $ttdbt + $row['debit'];
		  echo"
		  <td class='right' width='100'>". number_format($bal) ."</td>";
		  echo"</tr>";
		  
		}elseif($row['acc_type'] == 'C'){
			echo 
			"<tr class='payment'>
				  <td class='celltb' width='81' height='20'>". date('d/m/Y', strtotime($row["date"]))."</td>
				  <td class='center' width='98'>".$row['ref']."</td>
				 <td class='celltb' width='101'>{$row["vehicle_no"]}</td>
				  <td class='celltb' width='70'>-</td>
				  <td class='celltb' width='70'>-</td>
				  <td class='center' width='57'>{$row["ltrs"]}</td>
				  <td class='right' width='95'></td>
				  <td class='right' width='95'>".number_format(-1*($row["credit"]))."</td>";
				  
				  $bal = $bal - $row['credit'];
				  $ttcdt = $ttcdt + $row['credit'];
				  echo"<td class='right' width='95'>". number_format($bal) ."</td>
         </tr>";
		  
			}
		}
		//totals row for the colums brought witihin the internal section
		echo "             
        <tr class='tablehead'>
          <td class='celltb' width='73' height='30'>TOTAL</td>
          <td class='center' width='83'></td>
          <td class='celltb' width='140'></td>
          <td class='celltb' width='70'></td>
		  <td class='celltb' width='65'></td>
          <td class='center' width='63'>".$ttltrs."</td>
          <td class='right' width='86'>".number_format($ttdbt)."</td>
		  <td class='right' width='89'>".number_format($ttcdt)."</td>
		  <td class='right' width='100'>".number_format($bal)."</td></tr>";
  		  
  }//end of while
		   
        echo"
            </table>";
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
