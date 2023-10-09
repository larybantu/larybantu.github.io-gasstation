<?php require_once('../includes/db_connection.php');?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/modify.css" rel="stylesheet" type="text/css" />
<link rel="Shortcut icon" href="../img/newfav.ico" />
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<title>Change Price Transaction</title>

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
	   //intialising the printable values to selected for editing
			$sel_pprice = $_GET['pprice_id'];
			$result=mysql_query("SELECT * FROM pump_price WHERE pump_price_id=$sel_pprice");
			$editpprice = mysql_fetch_array($result);
			$printdate = date('d/m/Y', strtotime($editpprice['date_of_change']));
			$printpdtcode = $editpprice['product_code'];
			$printcurrentpx = $editpprice['current_price'];
			$printoldpx = $editpprice['old_price'];
		?>
        <div id="display">
        <div class="menuhead">Editing Change Price Transaction</div>
    <form  id="formID" name="invoice" method="post" action="<?php echo "update_pprice.php?pprice_id=". urlencode($editpprice['pump_price_id']) .""?>">
    <table width="552">
      <tr>
      <td width="75" class="rightalign">Current  </td>
        <td width="113">Pump Prices</td>
        <td width="100">Date:</td>
       <td width="244"><input type="text" name="date" id="datepicker" value="<?php echo "$printdate"?>"/></td>
      </tr>
      <tr>   
       <td height="36" class="rightalign">PMS:</td>
        <td>
          <?php 
			$querycurrent = mysql_query("SELECT max(date_of_change), current_price, product_code FROM pump_price WHERE product_code='PMS'");
			$pms_current_price = mysql_fetch_array($querycurrent);
			echo "<strong>". $pms_current_price['current_price'] . "</strong>";?></td>
         <td>Change:</td>
        <td><select name="product" class="btn">
                  <?php 
			   	if($printpdtcode == 'PMS'){
					echo "
					<option value=\"PMS\">PMS</option>
					<option value=\"AGO\">AGO</option>
                    <option value=\"BIK\">BIK</option>";
					
					}elseif($printpdtcode == 'AGO'){
						echo "
						<option value=\"AGO\">AGO</option>
						<option value=\"PMS\">PMS</option>
						<option value=\"BIK\">BIK</option>";
						}elseif($printpdtcode == 'BIK'){
							echo "
							<option value=\"BIK\">BIK</option>
							<option value=\"AGO\">AGO</option>
							<option value=\"PMS\">PMS</option>";
						}
                 ?>
        </select></td>
      </tr>
        <tr>  
          <td height="33" class="rightalign">AGO:</td>
           <td><?php 
		   	$querycurrent1 = mysql_query("SELECT MAX(date_of_change) as DATE, current_price, product_code FROM pump_price WHERE product_code='AGO' LIMIT 1");
			$ago_current_price = mysql_fetch_assoc($querycurrent1);
		   echo "<strong>". $ago_current_price['current_price'] . "</strong>"?></td>
          <td>From:</td>
           <td><input type="text" name="old_price" value="<?php echo $printoldpx ?>"/></td>
        </tr>
        <tr>
          <td class="rightalign">BIK:</td><td><?php 
		   $querycurrent2 = mysql_query("SELECT max(date_of_change), current_price, product_code FROM pump_price WHERE product_code='BIK'");
			$bik_current_price = mysql_fetch_array($querycurrent2);
		   echo "<strong>". $bik_current_price['current_price'] . "</strong>"?></td>
        	<td>To:</td>
           <td><input type="text" name="change_price" value="<?php echo $printcurrentpx ?>"/></td>
        </tr>
        <tr> 
          <td class="rightalign">&nbsp;</td>
           <td></td>
            <td class="del"><a href="<?php 
			 	echo "delete_pprice.php?pprice_id=". urlencode($editpprice['pump_price_id']) ."";
			 ?>" onclick="return confirm('Are you sure you want to delete this Price Change Entry?');">Delete Entry</a></td>
           <td><input type="submit" class="btn" name="post" value="Update" /> </td>
           </tr>
         <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
             <td></td>
             <td></td>
      </tr>
     </table>
       </form>
           <div id="results"><br />
    Today's Entries &nbsp; &nbsp; <?php 
	$daten = date("Y-m-d");
	print date('d/m/Y', strtotime($daten)); 
    	if (isset($_POST['post'])){
			if($_POST['date'] != NULL && 
			   $_POST['change_price'] != NULL && 
			   $_POST['product'] != NULL){
					global $m,$d,$y;
					list($m,$d,$y) = explode("/",$_POST['date']);
					/*1*/ $date = mysql_real_escape_string("$y-$m-$d ");
					/*2*/ $change_price = trim(mysql_prep($_POST['change_price']));
					/*3*/ $product = trim(mysql_prep($_POST['product'])) ;
					//getting the current price
			$query_oldp = mysql_query("SELECT max(date_of_change), current_price, old_price FROM pump_price WHERE product_code = '{$product}' LIMIT 1");
			$row_currentp = mysql_fetch_array($query_oldp); 
			$current_price = $row_currentp['current_price'];
					//inserting entries into the table 
					$query = "INSERT INTO pump_price(date_of_change, date_of_entry, product_code, current_price, old_price)
					 VALUES('{$date}',CURDATE(),'{$product}',{$change_price}, {$current_price})";
					
					if(mysql_query($query)){
						echo " &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font color=\"#438787\">Price of <strong>". $product ."</strong> Successfully</font>";
						}else{
							echo "&nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<font color='#FF0000'>Failed: " . mysql_error() . "</font>";
						}
				
				}else{ 
			echo "<br /><font color='#FF0000'>Some Field(s) above are Empty, Please Check again before Posting</font>"; 
			}	
		}
		?>
    <table  class="displaytb" width="747">
        <tr class="tablehead">
          <td class="celltb" width="111" height="38">Date</td>
          <td class="celltb" width="172">Product</td>
          <td class="celltb" width="135">From</td>
          <td class="celltb" width="117">To</td>
           <td class="celltb" width="116">Difference</td>
          <td width="68">Action</td>
        </tr>
     <?php
			$result = mysql_query("SELECT * FROM pump_price");
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			  <td class='celltb' width='70' height='35'>". date('d/M/Y', strtotime($row['date_of_change'])) ."</td>
			  <td class='celltb' width='250'>{$row["product_code"]}</td>
			  <td class='celltb' width='108'>{$row["old_price"]}</td>
			  <td class='celltb' width='196'>{$row["current_price"]}</td>
			  <td class='celltb' width='59'>";
			  
			  $diff = $row['current_price'] - $row['old_price'];
			  echo $diff;
			  echo "</td>
			  <td width='10'><a href='editentryprice.php?pprice_id=". urlencode($row['pump_price_id']) ."'>Edit</a></td>
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