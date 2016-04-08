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

function directToProfilePage() {
	echo "<script type='text/javascript'> document.location = 'profile.php'; </script>";
}

function directToMyVechicles() {
	echo "<script type='text/javascript'> document.location = 'myvehicles.php'; </script>";
}

function directToMyTrips() {
	echo "<script type='text/javascript'> document.location = 'mytrips.php'; </script>";
}

function directToAdminTrips() {
	echo "<script type='text/javascript'> document.location = 'admintrips.php'; </script>";
}
?>