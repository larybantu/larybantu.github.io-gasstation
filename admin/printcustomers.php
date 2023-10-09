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
<title>Customer Accounts</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" media="print" />
<link href="../css/print.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="Shortcut icon" href="../img/newfav.ico" />
</head>

<body>
<table  class="displaytb" width="591">
        <tr class="tablehead">
          <td class="celltb" width="50" height="40">No.</td>
          <td class="celltb" width="414">Account Name</td>
          <td class="celltb" width="138">Customer Code</td>
  </tr>
          <?php
	 
			$result = mysql_query("SELECT * FROM customers ORDER BY customer_name");
			$counter2 = 1;
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			<td class=\"rightalign2\" width=\"37\" height=\"10\">".$counter2."</td>
			  <td class='celltb2'>{$row["customer_name"]}</td>
			  <td class='celltb2'>{$row["customer_code"]}</td>
			</tr>
		";
		$counter2++;
		  }
			?>
</table>
<br />
<?php $daten = date("Y-m-d");
			echo "Print Date " . date('d/m/Y', strtotime($daten));  ?>
</body>
</html>