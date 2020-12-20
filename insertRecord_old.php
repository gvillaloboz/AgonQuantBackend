<?php
    include 'db_config.php';
    $connection = @mysqli_connect($host,$user,$password,$db);

    $userId = $_POST['a'];
    $weekNumber = $_POST['b'];
    $suggestedNumSteps = $_POST['c'];
    $choice = $_POST['d'];
    $weeklyGoal = $_POST['e'];

    if(!$connection){
        echo "Error: " .mysqli_connect_error();
        exit();
    }

    else{
        //echo 'Connected to MySQL';
        $sql = "INSERT INTO `goal_setting_record` (`user_id`, `week_number`, `suggested_number_of_steps`, `choice`, `weekly_goal`, `weekly_cumulative`, `goal_achieved`) VALUES ($userId, $weekNumber, $suggestedNumSteps, $choice, $weeklyGoal, '0', '-1')";

        $query = mysqli_query($connection, $sql);
        //echo 'Succesfully added';
        //echo $query;

        if ( false===$query ) {
          printf("error: %s\n", mysqli_error($connection));
        }
        else {
          echo 'done';
          //printf("New record has id %d.\n",mysqli_insert_id($connection));
        }
    }
        // Query
        //echo'STEPS: ';
        //echo $steps;-
mysqli_close($connection);
?>
