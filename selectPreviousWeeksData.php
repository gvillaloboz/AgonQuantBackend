<?php
	// Author: Gabriela Villalobos-Zúñiga
	// Date: 27.06.20
try{
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$userId = $_POST['a'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}
	

	$sql = "SELECT 
user_id,
week_desc,
sum(step_count) weekly_steps
FROM(
SELECT 
	t1.user_id,
	DATE(date_1) as day,
	t1.server_timestamp,
	MAX(step_count) step_count
	FROM `step` t1
	INNER JOIN (
		SELECT
		user_id,
		CAST(server_timestamp as date) date_1, MAX(server_timestamp) server_timestamp
		FROM `step`
		GROUP BY user_id,CAST(server_timestamp as date) ) t2
	ON t1.user_id=t2.user_id AND t1.server_timestamp=t2.server_timestamp
	WHERE t1.user_id = $userId
	GROUP BY
	t1.user_id,
	date_1,
	t1.server_timestamp
	ORDER BY `t2`.`date_1` ASC
    ) A
    INNER JOIN week_dim B ON A.day=B.date
    group by user_id,
    week_desc, week_number
    ORDER BY week_number DESC";
    
   
	$result = mysqli_query($connection, $sql);

	$dbdata = array();

	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result)) {
	        //echo $row["numSteps"]. "," . $row["timestamp"]. "\n" ;
	        $dbdata[]=$row;
	    }
	    echo json_encode($dbdata);
	} else {
	    // return empty array
	    $emptyArray = array();
	    echo json_encode($emptyArray);
	}
	}

	catch (mysqli_sql_exception $e) { // Failed to connect? Lets see the exception details..
            echo "MySQLi Error Code: " . $e->getCode() . "<br />";
            echo "Exception Msg: " . $e->getMessage();
            exit(); // exit and close connection.
        }

mysqli_close($connection);

?>
