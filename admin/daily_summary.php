<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Meter Readings</title>
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
    <div class="menuhead">Daily Summary<br />
    </div>
    <div id="form">    
    <table width="486">
     <tr><form method="post" action="daily_summary.php">
      <td height="39">Select Date: </td>
       <td> <input type="text" name="date" id="datepicker"/></td>
        <td><input type="submit" class="btn" name="post" value="Generate" /></td>
         </form>
      </tr>
    </table>
    <hr />
  </div>
  <div id="summarycontainer">
  <?php 
      if ($_POST){
		global $m,$d,$y;
		if($_POST['date'] != NULL){
			list($m,$d,$y) = explode("/",$_POST['date']);
			/*1*/ $date = mysql_real_escape_string("$y-$m-$d ");
			
			// begining of html after post has been set	
		echo "
	<div id='printer'><a href='printsummary.php?sel_date=".$date."' target='_blank' >Printer Friendly Version</a></div>
    <div class='view_header'>
      <strong>BULK SALES ANALYSIS</strong> as of ";
	  print date('d/M/Y', strtotime($date));
	  echo "	
		    </div>  
    <div id='view_container_left'>
    <div class='view_shift'>
	DAY & NIGHT SHIFTS</div>
    <div class='view_headinfo'><font size='-1'>
    <table width='747' height='300'>
     <tr class='tablehead'>
      <td width='45' class='rightalign'>PUMP</td> 
      <td width='63'>PRODUCT</td> 
       <td width='80'>CLOSING</td>
        <td width='71'>OPENING</td> 
		<td width='56'>LITRES</td>
         <td width='56'>RETURN</td>
       <td width='65'>SALES</td>
      </tr>";
	  $result = mysql_query("SELECT * FROM meter_transaction WHERE meter_date = '{$date}' ORDER BY pump_no ASC,product_code DESC");
	  while($printval = mysql_fetch_assoc($result)){
		  if($printval['product_code']=='PMS'){
      echo "<tr>
      <td class='rightalign' ><font size='-1'>".$printval['pump_no']."</font></td> 
       <td><font size='-1'>".$printval['product_code']."</font></td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		  <td>".$printval['rtt']."</td>
            <td class='rightalign'>".number_format($printval['sales_amount'])."</td></tr>";
	  		}elseif($printval['product_code']=='AGO'){
	  echo "<tr>
      <td class='rightalign' ><font size='-1'>".$printval['pump_no']."</font></td> 
       <td><font size='-1'>".$printval['product_code']."</font></td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		   <td>".$printval['rtt']."</td>
            <td class='rightalign'>".number_format($printval['sales_amount'])."</td></tr>";
	  	}elseif($printval['product_code']=='BIK'){
	  echo "<tr>
      <td class='rightalign' ><font size='-1'>".$printval['pump_no']."</font></td> 
       <td><font size='-1'>".$printval['product_code']."</font></td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		   <td>".$printval['rtt']."</td>
            <td class='rightalign'>".number_format($printval['sales_amount'])."</td></tr>";
	  	}
		
	  //end of while 
	  }
	  echo "
     </table>
          </font>
         </div>
       </div><div id='summary'><font size='-1'>DAILY SUMMARY</font></div>
     <div id='view_container_right'>
     <font size='-1'>SALES BY TANK
     <table width='400' id='lower'>
            <tr class='tablehead'>
             <td>SALES</td>
             <td>B/FD</td>
             <td>CUMMULATIVE</td>
            </tr>";
			$tank = 1;
			$querysum1p = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 2 AND product_code ='PMS'");

			while($tank<=4 && $printsum = mysql_fetch_array($querysum1p)){
			//tank 1
			echo "
             <tr>
               <td>".number_format($printsum['SUM(sales_amount)'])."</td><td> B/FD </td><td>".number_format($printsum['SUM(sales_amount)'])."</td>
			   </tr>";
			   $tank++;
			   //tank 2
			   echo"
                <tr>";
			   $querysum2p = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no > 2 AND product_code ='PMS'");
			   $printsum2 = mysql_fetch_array($querysum2p);
               echo "<td>".number_format($printsum2['SUM(sales_amount)'])."</td><td> B/FD </td><td>".number_format($printsum2['SUM(sales_amount)'])."</td></tr>";
			   $tank++;
			   //tank3
			   echo"
                <tr>";
			   $querysum1a = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 2 AND product_code ='AGO'");
			   $printsum3 = mysql_fetch_array($querysum1a);
               echo "<td>".number_format($printsum3['SUM(sales_amount)'])."</td><td> B/FD </td><td>".number_format($printsum3['SUM(sales_amount)'])."</td></tr>";
			   $tank++;
			   //tank 4
			   echo"
                <tr>";
			   $querysum2a = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no > 2 AND product_code ='AGO'");
			   $printsum4 = mysql_fetch_array($querysum2a);
               echo "<td>".number_format($printsum4['SUM(sales_amount)'])."</td><td> B/FD </td><td>".number_format($printsum4['SUM(sales_amount)'])."</td></tr>";
			   $tank++;
			    //tank 5
			   echo"
                <tr>";
			   $querysumb = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND product_code ='BIK'");
			   $printsumb = mysql_fetch_array($querysumb);
               echo "<td>".number_format($printsumb['SUM(sales_amount)'])."</td> <td>
             B/FD
             </td><td>".number_format($printsumb['SUM(sales_amount)'])."</td></tr>";
			   $tank++;
			   //TOTAL
			   echo"
                <tr>";
			   $querysumf = mysql_query("SELECT meter_date, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}'");
			   $printsumf = mysql_fetch_array($querysumf);
               echo "<td class='total'>".number_format($printsumf['SUM(sales_amount)'])."</td> <td class='total'>
             tt-B/FD
             </td><td class='total'>".number_format($printsumf['SUM(sales_amount)'])."</td></tr>";
			//end of while i
			}
        echo "
		</table></font>
      </div>
    <div id='view_container_left'>
	<font size='-1'>LITRES BY TANK
    <div id='lower'>
    <table width='411' id='lower'>
            <tr class='tablehead'>
             <td>
             TANKS
             </td>
             <td>
             LITRES</td>
			 <td>
             B/FD
             </td>
             <td>
             CUMMULATIVE
             </td>
             </tr>";
			$tank = 1;
			$querysum1p = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 2 AND product_code ='PMS'");

			while($tank<=4 && $printsum = mysql_fetch_array($querysum1p)){
			//tank 1
			echo "
             <tr>
              <td> ". $tank ." PMS </td>
               <td>".$printsum['SUM(total_sales)']."</td> <td>
             B/FD
             </td><td>".$printsum['SUM(total_sales)']."</td></tr>";
			   $tank++;
			   //tank 2
			   echo"
                <tr>
              <td> ". $tank ." PMS </td>";
			   $querysum2p = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no > 2 AND product_code ='PMS'");
			   $printsum2 = mysql_fetch_array($querysum2p);
               echo "<td>".$printsum2['SUM(total_sales)']."</td> <td>
             B/FD
             </td><td>".$printsum2['SUM(total_sales)']."</td></tr>";
			   $tank++;
			   //tank3
			   echo"
                <tr>
              <td> ". $tank ." AGO </td>";
			   $querysum1a = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 2 AND product_code ='AGO'");
			   $printsum3 = mysql_fetch_array($querysum1a);
               echo "<td>".$printsum3['SUM(total_sales)']."</td> <td>
             B/FD
             </td><td>".$printsum3['SUM(total_sales)']."</td></tr>";
			   $tank++;
			   //tank 4
			   echo"
                <tr>
              <td> ". $tank ." AGO </td>";
			   $querysum2a = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no > 2 AND product_code ='AGO'");
			   $printsum4 = mysql_fetch_array($querysum2a);
               echo "<td>".$printsum4['SUM(total_sales)']."</td> <td>
             B/FD
             </td><td>".$printsum4['SUM(total_sales)']."</td></tr>";
			   $tank++;
			   //tank 5
			   echo"
                <tr>
              <td> ". $tank ." BIK </td>";
			   $querysumb = mysql_query("SELECT product_code, pump_no, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no > 2 AND product_code ='BIK'");
			   $printsumb = mysql_fetch_array($querysumb);
               echo "<td>".$printsumb['SUM(total_sales)']."</td> <td>
             B/FD
             </td><td>".$printsumb['SUM(total_sales)']."</td></tr>";
			   $tank++;
			   //TOTAL
			   echo"
                <tr>
              <td class='total'> <strong> TOTAL </strong></td>";
			   $querysumf = mysql_query("SELECT meter_date, SUM(sales_amount), SUM(total_sales) FROM meter_transaction WHERE meter_date = '{$date}'");
			   $printsumf = mysql_fetch_array($querysumf);
               echo "<td class='total'>".$printsumf['SUM(total_sales)']."</td> <td class='total'>
             tt-B/FD
             </td><td class='total'>".$printsumf['SUM(total_sales)']."</td></tr>";
			//end of while i
			
			}
        echo "
           </table>
        </div></font>
    </div>";
	//Getting & displaying the Balance sheet summary
	echo "<div id='summary'><font size='-1'>SALES/CREDIT SUMMARY</font></div>";
	
	$querycash = mysql_query("SELECT * FROM cash_transaction WHERE date_of_cash = '{$date}'");
		 while($printcash = mysql_fetch_assoc($querycash)){
		 echo "<font size='-1'>   
		 <div id='lower'>
	  <table width='747' id='lower'>
		<tr class='tablehead'>
			<td></td>
			 <td>CASH SALES</td>
			  <td>CREDIT SALES</td>
			   <td>TOTAL SALES</td>
				<td>IN LESS/EXC OF</td>
		</tr>
		<tr>
	   <td class='tablehead'>PMS</td>
		 <td>".number_format($printcash['pms_cash'])."</td>
		 <td>";
			//get invoice payments
			$queryinv = mysql_query("SELECT product_code, SUM(amount) FROM invoice_transaction WHERE date_of_invoice = '{$date}' AND product_code = 'PMS'");
			while($printinvoice = mysql_fetch_assoc($queryinv)){
			echo number_format($printinvoice['SUM(amount)']);
			echo "</td>
		  <td>";
		 $querysales1 = mysql_query("SELECT product_code, pump_no, SUM(sales_amount) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 4 AND product_code ='PMS'");
		  while($printsales1 = mysql_fetch_array($querysales1)){
			  echo number_format($printsales1['SUM(sales_amount)']);
			  echo "</td>
				<td>";
				$shortage = (($printsales1['SUM(sales_amount)'] ) - ($printcash['pms_cash'] + $printinvoice['SUM(amount)']) );
				if($shortage > 0){
					echo  "<font color='#990000'>-".$shortage."</font>";
					}elseif($shortage == 0){
						echo "<font color='#000000'>".$shortage."</font>";
						}elseif($shortage < 0){
							echo "<font color='#009933'>+".$shortage."</font>";
							}
				echo "</td>
			</tr>
			<tr>";
			}}
		echo"
			<td class='tablehead'>AGO</td>
			 <td>".number_format($printcash['ago_cash'])."</td>
			  <td>";
			//get invoice payments
			$queryinv = mysql_query("SELECT product_code, SUM(amount) FROM invoice_transaction WHERE date_of_invoice = '{$date}' AND product_code = 'AGO'");
			while($printinvoice = mysql_fetch_assoc($queryinv)){
			echo number_format($printinvoice['SUM(amount)']);
			echo "</td>
			   <td>";
		 $querysales1 = mysql_query("SELECT product_code, pump_no, SUM(sales_amount) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no <= 4 AND product_code ='AGO'");
		  while($printsales1 = mysql_fetch_array($querysales1)){
			  echo number_format($printsales1['SUM(sales_amount)']);
			  echo "</td>
				<td>";
				$shortage = (($printsales1['SUM(sales_amount)'] ) - ($printcash['ago_cash'] + $printinvoice['SUM(amount)']));
				if($shortage > 0){
					echo  "<font color='#990000'>-".$shortage."</font>";
					}elseif($shortage == 0){
						echo "<font color='#000000'>".$shortage."</font>";
						}elseif($shortage < 0){
							echo "<font color='#009933'>+".$shortage."</font>";
							}
				echo "</td>
		</tr>";
			}}
		echo"
		<tr>
			<td class='tablehead'>BIK</td>
			<td>".number_format($printcash['bik_cash'])."</td>
			  <td>";
			//get invoice payments
			$queryinv = mysql_query("SELECT product_code, SUM(amount) FROM invoice_transaction WHERE date_of_invoice = '{$date}' AND product_code = 'BIK'");
			while($printinvoice = mysql_fetch_assoc($queryinv)){
			echo number_format($printinvoice['SUM(amount)']);
			echo "</td>
			   <td>";
		 $querysales1 = mysql_query("SELECT product_code, pump_no, SUM(sales_amount) FROM meter_transaction WHERE meter_date = '{$date}' AND pump_no = 5 AND product_code ='BIK'");
		  while($printsales1 = mysql_fetch_array($querysales1)){
			  echo number_format($printsales1['SUM(sales_amount)']);
			  echo "</td>
				<td>";
				$shortage = (($printsales1['SUM(sales_amount)'] ) - ($printcash['bik_cash'] + $printinvoice['SUM(amount)']) );
				if($shortage > 0){
					echo  "<font color='#990000'>-".$shortage."</font>";
					}elseif($shortage == 0){
						echo "<font color='#000000'>".$shortage."</font>";
						}elseif($shortage < 0){
							echo "<font color='#009933'>+".$shortage."</font>";
							}
				echo "</td>
		</tr>";
			}}
		echo"
		<tr>
			<td class='tablehead'>PAYMENTS</td>
			<td class='total'>";
			$ttpymt = ($printcash['payment_cash_1'] + $printcash['payment_cash_2']); 
			
			echo number_format($ttpymt);
			echo "</td>
				<td class='total'>0</td>
				 <td class='total'>0</td>
				 <td class='total'>0</td>
		</tr>
		<tr>
		<td class='tablehead'><strong>TOTAL</strong></td>
		  <td class = 'total' >".number_format($printcash['total_cash'])."</td>
			<td class = 'total'>";
			//get invoice payments
			$queryinv = mysql_query("SELECT product_code, SUM(amount) FROM invoice_transaction WHERE date_of_invoice = '{$date}'");
			while($printinvoice = mysql_fetch_assoc($queryinv)){
			echo number_format($printinvoice['SUM(amount)']);
			echo "</td>
			  <td class = 'total'>";
		 $querysales1 = mysql_query("SELECT pump_no, SUM(sales_amount) FROM meter_transaction WHERE meter_date = '{$date}'");
		  while($printsales1 = mysql_fetch_array($querysales1)){
			  echo number_format($printsales1['SUM(sales_amount)']);
			  echo "</td>
				<td class = 'total'>";
				$shortage = (($printsales1['SUM(sales_amount)'] ) - (($printcash['total_cash'] - $ttpymt) + $printinvoice['SUM(amount)']));
				if($shortage > 0){
					echo  "<font color='#990000'>-".$shortage."</font>";
					}elseif($shortage == 0){
						echo "<font color='#000000'>".$shortage."</font>";
						}elseif($shortage < 0){
							echo "<font color='#009933'>+".(-1*$shortage)."</font>";
							}
				echo "</td>
		</tr>";
			}}
		echo"
	  </table></div></font>";
	  }
	   
			  }else{
						echo "<font color='#FF0000' size='-1'> Date field is Empty </font>";
						}
				
			  }else{
				  echo "<font color='#FF0000' size='-1'> No date selected </font>";
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
	//Close connection
	if (isset($connection)){
	mysql_close($connection);
	}
	
?>