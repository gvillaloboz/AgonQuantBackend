<?php
	function resize_image($image_src, $dest, $dest_w, $dest_h) {
		$image_p = imagecreatetruecolor($dest_w, $dest_h);
		$image = imagecreatefromjpeg($image_src);
		$src_dim = getimagesize($image_src);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $dest_w, $dest_h, $src_dim[0], $src_dim[1]);
		imagejpeg($image_p, $dest);
		return $dest;
	}
		
	include 'db_config.php';
	$DEFAULT_CONDITION = 'C';
	$DEFAULT_TEAM_NAME = 'Qualtrics1';
	$AUTH_SECRET = '5877cf648e85571c113ed1a889f8bd7405bf8f41568bbce185b562253f92755e';	
	$IMAGE_UPLOAD_FOLDER = '/var/www/html/TheAgon/avatars/';
	$IMAGE_MAX_WIDTH = 100;
	$IMAGE_MAX_HEIGHT = 100;
	$IMAGE_MAX_WEIGHT = 1000000;
	$BACKEND_AVATAR_BASEPATH = 'https://pow.unil.ch/TheAgon/avatars/';

	$headers = apache_request_headers();
	if(isset($headers['Authorization'])) {
		if($headers['Authorization'] != 'Bearer '.$AUTH_SECRET) {
                	header("HTTP/1.1 401 Unauthorized");
                	exit;
		}
	} else {
                header("HTTP/1.1 401 Unauthorized");
                exit;
	}
	
	if($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("HTTP/1.1 401 Unauthorized");
                exit;
        }
	
        if(!isset($_POST['firstname']) || !isset($_POST['lastname']) ||
           !isset($_POST['email']) || !isset($_POST['hobbies']) || 
	   !isset($_FILES['avatar']) || !isset($_POST['age']) ||
	   !isset($_POST['pseudonym'])) {
                header("HTTP/1.1 500 Server Error");
                exit;
        }
	

	
	$connection = @mysqli_connect($host,$user,$password,$db);
	
	if(!$connection) {
                header("HTTP/1.1 500 Server Error");
                exit;	
	}

	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$hobbies = $_POST['hobbies'];
	$age = $_POST['age'];
	$pseudonym = $_POST['pseudonym'];
	
	if($_FILES['avatar']['size'] > $IMAGE_MAX_WEIGHT) {
                header("HTTP/1.1 500 Server Error");
                exit;
	}
		
	$file = $IMAGE_UPLOAD_FOLDER.$_FILES['avatar']['name'];	
	$dimensions = getimagesize($_FILES['avatar']['tmp_name']);
	if($dimensions[0] <= $IMAGE_MAX_WIDTH && $dimensions[1] <= $IMAGE_MAX_HEIGHT) {
		if(move_uploaded_file($_FILES['avatar']['tmp_name'], $file)) {
			$avatar = $file;
		} else {
               		header("HTTP/1.1 500 Server Error");
            		exit;
		}
	} else {
               	$resampled = resize_image($_FILES['avatar']['tmp_name'], $file, $IMAGE_MAX_WIDTH, $IMAGE_MAX_HEIGHT);
		$size = filesize($resampled);
		if($size <= $IMAGE_MAX_WEIGHT) {
			$avatar = $resampled;
		} else {
			unlink($resampled);
               		header("HTTP/1.1 500 Server Error");
               		exit;
		}
	}	
	
	
	$avatar_backend_path = $BACKEND_AVATAR_BASEPATH.$_FILES['avatar']['name'];		
	$query = "INSERT INTO user(name, lastname, pseudonym, email, experimental_condition, hobies, age, team_name, avatarId)
		  VALUES('$firstname', '$lastname', '$pseudonym', '$email', '$DEFAULT_CONDITION', '$hobbies', $age, '$DEFAULT_TEAM_NAME', '$avatar_backend_path')";
	$query = mysqli_query($connection, $query);
	
	$query = "SELECT * FROM user ORDER BY id DESC LIMIT 0,1";

	$query = mysqli_query($connection, $query);
	if (mysqli_num_rows($query) > 0) {
		$user_row = mysqli_fetch_assoc($query);
		include('common/cors_headers.php');
		echo json_encode($user_row);
	}	
	mysqli_close($connection);
