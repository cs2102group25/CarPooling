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
    <?php
    ?>
	<div class="header">
		<div style="float:left; font-size:4vmin;"><b>Trips available</b></div>
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

  <table class="resultTable">
    <tr>
      <td>
        <form method='get' action="index.php">
                Location: <input type="text" name="searchQuery" id="searchQuery" placeholder="Location">
                 <input type="submit" name="searchForTrip" value="Search">
        </form>
        <form method='post'>
          <?php require_once 'php/sqlconn.php';
          $arrayTitle = ["From", "To", "When", "Duration", "No. Seats", "By", "Price", "Plate no", "Actions"];

          $query;
          if (isset($_GET['searchForTrip'])) {
            $query = 'SELECT * FROM trip WHERE pick_up LIKE \'%'.$_GET['searchQuery'].'%\' OR drop_off LIKE \'%'.$_GET['searchQuery'].'%\'';
          } else {
            $query = 'SELECT * FROM provides_trip';
          }

          $result = pg_query($query) or die('Query failed: '.pg_last_error());

          echo "<div class='container'><div class='row'>";	
          for($i = 0; $i < count($arrayTitle); $i++) {
            if($i == 0 || $i == 1 || $i == 7) {
             echo "<div class='col-lg-2 col-md-2 result'>".$arrayTitle[$i]."</div>";
           }
           else {
             echo "<div class='col-lg-1 col-md-1 result'>".$arrayTitle[$i]."</div>";
           }
         }
         echo "</div>";

        while ($line = pg_fetch_row($result)) {
          echo "<div class='row'>";
          for ($i = 0; $i < count($line); $i++) {
            if($i == 0 || $i == 1) {}
          else if($i == 2 || $i == 3 || $i == 9) {
            echo "<div class='col-lg-2 col-md-2 result'>".$line[$i]."</div>";	
          } else {
            echo "<div class='col-lg-1 col-md-1 result'>".$line[$i]."</div>";
          }
        }
        echo "<div class='col-lg-1 col-md-1 result'><input type='submit' name=delete[] value='Delete'><br>
        <input type='submit' name=update[] value='Update'></div></div>";
      }
      echo "</div>";
      pg_free_result($result);
      ?>
    </form>
  </td> </tr>

  <tr> <td>
    Add new trip
    <form method="post" action="index.php">
      Plate No <input type="text" name="plate-no" placeholder="Your car plate number"><br>
      Pick-up <input type="text" name="pick-up" placeholder="Pick-up"><br>
      Drop-off <input type="text" name="drop-off" placeholder="Drop-off"><br>
      Time <input type="text" name="time" value="00:00:00"><br>
      Est. duration <input type="text" name="duration" placeholder="0 minutes"><br>
      Seats available <input type="number" name="seat-available" value=1 min=1><br>
      Vehicle <input type="text" name="vehicle" placeholder="Vehicle mode"><br>
      Price <input type="text" name="price" placeholder="0"><br>
      <input type="submit" name="add" value="Add">
    </form>

    <?php require_once 'php/sqlconn.php';
        require_once 'libs.php';

        if(!isset($_SESSION['email'], $_SESSION['password'])) {
          exit;
        }

        // Add Functions
        if(isset($_POST['add'], $_POST['pick-up'], $_POST['drop-off'], $_POST['time'], $_POST['duration'], $_POST['seat-available'],
          $_POST['vehicle'], $_POST['price'], $_POST['plate-no'])){

          $num_rows = countingRows("trip");
          $query = "INSERT INTO trip(trip_id, email, pick_up, drop_off, time, duration, seat_available, vehicle, price, plate_no)
          VALUES(".($num_rows + 1).", '".$_SESSION['user_name']."', '".$_POST['pick-up']."', '".$_POST['drop-off']."', '".$_POST['time']."', '"
          .$_POST['duration']."', ".$_POST['seat-available'].", '".$_POST['vehicle']."', ".$_POST['price'].", '".$_POST['plate-no']."')";

          $result = pg_query($query) or die('Query failed: '.pg_last_error());
          
          if ($result) {
            echo "New trip added";
          }  else {
            echo "Trip not added";
          }
        } else {
          echo "Missing input<br>";
        }

        // Delete + Update functions
        if (isset($_POST['actions']) && isset($_POST['delete'])) {
          $query = "DELETE FROM trip WHERE trip_id IN (".implode(",", $_POST['delete']).")";
          $result = pg_query($query) or die('Query failed: '.pg_last_error());
          if ($result) {
            echo "Trip(s) deleted";
          } else {
            echo "Trip(s) not deleted";
          }
        }

        if (isset($_POST['actions']) && isset($_POST['update'])) {
          $query = "UPDATE FROM trip WHERE trip_id IN (".implode(",", $_POST['update']).")";
          $result = pg_query($query) or die('Query failed: '.pg_last_error());
          if ($result) {
            echo "Trip(s) updated";
          } else {
            echo "Trip(s) not updated";
          }
        }
    ?>
    <?php
    pg_close($dbconn);
    ?>
</table>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>