<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	$weekNumber = $_POST['a'];	
	$userId = $_POST['b'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

$sql = "SELECT user_id,
			   week_number,
			   suggested_number_of_steps,
			   choice,
			   weekly_goal, 
			   weekly_cumulative,
			   goal_achieved
		FROM `goal_setting_record`
		WHERE week_number = '$weekNumber'
		AND   user_id = '$userId'";

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

mysqli_close($connection);

?>
