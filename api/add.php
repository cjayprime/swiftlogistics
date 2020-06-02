<?php

    require('database.php');
    
    if(
        isset($_POST['tracking_id']) && !empty($_POST['tracking_id']) &&
        isset($_POST['location']) && !empty($_POST['location']) &&
        isset($_POST['comment']) && !empty($_POST['comment']) &&
        isset($_POST['datetime']) && !empty($_POST['datetime'])
    ){

        $tracking_id = $_POST['tracking_id'];
        $location = $_POST['location'];
        $comment = $_POST['comment'];
        $datetime = $_POST['datetime'];

        $sql = "INSERT INTO shipping_log(`shipping_id`, `location`, `comment`, `status`, `datetime`) VALUES((SELECT shipping_id FROM shipping WHERE `tracking_id` = '$tracking_id' LIMIT 1), '$location', '$comment', '1', '$datetime')";
        $query = mysqli_query($db, $sql);
        $num = mysqli_affected_rows($db);
        echo mysqli_error($db);

        $message = "";
        $output = array();
        if($num > 0){
            $message = "Shipping log successfully added.";
        } elseif ($num == -1 && mysqli_error($db) === "Column 'shipping_id' cannot be null") {
            $message = "Tracking ID not found. Shipping entry could not be successfully added.";
        }else{
            $message = "An error occured. Shipping entry could not be successfully added.";
        }
        
        echo '{"success": true, "message": "'. $message .'", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameters required are: location, comment and datetime."}';

    }
    
?>