<!DOCTYPE html>
<?php
session_start();
require_once 'redirectIfLogin.php';
$title = "Sign up";
?>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	<link rel="stylesheet" type="text/css" href="style.css">
    <?php require_once 'header.php'; ?>
</head>

<body>
    <?php 
        require_once 'menu.php';
        require_once 'php/sqlconn.php';
        require_once 'libs.php';

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
    <br/>
    <div class="container fill col-lg-12 col-sm-4" id="form1">
    <div class="well col-lg-4 col-lg-offset-4 col-sm-12 fill">
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
    <br/>
	<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>">
		<h2>Login Form</h2>
        <hr>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input class="form-control type="text" placeholder="Enter email" name="email" id="email"/>
        </div>
        <div class="form-group">
            <label for="pass">Password</label>
            <input class="form-control type="password" placeholder="Password" name="password" id="pass"/>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="rmb" value="1"/>
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn-primary" name="login" value="Login"/>Login</button>
	</form>
    Have no account? <a href="signup.php" class="button right">Sign up</a>
    </div>
    </div>
<?php require_once 'footer.php'; ?>
</body>
</html>
