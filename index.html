//<?php require_once("includes/db_connection.php"); ?>
//<?php require_once("includes/session.php"); ?>
//<?php require_once("includes/functions.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Mbarara Service Station</title>
<link rel="Shortcut icon" href="img/newfav.ico" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php 
logged_in();

	include_once("includes/form_functions.php");
	
	// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();

		// perform validations on the form data
		$required_fields = array('username', 'password');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

		$fields_with_lengths = array('username' => 30, 'password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

		$username = trim(mysql_prep($_POST['username']));
		$password = trim(mysql_prep($_POST['password']));
		$hsd_password = sha1($password);
		
		if ( empty($errors) ) {
			// Check database to see if username and the hashed password of the admin exist there.
			$queryadmin = "SELECT user_id, username ";
			$queryadmin .= "FROM admin ";
			$queryadmin .= "WHERE username = '{$username}' ";
			$queryadmin .= "AND hsd_password = '{$password}' ";
			$queryadmin .= "LIMIT 1";
			$result_set = mysql_query($queryadmin);
			confirm_query($result_set);
			
			// Check database to see if username and the hashed password of the front user exist there.
			$queryfront = "SELECT front_id, username ";
			$queryfront .= "FROM front_user ";
			$queryfront .= "WHERE username = '{$username}' ";
			$queryfront .= "AND hsd_password = '{$password}' ";
			$queryfront .= "LIMIT 1";
			$result_set_front = mysql_query($queryfront);
			confirm_query($result_set_front);
			
			if (mysql_num_rows($result_set) == 1) {
				// username/password authenticated
				// and only 1 match
				$found_user = mysql_fetch_array($result_set);
				$_SESSION['user_id'] = $found_user['user_id'];
				$_SESSION['username'] = $found_user['username'];
				redirect_to("meter_readings.php");
				//seesion is already open so just fuck the shit up
				
			}else if(mysql_num_rows($result_set_front) == 1) {
				// username/password authenticated
				// and only 1 match
				$found_user = mysql_fetch_array($result_set);
				$_SESSION['user_id'] = $found_user['user_id'];
				$_SESSION['username'] = $found_user['username'];
				redirect_to("frontarea.php");
					
					}else{
				// username/password combo was not found in the database
				$message = "<font color=\"#FF0000\">Username/password combination incorrect.</font><br />
					<font color=\"#FF0000\">Please make sure your caps lock key is off and try again.</font>";
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		
	} else { // Form has not been submitted.
		if (isset($_GET['logout']) && $_GET['logout'] == 1) {
			$message = "You are now logged out.";
		} 
		$username = "";
		$password = "";
	}
?>
		<div id="wrapper">
        <div id="beginpage">
          <div id="inlineimg1"><img src="img/webversion.png" height="160" width="156" /></div>
          <div id="inlinehead1">NEW MBARARA SERVICE STATION</div>
          <div id="heading12">Internal Audit System</div>
          <div id="red"></div>
          </div>
		<?php
		$curr_date = date("Y");
		if($curr_date = 2013){
                
        ?>
        <div id="contentmenu"> 
        <h2>Staff Login</h2>
                    <?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
                    <?php if (!empty($errors)) { display_errors($errors); } ?>
        <form action="index.php" method="post">
        <p>Username:
        <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
        </p>
        <p>
        Password:
        <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
        </p>
               <input type="submit" name="submit" class="btn" value="Login" /> &nbsp;
        
        </form></div>
        <?php 
         
		}else{
			?>
            <div id="contentmenu"> 
        <h2>Staff Login</h2>
                    <p>
                    Hello User, Happy New Year<br  />
                    While the year has come to and end, the system has to back up all the data from the previous year and a routine check up by the system administrator. <br />
                    Please Contact the system Admin to assist you with this backup process before data entry can commence.
                    </p>
        
            <?php } ?>

<div id="footer1">
    <a href="http://www.google.co.ug/search?sourceid=chrome&ie=UTF-8&q=fuel+news+in+uganda" target="_blank">Fuel News Online</a> &nbsp;| &nbsp;
    <a href="#">Pictorial</a> &nbsp;| &nbsp; 
    <a href="mailto: hbantu@gmail.com">Webmaster</a> &nbsp;| &nbsp; 
    <a href="#">Poultry System</a> &nbsp;| &nbsp; 
    <a href="#"></a> &nbsp;| &nbsp;
    <a href="#">Help</a> &nbsp;| &nbsp; 
    <a href="#">Contact Us</a> &nbsp;| &nbsp; 
    <a href="#">About Us</a>
</div>
</div>

</body>         
</body>
</html>
<?php
	//Close connection
	if (isset($connection)){
	mysql_close($connection);
	}

?>

