<?php
//this file includes all the basic functions

//magic quotes tp cater for unknown use input for all php versions
function mysql_prep($value){
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string"); // php v4.3
	if($new_enough_php){//php v4.3 or higher
	//undo any magic quote effects so mysql_real_escape_string can do its work
		if($magic_quotes_active){
			$value = stripslashes($value);
			}
			$value = mysql_real_escape_string($value);
	}else{//below php v4.3 add the slashes if they are not active
		if(!$magic_quotes_active){
			$value = addslashes($value);
			}//if magic quotes are active then the slashes already exist
		}
		return $value;
	}
	
//function to redirect pages and content
function redirect_to($location = NULL){
	if($location !=NULL){
		header("Location: {$location}");
		exit;
		}
	}
		
//function to confirm a query to check sql
function confirm_query($result_set){
	if (!$result_set) {
		die("Database query failed: " . mysql_error());
	}
	}
//function to get all courses
function get_all_courses(){
	global $connection;
	$course_set = mysql_query("SELECT * FROM courses ORDER BY course_id ASC", $connection);
	confirm_query($course_set);
	return $course_set;
	}
	
//function to get invoice by its id for editing
function get_invoice_by_id($invoice_id){
	global $connection;
	$query = "SELECT * FROM invoice_transaction WHERE invoice_transaction_id=" . $invoice_id ."";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	//REMEMBER:
	//if there are no rows returned from the query, mysql fetch array will return false
	if($subject=mysql_fetch_array($result_set)){
		return $subject;
		}else{
			return NULL;
			}
	}
/*function close the connection */
function close() {
	return (@mysql_close($connection));
	}
?>
