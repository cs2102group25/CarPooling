<!DOCTYPE html>
<?php
session_start();
$title = "Home";
?>
<html>
<head>
    <title>CS2102 Car Pooling</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php require_once 'header.php'; ?>
</head>

<body>
    <?php
    require_once 'menu.php';
    require_once 'php/sqlconn.php';
    require_once 'libs.php';

    if (!isset($_SESSION['email'])) {
        //exit;
    }

    // Booking
    if (isset($_POST['book'])) {
        $car_info = explode("_", $_POST['book']);
    }
    ?>

    <table class="resultTable">
    <tr> <td>
        <form method='get' action="index.php">
            Source: <input type="text" name="searchLocation" id="searchLocation" placeholder="Location"/>
            Maximum price: <input type="number" name="searchMaxPrice" id="searchMaxPrice" min="0"/>
            <input type="submit" name="searchForTrip" value="Search">
        </form>
        <?php 
        require_once 'php/sqlconn.php';
        $arrayTitle = ["Selected", "From", "To", "Start Time", "End Time", "Seat No.", "Price", "Vehicle"];
        $query = 'SELECT * FROM provides_trip t';
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
        }
        if (isset($_SESSION['email'])) {
            $query .= " WHERE NOT EXISTS (SELECT * FROM Ownership o WHERE t.car_plate = o.car_plate AND o.email='".$_SESSION['email']."');";
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
            echo "<div class='col-lg-1 col-md-1 result'><input type='checkbox' name=book[] value='".$line[1]."_".$line[0]."_".$timestamp."'/></div>";
            foreach (array(5, 6, 3, 4) as &$c) {
                echo "<div class='col-lg-2 col-md-2 result'>".$line[$c]."</div>";	
            }
            foreach (array(0, 2, 1) as &$c) {
                echo "<div class='col-lg-1 col-md-1 result'>".$line[$c]."</div>";	
            }
            
            echo "</div>";
        }
        pg_free_result($result);
        ?>
    </td> </tr>

    <tr> <td>
        <form method='post' action="payment.php">
        <?php
            require_once 'libs.php';

            if (!isset($_SESSION['email'])) {
                //exit;
            }

            echo "<button type='submit'>Book</button>";
            // Booking Functions
            if (isset($_POST['book'])) {
                
            }
            pg_close($dbconn);
        ?>
        </form>
    </td> </tr>
    </table>
<?php require_once 'footer.php'; ?>
</body>
</html>