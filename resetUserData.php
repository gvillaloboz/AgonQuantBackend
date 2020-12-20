<?php
    

    include 'db_config.php';
    $connection = @mysqli_connect($host,$user,$password,$db);
    
    
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("HTTP/1.1 401 Unauthorized");
                exit;
    }
    
    if(!isset($_POST['userId'])) {
            header("HTTP/1.1 500 Server Error");
            exit;
    }
    
    try {
        $userId = intval($_POST['userId']);
    } catch(Exception $e) {
                header("HTTP/1.1 500 Server Error");
                exit;
    }
    
    if(!$connection){
        header("HTTP/1.1 500 Server Error");
        exit();
    }else{
        resetUserData($connection, $userId);
    }
    
    function resetSteps($connection, $userId){
        
        $delete_step_query = 'delete from step where user_id='.$userId;
        $reseted_steps = mysqli_query($connection, $delete_step_query);
        if($reseted_steps===false){
            header("HTTP/1.1 500 Server Error");
            exit;
        }
        else{
            print("Steps reseted \n");
            header('HTTP/1.1 200 OK');
        }
    }
    
    function resetGoalSettingRecord($connection, $userId){
        
        $delete_goal_setting_record_query = 'delete from goal_setting_record where user_id='.$userId;
        $reseted_goal_setting_record = mysqli_query($connection, $delete_goal_setting_record_query);
        if($reseted_goal_setting_record===false){
            header("HTTP/1.1 500 Server Error");
            exit;
        }
        else{
            print("Goal Setting Record reseted \n");
            header('HTTP/1.1 200 OK');
        }
    }
    
    function resetDailyGoalRecord($connection, $userId){
        
        $delete_daily_goal_record_query = 'delete from daily_goal_record where user_id='.$userId;
        $reseted_daily_goal_record = mysqli_query($connection, $delete_daily_goal_record_query);
        if($reseted_daily_goal_record===false){
            header("HTTP/1.1 500 Server Error");
            exit;
        }
        else{
            print("Daily Goal Records reseted \n");
            header('HTTP/1.1 200 OK');
        }
    }
    
    
    function resetWeeklySteps($connection, $userId){
        
        $delete_weekly_steps_query = 'delete from weekly_steps where user_id='.$userId;
        $reseted_weekly_steps = mysqli_query($connection, $delete_weekly_steps_query);
        if($reseted_weekly_steps===false){
            header("HTTP/1.1 500 Server Error");
            exit;
        }
        else{
            printf("Weekly Steps Records reseted \n");
            header('HTTP/1.1 200 OK');
        }
    }

    function resetUserData($connection, $userId) {
        
        resetSteps($connection, $userId);
        resetGoalSettingRecord($connection, $userId);
        resetWeeklySteps($connection, $userId);
    }
    
    resetUserData($connection, $userId);

    mysqli_close($connection);

?>
