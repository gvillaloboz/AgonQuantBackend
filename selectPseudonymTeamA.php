<?php
	// Gabriela Villalobos-Zúñiga - 20.04.20
	try{
	include 'db_config.php';
	$mysqli = new mysqli($host,$user,$password,$db);

	$weekNumber = $_POST['a'];
	$teamColumn = $_POST['b'];
	
	$statement = $mysqli->prepare("SELECT `pseudonym` FROM `user` WHERE `team_name` IN (SELECT $teamColumn FROM `team_competition` WHERE `week_number` = $weekNumber)");
	$statement->execute(); // Execute the statement.	
	$result = $statement->get_result(); // Binds the last executed statement as a result.
		$i = 0;
	    while ($row = $result->fetch_assoc()) {	    	
	    	$rows[$i] = $row;
	    	$i++;
	    }
		echo json_encode($rows); // Parse to JSON and print.
	   
    }
	catch (mysqli_sql_exception $e) { // Failed to connect? Lets see the exception details..
            echo "MySQLi Error Code: " . $e->getCode() . "<br />";
            echo "Exception Msg: " . $e->getMessage();
            exit(); // exit and close connection.
        }

    $mysqli->close(); // finally, close the connection
 
?>
