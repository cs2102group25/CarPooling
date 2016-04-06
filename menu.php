<div class="header">
    <div style="float:left; font-size:4vmin;">
        <div class='row'>
            <div class='col-md-12'>
                    <a href="index.php"><b>Car Pooling</b></a>
                <span class="pageTitle">
                    <?php
                    if ($title)
                        echo $title;?>
                </span>
            </div>
        </div>
        
        <div class='row'>
            <div class='col-md-12'>
            <?php    
        if (isset($_SESSION['email'])) {
            echo "<button type='button' class='btn btn-info btn-md' id='my-bookings-btn' onclick='location.href=\"mybookings.php\"'>My Bookings</button>
            <button type='button' class='btn btn-info btn-md' id='my-vehicles-btn' onclick='location.href=\"myvehicles.php\"'>My Vehicles</button>
            <button type='button' class='btn btn-info btn-md' id='my-trips-btn' onclick='location.href=\"mytrips.php\"'>My Trips</button>
            ";
            
            if (isset($_SESSION['admin'])) {
                echo "<button type='button' class='btn btn-danger btn-md' id='all-bookings-btn' onclick='location.href=\"adminbookings.php\"'>All Bookings</button>
                <button type='button' class='btn btn-danger btn-md' id='all-vehicles-btn' onclick='location.href=\"adminvehicles.php\"'>All Vehicles</button>
                <button type='button' class='btn btn-danger btn-md' id='all-trips-btn' onclick='location.href=\"admintrips.php\"'>All Trips</button>";
            }
        }
        ?>
            </div>
        </div>
        
    
        
    </div>
    
    <div style="float:right;">
        <?php    
        if (!isset($_SESSION['email'])) {
            if ($title != 'Sign up' && $title != 'Login') {
          echo "
          <button type='button' class='btn btn-info btn-md' id='login-btn' onclick='location.href=\"login.php\"'>Login</button>
          <button type='button' class='btn btn-info btn-md' id='signup-btn' onclick='location.href=\"signup.php\"'>Sign up</button>";
            }
        } else {
          echo "Logged in as ".$_SESSION['email'].".
          <button type='button' class='btn btn-info btn-sm' id='signup-btn' onclick='location.href=\"logout.php\"'>Logout</button>";
        }
        ?>
    </div>
</div>