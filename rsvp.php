<?php
header("Access-Control-Allow-Origin: *");

switch($_SERVER['REQUEST_METHOD'])
{
case 'GET': $the_request = &$_GET; break;
case 'POST': $the_request = &$_POST; break;
default: $the_request = &$_GET;
}

$mysql_host = "mysql4.000webhost.com";
$mysql_database = "a3715521_invite";
$mysql_user = "a3715521_george";
$mysql_password = "h4ckth1s";

//$mysql_host = "127.0.0.1";
//$mysql_database = "birthday";
//$mysql_user = "root";
//$mysql_password = "";

// Connecting, selecting database
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_select_db($mysql_database) or die('Could not select database');


$name = $the_request["name"];
$email = $the_request["email"];
$message = $the_request["message"];
$num_attending = $the_request["num_attending"];

if(trim($email) == "")
{
	$email = null;
}

if($email != null)
{
	$sql = sprintf("SELECT id FROM response WHERE email = '%s' LIMIT 1",
			mysql_real_escape_string($email));
	$result = mysql_query($sql);
	// Get the data
	$data=mysql_fetch_assoc($result);

	$existing = $data['id'];

	// Free resultset
	mysql_free_result($result);
}
else
{
	$existing = null;
}

if($existing != null)
{
	$sql = sprintf("UPDATE response set name = '%s', comment = '%s', num_total = %d WHERE id = %d",
			mysql_real_escape_string($name),
			mysql_real_escape_string($message),
			$num_attending,
			$existing);

}
else
{
	$sql = sprintf("INSERT INTO response(num_total, name, email, comment) VALUES (%d, '%s', '%s', '%s')",
			$num_attending,
			mysql_real_escape_string($name),
			mysql_real_escape_string($email),
			mysql_real_escape_string($message));

}

$result = mysql_query($sql);
if($result)
{

	if($num_attending > 2)
	{
		$party = "you and your " . ($num_attending - 1) . " guests"; 
	}
	elseif($num_attending > 1)
	{
		$party = "you and your guest";
	}
	else
	{
		$party = "you";
	}
	?>

<div class="row">
 <?php if($existing) {?><b>Thank you for updating your response</b><?php } ?>
 <?php if($num_attending > 0) { ?>
	 <p>Great!  Can't wait to see <?=$party?>, <?=$name?>!</p>
	 <p>You can change your response by using the same email address [<?=$email?>].</p>
	 <p>Stay tuned for updates in email.</p>
 <?php } else { ?>
 	<p>Sorry that you won't be attending <?=$name?>, you will be missed!<p>
 	<p>If you change your mind, you can always come back and change your response!</p>

 <?php } ?>
</div>

<?php
}
else
{
?>
<div class="row">
	Oh rats!  An error occurred:  <?=mysql_error()?>.  Please try to respond again later. <?=$sql?>
</div>
<?php
}
