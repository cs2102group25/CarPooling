<?php 
require_once 'php/sqlconn.php';
require_once 'libs.php';
require_once 'redirectIfLogin.php';
session_start();

$posted = isset($_POST['sign-up'], $_POST['email'], $_POST['password']);
if($posted) {
    
    $adminVal = var_export($_POST['admin'] == admin, true);
	$query = "INSERT INTO \"user\"(email, password, admin)
	VALUES('".$_POST['email']."', '".$_POST['password']."', '".$adminVal."');";
	$result = pg_query($query);
	if ($result) {
		$_SESSION['email'] = $_POST['email'];
		directToHomePage();
	}
} else {
    echo "Fill in the following information to sign up";
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
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		Email : <input type="text" name="email" id="email" placeholder="Email"/>
		<br/>
        Username : <input type="text" name="username" id="username" placeholder="Username"/>
		<br/>
		Password: <input type="password" name="password" id="password" placeholder="Password"/>
		<br/>
		<input type="checkbox" name="admin" value="admin"/> Admin 
		<br/>
        <input type="submit" name="sign-up" value="Sign up"/>
	</form>
<?php 
if ($posted && !$result) {
    echo "Error signing up, please try again.";
}
?>
</body>
</html>