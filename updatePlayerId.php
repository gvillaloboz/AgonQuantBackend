<?php
	if($_SERVER['REQUEST_METHOD'] != 'PUT') {
		exit();
	}
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	
	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}

	else{
    		parse_str(file_get_contents("php://input"),$post_vars);
		$userId = $post_vars['a'];
		$playerId = $post_vars['playerId'];
		$sql = "UPDATE `user` 
		 SET 
    		`os_player_id` = '$playerId'
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
