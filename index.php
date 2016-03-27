<!DOCTYPE html>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	<link rel="stylesheet" type="text/css" href="style.css"\>
</head>

<body>
	<div class="header">
		<div style="float:left; font-size:4vmin;"><b>Trips available</b></div>
	</div>

  <table class="resultTable">
    <tr>
      <?php
      $dbconn = pg_connect("host=localhost port=5433 dbname=postgres user=postgres password=postgres")
      or die('Could not connect: '.pg_last_error());
      if (isset($_POST['add'])) {
        $query = "INSERT INTO trip VALUES('".$_POST['src']."', '".$_POST['dest']."', '".$_POST['time']."', ".$_POST['dura'].", ".$_POST['seat'].", '".$_POST['vehicle']."', ".$_POST['cost'].", '".$_POST['note']."')";
        $result = pg_query($query) or die('Query failed: '.pg_last_error());
        if ($result == true) {
          echo "New trip added";
        } else {
          echo "Trip not added";
        }
      }
      if (isset($_POST['delete']) && isset($_POST['sel'])) {
        $query = "DELETE FROM trip WHERE id IN (".implode(",", $_POST['sel']).")";
        $result = pg_query($query) or die('Query failed: '.pg_last_error());
        if ($result == true) {
          echo "Trip(s) deleted";
        } else {
          echo "Trip(s) not deleted";
        }
      }
      ?>
      <td>
        <form>
                  Location: <input type="text" name="searchQuery" id="searchQuery" value="<?php echo $_GET['searchQuery'] ?>"\>
                  <input type="submit" name="searchForTrip" value="Search"\>
        </form>
        <form method='post'>
          <?php
          $arrayTitle = ["From", "To", "When", "How long", "No. of Seats", "By", "Price", "Notes", "#", "Delete"];

          if (isset($_GET['searchForTrip'])) {
            $query = 'SELECT * FROM trip WHERE src LIKE \'%'.$_GET['searchQuery'].'%\' OR dest LIKE \'%'.$_GET['searchQuery'].'%\'';
          } else {
            $query = 'SELECT * FROM trip;';
          }
          $result = pg_query($query) or die('Query failed: '.pg_last_error());
          echo "<b> SQL: </b>".$query."<br/><br/>";

          echo "<div class='container'><div class='row result'>";	
          for($i = 0; $i < count($arrayTitle) - 1; $i++) {	
            if($i == 7) {	
             echo "<div class='col-lg-3 col-md-3'>".$arrayTitle[$i]."</div>";	
           } else {
             echo "<div class='col-lg-1 col-md-1'>".$arrayTitle[$i]."</div>";
           }
         }
         echo "<div class='col-lg-1 col-md-1 result'><input type=submit name='delete' value='Delete'\></div></div>";

         while ($line = pg_fetch_row($result)) {
          echo "<div class='row result'>";
          for ($i = 0; $i < count($line); $i++) {
           if($i == 7) {	
            echo "<div class='col-lg-3 col-md-3'>".$line[$i]."</div>";	
          } else {
            echo "<div class='col-lg-1 col-md-1'>".$line[$i]."</div>";
          }
        }
        echo "<div><input type=checkbox name=sel[] value=checkbox></div></div>";
      }
      echo "</div>";
      pg_free_result($result);
      ?>
    </form>
  </td> </tr>
  <tr> <td>
    Add new trip
    <form method=POST action=index.php>
      Pick-up <input type=text name=src value=Pick-up><br>
      Drop-off <input type=text name=dest value=Drop-off><br>
      Time <input type=text name=time value="00:00:00"><br>
      Est. duration <input type=text name=dura value=0><br>
      Number of seats <input type=number name=seat value=1 min=1><br>
      Vehicle <input type=radio name=vehicle value=Limo checked> Limo
      <input type=radio name=vehicle value=Bus> Bus<br>
      Price <input type=text name=cost value=0><br>
      Note <input type=text name=note><br>
      <input type=submit name=add value="Add">
    </form>
    <?php
    pg_close($dbconn);
    ?>
  </table>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  <script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>