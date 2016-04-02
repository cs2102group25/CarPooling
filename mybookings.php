
<!DOCTYPE html>
<html>
<?php
session_start();
$title = "My Bookings";
require_once 'php/sqlconn.php';
require_once 'libs.php';
require_once 'header.php';

if (!isset($_SESSION['email'])) {
    directToLoginPage();
}
    
echo "Your bookings:";

echo "<div class='container'>";
$bookingQuery = "SELECT p.start_time, p.end_time, p.start_loc, p.end_loc FROM provides_trip p, make_transaction m, booking b WHERE m.email = '".$_SESSION['email']."' AND m.time = b.time AND m.email = b.email;";
$bookingResult = pg_query($bookingQuery);
    
$arrayTitle = ['Start Time', 'End Time', 'Source', 'Destination'];
echo "<div class='row result'>";
for ($i = 0; $i < count($arrayTitle); $i++ ) {
    echo "<div class='col-md-3'>".$arrayTitle[$i]."</div>";
}
echo "</div>";

if ($bookingResult) {
    $bookingCount = pg_num_rows($bookingResult);
    for ($i = 0; $i < $bookingCount; $i++) {
        $row = pg_fetch_row($bookingResult);

    echo "<div class='row result'>";
    for ($i = 0; $i < count($arrayTitle); $i++ ) {
        echo "<div class='col-md-3'>".$row[$i]."</div>";
    }

    echo "</div>";
    }
}

echo "</div>";


?>
</html>