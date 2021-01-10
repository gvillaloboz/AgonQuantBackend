<?php
    include 'db_config.php';
    $connection = @mysqli_connect($host,$user,$password,$db);
    
    if(!$connection) {
        exit();
    }
    
    
    function getDailyStepsPerUsers($connection) {
        $begin_date = date('Y-m-d', strtotime(date('Y-m-d').' -1 day')).' 00:00:01';
        $end_date = date('Y-m-d').' 23:59:59';
        $req = 'SELECT user_id, MAX(step_count) steps, server_timestamp FROM step WHERE server_timestamp>="'.$begin_date.'" AND server_timestamp<="'.$end_date.'" GROUP BY user_id, server_timestamp';
	$res = mysqli_query($connection, $req);
        if ($res != false) {
            if(mysqli_num_rows($res) > 0) {
		$data = array();
		while ($row = mysqli_fetch_assoc($res)) {
			$data[] = array_map('utf8_encode', $row);	
		}
                return $data;
            }
        }
        return null;
    }

    function getDailyStepRecord($connection, $user_id, $date) {
    	$day_numbering = array("Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6, "Sunday" => 7);
        $day_of_week = $day_numbering[date('l', strtotime($date))];
	echo date('l', strtotime($date)).' '.$day_of_week;
        $req = 'SELECT * FROM daily_goal_record  WHERE user_id='.$user_id.' AND day='.$day_of_week.' AND week=WEEK(DATE_SUB(CURDATE(), INTERVAL 1 DAY))';
	$res = mysqli_query($connection, $req);
        if ($res != false) {
            if (mysqli_num_rows($res) > 0) {
                return mysqli_fetch_assoc($res);
            }
        }
        return null;
    }

    function updateDailyStepsGoal($connection, $user_id, $day, $week, $achieved) {
        $req = 'UPDATE daily_goal_record SET goal_achieved='.$achieved.' WHERE user_id='.$user_id.' AND day='.$day.' AND week='.$week;
	$res = mysqli_query($connection, $req);
    }

    $daily_steps = getDailyStepsPerUsers($connection);
    foreach ($daily_steps as $user_daily_steps) {
	$user_daily_record = getDailyStepRecord($connection, $user_daily_steps['user_id'], $user_daily_steps['server_timestamp']);
        if ($user_daily_record == null) {
            continue;
        }
        $achieved = intval($user_daily_record['goal']) <= intval($user_daily_steps['steps']) ? 1:0;
        echo $user_daily_record['user_id'].' '.$user_daily_record['goal'].' '.$user_daily_steps['steps'].' '.$user_daily_record['day'].' '.$user_daily_record['week'].' '.$achieved.' | ';
	if ($achieved == 1) {
            updateDailyStepsGoal($connection, $user_daily_steps['user_id'], $user_daily_record['day'], $user_daily_record['week'], $achieved);
        }
    }
?>
