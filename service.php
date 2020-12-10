<?php
header('content-type: text/html; charset=utf-8');

include 'db_config.php';
// Create connection
$con=mysqli_connect($host, $user, $password, $db);

// Check connection
if(mysqli_connect_errno()){

	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// SQL Statement
$sql = "SELECT * From user;";

// Check if there are results
if($result = mysqli_query($con, $sql)){

	// If so, then create a results array and a temporary one to hold the data
	$resultsArray = array();
	$tempArray = array();

	//Loop through each row in the result set
	while($row = $result->fetch_object()) { //mysqli_fetch_object($result))
		// Encodes obj $row to utf-8

		// Converts obj $row to array
		//$array = json_decode(json_encode($row), true);
		//$array = (array)$row;
		//print_r($array);
		//$array =  json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		//print_r($array);
		// Add each row into our results array
		$tempArray = $row;

		array_push($resultsArray, $tempArray);

		//print_r($resultsArray);

		//var_dump($resultsArray);
	}
	// Finally, encode the arary to JSON and output the results
	//print_r($resultsArray);
	
	//$array = json_decode(json_encode($resultsArray), true);
	//print_r($resultsArray);
	//$resultsArray= array_map('encode_all_strings', $resultsArray);
	//echo json_encode($resultsArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	echo json_encode($resultsArray);
}



// Close connections
mysqli_close($con);

function utf8_encode_deep(&$input) {
	if (is_string($input)) {
		$input = utf8_encode($input);
	} else if (is_array($input)) {
		foreach ($input as &$value) {
			utf8_encode_deep($value);
		}
		
		unset($value);
	} else if (is_object($input)) {
		$vars = array_keys(get_object_vars($input));
		
		foreach ($vars as $var) {
			utf8_encode_deep($input->$var);
		}
	}
}

function encode_all_strings($arr) {
    foreach($arr as $key => $value) {
        $arr[$key] = utf8_encode($value);
    }
    return $arr;
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

?>
