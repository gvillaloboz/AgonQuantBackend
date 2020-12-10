<?php
	include 'db_config.php';
	$connection = @mysqli_connect($host,$user,$password,$db);

	$expCondition = $_POST['a'];

	if(!$connection){
		echo "Error: " .mysqli_connect_error();
		exit();
	}
	//echo 'Connected to MySQL';

// Query

//$sql = "SELECT name FROM `user` WHERE expCondition = '$expCondition'";
$sql = "SELECT 
s3.userId, 
name, 
maxTime, 
MAX(numSteps) numSteps
FROM `user` s1
INNER JOIN (
SELECT userId, MAX(timestamp) AS maxTime
FROM `step`
GROUP BY userId) s2 ON s1.id = s2.userId
INNER JOIN `step` s3 ON s1.id = s3.userId 
AND maxTime = timestamp
WHERE s1.expCondition = '$expCondition'
AND CAST(maxTime as DATE)= CAST(DATE_ADD(now(), INTERVAL -8 hour) AS date)
GROUP BY 
s3.userId, 
name, 
maxTime";

$result = mysqli_query($connection, $sql);


$dbdata = array();

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo $row["name"];
        $dbdata[]=$row;
    }
    echo json_encode($dbdata);
} else {
    echo "0 results";
}

//printf("New record has id %d.\n",mysqli_insert_id($connection));

mysqli_close($connection);

?>
