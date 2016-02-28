<html>
<head> <title>CS2102 Car Pooling</title> <link rel="stylesheet" type="text/css" href="style.css"></head>
<body>
<table>
<tr> <td colspan="2" style="background-color:#FFA500;">
<h1>Trips available</h1>
</td> </tr>
<tr>
<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres")
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
if (isset($_POST['sel'])) {
    $query = "DELETE FROM trip WHERE id IN (".implode(",", $_POST['sel']).")";
    $result = pg_query($query) or die('Query failed: '.pg_last_error());
    if ($result == true) {
        echo "Trip(s) deleted";
    } else {
        echo "Trip(s) not added";
    }
}
?>
<td style="background-color:#00FF00;">
<form>
        Location: <input type="text" name="searchQuery" id="searchQuery" value="<?php echo $_GET['searchQuery'] ?>">
        <input type="submit" name="formSubmit" value="Search" >
</form>
<form method=post>
<?php
    if(isset($_GET['formSubmit'])){
        $query = 'SELECT * FROM trip WHERE src LIKE \'%'.$_GET['searchQuery'].'%\' OR dest LIKE \'%'.$_GET['searchQuery'].'%\'';
    } else {
        $query = 'SELECT * FROM trip;';
    }
	$result = pg_query($query) or die('Query failed: '.pg_last_error());
	echo "<b> SQL: </b>".$query."<br/><br/>";
    echo "<table style='border: 1px solid black;'>";
    echo "<tr><th>From</th><th>To</th><th>When</th><th>How long</th><th>No. of Seats</th><th>By</th><th>Price</th><th>Note</th><th>#</th><th><input type=submit name=del value=Delete></th></tr>\n";
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach($line as $col_value){
			echo "\t\t<td class='result'>$col_value</td>\n";
		}
        echo "\t\t<td class='result' align=center><input type=checkbox name=sel[] value=".$line['id']."></td>\n";
		echo "\t</tr>\n";
	}
    echo "</table>";
	pg_free_result($result);
?>
</form>
</td> </tr>
<tr> <td style="background-color:#00FF00;">
Add new trip
<form method=POST action=index.php>
Pick-up <input type=text name=src value=Pick-up><br>
Drop-off <input type=text name=dest value=Drop-off><br>
Time <input type=text name=time value="00:00:00"><br>
Est. duration <input type=text name=dura value=0><br>
Number of seats <input type=number name=seat value=1 min=1><br>
Vehicle <input type=radio name=vehicle value=limo checked> Limo
<input type=radio name=vehicle value=bus> Bus<br>
Price <input type=text name=cost value=0><br>
Note <input type=text name=note><br>
<input type=submit name=add value="Add">
</form>
<?php
pg_close($dbconn);
?>
<tr>
<td colspan="2" style="background-color:#FFA500; text-align:center;"> Copyright &#169; CS2102
</td> </tr>
</table>
</body>
</html>
