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
<title>Meter Entries Validation </title>
<link href="../css/print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css/print.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="Shortcut icon" href="../img/newfav.ico" />
</head>

<body>
<table  class="displaytb" width="745">
        <tr class="tablehead">
          <td class="celltb" width="81" height="40">Date</td>
          <td class="celltb" width="98">Opening Meter</td>
          <td class="celltb" width="101">Changing Meter</td>
          <td class="celltb" width="127">Closing Meter</td>
          <td class="celltb" width="43">Pump</td>
          <td class="celltb" width="57">Product</td>
          <td class="celltb" width="57">Litres</td>
          <td class="celltb" width="95">Amount</td>
  </tr>
     <?php
	 
			$result = mysql_query("SELECT * FROM meter_transaction WHERE date_of_entry = CURDATE() ORDER BY meter_date");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' height=\"35\">". date('d/M/Y', strtotime($row['meter_date'])) ."</td>
			  <td class='celltb'>{$row["opening_meter"]}</td>
			  <td class='celltb'><font size='-2'>";
			  if($row['changing_meter1'] != 00000000000.00){
				  echo $row["changing_meter1"];
				  }elseif($row["changing_meter2"] != 00000000000.00 ){
					  echo $row["changing_meter2"];
					  }elseif($row["changing_meter3"] != 00000000000.00){
						  echo $row["changing_meter3"];
						  }else{
							  echo "Meter Not Changed</font>";
							  }
						  
			  echo "</td>
			  <td class='celltb'>{$row["closing_meter"]}</td>
			  <td class='celltb'>{$row["pump_no"]}</td>
			  <td class='celltb'>{$row["product_code"]}</td>
			  <td class='celltb'>{$row["total_sales"]}</td>
			  <td class='celltb'>";
			  $code = $row['product_code'];
			  $queryp = mysql_query("SELECT * FROM pump_price WHERE product_code = '{$code}'");//provision to pull the latest dates only
			  $pdt_price= mysql_fetch_array($queryp);
			  if($row['product_code'] ==  $pdt_price['product_code']){
				  $amount = (($row['total_sales'])*($pdt_price['current_price']));
				  echo $amount;
			  }
			  echo "</td>
			</tr>
		";
		  }
			?>
</table>
<?php $daten = date("Y-m-d");
			echo "Print Date " . date('d/m/Y', strtotime($daten));  ?>
</body>
</html>