<?php 
require_once 'php/sqlconn.php';
require_once 'libs.php';
require_once 'redirectIfLogin.php';
session_start();

if (isset($_POST['login'])) {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

        $userQuery = "SELECT * FROM \"user\" WHERE email='$email'";
        $userResult = pg_query($userQuery);
		if (!$userResult) exit("Error logging in.");
        if (pg_num_rows($userResult) != 0) {
            $loginQuery = "SELECT admin FROM \"user\" WHERE email='$email' AND
            password='$password'";
            $loginResult = pg_query($loginQuery);
            if (!$loginResult) exit("Error logging in.");
            if (pg_num_rows($loginResult) > 0) {
                $row = pg_fetch_row($loginResult);
                $_SESSION['email'] = $email;
                $db_admin = $row[0];

                if ($db_admin == 0) {
                    directToHomePage();
                } else if($db_admin == 1) {
                    directToAdminPage();
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
                <?php
                if (isset($_POST['login'])) {
                    if (isset($_POST['email']) && isset($_POST['password'])) {
                        if (pg_num_rows($userResult) == 0) {
                            echo 'Username not found.';
                        } else if (!$loginResult == 0) {
                            echo 'Incorrect password.';
                        }
                    }
                }
                ?>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					Email: <input type="text" name="email" id="email" placeholder="User Name">
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
