<?php 
require_once 'php/sqlconn.php';
session_start();

/*----------------All the commonly-used functions're added here--------------------------*/
function directToLoginPage() {
	echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
}

function directToSignupPage() {
	echo "<script type='text/javascript'> document.location = 'signup.php'; </script>";
}

function directToHomePage() {
	echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
}

function directToAdminPage() {
	echo "<script type='text/javascript'> document.location = 'admin.php'; </script>";
}

function directToProfilePage() {
	echo "<script type='text/javascript'> document.location = 'profile.php'; </script>";
}

function directToBookingPage() {
	echo "<script type='text/javascript'> document.location = 'booking.php'; </script>";
}

function countingRows($table) {
	$result = pg_query("SELECT * FROM ".$table);
	$num_rows = pg_num_rows($result);

	return $num_rows;
}
?>