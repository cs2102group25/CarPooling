<!DOCTYPE html>
<?php
session_start();
$title = "Home";
?>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php 
    require_once 'header.php';
    require_once 'menu.php';
    require_once 'php/sqlconn.php';
    require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          exit;
        }

        // Booking
        if (isset($_POST['book'])) {
            $car_info = explode("_", $_POST['book']);
        }
    ?>
	
  <table class="resultTable">
    <tr>
      <td>
        <form method='get' action="index.php">
            Source: <input type="text" name="searchLocation" id="searchLocation" placeholder="Location"/>
            Maximum price: <input type="number" name="searchMaxPrice" id="searchMaxPrice" min="0"/>
                 <input type="submit" name="searchForTrip" value="Search">
            
        </form>
        <?php 
          require_once 'php/sqlconn.php';
          $arrayTitle = ["Selected", "From", "To", "Start Time", "End Time", "Seat No.", "Price", "Vehicle"];
          $query = 'SELECT * FROM provides_trip';
          if (isset($_GET['searchForTrip'])) {
              $query = $query.' WHERE ';
              $condition = false;
              if ($_GET['searchLocation']) {
                  $query = $query.'(start_loc LIKE \'%'.$_GET['searchLocation'].'%\' OR end_loc LIKE \'%'.$_GET['searchLocation'].'%\')';
                  $condition = true;
              }
              if ($_GET['searchMaxPrice']) {
                  if ($condition) {
                      $query = $query.' AND ';
                  }
                  $query = $query.'price < '.$_GET['searchMaxPrice'];
                  $condition = true;
              }
              $query = $query.';';
          } else {
            $query = 'SELECT * FROM provides_trip';
          }

          $result = pg_query($query) or die('Query failed: '.pg_last_error());

          echo "<div class='container'><div class='row'>";	
          for ($i = 0; $i < count($arrayTitle); $i++) {
            if ($i == 1 || $i == 2 || $i == 3 || $i == 4) {
              echo "<div class='col-lg-2 col-md-2 result'>".$arrayTitle[$i]."</div>";
            } else {
              echo "<div class='col-lg-1 col-md-1 result'>".$arrayTitle[$i]."</div>";
            }
         }
         echo "</div>";

         while ($line = pg_fetch_row($result)) {
           echo "<div class='row'>";
           $timestamp = strtotime($line[3]);
           echo "<div class='col-lg-1 col-md-1 result'><input type='checkbox' name=book[] value='".$line[1]."_".$line[0]."_".$timestamp."' / >  </div>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[5]."</div>";	
           echo "<div class='col-lg-2 col-md-2 result'>".$line[6]."</div>";	

           echo "<div class='col-lg-2 col-md-2 result'>".$line[3]."</div>";
           echo "<div class='col-lg-2 col-md-2 result'>".$line[4]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[0]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[2]."</div>";

           echo "<div class='col-lg-1 col-md-1 result'>".$line[1]."</div>";
           echo "</div>";
         }
         pg_free_result($result);
      ?>
  </td> </tr>

        <form method='post' action="payment.php">
  <tr> <td>
    <?php require_once 'php/sqlconn.php';
        require_once 'libs.php';

        if (!isset($_SESSION['email'])) {
          exit;
        }

      echo "<button type='submit'>Book</button>";
        // Booking Functions
        if (isset($_POST['book'])) {
            
        }

    ?>
    <?php
    pg_close($dbconn);
    ?>
      </td>
    </tr>
</form>
</table>
</body>
    <?php require_once 'footer.php'; ?>
</html>