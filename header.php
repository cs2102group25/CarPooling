<link rel="stylesheet" type="text/css" href="style.css">
<script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="js/js.js" type="text/javascript"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<div class="header">
  <div style="float:left; font-size:4vmin;"><b>Car Pooling</b></div>
    <div style="float:right;">
        <?php    
        if (!isset($_SESSION['email'])) {
          echo "
          <button type='button' class='btn btn-info btn-lg' id='login-btn' onclick='location.href=\"login.php\"'>Login</button>
          <button type='button' class='btn btn-info btn-lg' id='signup-btn' onclick='location.href=\"signup.php\"'>Sign up</button>";
        } else {
          echo "Logged in as ".$_SESSION['email'].".
          <button type='button' class='btn btn-info btn-lg' id='signup-btn' onclick='location.href=\"logout.php\"'>Logout</button>";
        }
        ?>
    </div>
</div>