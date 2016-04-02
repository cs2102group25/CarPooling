
<!DOCTYPE html>
<html>
<?php
session_start();
$title = "My Vehicles";
require_once 'php/sqlconn.php';
require_once 'libs.php';
require_once 'header.php';
require_once 'menu.php';
if (!isset($_SESSION['email'])) {
    directToLoginPage();
}
    
if (isset($_POST['addVehicle'])) {
    $addCarQuery = "INSERT INTO car (car_plate, model) VALUES('".$_POST['car_plate']."', '".$_POST['model']."');";
    $addCarResult = pg_query($addCarQuery);
    
    $addOwnershipQuery = "INSERT INTO ownership (email, car_plate, expiration) VALUES('".$_SESSION['email']."', '".$_POST['car_plate']."', '".$_POST['expiration']."');";
    $addOwnershipResult = pg_query($addOwnershipQuery);
}
?>
    <table class="resultTable">
    <tr>
        <td>
    <?php
echo "<div class='container'>";
$vehicleQuery = "SELECT c.car_plate, c.model FROM car c, ownership o WHERE c.car_plate = o.car_plate AND o.email = '".$_SESSION['email']."';";

$arrayTitle = ['Plate No.', 'Model'];
echo "<div class='row result'>";

echo "<div class='col-md-6'>".$arrayTitle[0]."</div>";
echo "<div class='col-md-6'>".$arrayTitle[1]."</div>";

echo "</div>";

$vehicleResult = pg_query($vehicleQuery);

if ($vehicleResult && ($vehicleCount = pg_num_rows($vehicleResult)) > 0) {
    for ($i = 0; $i < $vehicleCount; $i++) {
        $row = pg_fetch_row($vehicleResult);

        echo "<div class='row result'>";
            echo "<div class='col-md-6'>".$row[0]."</div>";
            echo "<div class='col-md-6'>".$row[1]."</div>";
        echo "</div>";
    }
} else {
    echo "<div class='row result'>";
        echo "<div class='col-md-12'>You have no vehicles.</div>";
    echo "</div>";
}
    

echo "</div>";
?>
        </td>
        </tr>
        <tr>
            <td>
Add new vehicle
    <form method="post" action="myvehicles.php">
    <div class="addInfo">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Car Plate No.
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="car_plate" placeholder="Car plate number" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Model
            </div>
            <div class="col-lg-8 col-md-8">
                <input type="text" name="model" placeholder="Model"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                Ownership Expiration
            </div>
            <div class="col-lg-8 col-md-8">
                <input id="datepicker" type="text" name="expiration" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="addVehicle" value="Add vehicle" />
            </div>
        </div>
    </div>
    </form>
            </td>
        </tr>
    </table>
    <script>
        $('#datepicker').datepicker();
    </script>
</html>