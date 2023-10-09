<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>NEW MBARARA BULK SALES SUMMARY</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" media="all"/>
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="Shortcut icon" href="../img/newfav.ico" />
</head>

<body>

<?php
// check if the 'date' variable is set in URL, and check that it is valid
if(isset($_GET['sel_date'])){
	$date= mysql_prep($_GET['sel_date']);
			// begining of html after post has been set	
		echo "<hr />
    <div class='view_header'><br />
      <strong>BULK SALES ANALYSIS as of ";
	  print date('d/M/Y', strtotime($date));
	  echo "
	  		</strong>
		    </div><br />  
    <div id='view_container_left'>
    <div class='view_header'>
	DAY & NIGHT SHIFTS<hr /></div>
    <div class='view_headinfo'>
    <table width='650' height='300'>
     <tr class='tablehead'>
      <td width='25' class='celltb'>PUMP</td> 
      <td width='63' class='celltb'>PRODUCT</td> 
       <td width='80' class='celltb'>CLOSING</td>
        <td width='71' class='celltb'>OPENING</td> 
		<td width='56' class='celltb'>LITRES</td>
         <td width='56' class='celltb'>RETURN</td>
       <td width='65' class='celltb'>SALES</td>
      </tr>";
	  $result = mysql_query("SELECT * FROM meter_transaction WHERE meter_date = '{$date}' ORDER BY pump_no, product_code DESC");
	  while($printval = mysql_fetch_array($result)){
		  if($printval['product_code']=='PMS'){
      echo "<tr>
      <td >".$printval['pump_no']."</td> 
       <td>".$printval['product_code']."</td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		  <td>0.00</td>
            <td class='rightalign'>";
			$code = $printval['product_code'];
			  $queryp = mysql_query("SELECT * FROM pump_price WHERE product_code = '{$code}' ORDER BY date_of_change DESC");//provision to pull the latest dates only
			  $pdt_price= mysql_fetch_array($queryp);
			  if( $code ==  $pdt_price['product_code']){
				  $meter_id = $printval['meter_transaction_id'];
				  $amount = (($printval['total_sales'])*($pdt_price['current_price']));
				  //insert value into the database for further reference
				  $insertq="UPDATE meter_transaction SET sales_amount = '{$amount}' WHERE meter_transaction_id = {$meter_id}";
				  if(mysql_query($insertq)){
					  echo number_format($amount);
				  }else{
					  	echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
					  }
			  }
			echo "</td></tr>";
	  		}elseif($printval['product_code']=='AGO'){
	  echo "<tr>
      <td >".$printval['pump_no']."</td> 
       <td>".$printval['product_code']."</td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		   <td>0.00</td>
            <td class='rightalign'>";
			$code = $printval['product_code'];
			  $queryp = mysql_query("SELECT * FROM pump_price WHERE product_code = '{$code}' ORDER BY date_of_change DESC");
			  $pdt_price= mysql_fetch_array($queryp);
			  if( $code ==  $pdt_price['product_code']){
				  $meter_id = $printval['meter_transaction_id'];
				  $amount = (($printval['total_sales'])*($pdt_price['current_price']));
				  //insert value into the database for further reference
				 $insertq="UPDATE meter_transaction SET sales_amount = '{$amount}' WHERE meter_transaction_id = {$meter_id}";
				  if(mysql_query($insertq)){
					  echo number_format($amount);
				  }else{
					  	echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
					  }
			  }
			echo "</td></tr>";
	  	}elseif($printval['product_code']=='BIK'){
	  echo "<tr>
      <td >".$printval['pump_no']."</td> 
       <td>".$printval['product_code']."</td> 
        <td >".$printval['closing_meter']."</td>
         <td>".$printval['opening_meter']."</td> 
          <td>".$printval['total_sales']."</td>
		   <td>0.00</td>
            <td class='rightalign'>";
			$code = $printval['product_code'];
			  $queryp = mysql_query("SELECT * FROM pump_price WHERE product_code = '{$code}' ORDER BY date_of_change DESC");
			  $pdt_price= mysql_fetch_array($queryp);
			  if( $code ==  $pdt_price['product_code']){
				  $meter_id = $printval['meter_transaction_id'];
				  $amount = (($printval['total_sales'])*($pdt_price['current_price']));
				  //insert value into the database for further reference
				 $insertq="UPDATE meter_transaction SET sales_amount = '{$amount}' WHERE meter_transaction_id = {$meter_id}";
				  if(mysql_query($insertq)){
					  echo number_format($amount);
				  }else{
					  	echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
					  }
			  }
			echo "</td></tr>";
	  	}
		
	  //end of while 
	  }
	  echo "
     </table>
          <br />
         </div>
       </div><div class='view_header'><strong>DAILY SUMMARY</strong><hr /></div>
     <div id='view_container_right'>
     SALES BY TANK
     <table width='320' id='lower'>
            <tr class='tablehead'>
             <td class='total'>SALES</td>
             <td class='total'>B/FD</td>
             <td class='total'>CUMMULATIVE</td>
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
		</table>
      </div>
    <div id='view_container_left'>
	LITRES BY TANK
    <div id='lower'>
    <table width='350' id='lower'>
            <tr class='tablehead'>
             <td class='total'>TANKS</td>
             <td class='total'>LITRES</td>
			 <td class='total'>B/FD</td>
             <td class='total'>CUMMULATIVE</td>
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
        </div>
    </div><br /><br />";
	
		//Getting & displaying the Balance sheet summary
	echo "<div class='view_header'><strong>CASH SALES/CREDIT SUMMARY</strong></div>";
	
	$querycash = mysql_query("SELECT * FROM cash_transaction WHERE date_of_cash = '{$date}'");
		 while($printcash = mysql_fetch_assoc($querycash)){
		 echo "<font size='-1'>   
		 <div id='lower'>
	  <table width='700' id='lower'>
		<tr >
			<td class = 'total'></td>
			 <td class = 'total'>CASH SALES</td>
			  <td class = 'total'>CREDIT SALES</td>
			   <td class = 'total'>TOTAL SALES</td>
				<td class = 'total'>IN LESS/EXC OF</td>
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
			<td>";
			$ttpymt = ($printcash['payment_cash_1'] + $printcash['payment_cash_2']); 
			
			echo number_format($ttpymt);
			echo "</td>
				<td>0</td>
				 <td >0</td>
				 <td >0</td>
		</tr>
		<tr>
		<td class = 'total'><strong>TOTAL</strong></td>
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
						echo "<font color='#FF0000' size='-1'> No Information for the selected Date <br/></font>";
						}
						
	?>
<?php $daten = date("Y-m-d");
			echo "<br />Print Date " . date('d/m/Y', strtotime($daten));  ?>
</body>
</html>