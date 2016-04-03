<!DOCTYPE html>
<html>
<body>
<?php
session_start();
$title = "My Vehicles";
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

if(isset($_POST['deleteVehicle'])) {
    $delOwnershipQuery = "DELETE FROM Ownership WHERE car_plate='".$_POST['deleteVehicle']."'";
    $delOwnershipResult = pg_query($delOwnershipQuery);

    $delBookingQuery = "DELETE FROM Booking WHERE car_plate='".$_POST['deleteVehicle']."'";
    $delBookingResult = pg_query($delBookingQuery);

    $delTripQuery = "DELETE FROM Provides_Trip WHERE car_plate='".$_POST['deleteVehicle']."'";
    $delTripResult = pg_query($delTripQuery);

    $delCarQuery = "DELETE FROM Car WHERE car_plate='".$_POST['deleteVehicle']."'";
    $delCarResult = pg_query($delCarQuery);

}
?>
    <table class="resultTable">
        <tr>
            <td>
                <?php
                echo "<div class='container'>";
                $vehicleQuery = "SELECT c.car_plate, c.model, o.expiration FROM car c, ownership o WHERE c.car_plate = o.car_plate AND o.email = '".$_SESSION['email']."';";

                $arrayTitle = ['Plate No.', 'Model', 'Expiration', 'Delete'];
                echo "<div class='row result'>";

                echo "<div class='col-md-3'>".$arrayTitle[0]."</div>";
                echo "<div class='col-md-3'>".$arrayTitle[1]."</div>";
                echo "<div class='col-md-3'>".$arrayTitle[2]."</div>";
                echo "<div class='col-md-3'>".$arrayTitle[3]."</div>";

                echo "</div>";

                $vehicleResult = pg_query($vehicleQuery);
                if(!$vehicleResult) exit("There's error in displaying vehicle results");

                if (($vehicleCount = pg_num_rows($vehicleResult)) > 0) {
                    for ($i = 0; $i < $vehicleCount; $i++) {
                        $row = pg_fetch_row($vehicleResult);

                        echo "<div class='row result'>";
                        echo "<div class='col-md-3'>".$row[0]."</div>";
                        echo "<div class='col-md-3'>".$row[1]."</div>";
                        echo "<div class='col-md-3'>".$row[2]."</div>";
                        echo "<div class='col-md-3'><form method='post' action='myvehicles.php'>
                        <button type='submit' name='deleteVehicle' value='".$row[0]."'>Delete</button></form></div>";
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
    <?php
    if (isset($_POST['addVehicle'])) {
        if ($addOwnershipResult && $addCarResult) {
            echo "<div class='alert alert-success'>Vehicle added!</div>";
        } else {
            echo "<div class='alert alert-warning'>Please set all fields</div>";
        }
    }
    
    if (isset($_POST['deleteVehicle'])) {
        if ($delOwnershipResult && $delBookingResult && $delTripResult && $delCarResult) {
            echo "<div class='alert alert-success'>Vehicle deleted!</div>";
        } else {
            echo "<div class='alert alert-warning'>Error deleting vehicle.</div>";
        }
    }
    ?>
    <?php require_once 'footer.php'; ?>
    <script>
    $('#datepicker').datepicker();
    </script>
</body>
</html>