<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/sessionadmin.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/form_functions.php");?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Meter Entry Summary</title>
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
     <tr><form method="post" action="meter_statement.php">
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
         </div>";
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
	//Close connection
	if (isset($connection)){
	mysql_close($connection);
	}

?>