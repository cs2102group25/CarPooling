
<!DOCTYPE html>
<html>
<?php
session_start();
$title = "All Bookings";
require_once 'header.php';
require_once 'menu.php';

if (!isset($_SESSION['email'])) {
    directToLoginPage();
}
echo "<table class='resultTable table table-striped table-bordered table-hover'><tr><td>";
echo "<div class='container recordTable'>";
$bookingQuery = "SELECT u.email, p.start_time, p.end_time, p.start_loc, p.end_loc FROM \"user\" u, provides_trip p, make_transaction m, booking b WHERE m.time = b.time AND m.email = b.email AND b.car_plate = p.car_plate AND b.seat_no = p.seat_no AND b.start_time = p.start_time AND b.email = u.email ORDER BY p.start_time;";
$bookingResult = pg_query($bookingQuery);
    
$arrayTitle = ['User', 'Start Time', 'End Time', 'Source', 'Destination'];
echo "<div class='row result'>";
for ($i = 0; $i < count($arrayTitle); $i++ ) {
    echo "<div class='col-md-3'>".$arrayTitle[$i]."</div>";
}
echo "</div>";

if ($bookingResult && ($bookingCount = pg_num_rows($bookingResult)) > 0) {
    for ($i = 0; $i < $bookingCount; $i++) {
        $row = pg_fetch_row($bookingResult);

        echo "<div class='row result'>";
        for ($i = 0; $i < count($arrayTitle); $i++ ) {
            echo "<div class='col-md-2'>".$row[$i]."</div>";
            if ($i == 3 || $i == 4) {
                echo "<div class='col-md-3'>".$row[$i]."</div>";
            }
        }
        echo "</div>";
    }
} else {
    echo "<div class='row result'>";
    echo "<div class='col-md-12'>There are no bookings.</div>";
    echo "</div>";
}

echo "</div>";
echo "</td></tr></table>";

?>
<?php require_once 'footer.php'; ?>
</html>