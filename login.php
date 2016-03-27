<?php require_once 'sqlconn.php';
if(isset($_POST['login'])){
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$user_name = $_POST['username'];
		$password = $_POST['password'];

		$loginQuery = "SELECT * FROM "
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
	<table>
		<tr> <td colspan="2" style="background‐color:#FFA500;">
			<h1>Login</h1>
		</td> </tr>
		<tr>
			<td style="background‐color:#00FF00;">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					Username: <input type="text" name="username" id="username" placeholder="User Name">
					<br>
					Password: <input type="password" name="password" id="password" placeholder="Password">
					<br>
					  	<input type="submit" name="login" value="Login">
					<a href="registration.php" class="button right">REGISTER</a>
				</form>
			</td> 
		</tr>
		<tr>
			<td colspan="2" style="background‐color:#FFA500; text‐align:center;"> Copyright &#169; CS2102
			</td> 
		</tr>
	</table>
</body>
</html>
