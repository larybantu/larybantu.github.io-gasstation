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

<title>Meter Entry</title>
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
    <div class="menuhead">Meter Entry Transaction</div>
    
    <form  id="formID" name="invoice" method="post" action="meter_form.php">
     
    <table width="707">
      <tr>
      <td width="117">Date:</td>
        <td width="166"><input type="text" name="date" id="datepicker" /> </td>
        <td class="rightalign" width="164">Changing Meter1:</td>
       <td width="240"><input type="text" name="cm1" id="name" value="0"/></td>
       </tr>
       <tr> 
          <td height="36">Pump No:</td>
          <td><select name="pump_no" class="btn3">
               <option value="">--Select Pump--</option>
                <option value="1">Pump 1</option>
                   <option value="2">Pump 2</option>
                      <option value="3">Pump 3</option>
                      	<option value="4">Pump 4</option>
                        	<option value="5">Pump 5</option>
        </select></td>
            <td class="rightalign">Changing Meter2:</td>
           <td><input type="text" name="cm2" id="name" value="0"/></td>
        </tr>
       <tr> 
          <td>Product:</td>
          <td><select name="product" class="btn3">
               <option value="">--Choose Product--</option>
                 <option value="PMS">PMS</option>
                   <option value="AGO">AGO</option>
                      <option value="BIK">BIK</option>
             </select></td>
            <td class="rightalign">Changing Meter3:</td>
           <td><input type="text" name="cm3" id="name" value="0"/></td>
        </tr>
      <tr>   
       <td>Closing Meter:</td>
        <td>
          <input type="text" name="closing"/></td>
         <td class="rightalign">RTT:</td><td><input type="text" name="rtt" id="name" value="0.00"/></td>
        </tr>
        <tr>  
          <td>Opening Meter:</td>
           <td><input type="text" name="opening" /></td>
            <td>&nbsp;</td>
           <td>&nbsp;</td>
        </tr>
         <tr>
         <td>&nbsp;</td>
         <td></td>
             <td><input type="submit" class="btn" name="post" value="Post" /></td>
             <td class="rightalign"></td>
        </tr>
       </table>
                        
    </form>
    <div id="floatright">
	<?php
		 echo "
		 <font size='-1'>Current Pump Prices:<br />";
		 
			$queryp = mysql_query("SELECT current_price, product_code FROM pump_price WHERE product_code = 'PMS'" );
			$pms_price= mysql_fetch_array($queryp);
			echo "PMS: <font color='#006633'>".$pms_price['current_price']."</font><br />";	
			$querya = mysql_query("SELECT current_price, product_code FROM pump_price WHERE product_code = 'AGO'" );
			$ago_price= mysql_fetch_array($querya); 
			echo "AGO: <font color='#006633'>".$ago_price['current_price']."</font><br />"; 
			$queryb = mysql_query("SELECT current_price, product_code FROM pump_price WHERE product_code = 'BIK'" );
			$bik_price= mysql_fetch_array($queryb);
			echo "BIK: <font color='#006633'>".$bik_price['current_price']."</font><br /></font>";

			echo"<a href='change_price_admin.php'>Change pump prices</a><br />";		
		?>
        </div>
    
    <div id="results">
	<?php 
	$daten = date("Y-m-d");
	echo "<font size='-1'> <br />Today's Entries &nbsp; ". date('d/m/Y', strtotime($daten)) . "</font>";
	
	if (isset($_POST['post'])){
		if($_POST['pump_no'] != NULL && 
		   $_POST['product'] != NULL && 
		   $_POST['opening'] != NULL &&
		   $_POST['closing'] != NULL && 
		   $_POST['date'] != NULL){
		global $m,$d,$y;
		 $pump_no = trim(mysql_prep($_POST['pump_no']));
		 $product = trim(mysql_prep($_POST['product']));
		 $opening = trim(mysql_prep($_POST['opening']));
		 $cm1 = trim(mysql_prep($_POST['cm1']));
		 $cm2 = trim(mysql_prep($_POST['cm2']));
		 $rtt = trim(mysql_prep($_POST['rtt']));
		 $cm3 = trim(mysql_prep($_POST['cm3']));
		 $closing = trim(mysql_prep($_POST['closing']));
		 list($m,$d,$y) = explode("/",$_POST['date']);
		 $date = mysql_real_escape_string("$y-$m-$d ");
		 //code for changing meter if set
		 $litres_sold_new=0; 
		 $litres_sold_new_1=0; 
		 $litres_sold_new_2=0;
		 $litres_sold_old=0;
		 if($cm1!==0 && $cm2==0 && $cm3==0){
					 //one meter
					 $litres_sold_new = ($closing - $cm1);
					 $litres_sold_old = ($cm1 - $opening);
					 $litres_sold = $litres_sold_new + $litres_sold_old - $rtt;
				 	
					//no meter at all
					}elseif($cm1==0 && $cm2==0 && $cm3==0){
						$litres_sold =($closing - $opening - $rtt);
				
						//two meters
						}elseif($cm1!==0 && $cm2!==0 && $cm3==0){
							 $litres_sold_new = ($closing - $cm1);
							 $litres_sold_new_1 = ($cm1 - $cm2);
							 $litres_sold_old = ($cm2 - $opening);
							 $litres_sold = $litres_sold_new + $litres_sold_old - $rtt;
							 }
	
	
	//checking if entry already exists to eliminate duplicate entries
	$queryexist = mysql_query("SELECT * FROM meter_transaction WHERE pump_no = {$pump_no} AND product_code = '{$product}' AND opening_meter = '{$opening}' AND closing_meter = '{$closing}' AND meter_date = '{$date}'");
	
	if(mysql_fetch_row($queryexist) == 0){
		$query = "INSERT INTO meter_transaction(pump_no, product_code, opening_meter, changing_meter1, changing_meter2, changing_meter3, closing_meter, date_of_entry, meter_date, total_sales, rtt, newest_ltrs, second_newest_ltrs, third_newest_ltrs, old_ltrs) 
		VALUES ('{$pump_no}','{$product}','{$opening}','{$cm1}','{$cm2}','{$cm3}','{$closing}',CURDATE(),'{$date}','{$litres_sold}', '{$rtt}','{$litres_sold_new}','{$litres_sold_new_1}','{$litres_sold_new_2}','{$litres_sold_old}')";
		//handling failure to post
			if(mysql_query($query)){			
				echo " <br /> <font size='-1' color=\"#438787\">Meter Entry Added Successfully</font>";
				}else{
					echo "<br /> <font size='-1' color='#FF0000'>Failed: " . mysql_error() . "</font>";
				}
	}else{
		echo "<font color='#FF0000'><br /><strong>ENTRY ALREADY EXISTS <br /> Please Check your entries again</strong></font>";		
	}
	}else{ 
		echo "<br /><font color='#FF0000'>Some Field(s) above are Empty, Please Check again before Posting</font>"; 
		}
	}
	?>
    
     <div id="printer">
	 
	 <?php 
	$querydate = mysql_query("SELECT MAX(meter_date) FROM meter_transaction LIMIT 1");
	$lastdate =  mysql_fetch_array($querydate);
	echo "<font color='#990000' size='-1'>Meter Date Last Entered: ". date('d/M/Y', strtotime($lastdate['MAX(meter_date)'])) . "&nbsp; &nbsp; </font>";
	?>
    
    <a href="printmeters.php" target="_blank" >Printer Friendly Version</a></div><br /><br />
    <table  class="displaytb" width="747">
        <tr class="tablehead">
          <td class="celltb" width="81" height="40">Date</td>
          <td class="celltb" width="98">Opening Meter</td>
          <td class="celltb" width="101">Changing Meter</td>
          <td class="celltb" width="127">Closing Meter</td>
          <td class="celltb" width="43">Pump</td>
          <td class="celltb" width="57">Product</td>
          <td class="celltb" width="57">Litres</td>
          <td class="celltb" width="95">Amount</td>
          <td width="48">Action</td>
        </tr>
     <?php
	 
			$result = mysql_query("SELECT * FROM meter_transaction WHERE date_of_entry = CURDATE() ORDER BY meter_date DESC");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' height=\"35\">". date('d/M/Y', strtotime($row['meter_date'])) ."</td>
			  <td class='celltb'>{$row["opening_meter"]}</td>
			  <td class='celltb'>";
			  if($row['changing_meter1'] != 0 ){
				  echo $row["changing_meter1"];
				  }elseif($row["changing_meter2"] != 0){
					  echo $row["changing_meter2"];
					  echo $row["changing_meter1"];
					  }elseif($row["changing_meter3"] != 0){
						  echo $row["changing_meter3"];
						  echo $row["changing_meter2"];
					  echo $row["changing_meter1"];
						  }else{
							  echo "<font size='-2' color = '438787'>Meter Not Changed</font>";
							  }
						  
			  echo "</td>
			  <td class='celltb'>{$row["closing_meter"]}</td>
			  <td class='celltb'>{$row["pump_no"]}</td>
			  <td class='celltb'>{$row["product_code"]}</td>
			  <td class='celltb'>{$row["total_sales"]}</td>
			  <td class='celltb'>";
			  
			 //==================================================================================================================================  
			     
				 if($row["changing_meter1"]!=0){
				 
				 //if one changing meter is set
				$code = $row['product_code'];
				$queryp = mysql_query("SELECT product_code, current_price, old_price FROM pump_price WHERE product_code = '{$code}' LIMIT 1");
				$pdt_price= mysql_fetch_array($queryp);
				if($row['product_code'] == $pdt_price['product_code']){
					$meter_id = $row['meter_transaction_id'];
					$amount1 = (($row['old_ltrs'])*($pdt_price['old_price']));
					$amount2 =  (($row['newest_ltrs'])*($pdt_price['current_price']));
					$amount = $amount1 + $amount2;
					//insert value into the database for further reference
					$insertq="UPDATE meter_transaction SET sales_amount = '{$amount}' WHERE meter_transaction_id = {$meter_id}";
					if(mysql_query($insertq)){
						echo number_format($amount);
						}else{
							echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
					}
				}
				
				}else{
					$code = $row['product_code'];
					$queryp = mysql_query("SELECT current_price, product_code FROM pump_price WHERE product_code = '{$code}'");
					$pdt_price= mysql_fetch_array($queryp);
					if($row['product_code'] ==  $pdt_price['product_code']){
						$meter_id = $row['meter_transaction_id'];
						$amount = (($row['total_sales'])*($pdt_price['current_price']));
						//insert value into the database for further reference
						$insertq="UPDATE meter_transaction SET sales_amount = '{$amount}' WHERE meter_transaction_id = {$meter_id}";
						if(mysql_query($insertq)){
							echo number_format($amount);
								}else{
									echo "<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
								}
					}
				}
	 //===================================================================================================================================
	 echo "</td>
		  <td><a href='editentrym.php?meter_id=". urlencode($row['meter_transaction_id']) ."'>Edit</a></td>
	</tr>
";
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

<?php 
close();
?>
