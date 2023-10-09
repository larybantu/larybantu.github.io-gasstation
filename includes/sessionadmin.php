<?php
	session_start();

	function logged_in() {
		return isset($_SESSION['user_id']);
		if (isset($_SESSION['user_id']) == 1000){
			redirect_to("meter_readings.php");
			}else{
				redirect_to("../index.php");
				}
	}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("../index.php");
		}
	}
?>
