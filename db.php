<?php

//Database setup
$db_hostname = 'localhost:3306';  //Database server
$db_username = 'root';  //Database username
$db_password = '123456';  //Database password
$db_name = 'qzavzlsr_soccer';  //Database name

//Connect to database
$connections = mysql_connect($db_hostname, $db_username, $db_password) or die('Unable to connect to the database!');
mysql_query('SET NAMES UTF8');
mysql_select_db($db_name) or die('Unable to select database!');
?>
