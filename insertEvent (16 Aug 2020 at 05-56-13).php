<?php
	$host='localhost';
	$user='root';
	$password='Drlf@187!!';
	$db='TheAgonDB';

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
			echo json_encode($result[]);
		}
	}

mysqli_close($connection);
?>


$result = mysqli_query($connection, $sql);

	if($result->num_rows == 0){
		// return empty array
	    $emptyArray = array();
	    echo json_encode($emptyArray);
	}else{ 

		$i = 0;
		while ($row = $result->fetch_assoc()) {	    	
			$rows[$i] = $row;
		    $i++;
		 }
	 	echo json_encode($rows); // Parse to JSON and print.
	}