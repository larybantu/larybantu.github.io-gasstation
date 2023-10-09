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

<title>Transaction Error Correction</title>
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
    <div class="menuhead">Select Entry to be Edited</div>
    <form  id="formID" name="invoice" method="post" action="edit_entries.php">
    <table width="370">
    <tr>   
       <td height="40" class="rightalign"><strong>Transaction Type:</strong></td>
        <td><select name="type" class="btn2">
               <option value="">--Select Transaction--</option>
               <option value="M">Meter Entry</option>
               <option value="I">Invoice Entry</option>
               <option value="SI">Service Invoice Entry</option>
               <option value="C">Cash Entry</option>
            </select>
          </td>
        </tr><br />
        <tr>   
       <td height="40" class="rightalign"><strong>Date Type:</strong></td>
        <td><select name="date_type" class="btn2">
               <option value="">--Select Date Type--</option>
               <option value="DE">Date of Entry</option>
               <option value="DIMC">Date of Invoice/Meter/Cash</option>
            </select>
          </td>
        </tr>
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
		if($_POST['type'] != NULL && 
		   $_POST['fromdate'] != NULL &&
		   $_POST['date_type'] != NULL && 
		   $_POST['todate'] != NULL){
		global $m,$d,$y;
		 $transaction_type = trim(mysql_prep($_POST['type']));
		 $date_type = trim(mysql_prep($_POST['date_type']));
		 list($m,$d,$y) = explode("/",$_POST['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_POST['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d ");

	//ACTIONS TO SEARCH DB
	if($transaction_type == 'M' && $date_type == 'DE'){
		echo "
		<div id='printer'>
		    <a href='printmeters.php' target='_blank' >Printer Friendly Version</a></div><br />
		<div id ='tableheading'><hr /> 
			Meter Readings by Dates of Entry From <strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='747'>
        <tr class='tablehead'>
          <td class='celltb' width='81' height='40'>Date</td>
          <td class='celltb' width='98'>Opening Meter</td>
          <td class='celltb' width='101'>Changing Meter</td>
          <td class='celltb' width='127'>Closing Meter</td>
          <td class='celltb' width='43'>Pump</td>
          <td class='celltb' width='57'>Product</td>
          <td class='celltb' width='57'>Litres</td>
          <td class='celltb' width='95'>Amount</td>
          <td width='48'>Action</td>
        </tr>";
		
		$result = mysql_query("SELECT * FROM meter_transaction WHERE date_of_entry BETWEEN '{$fromdate}' AND '{$todate}' ORDER BY meter_date,pump_no ASC");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' height=\"35\">". date('d/M/Y', strtotime($row['meter_date'])) ."</td>
			  <td class='celltb'>{$row["opening_meter"]}</td>
			  <td class='celltb'>";
			  if($row['changing_meter1'] != 00000000000.00){
				  echo $row["changing_meter1"];
				  }elseif($row["changing_meter2"] != 00000000000.00 ){
					  echo $row["changing_meter2"];
					  }elseif($row["changing_meter3"] != 00000000000.00){
						  echo $row["changing_meter3"];
						  }else{
							  echo "<font size='-2' color = '438787'>Meter Not Changed</font>";
							  }
						  
			  echo "</td>
			  <td class='celltb'>{$row["closing_meter"]}</td>
			  <td class='celltb'>{$row["pump_no"]}</td>
			  <td class='celltb'>{$row["product_code"]}</td>
			  <td class='celltb'>{$row["total_sales"]}</td>
			  <td class='celltb'>{$row["sales_amount"]}</td>
			  <td><a href='editentrym_ee.php?meter_id=". urlencode($row['meter_transaction_id']) ."'>Edit</a></td>
			</tr>
		";
		  }
		
        echo"
            </table>";
		}elseif($transaction_type == 'M' && $date_type == 'DIMC'){
		echo "
		<div id='printer'>
    <a href='printmeters.php' target='_blank' >Printer Friendly Version</a></div><br /><br />
	<div id ='tableheading'><hr /> 
			Meter Readings by Meter Date From <strong>". date('d/m/Y', strtotime($fromdate)) ."</strong> to <strong>".  date('d/m/Y', strtotime($todate)) ."</strong><hr />
		</div>
    <table  class='displaytb' width='747'>
        <tr class='tablehead'>
          <td class='celltb' width='81' height='40'>Date</td>
          <td class='celltb' width='98'>Opening Meter</td>
          <td class='celltb' width='101'>Changing Meter</td>
          <td class='celltb' width='127'>Closing Meter</td>
          <td class='celltb' width='43'>Pump</td>
          <td class='celltb' width='57'>Product</td>
          <td class='celltb' width='57'>Litres</td>
          <td class='celltb' width='95'>Amount</td>
          <td width='48'>Action</td>
        </tr>";
		
		$result = mysql_query("SELECT * FROM meter_transaction WHERE meter_date BETWEEN '{$fromdate}' AND '{$todate}' ORDER BY meter_date,pump_no ASC");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' height=\"35\">". date('d/M/Y', strtotime($row['meter_date'])) ."</td>
			  <td class='celltb'>{$row["opening_meter"]}</td>
			  <td class='celltb'>";
			  if($row['changing_meter1'] != 00000000000.00){
				  echo $row["changing_meter1"];
				  }elseif($row["changing_meter2"] != 00000000000.00 ){
					  echo $row["changing_meter2"];
					  }elseif($row["changing_meter3"] != 00000000000.00){
						  echo $row["changing_meter3"];
						  }else{
							  echo "<font size='-2' color = '438787'>Meter Not Changed</font>";
							  }
						  
			  echo "</td>
			  <td class='celltb'>{$row["closing_meter"]}</td>
			  <td class='celltb'>{$row["pump_no"]}</td>
			  <td class='celltb'>{$row["product_code"]}</td>
			  <td class='celltb'>{$row["total_sales"]}</td>
			  <td class='celltb'>{$row["sales_amount"]}</td>
			  <td><a href='editentrym_ee.php?meter_id=". urlencode($row['meter_transaction_id']) ."' onclick='return confirm('Are you sure you want to delete this Invoice Entry?');'>Edit</a></td>
			</tr>
		";
		  }
		
        echo"
            </table>";
		}
			}elseif($transaction_type == 'I' && $date_type == 'DE'){
				//here we shall display invoice transactions with respect to the date of entry
				
				}elseif($transaction_type == 'I' && $date_type == 'DIMC'){
					//here we shall display invoice transactions with respect to the date of invoice
					}elseif($transaction_type == 'C' && $date_type == 'DE'){
						//here we shall display cash entries with respect to the Date of Entry
						}elseif($transaction_type == 'C' && $date_type == 'DIMC'){
						Sóãb÷¥tüéšœ0Á5ÃÂÁ©ÅGÓ/ Ô­DŒíƒ2Ù¹šóúŸ+pÀÄt<:XñÍßJòË+;vôxğÁûêáÜ²®>-˜/‰öV_ÒÑéh9Z{sx3Í1Aø´Lu	1Úóœ;'cJxÉ\Y¢÷ŒĞD¥K (i•ğØ¾ÒSêHZ1åaà÷ÛÅSQLËMÕUUUUUUUUU dcwE™  ]’’,4<×ÀÌí} IsN°R@ÍË~ç&B[˜PYhUbñä]T0¦ÆkÅúo«gXòÜ¢^Ê"£Ebxò7„5‡øÙØÈGê„“5i$óq,üx?‰2E6I‘Kn›“Q2p‘¦n~u+Û?.­äy(è}o­9W¾Î¦$‹CÉtu0£%óñ˜ä™zÂŞL×°xª„‹YyÄt!ğ[£¥ìõúEãØó0#ÇuÓ×ğğœŒó¶Áf§«kP4ò2ş6«x±5%ßOÔ¿¦?œç$½ôËfo¨“Ô 5IÀJ‚ùˆ"‚ ešs–„-D!#€DëyÆ€¸ÑÄÛLPã*4e-
õ$bT+™h&1qŠNËôez±ŸÂ,ïOâ~È_"ú¥TÀõ.åbØ\ªB¨>$8^;Œ®ZJwø˜iáMzk‰eÓöÎ˜÷C‚hüJNÅ…(ÁrtNK±ÇÿûâDÅÇ¿qUl~bì­*¢iì´}ÏVM0}CË´«—²øQ0Êäkl8y/„	ÉkKN:¼úÅñ)*ÔHuÌÁ-:fëJ©‚¡‚4Êr¸f‘óÂâuÌ=¬@á%/X~0P¬Ë®B,¼vúÖ@5T*YÃZÓÉÜ?ù¥]İ0Y±.CF