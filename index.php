<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	
</head>

<body>
    <?php require_once 'header.php';
    require_once 'php/sqlconn.php';
    require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          exit;
        }

        // Add Functions
        if (isset($_POST['pick-up'], $_POST['drop-off'], $_POST['time'], $_POST['duration'], $_POST['seats'],
         $_POST['price'], $_POST['vehicle'])) {
          for ($i = 0; $i < $_POST['seats']; $i++) {
            $endTime = date('Y-m-d h:i:s', strtotime("+".$_POST['duration'], strtotime($_POST['time'])));
            $addQuery = "INSERT INTO provides_trip(seat_no, car_plate, price, start_time, end_time, start_loc, end_loc, posted)
            VALUES(".($i+1).", '".$_POST['vehicle']."', '".$_POST['price']."', '".$_POST['time']."', '$endTime', '".$_POST['pick-up']."', '".$_POST['drop-off']."', 'true')";

            $addResult = pg_query($addQuery);
            if (!$addResult) $addError = true;
          }
        }

        // Delete
        if (isset($_POST['delete'])) {
            $car_info = explode("_", $_POST['delete']);
            $deleteQuery = "DELETE FROM provides_trip p WHERE p.car_plate IN (
            SELECT o.car_plate FROM ownership o WHERE p.car_plate = '$car_info[0]' AND p.seat_no = $car_info[1]
            AND p.car_plate = o.car_plate AND o.email = '".$_SESSION['email']."');";
            $deleteResult = pg_query($deleteQuery);
            $rowsDeleted = pg_affected_rows($deleteResult);
        }
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
          if (isset($_GET['searchForTrip'])) {
            $query = 'SELECT * FROM provides_trip WHERE start_loc LIKE \'%'.$_GET['searchQuery'].'%\' OR end_loc LIKE \'%'.$_GET['searchQuery'].'%\'';
          } else {
            $query = 'SELECT * FROM provides_trip';
          }

          $result = pg_query($query) or die('Query failed: '.pg_last_error());

          echo "<div class='container'><div class='row'>";	
          for ($i = 0; $i < count($arrayTitle); $i++) {
            if ($i == 0 || $i == 1 || $i == 2 || $i == 3) {
              echo "<div class='col-lg-2 col-md-2 result'>".$arrayTitle[$i]."</div>";
            } else {
              echo "<div class='col-lg-1 col-md-1 result'>".$arrayTitle[$i]."</div>";
            }
         }
         echo "</div>";

         while ($line = pg_fetch_row($result)) {
           echo "<div class='row'>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[5]."</div>";	
           echo "<div class='col-lg-2 col-md-2 result'>".$line[6]."</div>";	

           echo "<div class='col-lg-2 col-md-2 result'>".$line[3]."</div>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[4]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[0]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[2]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[1]."</div>";
           echo "<div class='col-lg-1 col-md-1 result'><button type='submit' name=delete value='".$line[1]."_".$line[0]."'] >Delete</button></div>";
           echo "</div>";
         }
         pg_free_result($result);
      ?>
    </form>
  </td> </tr>

    <?php
    pg_close($dbconn);
    ?>
</table>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>