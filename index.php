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
          exit;
        }

        // Add Functions
        if (isset($_POST['pick-up'], $_POST['drop-off'], $_POST['time'], $_POST['duration'], $_POST['seats'],
         $_POST['price'], $_POST['vehicle'])) {

          for ($i = 0; $i < $_POST['seats']; $i++) {
            $endTime = date('Y-m-d h:i:s', strtotime("+".$_POST['duration']." minutes", strtotime($_POST['time'])));
            $addQuery = "INSERT INTO provides_trip(seat_no, car_plate, price, start_time, end_time, start_loc, end_loc, posted)
            VALUES(".($i+1).", '".$_POST['vehicle']."', '".$_POST['price']."', '".$_POST['time']."', '$endTime', '".$_POST['pick-up']."', '".$_POST['drop-off']."', 'true')";

            $addResult = pg_query($addQuery);
            if (!$addResult) $addError = true;
          }
          
        }

        // Delete
        if (isset($_POST['delete'])) {
            $car_info = explode("_", $_POST['delete']);
            $deleteQuery = "DELETE FROM provides_trip WHERE car_plate = '$car_info[0]' AND seat_no = $car_info[1];";
            $deleteResult = pg_query($deleteQuery);
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
          $arrayTitle = ["From", "To", "When", "Duration", "Seat No.", "Price", "Vehicle", "Actions"];

          $query;
          if (isset($_GET['searchForTrip'])) {
            $query = 'SELECT * FROM provides_trip WHERE start_loc LIKE \'%'.$_GET['searchQuery'].'%\' OR end_loc LIKE \'%'.$_GET['searchQuery'].'%\'';
          } else {
            $query = 'SELECT * FROM provides_trip';
          }

          $result = pg_query($query) or die('Query failed: '.pg_last_error());

          echo "<div class='container'><div class='row'>";	
          for ($i = 0; $i < count($arrayTitle); $i++) {
            if ($i == 0 || $i == 1 || $i == 2 || $i == 6) {
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
               echo "<div class='col-lg-1 col-md-1 result'>".$line[4]."</div>";
               
               echo "<div class='col-lg-1 col-md-1 result'>".$line[0]."</div>";
               
               echo "<div class='col-lg-1 col-md-1 result'>".$line[2]."</div>";
               
               echo "<div class='col-lg-2 col-md-2 result'>".$line[1]."</div>";
           echo "<div class='col-lg-1 col-md-1 result'><button type='submit' name=delete value='".$line[1]."_".$line[0]."'] >Delete</button></div>";
      echo "</div>";
         }
      pg_free_result($result);
      ?>
    </form>
  </td> </tr>

  <tr> <td>
    Add new trip
    <form method="post" action="index.php">
    <div class="addTrip">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Vehicle:
            </div>
            <div class="col-lg-8 col-md-8">
                <select name="vehicle">
                    <?php
                    $vehicleQuery = "SELECT c.car_plate, c.model FROM car c, ownership o WHERE '".$_SESSION['email']."' = o.email AND o.car_plate = c.car_plate;";
                    $vehicleResult = pg_query($vehicleQuery)or die('Query failed: '.pg_last_error());;
                    $numRows = pg_num_rows($vehicleResult);
                    for ($i = 0; $i < $numRows; $i++) {
                        $curVehicle = pg_fetch_row($vehicleResult);
                        echo "<option value='".$curVehicle[0]."' >".$curVehicle[0]." (".$curVehicle[1].")"."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Pick-up Point
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="pick-up" placeholder="Pick-up" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Drop-off Point
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="drop-off" placeholder="Drop-off"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Starting time
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="time" value="<?php echo date('Y-m-d h:i:s', time() + 7*24*60*60); ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Estimated Duration
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="duration" value="30 minutes"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Seats available
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="number" name="seats" value=1 min=1 />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Price
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="price" value="0" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="add" value="Add" />
            </div>
        </div>
    </div>
    </form>

    <?php require_once 'php/sqlconn.php';
        require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          exit;
        }

        // Add Functions
        if (isset($_POST['add'])) {
          if (isset($_POST['pick-up'], $_POST['drop-off'], $_POST['time'], $_POST['duration'], $_POST['seats'],
             $_POST['price'], $_POST['vehicle'])) {
             if (!$addError) {
               echo "New trip added!";
             }  else {
               echo "Error occurred.";
             }
           } else {
             echo "Please fill in all fields.";
           }
        }

        // Delete
        if (isset($_POST['delete'])) {
          if ($deleteResult) {
            echo "Trip deleted";
          } else {
            echo "Error occurred deleting trip.";
          }
        }
    ?>
    <?php
    pg_close($dbconn);
    ?>
      </td>
      </tr>
</table>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>