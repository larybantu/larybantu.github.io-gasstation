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
  <div id="display">
    <div class="menuhead">Add/Edit A Customer Account</div>
    <form  id="formID" name="add_customer" method="post" action="addacc.php">
      <table  id="add" width="367">
        <tr> 
          <td width="120">Customer Name:</td>
           <td width="269"><input type="text" name="customer_name" /></td>
        </tr> 
        <tr> 
          <td width="120">Contact no:</td>
           <td width="269"><input type="text" name="customer_contact" /></td>
        </tr>
         </tr>
       <tr> 
          <td>Customer Type:</td>
          <td><select name="type" class="btn3">
               <option value="">--Choose Type--</option>
              <option value="D">Due Payer</option>
              <option value="M">Monthly Payer</option>
              <option value="T">Trade Payer</option>
              <option value="Cr">Credit Payer</option>
               <option value="L">Local Payer</option>
              <option value="Dr">Director</option>
            </select></td>
        </tr>
      <tr>   
       <td>Payment Mode:</td>
        <td>
          <select name="mode" class="btn3">
               <option value="">--Choose Mode--</option>
                 <option value="C">Cash</option>
                   <option value="Q">Cheque</option>
                   <option value="B">Both</option>
            </select></td>
        </tr>
        <tr>  
          <td>&nbsp;</td>
           <td>&nbsp;</td>
        </tr>
         <tr>
         <td>&nbsp;</td>
         <td><input type="submit" class="btn" name="post" value="Add Account" /></td>

        </tr>
     </table>
                        
    </form>
    
    <?php 
	
	$daten = date("Y-m-d");	
	if (isset($_POST['post'])){
		if($_POST['customer_name'] != NULL && 
		   $_POST['type'] != NULL &&    
		   $_POST['mode'] != NULL){
		global $m,$d,$y;
		 $customer_name = trim(mysql_prep($_POST['customer_name']));
		 $type = trim(mysql_prep($_POST['type']));
		 $mode = trim(mysql_prep($_POST['mode']));
		 $contact = trim(mysql_prep($_POST['customer_contact']));
		 $querycode = mysql_query("SELECT MAX(customer_id) FROM customers");
		 $viewcode = mysql_fetch_array($querycode);
		 $counter = $viewcode['MAX(customer_id)'];
		 $counter = $counter + 1;
		 if($counter<10){
			 $zero2 = '00';
			 $company_code = 'NM';
			 $counter1 = $zero2.$counter;
			 $code = $company_code.$type.'/'.$counter1.'/'.$mode;
			 }elseif($counter<100){
				 $zero2 = '0';
				 $company_code = 'NM';
				 $counter1 = $zero2.$counter;
				 $code = $company_code.$type.'/'.$counter1.'/'.$mode;
				 }elseif($counter<1000){
					 $company_code = 'NM';
					 $code = $company_code.$type.'/'.$counter.'/'.$mode;
					 }
	//validation of entries 

	//to eliminate duplicate entries	
	$query = "INSERT INTO customers(customer_name, customer_code, date_added, customer_contact) 
	VALUES ('{$customer_name}','{$code}',CURDATE(), '{$contact}')";
		
		if(mysql_query($query)){
			
			 echo " <br /> <font size='-1' color=\"#438787\">Account Added Successfully</font>";
			}else{
				echo "<br /> <font size='-1' color='#FF0000'>Failed: " . mysql_error() . "</font>";
				}
	}else{ 
		echo "<br /><font size='-1' color='#FF0000'>Some Field(s) above are Empty, Please Check again before Posting</font>"; 
		}
	}
	?>
     <div id="printer"><a href="printcustomers.php" target="_blank" >Printer Friendly Version</a></div><br />
    <div id="results">
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
