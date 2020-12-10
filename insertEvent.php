<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$userId = $_POST['a'];
	$eventType = $_POST['b'];
	$parameter = $_POST['c'];
	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{

		$sql = "INSERT INTO `events` (`user_id`, `event_type_id`, `parameter`) VALUES ($userId, $eventType, $parameter)";	

		$query = mysqli_query($connection, $sql);

		if ( false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}
		else {
			echo json_encode([]);
		}
	}

mysqli_close($connection);
?>
