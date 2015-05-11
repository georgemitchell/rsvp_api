<?php
header("Access-Control-Allow-Origin: *");

$mysql_host = "mysql4.000webhost.com";
$mysql_database = "a3715521_invite";
$mysql_user = "a3715521_george";
$mysql_password = "h4ckth1s";


// Connecting, selecting database
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_select_db($mysql_database) or die('Could not select database');

// Performing SQL query
$query = "SELECT sum(num_total) as total from response";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

// Get the data
$data=mysql_fetch_assoc($result);
echo $data['total'];

// Free resultset
mysql_free_result($result);

// Closing connection
mysql_close($link);

?>
