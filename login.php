<?php require_once '/php/sqlconn.php';
	require_once 'libs.php';

session_start();

if(isset($_POST['login'])){
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$user_name = $_POST['username'];
		$password = $_POST['password'];

		$loginQuery = "SELECT email, password, admin FROM profile WHERE email='".$user_name."'";
		$res = pg_query($loginQuery);
		if(!$res) exit("There's error in login");

		while($row = pg_fetch_row($res)) {
			if(count($row) == 0) {
				directToLoginPage();
				echo "User name does not exist <br>";
			}
			else {
				$db_user = $row[0];
				$db_pwd = $row[1];
				$db_admin = $row[2];

				if($db_user == $user_name){
					if($db_pwd == $password) {
						$_SESSION['user_name'] = $user_name;
						$_SESSION['password'] = $password;

						if($db_admin == 0) {
							directToProfilePage();
						} else if($db_admin == 1) {
							directToAdminPage();
						}
					}else {
						echo "<br> Wrong Password";
					}
					echo "<br> Wrong User Name";
				}
			}
		}
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
					Email: <input type="text" name="username" id="username" placeholder="User Name">
					<br>
					Password: <input type="password" name="password" id="password" placeholder="Password">
					<br>
					  	<input type="submit" name="login" value="Login">
					<a href="signup.php" class="button right">Sign up</a>
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
