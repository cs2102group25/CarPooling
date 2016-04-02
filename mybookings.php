
<!DOCTYPE html>
<html>
<?php
session_start();
$title = "My Bookings";
require_once 'php/sqlconn.php';
require_once 'libs.php';
require_once 'header.php';
require_once 'menu.php';

if (!isset($_SESSION['email'])) {
    directToLoginPage();
}
echo "<table class='resultTable'><tr><td>";
echo "<div class='container'>";
$bookingQuery = "SELECT p.start_time, p.end_time, p.start_loc, p.end_loc FROM provides_trip p, make_transaction m, booking b WHERE m.email = '".$_SESSION['email']."' AND m.time = b.time AND m.email = b.email AND b.car_plate = p.car_plate AND b.seat_no = p.seat_no AND b.start_time = p.start_time ORDER BY p.start_time;";
$bookingResult = pg_query($bookingQuery);
    
$arrayTitle = ['Start Time', 'End Time', 'Source', 'Destination'];
echo "<div class='row result'>";
for ($i = 0; $i < count($arrayTitle); $i++ ) {
    echo "<div class='col-md-3'>".$arrayTitle[$i]."</div>";
}
echo "</div>";

if ($bookingResult && $bookingCount = pg_num_rows($bookingResult) > 0) {
    for ($i = 0; $i < $bookingCount; $i++) {
        $row = pg_fetch_row($bookingResult);

        echo "<div class='row result'>";
        for ($i = 0; $i < count($arrayTitle); $i++ ) {
            echo "<div class='col-md-3'>".$row[$i]."</div>";
        }
        echo "</div>";
    }
} else {
    echo "<div class='row result'>";
    echo "<div class='col-md-12'>You have no bookings.</div>";
    echo "</div>";
}

echo "</div>";
echo "</td></tr></table>";

?>
</html>