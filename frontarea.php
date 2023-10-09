<?php require_once('includes/db_connection.php');?>
<?php require_once("includes/session.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Front Section</title>
<link href="css/modify.css" rel="stylesheet" type="text/css" />
<link rel="Shortcut icon" href="img/newfav.ico" />
</head>

<body>
<div id="wrapper">
    <?php require("includes/header.php");?>
    <div id="display">
    <div class="menuhead"> Transaction Entry</div>
    <br />
    <div id="myinfo">
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Welcome to the Internal Audit System at New Mbarara Service Station<br /><br />
    Instructions:<br />
    &nbsp; &nbsp; -Choose a transaction type from the Main menu under the transactions link<br />
    &nbsp; &nbsp; -Enter the Transaction Details using the form that will load after selecting the Transaction<br />
    &nbsp; &nbsp; -Click Post after entering the details in the form<br />
    &nbsp; &nbsp; -<font color="#FF0000">*Be careful to have entered the right information in the form to avoid reversing</font><br />
    &nbsp; &nbsp; <font color="#999999">-Created by Hillary(Systems Developer) BSc.CS,CCNA<br  /> &nbsp; &nbsp; &nbsp; &nbsp; For Help Email hbantu@gmail.com, hillary@naxicsolutions.com</font><br />
    </div>
   </div>
  <?php require("includes/mainmenu.php");?>
    
</div>
</body>
</html>
