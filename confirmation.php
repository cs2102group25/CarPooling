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
      directToLoginPage();
    }
    if (!isset($_POST['payment'])) {
      directToBookingPage();
    }
    $tripCount = count($_POST['trip']);
    
    // handle transaction here
    $totalPrice = 0;
    for ($i = 0; $i < $tripCount; $i++) {
        $car_info = explode("_", $_POST['trip'][$i]);
        $carCostQuery = "SELECT p.price FROM provides_trip p WHERE p.car_plate = '$car_info[0]' AND p.seat_no = $car_info[1] AND p.start_time = '".date('Y-m-d h:i:s', $car_info[2])."';";
        $carCostResult = pg_query($carCostQuery);
        $line = pg_fetch_row($carCostResult);
        $totalPrice += $line[0];
    }
    $transactionTime = date('Y-m-d h:i:s', time());
    $transactionQuery = "INSERT INTO make_transaction (email, time, amount) VALUES ('".$_SESSION['email']."', '".$transactionTime."', $totalPrice);";
    $transactionResult = pg_query($transactionQuery);
    if (!$transactionResult || pg_affected_rows($transactionResult) != 1) {
        exit("Error occurred while making transaction.");
    }
    
    
    // handle booking here
    for ($i = 0; $i < $tripCount; $i++) {
        $car_info = explode("_", $_POST['trip'][$i]);
        $bookingQuery = "INSERT INTO booking (seat_no, car_plate, start_time, email, time) VALUES ('$car_info[1]', $car_info[0], '".date('Y-m--d h:i:s', $car_info[2])."', '".$_SESSION['email']."', '$transactionTime');";
        $bookingResult = pg_query($bookingQuery);
        if (!$bookingResult || pg_affected_rows($bookingResult) != 1) {
            exit("Error occurred while booking.");
        }
    }
    echo "Transaction successful!";
    ?>
	
    <?php
    pg_close($dbconn);
    ?>

  <footer class="footer"> Copyright &#169; CS2102</footer>

  
</body>
</html>