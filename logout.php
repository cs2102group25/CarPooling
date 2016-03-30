<?php require_once 'libs.php';

	session_start();

	session_unset();
	session_destroy();
	directToLoginPage();
?>