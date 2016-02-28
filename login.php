<html>
<head> <title>CS2102 Car Pooling</title> <link rel="stylesheet" type="text/css" href="style.css"></head>
<body>
<table>
<tr> <td colspan="2" style="background‐color:#FFA500;">
<h1>Login</h1>
</td> </tr>
<tr>
<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres")
	or die('Could not connect: '.pg_last_error());
	if (isset($_POST['login'])) {
		echo "User is ".$_POST['username'];
		echo "Password is ".$_POST['password'];
	}
?>
<td style="background‐color:#00FF00;">
<form action="login.php" method="post">
	Username: <input type="text" name="username" id="username" value="">
	<br/>
  Password: <input type="password" name="password" id="password" value="">
	<br/>
  <input type="submit" name="login" value="Login" >
</form>
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
