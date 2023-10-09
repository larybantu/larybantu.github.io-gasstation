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
    <?php 
			$sel_meter = mysql_prep($_GET['meter_id']);
			$result=mysql_query("SELECT * FROM meter_transaction WHERE meter_transaction_id=$sel_meter");
			$editmeter = mysql_fetch_array($result);
			$printdate = date('m/d/Y', strtotime($editmeter['meter_date']));
			
			$printpump_no = $editmeter['pump_no'];
			$printopening =$editmeter['opening_meter'];
			$printclosing = $editmeter['closing_meter'];
			$printcm1 = $editmeter['changing_meter1'];
			$printcm2 = $editmeter['changing_meter2'];
			$printcm3 = $editmeter['changing_meter3'];
			$printproduct = $editmeter['product_code'];
			$printrtt = $editmeter['rtt'];
		?>
   
  <div id="display">
    <div class="menuhead">Meter Transaction Entry</div>
     <form  id="formID" name="meter" method="post" action="<?php echo "update_meter_ee.php?meter_id=". urlencode($editmeter['meter_transaction_id']) .""?>">
    <table width="658">
      <tr>
      <td width="111">Date:</td>
        <td width="172"><input type="text" name="date" id="datepicker" value="<?php echo "$printdate"?>" /> </td>
        <td width="164">Changing Meter1:</td>
       <td width="191"><input type="text" name="cm1" id="name" value="<?php echo $printcm1?>"/></td>
       </tr>
       <tr> 
          <td>Pump No:</td>
           <td><select name="pump_no" class="btn">
               <?php 
			   	if($printpump_no == 1){
					echo "
					 <option value='1'>Pump 1</option>
                   	 <option value='2'>Pump 2</option>
                     <option value='3'>Pump 3</option>
                     <option value='4'>Pump 4</option>
                     <option value='5'>Pump 5</option>";
					
					}elseif($printpump_no == 2){
						echo "
					 <option value='2'>Pump 2</option>
                   	 <option value='1'>Pump 1</option>
                     <option value='3'>Pump 3</option>
                     <option value='4'>Pump 4</option>
                     <option value='5'>Pump 5</option>";
						}elseif($printpump_no == 3){
							echo "
					 <option value='3'>Pump 3</option>
                   	 <option value='2'>Pump 2</option>
                     <option value='1'>Pump 1</option>
                     <option value='4'>Pump 4</option>
                     <option value='5'>Pump 5</option>";
							}elseif($printpump_no == 4){
								echo "
					 <option value='4'>Pump 4</option>
                   	 <option value='3'>Pump 3</option>
                     <option value='2'>Pump 2</option>
                     <option value='1'>Pump 1</option>
                     <option value='5'>Pump 5</option>";
									}elseif($printpump_no == 5){
									echo "
					 <option value='5'>Pump 5</option>
                   	 <option value='4'>Pump 4</option>
                     <option value='3'>Pump 3</option>
                     <option value='2'>Pump 2</option>
                     <option value='1'>Pump 1</option>";
									}
						?>
        </select></td>
            <td>Changing Meter2:</td>
           <td><input type="text" name="cm2" id="name" value="<?php echo $printcm2 ?>"/></td>
        </tr>
        <tr><td>Product:</td><td><select name="product" class="btn">
               <?php 
			   	if($printproduct == 'PMS'){
					echo "
					<option value=\"PMS\">PMS</option>
					<option value=\"AGO\">AGO</option>
                    <option value=\"BIK\">BIK</option>";
					
					}elseif($printproduct == 'AGO'){
						echo "
						<option value=\"AGO\">AGO</option>
						<option value=\"PMS\">PMS</option>
						<option value=\"BIK\">BIK</option>";
						}elseif($printproduct == 'BIK'){
							echo "
							<option value=\"BIK\">BIK</option>
							<option value=\"AGO\">AGO</option>
							<option value=\"PMS\">PMS</option>";
						}?>
             </select></td> <td class="rightalign">RTT:</td><td><input type="text" name="rtt" id="name" value="<?php echo $printrtt?>" /></td></tr>
      <tr>   
       <td>Closing Meter:</td>
        <td><input type="text" name="closing" value="<?php echo $printclosing ?>"/></td>
         <td>&nbsp;</td>
        <td><?php
         
		 echo "
		 <font size='-1'>Current Pump Prices:<br />";
		$resultp = mysql_query("SELECT * FROM pump_price"); 
		while($row1 = mysql_fetch_array($resultp)){
			$code1 = $row1['product_code'];
		 $querya = mysql_query("SELECT max(date_of_change),product_code, current_price, old_price FROM pump_price WHERE product_code = '{$code1}'");
		 while($pump_price= mysql_fetch_array($querya)){
		 if($pump_price['product_code'] == 'AGO'){
		 echo "AGO: <font color='#006633'>".$pump_price['current_price']."</font><br />"; 
			 }elseif($pump_price['product_code'] == 'PMS'){
				 echo "PMS: <font color='#006633'>".$pump_price['current_price']."</font><br />";
		 		}elseif($pump_price['product_code'] == 'BIK'){
					 echo "BIK: <font color='#006633'>".$pump_price['current_price']."</font><br />";
							}
						}
				
					}
					echo" </font></td><td><a href='change_price_admin.php'>Change pump prices</a>";
		?></td>
        </tr>
        <tr>  
          <td>Opening Meter:</td>
           <td><input type="text" name="opening" value="<?php echo $printopening ?>"/></td>
            <td><input type="submit" class="btn" name="post" value="Update" /></td>
           <td class="del"><a href="<?php 
			 	echo "delete_meter_entry_ee.php?meter_id=". urlencode($editmeter['meter_transaction_id']) ."";
			 ?>" onclick="return confirm('Are you sure you want to delete this Meter Entry?');">Delete Entry</a></td>
        </tr>
         <tr>
         <td>&nbsp;</td>
         
             <td></td>
             <td></td>
        </tr>
       </table>
                        
    </form>
    
    <div id="results"><br />Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
	?> <div id="printer"><a href="printmeters.php" target="_blank" >Printer Friendly Version</a></div><br /><br />
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
	 
			$result = mysql_query("SELECT * FROM meter_transaction WHERE date_of_entry = CURDATE()");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' height=\"35\">". date('d/M/Y', strtotime($row['meter_date'])) ."</td>
			  <td class='celltb'>{$row["opening_meter"]}</td>
			  <td class='celltb'><font size='-2' color = '438787'>";
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