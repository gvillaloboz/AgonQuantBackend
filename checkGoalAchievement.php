<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{
		$sql = "DELETE FROM weekly_steps 
				WHERE week_number=week(DATE_SUB(CURDATE(), INTERVAL 1 DAY));";

		$query = mysqli_query($connection, $sql);
		

		if (false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}
		else {
		  echo 'done';
		
		}

//		$sql = "
//			INSERT INTO weekly_steps (user_id, week_number, week_desc, week_cumulative, goal_achieved)
//SELECT 
//A.user_id,
//A.week_number,
//A.week_desc,
//A.weekly_steps as week_cumulative,
//CASE WHEN A.weekly_steps>=COALESCE((SELECT weekly_goal FROM goal_setting_record gsr WHERE week_number=week(curdate()) AND A.user_id=gsr.user_id ORDER BY gsr.server_timestamp DESC LIMIT 0,1),999999) THEN 1 ELSE 0 END 
//goal_achieved
//FROM
//(SELECT 
//user_id,
//week_number,
//week_desc,
//sum(step_count) weekly_steps
//FROM(
//SELECT 
//	t1.user_id,
//	DATE(date_1) as day,
//	t1.server_timestamp,
//	MAX(step_count) step_count
//	FROM `step` t1
//	INNER JOIN (
//		SELECT
//		user_id,
//		CAST(server_timestamp as date) date_1, MAX(server_timestamp) server_timestamp
//		FROM `step`
//		GROUP BY user_id,CAST(server_timestamp as date) ) t2
//	ON t1.user_id=t2.user_id AND t1.server_timestamp=t2.server_timestamp
//	GROUP BY
//	t1.user_id,
//	date_1,
//	t1.server_timestamp
//	ORDER BY `t2`.`date_1` ASC
//          ) A
//    INNER JOIN week_dim B ON A.day=DATE(B.date)
//    group by user_id, 
//    week_number,
//    week_desc
//    ) A
//    WHERE  A.week_number=week(curdate());
//		";

		$sql="
			INSERT INTO weekly_steps (user_id, week_number, week_desc, week_cumulative, goal_achieved)
			SELECT 
				user_id,
				week_number,
				week_desc,
				sum(step_count) weekly_cumulative,
				CASE WHEN sum(step_count)>=COALESCE((SELECT weekly_goal FROM goal_setting_record gsr WHERE week_number=week(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND A.user_id=gsr.user_id ORDER BY gsr.server_timestamp DESC LIMIT 0,1),999999) THEN 1 ELSE 0 END 
				goal_achieved
			FROM(
			SELECT 
				t1.user_id,
				CAST(t1.server_timestamp as date) date_1,
				MAX(step_count) step_count
			FROM step t1
			GROUP BY
				CAST(t1.server_timestamp as date),
				t1.user_id
			) A
			INNER JOIN week_dim B ON A.date_1=DATE(B.date)
			WHERE week_number=week(DATE_SUB(CURDATE(), INTERVAL 1 DAY))
			GROUP BY user_id, week_desc, week_number
		";
		$query = mysqli_query($connection, $sql);
		

		if (false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}
		else {
		  echo 'done';
		
		}
	}
		
mysqli_close($connection);
?>
