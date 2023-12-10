<?php
function dbconn()
{
	$db_server = "localhost";
	$db_username = "kulp3765_5025221024";
	$db_password = "Bijionta1";
	$db_database = "kulp3765_5025221024";
	$conn = mysqli_connect($db_server, $db_username, $db_password, $db_database);
	if (!$conn) {
		die("koneksi error");
	}
	return $conn;
}