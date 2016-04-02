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
	
        <form method='post' action="payment.php">
  <table class="resultTable">
    <tr>
      <td>
        <form method='get' action="index.php">
                Location: <input type="text" name="searchQuery" id="searchQuery" placeholder="Location">
                 <input type="submit" name="searchForTrip" value="Search">
        </form>
        <?php 
          require_once 'php/sqlconn.php';
          $arrayTitle = ["Selected", "From", "To", "Start Time", "End Time", "Seat No.", "Price", "Vehicle"];
          if (isset($_GET['searchForTrip'])) {
            $query = 'SELECT * FROM provides_trip WHERE start_loc LIKE \'%'.$_GET['searchQuery'].'%\' OR end_loc LIKE \'%'.$_GET['searchQuery'].'%\'';
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
</table>

    </form>
  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>