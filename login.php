<!DOCTYPE html>
<?php
session_start();
require_once 'redirectIfLogin.php';
$title = "Login";
?>
<html>
<?php require_once 'header.php'; ?>
<body>
    <?php 
        require_once 'menu.php';

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
                        if ($db_admin == 'f') {
                            directToHomePage();
                        } else if($db_admin == 't') {
                            $_SESSION['admin'] = true;
                            directToHomePage();
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
                    echo "<div class='alert alert-warning'>Username not found.</div>";
                } else if (!$loginResult == 0) {
                    echo "<div class='alert alert-warning'>Incorrect password.</div>";
                }
            }
        }
    ?>
	<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>">
		<h2>Login Form</h2>
        <hr>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input class="form-control" type="text" placeholder="Enter email" name="email" id="email"/>
        </div>
        <div class="form-group">
            <label for="pass">Password</label>
            <input class="form-control" type="password" placeholder="Password" name="password" id="pass"/>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="rmb" value="1"/>
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn-primary" name="login" value="Login">Login</button>
	</form>
    Have no account? <a href="signup.php" class="button right">Sign up</a>
    </div>
    </div>
<?php require_once 'footer.php'; ?>
</body>
</html>
