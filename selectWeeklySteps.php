<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	$userId = $_POST['a'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

$sql = "SELECT ws.id, user_id, ws.week_number, ws.week_desc, week_cumulative, goal_achieved,
CASE
WHEN (SELECT winner FROM team_competition WHERE team_competition.week_number=ws.week_number AND winner=user.team_name LIMIT 0,1) IS NULL THEN 0
ELSE 1
END AS won_competition
FROM  weekly_steps ws
INNER JOIN user ON user.id=user_id
WHERE user_id = ".$userId."
GROUP BY ws.id DESC;";

$result = mysqli_query($connection, $sql);
	if($result->num_rows == 0){
		// return empty array
	    $emptyArray = array();
	    echo json_encode($emptyArray);
	}else{
		$i = 0;
		$rows = array();
		while ($row = $result->fetch_assoc()) {
		    $rows[] = array_map('utf8_encode', $row);
		    $i++;
		 }
	 	echo json_encode($rows); // Parse to JSON and print.
	} 

mysqli_close($connection);

?>
