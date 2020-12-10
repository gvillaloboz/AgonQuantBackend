<?php
	//header('Content-Type: text/html; charset=utf-8');
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	$email = $_POST['a'];	

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}
	//echo 'Connected to MySQL';

// Query


$sql = "SELECT id, name, lastName, pseudonym, email, experimental_condition, team_name FROM `user` WHERE email = '$email'";

//$sql = "INSERT INTO `Step`(`Steps`,`UserId`) VALUES ($steps, $userId)";
$result = mysqli_query($connection, $sql);

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
	 	echo json_encode($rows); // Parse to JSON and print.
	} 

// if (mysqli_num_rows($result) > 0) {
//     // output data of each row
//     while($row = mysqli_fetch_assoc($result)) {
//         echo utf8_encode($row["id"]. " " . $row["name"]. " " . $row["lastName"]. " " . $row["pseudonym"]. " " . $row["email"]. " ". $row["experimental_condition"]." ". $row["team_name"]);
//     }
// } else {
//     echo "0 results";
// }

//printf("New record has id %d.\n",mysqli_insert_id($connection));

mysqli_close($connection);

?>
