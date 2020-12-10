<?php
	// Author: Gabriela Villalobos-Zúñiga
	// Date: 18.05.20
	try{
	include 'db_config.php';
	$mysqli = new mysqli($host,$user,$password,$db);

	if($mysqli->connect_error) {
	  exit('Error connecting to database'); 
	}
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	//$mysqli->set_charset("utf8");


	$weekNumber = $_POST['a'];
	$teamName = $_POST['b'];
	
	
	$statement = $mysqli->prepare(
                                  
//    "SELECT a.id,pseudonym,team_name,
//	(
//	      SELECT
//	      CASE WHEN count(step.step_count) <> '' THEN max(step.step_count) ELSE 0 END
//	      FROM step
//	      WHERE step.server_timestamp >= w.begin_date
//	      AND step.server_timestamp <= w.end_date
//	      AND step.user_id = a.id
//	) steps
//	FROM user a
//	INNER JOIN  team_competition c ON c.team_b=a.team_name
//	INNER JOIN week w ON c.week_number = w.week_number
//	WHERE c.team_a = ?
//	AND w.week_number = ?
//	AND team_name = c.team_b
//	GROUP by a.id, pseudonym, team_name, w.begin_date, w.end_date
//	ORDER BY steps DESC;");
                                  
//"SELECT a.id,
//pseudonym,
//team_name,
//SUM(coalesce(steps,0)) steps
//FROM user a
//LEFT join (
//           SELECT
//       user_id,
//       cast(server_timestamp AS DATE) steps_date,
//       CASE WHEN count(step.step_count) <> '' THEN max(step.step_count) ELSE 0 END steps
//       FROM step
//       INNER JOIN week w ON server_timestamp >= w.begin_date  AND  server_timestamp <= w.end_date
//       where w.week_number = ?
//       group by user_id,
//       cast(server_timestamp AS DATE)
//           ) steps_d ON  a.id=steps_d .user_id
//LEFT JOIN  team_competition c ON c.team_b=a.team_name
//WHERE c.team_a = ?
//AND c.week_number = ?
//AND team_name = c.team_b
//GROUP by a.id, pseudonym, team_name
//ORDER BY steps DESC;");
	"
	SELECT
		user.id,
    		user.pseudonym,
    		user.team_name,
    		COALESCE(sum(s.steps),0) as steps
	FROM
		user
	LEFT JOIN (SELECT
		user_id,
		cast(server_timestamp AS DATE) steps_date,
		CASE WHEN count(step.step_count) <> '' THEN max(step.step_count) ELSE 0 END steps
		FROM step
		INNER JOIN week w ON server_timestamp >= w.begin_date  AND  server_timestamp <= w.end_date
		WHERE w.week_number = ?
		GROUP BY user_id,
		steps_date) as s ON s.user_id=user.id
	INNER JOIN team_competition tc ON tc.team_b=user.team_name
	WHERE tc.team_a=?
	GROUP BY user.id
	ORDER BY steps DESC");                                

	$statement-> bind_param("is", $weekNumber, $teamName);
    	//$statement-> bind_param("isi", $weekNumber, $teamName, $weekNumber);

	$statement->execute(); // Execute the statement.

	$result = $statement->get_result(); // Binds the last executed statement as a result.
	
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
	 echo json_encode($rows,JSON_NUMERIC_CHECK); // Parse to JSON and print.
	}
    }
	catch (mysqli_sql_exception $e) { // Failed to connect? Lets see the exception details..
            echo "MySQLi Error Code: " . $e->getCode() . "<br />";
            echo "Exception Msg: " . $e->getMessage();
            exit(); // exit and close connection.
        }

    $mysqli->close(); // finally, close the connection
 
?>
