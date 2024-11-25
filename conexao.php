<?php 

	$db_host = HOST;
	$db_user = USER;
	$db_pass = PASS;
	$db_name = DBNAME;


	try {
		
		$conn = new PDO("mysql:dbname=$db_name;charset=utf8;host=$db_host;", $db_user, $db_pass);

	} catch (PDOException $e) {
		echo "ERROR: " .$e->getMessage();
	}
?>