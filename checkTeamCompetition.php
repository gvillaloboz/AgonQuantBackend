<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	} 
		//$sql = "DELETE FROM team_competition 
		//		WHERE week_number=week(curdate());";

		//$query = mysqli_query($connection, $sql);
		

		/*if (false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}*/

		
		$get_week_competitions = "
			select * from team_competition where week_number=WEEK(DATE_SUB(CURDATE(), INTERVAL 2 DAY))
		";

		$query_competitions = mysqli_query($connection, $get_week_competitions);
		
		function getTeamWeekScore($connection, $team_name) {
		$get_week_score = "
		select
				*
			from(
				select
				sum(day_total) team_week_total,
				user.team_name,
				week_number
				from (
					select 
						max(step_count) as day_total,
						user_id,
						date(server_timestamp) as ts
					from step
					group by user_id, ts
				) as steps_per_day
                inner join week on date(week.begin_date) <= steps_per_day.ts and date(week.end_date) >= steps_per_day.ts 
				inner join user on user.id=user_id
				group by week_number, team_name
			) as teamsteps_per_week
			where week_number= WEEK(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) and team_name='".$team_name."'";

			$teams_week_score = mysqli_query($connection, $get_week_score);
		#	var_dump($teams_week_score);
			if(mysqli_num_rows($teams_week_score) > 0) {
				$team_week_score = mysqli_fetch_assoc($teams_week_score);
	#			var_dump($team_week_score);
				$team_week_score = intval($team_week_score['team_week_total']);
			} else {
				$team_week_score = 0;
			}
			return $team_week_score;
		}
		function updateCompWinner($connection, $winner, $team_a, $team_b) {
			$set_competition_winner = "update team_competition set winner='".$winner."' where week_number=WEEK(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) and team_a='".$team_a."' and team_b='".$team_b."'";
			echo 'winner: '.$winner.' team_a: '.$team_a.' team_b: '.$team_b.' | ';
			mysqli_query($connection, $set_competition_winner);
		}

		if(mysqli_num_rows($query_competitions) > 0) {
			while($team_res = mysqli_fetch_assoc($query_competitions)) {
				$team_a_score = getTeamWeekScore($connection, $team_res["team_a"]);
				$team_b_score = getTeamWeekScore($connection, $team_res["team_b"]);
				$winner = "";
				if($team_a_score > $team_b_score) {
					$winner = $team_res["team_a"];
				}elseif($team_a_score < $team_b_score) {
					$winner = $team_res["team_b"];
				}
				updateCompWinner($connection, $winner, $team_res["team_a"], $team_res["team_b"]);
				updateCompWinner($connection, $winner, $team_res["team_b"], $team_res["team_a"]);
			}
		}
		
mysqli_close($connection);
?>
