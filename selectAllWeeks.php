<?php
	// Gabriela Villalobos-Zúñiga - 12.04.20
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	//$email = $_POST['a'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}
	else{
		//echo 'Connected to MySQL';	
		$sql = "SELECT * FROM `week`";
		$result = mysqli_query($connection, $sql);

		if (mysqli_num_rows($result) > 0) {
 		   // output data of each row
    		while($row = mysqli_fetch_assoc($result)) {
        	echo $row["week_number"]. " " . $row["begin_date"]. " " . $row["end_date"];
    		}
		} else {
    		echo "0 results";
		}
	}
mysqli_close($connection);
?>
