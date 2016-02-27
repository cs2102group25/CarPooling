<html>
<head> <title>CS2102 Car Pooling</title> <link rel="stylesheet" type="text/css" href="style.css"></head>
<body>
<table>
<tr> <td colspan="2" style="background‐color:#FFA500;">
<h1>Trips available</h1>
</td> </tr>
<tr>
<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres")
	or die('Could not connect: '.pg_last_error());
?>
<td style="background‐color:#00FF00;">
<form>
        Location: <input type="text" name="searchQuery" id="searchQuery" value="<?php echo $_GET['searchQuery'] ?>">
        <input type="submit" name="formSubmit" value="Search" >
</form>
<?php if(isset($_GET['formSubmit'])){
	$query = 'SELECT src, dest FROM trip WHERE src LIKE \'%'.$_GET['searchQuery'].'%\' OR dest LIKE \'%'.$_GET['searchQuery'].'%\'';
	$result = pg_query($query) or die('Query failed: '.pg_last_error());
	echo "<b> SQL: </b>".$query."<br/><br/>";
	$resultNumber = 1;
	while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		echo "<table style='border: 1px solid black;'>";
		echo "\t<tr>\n";
		echo "\t\t<td class='result'>$resultNumber :</td>\n";
		foreach($line as $col_value){
			echo "\t\t<td class='result'>$col_value</td>\n";
		}
		echo "\t</tr>\n";
		echo "</table>";
	}
	pg_free_result($result);
}
?>
</td> </tr>
<?php
pg_close($dbconn);
?>
<tr>
<td colspan="2" style="background‐color:#FFA500; text‐align:center;"> Copyright &#169; CS2102
</td> </tr>
</table>
</body>
</html>
