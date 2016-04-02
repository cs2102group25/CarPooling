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