<!DOCTYPE html>
<?php
$title = "Home";
?>
<html>
<?php require_once 'header.php'; ?>
    <body>
    <?php
    require_once 'menu.php';
    
    $rowsPerPage = 50;
    if (isset($_SESSION['email']) && !isset($_GET['searchWithOwnTrips'])) {
        $query = "SELECT * 
        FROM provides_trip t, ownership o 
        WHERE NOT EXISTS (
            SELECT * FROM booking b
            WHERE b.seat_no = t.seat_no AND b.car_plate = t.car_plate AND b.start_time = t.start_time
            ) 
        AND t.car_plate = o.car_plate 
        AND o.email <> '".$_SESSION['email']."'";
    } else {
        $query = "SELECT * FROM provides_trip t WHERE NOT EXISTS (
            SELECT * FROM booking b
            WHERE b.seat_no = t.seat_no AND b.car_plate = t.car_plate AND b.start_time = t.start_time
            )";
    }
    if (isset($_SESSION['email'])) {
        if (isset($_GET['searchForTrip'])) {
            $includeSelf = $_GET['searchWithOwnTrips'];
        } else {
            $includeSelf = false;
        }
    }
    if (isset($_GET['searchForTrip'])) {
        if ($_GET['searchLocation']) {
            $query .= " AND (t.start_loc LIKE '%".$_GET['searchLocation']."%' OR t.end_loc LIKE '%".$_GET['searchLocation']."%')";
        }
        if ($_GET['searchMaxPrice']) {
            $query .= " AND t.price < ".$_GET['searchMaxPrice'];
        }
    }
    $rowQuery = $query.";";
    $rowResult = pg_query($rowQuery);
    $rowResultError = $rowResult ? false : true;
        
    $allRowsCount = pg_num_rows($rowResult);
        
    $query .= " LIMIT ".$rowsPerPage.(isset($_GET['page'])?" OFFSET ".strval(($_GET['page']-1)*$rowsPerPage):"");
    $result = pg_query($query);
    $resultError = $rowResult ? false : true;
        
    if ($rowResultError || $resultError) {
        $hasError = true;
        echo "<div class='alert alert-warning'>Error obtaining results.</div>";
    }
    ?>

    <table class="resultTable table table-striped table-bordered table-hover">
      <tr>
        <td>
            <!-- search filters and page navigator -->
            <form method='get' action="index.php">
                Location: <input type="text" name="searchLocation" id="searchLocation" placeholder="Location"/>
                Maximum price: <input type="number" name="searchMaxPrice" id="searchMaxPrice" min="0"/>
                <?php if (isset($_SESSION['email'])) {
    echo 'Include my trips: <input type="checkbox" name="searchWithOwnTrips" id="searchWithOwnTrips" '.($includeSelf?'checked':'').'/>';
}
    ?>
                <input type="submit" name="searchForTrip" value="Search">
                <span style="float:right">
                    <?php
                        $searchLink = "index.php?"
                        .(isset($_GET['searchLocation'])?'&searchLocation='.isset($_GET['searchLocation']):'')
                        .(isset($_GET['searchMaxPrice'])?'&searchMaxPrice='.isset($_GET['searchMaxPrice']):'')
                        .(isset($_GET['searchWithOwnTrips'])?'&searchWithOwnTrips='.isset($_GET['searchWithOwnTrips']):'');
                    
                    
                        $maxPages = floor($allRowsCount/$rowsPerPage)+1;
                        $curPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    ?>
                    <a href="<?=$searchLink?>&page=1">&lt;&lt;</a>
                    <?=$curPage>1?'<a href="'.$searchLink.'&page='.($curPage-1).'">&lt;</a>':'&lt;'?>
                    <input type=text style="height:20px;width:20px;text-align:center" name="page" value=<?=$curPage?> /> / <?=$maxPages?>
                    <?=$curPage!=$maxPages?'<a href="'.$searchLink.'&page='.strval(min($curPage+1, $maxPages)).'">&gt;</a>':'&gt;'?>
                    <a href="<?=$searchLink?>&page=<?=$maxPages?>">&gt;&gt;</a>
                </span>
            </form>

            <!-- booking form -->
            <form method='post' action="payment.php">
            <div class='container recordTable'>
                <div class='row'>
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
                if ($hasError || $allRowsCount == 0) {
                    echo "<div class='row'>";
                    echo "<div class='col-lg-12 col-md-12 result'>No trips found. Click <a href='mytrips.php'>here</a> to advertise yours!</div>";
                    echo "</div>";
                } else {
                    while ($line = pg_fetch_row($result)) {
                        echo "<div class='row'>";

                        $timestamp = strtotime($line[3]);
                        echo "<div class='col-lg-1 col-md-1 result'><input type='checkbox' name=book[] value='".$line[1]."_".$line[0]."_".$timestamp."'/></div>";
                        foreach (array(5, 6, 3, 4) as &$c) {
                            echo "<div class='col-lg-2 col-md-2 result'>".$line[$c]."</div>";	
                        }

                        echo "<div class='col-lg-1 col-md-1 result'>".$line[0]."</div>";	
                        $price = $line[2];
                        if ($price == 0) {
                            $price = 'FREE';
                        }
                        echo "<div class='col-lg-1 col-md-1 result'>".$price."</div>";	
                        echo "<div class='col-lg-1 col-md-1 result'>".$line[1]."</div>";	
                        echo "</div>";
                    }
                }
                pg_free_result($result);
                pg_close($dbconn);
                ?>
                <div class='row'>
                <div class='col-lg-12 col-md-12 result'><button type='submit'>Book</button></div>
                </div>
            </div>
            </form>
      </td> 
    </tr>
</table>
</body>
<?php require_once 'footer.php'; ?>
</html>