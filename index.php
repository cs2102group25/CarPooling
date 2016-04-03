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
    
    $rowsPerPage = 50;
    $query = "SELECT * FROM provides_trip t WHERE TRUE";
    if (isset($_GET['searchForTrip'])) {
        if ($_GET['searchLocation']) {
            $query = $query." AND (start_loc LIKE '%".$_GET['searchLocation']."%' OR end_loc LIKE '%".$_GET['searchLocation']."%')";
        }
        if ($_GET['searchMaxPrice']) {
            $query = $query." AND price < ".$_GET['searchMaxPrice'];
            $condition = true;
        }
    }
    if (isset($_SESSION['email'])) {
        $query .= " AND NOT EXISTS (SELECT * FROM Ownership o WHERE t.car_plate = o.car_plate AND o.email='".$_SESSION['email']."')";
    }
    $result = pg_query($query." LIMIT ".$rowsPerPage
        .(isset($_GET['page'])?" OFFSET ".strval(($_GET['page']-1)*$rowsPerPage):""))
        or die('Query failed: '.pg_last_error());
    $allRowsCount = pg_num_rows(pg_query($query));
    ?>

    <table class="resultTable">
    <tr> <td>
        <form method='get' action="index.php">
            Source: <input type="text" name="searchLocation" id="searchLocation" placeholder="Location"/>
            Maximum price: <input type="number" name="searchMaxPrice" id="searchMaxPrice" min="0"/>
            <input type="submit" name="searchForTrip" value="Search">
            <span style="float:right">
                <?php
                    $searchLink = "index.php?"
                    .(isset($_GET['searchLocation'])?'&searchLocation='.isset($_GET['searchLocation']):'')
                    .(isset($_GET['searchMaxPrice'])?'&searchMaxPrice='.isset($_GET['searchMaxPrice']):'');
                ?>
                <a href="<?=$searchLink?>&page=1">&lt;&lt;</a>
                <?=isset($_GET['page'])&&$_GET['page']>1?'<a href="'.$searchLink.'&page='.strval($_GET['page']-1).'">&lt;</a>':'&lt;'?>
                <input type=text style="height:20px;width:20px;text-align:center" name=page value=<?=isset($_GET['offset'])?$_GET['offset']:1?> />
                / <?=$allRowsCount/$rowsPerPage|0+1?>
                <?=(isset($_GET['page'])?(int)$_GET['page']:1)<=$allRowsCount/$rowsPerPage|0?'<a href="'.$searchLink.'&page='.strval(isset($_GET['page'])?$_GET['page']+1:2).'">&gt;</a>':'&gt;'?>
                <a href="<?=$searchLink?>&page=<?=$allRowsCount/$rowsPerPage|0+1?>">&gt;&gt;</a>
            </span>
        </form>
        
        <form method='post' action="payment.php">
        <div class='container' style="margin: 10px 0"><div class='row'>
        <?php 
        $arrayTitle = ["Selected", "From", "To", "Start Time", "End Time", "Seat No.", "Price", "Vehicle"];
        for ($i = 0; $i < count($arrayTitle); $i++) {
            if ($i == 1 || $i == 2 || $i == 3 || $i == 4) {
                echo "<div class='col-lg-2 col-md-2 result'>".$arrayTitle[$i]."</div>";
            } else {
                echo "<div class='col-lg-1 col-md-1 result'>".$arrayTitle[$i]."</div>";
            }
        }
        ?>
        </div>
        <?php
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
        <?php
            require_once 'libs.php';

            if (!isset($_SESSION['email'])) {
                //exit;
            }

            echo "<button type='submit'>Book</button>";
            pg_close($dbconn);
        ?>
        </form>
    </td> </tr>
    </table>
<?php require_once 'footer.php'; ?>
</body>
</html>