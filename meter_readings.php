<?php require_once('includes/db_connection.php');?>
<?php require_once("includes/sessionadmin.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Meter Readings</title>
<link href="css/modify.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="Shortcut icon" href="img/newfav.ico" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script  type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker();
  });
</script>
</head>

<body>
<div id="wrapper">
	<?php require("includes/header.php");?>
    <?php include("includes/bodycontent.php")?>
    <?php include("mainmenu_out.php");?>    
</div>
</body>
</html>