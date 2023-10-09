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

<title>Edit A Cash Entry</title>
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
     <?php 
			$sel_cash = mysql_prep($_GET['cash_id']);
			$result=mysql_query("SELECT * FROM cash_transaction WHERE cash_transaction_id=$sel_cash");
			$editcash = mysql_fetch_array($result);
			$printdate = date('m/d/Y', strtotime($editcash['date_of_cash']));
 			$printpmscash = $editcash['pms_cash'];
			$printagocash = $editcash['ago_cash'];
			$printbikcash = $editcash['bik_cash'];
			$printpaymt1 = $editcash['payment_cash_1'];
			$printpaymt2 = $editcash['payment_cash_2'];
			$printcashier = $editcash['cashier_name'];
		?>
  <div id="display">
    <div class="menuhead"> Cash Entry</div>
    <form  id="formID" name="invoice" method="post" action="<?php echo "update_cash.php?cash_id=". urlencode($editcash['cash_transaction_id']) .""?>">
    <table width="708">
      <tr>
      <td width="124">Date:</td>
        <td width="179"><input type="text" name="date" id="datepicker" value="<?php echo "$printdate"?>"/> </td>
        <td></td>
       </tr>
      <tr>   
       <td>PMS Cash:</td>
        <td><input type="text" name="pms_cash" value=<?php echo "$printpmscash"?> /></td>
         <td>&nbsp;</td>
        </tr>
        <tr>  
          <td>AGO Cash:</td>
           <td><input type="text" name="ago_cash" value=<?php echo "$printagocash"?> /></td>
           <td></td>
        </tr>
        <tr> 
          <td>BIK Cash:</td>
           <td><input type="text" name="bik_cash" value=<?php echo "$printbikcash"?> /></td>
           <td></td>
           </tr>
         <tr>
         <td>Payment Cash 1:</td>
         <td><input type="text" name="payment_cash_1" value=<?php echo "$printpaymt1"?> /></td>
             <td>&nbsp;</td>
          </tr>
          <tr>
         <td>Payment Cash 2:</td>
         <td><input type="text" name="payment_cash_2" value=<?php echo "$printpaymt2"?> /></td>
         
              <td><input type="submit" class="btn" name="post" value="Update" /></td>
              
           <td width="157" class="del"><a href="<?php 
			 	echo "delete_cash_entry.php?cash_id=". urlencode($editcash['cash_transaction_id']) ."";
			 ?>" onclick="return confirm('Are you sure you want to delete this Meter Entry Transaction?');">Delete Entry</a></td>
        </tr>
        <tr>
         <td>Cashier:</td>
         <td width="228"><select name="cashier" class="btn">
              <option value="<?php echo "$printcashier"?>"><?php echo "$printcashier"?></option>
              <option value="MS.FLORENCE">MS. FLORENCE</option>
              <option value="JANE">JANE</option>
              <option value="RONALD">RONALD</option>
              <option value="HILLARY">HILLARY</option>
                      
       </select></td>             <td></td>
          </tr>
       </table>
                        
    </form>
    <div id="results">
    Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
	if (isset($_POST['post'])){
		if($_POST['cashier'] != NULL && 
		   $_POST['pms_cash'] != NULL && 
		   $_POST['date'] != NULL){
		global $m,$d,$y;
		 $pms_cash = trim(mysql_prep($_POST['pms_cash']));
		 $ago_cash = trim(mysql_prep($_POST['ago_cash']));
		 $bik_cash = trim(mysql_prep($_POST['bik_cash']));
		 $payment_cash_1 = trim(mysql_prep($_POST['payment_cash_1']));
		 $payment_cash_2 = trim(mysql_prep($_POST['payment_cash_2']));
		 $cashier = trim(mysql_prep($_POST['cashier']));
		 list($m,$d,$y) = explode("/",$_POST['date']);
		 $date = mysql_real_escape_string("$y-$m-$d ");
		 $total_cash= ($pms_cash + $ago_cash + $bik_cash + $payment_cash_1 + $payment_cash_2);
	//code for changing meter if set
	//validation of entries 

	//to eliminate duplicate entries	
	$query = "INSERT INTO cash_transaction
	(date_of_cash, cashier_name, pms_cash, ago_cash, bik_cash, payment_cash_1, payment_cash_2, date_of_entry, total_cash) VALUES 
	('{$date}',
	'{$cashier}', 
	{$pms_cash},
	 {$ago_cash}, 
	 {$bik_cash}, 
	 '{$payment_cash_1}', 
	 '{$payment_cash_2}',
	 CURDATE(), 
	 {$total_cash})";
		
		if(mysql_query($query)){
			
			 echo " <br /> <font size='-1' color=\"#438787\">Cash Entry Added Successfully</font>";
			}else{
				echo "<br /> <font size='-1' color='#FF0000'>Failed: " . mysql_error() . "</font>";
				}
	}else{ 
		echo "<br /><font color='#FF0000'>Important Field(s) above are Empty, Please Check again before Posting</font>"; 
		}
	}	?> <br />
    <?php 
	$querydate = mysql_query("SELECT MAX(date_of_cash) FROM cash_transaction LIMIT 1");
	$lastdate =  mysql_fetch_array($querydate);
	echo "<font color='#990000' size='-1'>Cash Date Last Entered: ". date('d/M/Y', strtotime($lastdate['MAX(date_of_cash)'])) . "&nbsp; &nbsp; </font>";
	?>
    <table  class="displaytb" width="747">
        <tr class="tablehead">
          <td class="celltb" width="56" height="38">Date</td>
          <td class="celltb" width="84">PMS</td>
          <td class="celltb" width="84">AGO</td>
          <td class="celltb" width="82">BIK</td>
          <td class="celltb" width="84">PYMNT A</td>
          <td class="celltb" width="90">PYMNT B</td>
          <td class="celltb" width="87">TOTAL</td>
          <td class="celltb" width="87">CASHIER</td>
          <td width="53">Action</td>
        </tr>
     <?php
			$result = mysql_query("SELECT * FROM cash_transaction WHERE date_of_entry = CURDATE()");
		while($row = mysql_fetch_array($result)){
			echo "<tr>
			  <td class='celltb' width='79' height='35'>". date('d/M/Y', strtotime($row['date_of_cash'])) ."</td>
			  <td class='celltb' width='87'>". number_format($row['pms_cash']) ."</td>
			  <td class='celltb' width='56'>". number_format($row['ago_cash']) ."</td>
			  <td class='celltb' width='196'>". number_format($row['bik_cash']) ."</td>
			  <td class='celltb' width='128'>". number_format($row['payment_cash_1']) ."</td>
			  <td class='celltb' width='128'>". number_format($row['payment_cash_2']) ."</td>
			  <td class='celltb' width='119'>". number_format($row['total_cash']) ."</td>
			   <td class='celltb' width='119'>{$row["cashier_name"]}</td>
			  <td width='30'><a href='editentrycash.php'>Edit</a></td>
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
<?php 
close();
?>
