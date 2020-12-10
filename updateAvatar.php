<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$userId = $_POST['a'];
	$avatarId = $_POST['b'];
	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{
		$sql = "UPDATE `user` 
		 SET 
    		`avatarId` = $avatarId
		  WHERE
    		`id` = $userId";
    		
		$query = mysqli_query($connection, $sql);
		//echo 'Succesfully added';
		//echo $query;

		if ( false===$query ) {
		  printf("error: %s\n", mysqli_error($connection));
		}
		else {
		  echo 'done';
		
		}
	}
		
mysqli_close($connection);
?>
