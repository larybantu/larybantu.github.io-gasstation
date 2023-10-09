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

<title>Cash Sales Statement</title>
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
    <div class="menuhead">Cash Sales Period Selection</div><br />
    SELECT PERIOD AND PRODUCT:
    <hr />
      <form  id="formID" name="cash" method="post" action="cash_sales.php">
    <table width="620">
    <tr>   
       <td height="40" class="rightalign"><strong>Product:</strong></td>
        <td><select name="pdt" class="btn2">
               <option value="">--Select Product--</option>
               <option value="PMS">PMS</option>
               <option value="AGO">AGO</option>
               <option value="BIK">BIK</option>
               <option value="ALL">ALL PRODUCTS</option>
            </select>
          </td>
        </tr>
        <tr>
        	<td class="rightalign" height="45">From:</td>
            	<td><input type="text" name="fromdate" id="datepicker" /></td>
           </tr>
        <tr> 
          <td  class="rightalign" height="47">To:</td>
    		<td><input type="text" name="todate" id="datepicker2" /></td>
          </tr>
         <tr>
         	<td height="36">&nbsp;</td>
         	<td><input type="submit" class="btn" name="post" value="Generate" /></td>   
        </tr>
      </table>
       </form>
    
      <div id="results"> 
      <?php
      if (isset($_POST['post'])){
		if($_POST['pdt'] != NULL && 
		   $_POST['fromdate'] != NULL &&
		   $_POST['todate'] != NULL){
			   
			   //Declaration of variables
			   
		global $m,$d,$y;
		 $pdt = trim(mysql_prep($_POST['pdt']));
		 list($m,$d,$y) = explode("/",$_POST['fromdate']);
		 $fromdate = mysql_real_escape_string("$y-$m-$d ");
		 list($m,$d,$y) = explode("/",$_POST['todate']);
		 $todate = mysql_real_escape_string("$y-$m-$d ");
		 
		 
		 
		 /*IF STATEMENT to identify what product should results should be pulled from the database*/
		 
		 if($pdt == "ALL"){
			$ttpmscash = 0;
			$ttagocash = 0;
			$ttbikcash = 0;
             echo "
             <table class='displaytb' width='800' />
                 <tr class='tablehead'>
                    <td>Date</td>
                    <td>PMS</td>
                    <td>AGO</td>
                    <td>BIK</td>
                 </tr>";
				 
				 
			 $cash_data = mysql_query("
				SELECT *
				FROM cash_transaction
				WHERE date_of_cash BETWEEN '{$fromdate}' AND '{$todate}'  
				ORDER BY date ASC");	 
				 
			while($row = mysql_fetch_array($cash_data)){
				echo "
				<tr>
				  <td>". date('d/m/Y', strtotime($row["date_of_cash"]))."</td>
				  <td>{$row["pms_cash"]}</td>
				  <td>{$row["ago_cash"]}</td>
				  <td>{$row["bik_cash"]}</td>
				</tr>" ;
				$ttpmscash = $ttpmscash + $row['pms_cash'];
				$ttagocash = $ttagocash + $row['ago_cash'];
				$ttbikcash = $ttbikcash + $row['bik_cash'];
				
				}
				echo "
				<tr>
				  <td>TOTALS</td>
				  <td>".number_format($ttpmscash)."</td>
				  <td>".number_format($ttagocash)."</td>
				  <td>".number_format($ttbikcash)."</td>
				</tr>" ;
				 
             echo "</table>" ;
			 
			 }elseif($pdt == "PMS"){
				 }elseif($pdt == "AGO"){
					 }elseif($pdt == "BIK"){
						 }else{
							 echo " Product Selection Unknown" ;
							 }
		 
		   }
	  }
      ?>
      </div>
      
	<?php include("../includes/footer.php");?>
    </div>
    <?php include("../includes/mainmenu.php");?>
    </div>
</div>
</body>
</html>