<?php
	// Author: Gabriela Villalobos-Zúñiga
	// Date: 07.05.20
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$stepCount = $_POST['a'];
	$userId = $_POST['b'];
	$timestamp = $_POST['c'];
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}else{
		$sql = "INSERT INTO `step`(`step_count`,`user_id`,`server_timestamp`) VALUES ($stepCount, $userId, $timestamp)";
		$query = mysqli_query($connection, $sql);
        if ($query===false) {
            printf("error: %s\n", mysqli_error($connection));
                    header("HTTP/1.1 500 Server Error");
                    exit;
        } else {
            header('HTTP/1.1 200 OK');
        }
	}

mysqli_close($connection);

?>
