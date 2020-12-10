<?php
	// Gabriela Villalobos-Zúñiga - 12.04.20
	try{
	include 'db_config.php';
	$mysqli = new mysqli($host,$user,$password,$db);
	
	$statement = $mysqli->prepare("SELECT * FROM `week`");
	$statement->execute(); // Execute the statement.	
	$result = $statement->get_result(); // Binds the last executed statement as a result.
		$i = 0;
	    while ($row = $result->fetch_assoc()) {	    	
	    	$rows[$i] = $row;
	    	$i++;
	    }
		echo json_encode($rows); // Parse to JSON and print.
	    //while ($row = $result->fetch_assoc()) {
        //printf ("%s (%s)\n", $row["week_number"], $row["begin_date"]);
    }
	catch (mysqli_sql_exception $e) { // Failed to connect? Lets see the exception details..
            echo "MySQLi Error Code: " . $e->getCode() . "<br />";
            echo "Exception Msg: " . $e->getMessage();
            exit(); // exit and close connection.
        }

    $mysqli->close(); // finally, close the connection

?>
