<?php

	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("HTTP/1.1 401 Unauthorized");
                exit;
        }
	
        if(!isset($_POST['userId'])) {
                header("HTTP/1.1 500 Server Error");
                exit;
        }
	
	try {	
		$userId = intval($_POST['userId']);
	} catch(Exception $e) {
                header("HTTP/1.1 500 Server Error");
                exit;
	}

	if(!$connection) {
                header("HTTP/1.1 500 Server Error");
                exit;	
	}	
    
    $sql = 'SELECT max(goal_achieved) as goal_achieved, day, week, user_id FROM daily_goal_record WHERE user_id= '.$userId.' GROUP BY day, week, user_id';
    
//	$sql = 'SELECT * FROM daily_goal_record WHERE user_id='.$userId.'';
	$q = mysqli_query($connection, $sql);
	if(mysqli_num_rows($q) > 0) {		
		$daily_goals = array();
		while($row = mysqli_fetch_assoc($q)) {
			$daily_goals[] = array_map('utf8_encode', $row);
		}
		echo json_encode($daily_goals);
	} else {
		echo json_encode([]);
	}
	mysqli_close($connection);
?>
