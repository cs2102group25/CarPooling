<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	<link rel="stylesheet" type="text/css" href="style.css">
    <script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="js/js.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once 'header.php';
    require_once 'php/sqlconn.php';
    require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          directToLoginPage();
        }
        if (!isset($_POST['book'])) {
          directToBookingPage();
        }
    
        $tripCount = count($_POST['book']);
    ?>
	
  <table class="resultTable">
    <tr>
      <td>
        <form method='get' action="index.php">
                Location: <input type="text" name="searchQuery" id="searchQuery" placeholder="Location">
                 <input type="submit" name="searchForTrip" value="Search">
        </form>
        <form method='post'>
        <?php 
          require_once 'php/sqlconn.php';
          $arrayTitle = ["From", "To", "Start Time", "End Time", "Seat No.", "Price", "Vehicle", "Actions"];
          
          echo "<div class='container'><div class='row'>";	
          for ($i = 0; $i < count($arrayTitle); $i++) {
            if ($i == 0 || $i == 1 || $i == 2 || $i == 3) {
              echo "<div class='col-lg-2 col-md-2 result'>".$arrayTitle[$i]."</div>";
            } else {
              echo "<div class='col-lg-1 col-md-1 result'>".$arrayTitle[$i]."</div>";
            }
         }
         echo "</div>";

            
         for ($i = 0; $i < $tripCount; $i++) {
           $car_info = explode("_", $_POST['book'][$i]);
           $tripQuery = "SELECT * FROM provides_trip p WHERE p.car_plate = '$car_info[0]' AND p.seat_no = $car_info[1] AND p.start_time = '".date('Y-m-d h:i:s', $car_info[2])."';";
           $tripResult = pg_query($tripQuery);
           $line = pg_fetch_row($tripResult);
           echo "<div class='row'>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[5]."</div>";	
           echo "<div class='col-lg-2 col-md-2 result'>".$line[6]."</div>";	

           echo "<div class='col-lg-2 col-md-2 result'>".$line[3]."</div>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[4]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[0]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[2]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[1]."</div>";
           echo "<div class='col-lg-1 col-md-1 result'><button type='submit' name=book value='".$car_info[0]."_".$car_info[1]."_".$car_info[2]."'] >Remove (dummy)</button></div>";
           echo "</div>";
         }
         pg_free_result($result);
      ?>
    </form>
  </td> </tr>

  <tr> <td>
      <form method="post" action="confirmation.php">
    <?php require_once 'php/sqlconn.php';
        require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          exit;
        }

        // Booking Functions
        for ($i = 0; $i < $tripCount; $i++) {
          echo "<input type='text' name=trip value='".$_POST['book'][$i]."' hidden/>";
        }

        echo "<button type='submit' name=payment>Make payment for the above trips</button>";

    ?>
      </form>
    <?php
    pg_close($dbconn);
    ?>
      </td>
      </tr>
</table>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>