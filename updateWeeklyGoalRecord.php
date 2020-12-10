<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$userId = $_POST['a'];
	$weeklyCummulative = $_POST['b'];
	$goalAchieved = $_POST['c'];
	$weekNumber = $_POST['d'];
	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{
		$sql = "UPDATE `goal_setting_record` 
		 SET 
    		`goal_achieved` = $goalAchieved,
    		`weekly_cumulative` = $weeklyCummulative
		  WHERE
    		`user_id` = $userId
    		AND `week_number` = $weekNumber";

		$query = mysqli_query($connection, $sql);
		//echo 'Succesfully added';
		//echo $query;

		if ( false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}
		else {
		  echo 'done';
		
		}
	}
		
mysqli_close($connection);
?>
