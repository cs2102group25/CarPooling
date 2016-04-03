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
    <br/>
    <div class="container fill col-lg-12 col-sm-4" id="form1">
    <div class="well col-lg-4 col-lg-offset-4 col-sm-12 fill">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<h2>Sign Up Form</h2>
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
                <input type="checkbox" name="admin" value="admin"/>
                Admin
            </label>
        </div>
        <button type="submit" class="btn btn-primary" name="sign-up" value="Sign up"/>Sign up</button>
	</form>
    </div>
    </div>
<?php 
if ($posted && !$result) {
    echo "Error signing up, please try again.";
}
?>
<?php require_once 'footer.php'; ?>
</body>
</html>