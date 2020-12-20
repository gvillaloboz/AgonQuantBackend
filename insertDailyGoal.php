<?php

	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("HTTP/1.1 401 Unauthorized");
                exit;
        }
	
        if(!isset($_POST['userId']) || !isset($_POST['goal']) ||
           !isset($_POST['week']) || !isset($_POST['day']) || !isset($_POST['timestamp'])) {
                header("HTTP/1.1 500 Server Error");
                exit;
        }
	
	try {	
		$userId = intval($_POST['userId']);
		$goal = intval($_POST['goal']);
		$week = intval($_POST['week']);
		$day = intval($_POST['day']);
        $timestamp = $_POST['timestamp'];
	} catch(Exception $e) {
                header("HTTP/1.1 500 Server Error");
                exit;	
	}

	if(!$connection){
        
                header("HTTP/1.1 500 Server Error");
                exit;	
	} else {
		$sql = 'INSERT INTO daily_goal_record(week, day, goal, user_id, timestamp) VALUES('.$week.', '.$day.', '.$goal.', '.$userId.', '.$timestamp.')';
		$q = mysqli_query($connection, $sql);
		if ($q===false) {
            printf("error: %s\n", mysqli_error($connection));
                	header("HTTP/1.1 500 Server Error");
                	exit;	
		} else {
			header('HTTP/1.1 200 OK');
		}
	}
	mysqli_close($connection);
?>
