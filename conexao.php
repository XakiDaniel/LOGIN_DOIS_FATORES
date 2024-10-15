<?php 

	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "xdb";


	try {
		
		$conn = new PDO("mysql:dbname=$db_name;charset=utf8;host=$db_host;", $db_user, $db_pass);

	} catch (PDOException $e) {
		echo "ERROR: " .$e->getMessage();
	}
?>