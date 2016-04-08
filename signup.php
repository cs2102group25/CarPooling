<!DOCTYPE html>
<?php
session_start();
$title = "Sign up";
?>
<html>
<?php require_once 'header.php'; ?>
<body>
    <?php 
        require_once 'menu.php';
        if ($_SESSION['email']) directToHomePage();

        $posted = isset($_POST['sign-up'], $_POST['email'], $_POST['password']);
        if ($posted) {
            
            $adminVal = var_export($_POST['admin'] == 'admin', true);
            $query = "INSERT INTO \"user\"(email, password, admin)
            VALUES('".$_POST['email']."', '".$_POST['password']."', '".$adminVal."');";
            $result = pg_query($query);
            
            if (pg_affected_rows($result) == 1) {
                $_SESSION['email'] = $_POST['email'];
                if ($_POST['admin'] == 'admin') {
                    $_SESSION['admin'] = true;
                }
                directToHomePage();
            } else {
                $usernameTaken = true;
            }
        }

        pg_close($dbconn);
    ?>
    <br/>
    <div class="container" id="loginSignupForm">
        <div class="row">
            <div class="well col-md-4 fill">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>Sign Up</h2>
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
                        <input type="checkbox" name="admin" value="admin"/>
                        Admin
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" name="sign-up" value="Sign up">Sign up</button>
            </form>
            Already have an account? <a href="login.php" class="button right">Login</a>
            </div>
            <div class="col-md-8">

            </div>
        </div>
    </div>
<?php 
if ($usernameTaken) {
    echo "Username is already taken!";
} else if ($posted && !$result) {
    echo "Error signing up, please try again.";
}
?>
</body>
<?php require_once 'footer.php'; ?>
</html>