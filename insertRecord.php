<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$userId = $_POST['userId'];
	$weekNumber = $_POST['weekNumber'];
	$suggestedNumSteps = $_POST['suggestedNumSteps'];
	$choice = $_POST['choice'];
	$weeklyGoal = $_POST['weeklyGoal'];
    $timestamp = $_POST['timestamp'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{
        $sql = "INSERT INTO `goal_setting_record` (`user_id`, `week_number`, `suggested_number_of_steps`, `choice`, `weekly_goal`, `weekly_cumulative`, `goal_achieved`, `server_timestamp`) VALUES ($userId, $weekNumber, $suggestedNumSteps, $choice, $weeklyGoal, '0', '-1', $timestamp)";

		$query = mysqli_query($connection, $sql);

		if ($query === false) {
            printf("error: %s\n", mysqli_error($connection));
            header("HTTP/1.1 500 Server Error");
            exit;
		}
		else {
            header('HTTP/1.1 200 OK');
		}
	}
		// Query
		//echo'STEPS: ';
		//echo $steps;-
mysqli_close($connection);
?>
