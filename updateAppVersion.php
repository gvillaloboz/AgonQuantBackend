<?php

	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("HTTP/1.1 401 Unauthorized");
                exit;
        }
	
        if(!isset($_POST['userId']) || !isset($_POST['appVersion'])) {
                header("HTTP/1.1 500 Server Error");
                exit;
        }
	
	try {	
		$userId = intval($_POST['userId']);
		$appVersion = strval($_POST['appVersion']);
		
	} catch(Exception $e) {
                header("HTTP/1.1 500 Server Error");
                exit;	
	}

	if(!$connection){
                header("HTTP/1.1 500 Server Error");
                exit;	
	} else {
		$sql = 'UPDATE `user` SET `app_version`= '.$appVersion.' WHERE id = '.$userId.'';

		$q = mysqli_query($connection, $sql);
		if ($q===false) {
                	header("HTTP/1.1 500 Server Error");
                	exit;	
		} else {
			header('HTTP/1.1 200 OK');
		}
	}
	mysqli_close($connection);
?>
