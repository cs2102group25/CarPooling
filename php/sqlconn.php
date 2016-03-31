<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres")
or die('Could not connect: '.pg_last_error());
?>