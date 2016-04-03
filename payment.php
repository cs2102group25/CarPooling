<!DOCTYPE html>
<?php
session_start();
?>
<html>
<?php
    require_once 'header.php';
    require_once 'menu.php';
    require_once 'php/sqlconn.php';
    require_once 'libs.php';

    if (!isset($_SESSION['email'])) {
      directToLoginPage();
    }
    if (!isset($_POST['book'])) {
      directToHomePage();
    }
?>
<body>
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

        
         $tripCount = count($_POST['book']);    
         for ($i = 0; $i < $tripCount; $i++) {
           $car_info = explode("_", $_POST['book'][$i]);
           $tripQuery = "SELECT * FROM provides_trip p WHERE p.car_plate = '$car_info[0]' AND p.seat_no = $car_info[1] AND p.start_time = '".date('Y-m-d H:i:s', $car_info[2])."';";
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
      ?>
    </form>
  </td> </tr>

  <tr> <td>
      <form method="post" action="confirmation.php">
    <?php

        if (!isset($_SESSION['email'])) {
          exit;
        }

        // Booking Functions
        for ($i = 0; $i < $tripCount; $i++) {
          echo "<input type='text' name=trip[] value='".$_POST['book'][$i]."' hidden/>";
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
</body>
    <?php require_once 'footer.php'; ?>
</html>