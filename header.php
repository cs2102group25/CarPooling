<div class="header">
    <div style="float:left; font-size:4vmin;"><a href="index.php"><b>Car Pooling</b></a><br/><?php
    if ($title)
        echo $title;
    else
        echo "Unknown page";
    ?><div style="float:right;">
        <?php    
        if (isset($_SESSION['email'])) {
          echo "<button type='button' class='btn btn-info btn-lg' id='my-bookings-btn' onclick='location.href=\"mybookings.php\"'>My Bookings</button>
          <button type='button' class='btn btn-info btn-lg' id='my-vehicles-btn' onclick='location.href=\"myvehicles.php\"'>My Vehicles</button>
          <button type='button' class='btn btn-info btn-lg' id='my-trips-btn' onclick='location.href=\"mytrips.php\"'>My Trips</button>";
        }
        ?>
    </div></div>
    
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