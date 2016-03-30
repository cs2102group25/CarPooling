<?php require_once '/php/sqlconn.php';
	require_once 'libs.php';
	session_start();

if(isset($_POST['sign-up'], $_POST['email'], $_POST['password'], $_POST['first-name'], $_POST['last-name'], 
	$_POST['credit-card-num'], $_POST['acc-balance'], $_POST['admin'])){
	$rows = countingRows("profile");

	$query = "INSERT INTO profile(profile_id, email, password, first_name, last_name, credit_card_num, acc_balance, admin)
	VALUES(".($rows + 1).", '".$_POST['email']."', '".$_POST['password']."', '".$_POST['first-name']."', '".$_POST['last-name']."', "
	.$_POST['credit-card-num'].", ".$_POST['acc-balance'].", ".$_POST['admin'].")";

	$result = pg_query($query) or die('Query failed: '.pg_last_error());
	if ($result) {
		echo "New user added";
		$_SESSION['user_name'] = $_POST['email'];
		$_SESSION['password'] = $_POST['password'];
		directToHomePage();
	} else {
		echo "User not added";
	}
}

pg_close($dbconn);
?>



<!DOCTYPE html>
<html>
<head> 
	<title>CS2102 Car Pooling</title> 
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		Email : <input type="text" name="email" id="username" placeholder="Email">
		<br>
		Password: <input type="password" name="password" id="password" placeholder="Password">
		<br>
		First name: <input type="text" name="first-name" id="first-name" placeholder="First Name">
		<br>
		Last name: <input type="text" name="last-name" id="last-name" placeholder="Last Name">
		<br>
		Credit card number: <input type="text" name="credit-card-num" id="card-no">
		<br>
		Account Balance: <input type="text" name="acc-balance" id="acc-bal" placeholder="Account Balance">
		<br>
		Admin <input type="number" name="admin" id="admin" value="0" max="1" min="0">
		<br>
		  	<input type="submit" name="sign-up" value="Sign up">
	</form>
</body>
</html>