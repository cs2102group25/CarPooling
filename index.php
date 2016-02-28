<!DOCTYPE html>
<html>
<head>
	<title>CS2102 Car Pooling</title>
	<link rel="stylesheet" type="text/css" href="style.css"\>

	<link rel="stylesheet" type="text/css" href="bootstrap.min.css"\>
</head>

<body>
<table class="resultTable">
<tr>
	<td class="header">
		<b>Trips available</b>
	</td>
</tr>
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
if (isset($_POST['delete']) && isset($_POST['sel'])) {
    $query = "DELETE FROM trip WHERE id IN (".implode(",", $_POST['sel']).")";
    $result = pg_query($query) or die('Query failed: '.pg_last_error());
    if ($result == true) {
        echo "Trip(s) deleted";
    } else {
        echo "Trip(s) not deleted";
    }
}
?>
<td style="background-color:#00FF00;">
<form>
        Location: <input type="text" name="searchQuery" id="searchQuery" value="<?php echo $_GET['searchQuery'] ?>"\>
        <input type="submit" name="searchForTrip" value="Search"\>
</form>
<form method='post'>
<?php
    if (isset($_GET['searchForTrip'])) {
        $query = 'SELECT * FROM trip WHERE src LIKE \'%'.$_GET['searchQuery'].'%\' OR dest LIKE \'%'.$_GET['searchQuery'].'%\'';
    } else {
        $query = 'SELECT * FROM trip;';
    }
		$result = pg_query($query) or die('Query failed: '.pg_last_error());
		echo "<b> SQL: </b>".$query."<br/><br/>";
    echo "<table class='table'>
		<tr class='row'>
			<td class='col-md-1 result'>From</td>
			<td class='col-md-1 result'>To</td>
			<td class='col-md-1 result'>When</td>
			<td class='col-md-1 result'>How long</td>
			<td class='col-md-1 result'>No. of Seats</td>
			<td class='col-md-1 result'>By</td>
			<td class='col-md-1 result'>Price</td>
			<td class='col-md-3 result'>Notes</td>
			<td class='col-md-1 result'>#</td>
			<td class='col-md-1 result'><input type=submit name='delete' value='Delete'\></td>
		</tr>";
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		echo "<tr class='row'>";
		foreach ($line as $col_value) {
			echo "<td class='result'>$col_value</td>\n";
		}
		echo "<td class='result'><input type=checkbox name=sel[] value=".$line['id']."></td>";
		echo "</tr>";
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
Vehicle <input type=radio name=vehicle value=Limo checked> Limo
<input type=radio name=vehicle value=Bus> Bus<br>
Price <input type=text name=cost value=0><br>
Note <input type=text name=note><br>
<input type=submit name=add value="Add">
</form>
<?php
pg_close($dbconn);
?>
<tr>
<td class="footer"> Copyright &#169; CS2102
</td> </tr>
</table>
</body>
</html>
