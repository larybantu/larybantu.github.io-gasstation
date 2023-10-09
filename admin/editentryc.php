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

<title>Add Customer Account</title>
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
			$sel_customer = mysql_prep($_GET['customer']);
			$result=mysql_query("SELECT * FROM customers WHERE customer_id=$sel_customer");
			$editcustomer = mysql_fetch_array($result);
			$printcode = $editcustomer['customer_code'];
			$printname = $editcustomer['customer_name'];
			$printcontact = $editcustomer['customer_contact'];
		?>
  <div id="display">
    <div class="menuhead">Add/Edit A Customer Account</div>
    <form  id="formID" name="add_customer" method="post" action="<?php echo "update_customer.php?customer_id=". urlencode($editcustomer['customer_id']) .""?>">
      <table  id="add" width="426">
        <tr> 
          <td width="104">Customer Name:</td>
           <td width="205"><input type="text" name="customer_name" value="<?php echo $printname?>"/></td>
        </tr> 
        <tr> 
          <td width="104">Contact no:</td>
           <td width="205"><input type="text" name="customer_contact" value="<?php echo $printcontact?>" /></td>
        </tr>
         
       <tr> 
          <td>Customer Code</td>
          <td><input type="text" name="code" value="<?php echo $printcode?>"/></td>
        </tr>
      <tr>
      <td>&nbsp;</td>   
         <td><input type="submit" class="btn" name="post" value="Update" /></td>
         <td width="101" class="del"><a href="<?php 
			 	echo "delete_customer.php?customer_id=". urlencode($editcustomer['customer_id']) ."";
			 ?>" onclick="return confirm('Are you sure you want to delete this Invoice Entry?');">Delete Entry</a></td>

        </tr>
     </table>
                        
    </form>
    
   
    <div id="printer"><a href="printmeters.php" target="_blank" >Printer Friendly Version</a></div><br />
    <div id="results">
    Changed accounts
      <table  class="displaytb" width="747">
        <tr class="tablehead">
         <td class="celltb" width="37" height="10">No.</td>
          <td class="celltb" width="107" height="30">Date Added</td>
          <td class="celltb" width="289">Account Name</td>
          <td class="celltb" width="131">Contact No.</td>
          <td class="celltb" width="105">Code</td>
          <td width="50">Action</td>
        </tr>
     <?php
	 
			$result = mysql_query("SELECT * FROM customers ORDER BY customer_name");
			$counter2 = 1;
		while($row = mysql_fetch_array($result)){
			echo "
			<tr>
			<td class=\"celltb2\" width=\"37\" height=\"10\">".$counter2."</td>
			  <td class='celltb2' height=\"25\">". date('d/M/Y', strtotime($row['date_added'])) ."</td>
			  <td class='celltb2'>{$row["customer_name"]}</td>
			  <td class='celltb2'>{$row["customer_contact"]}</td>
			  <td class='celltb2'>{$row["customer_code"]}</td>
			  <td  class='celltb2'><a href='editentryc.php?customer=". urlencode($row['customer_id']) ."'>Edit</a></td>
			</tr>
		";
		$counter2++;
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
