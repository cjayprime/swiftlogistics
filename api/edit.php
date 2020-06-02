<?php

    require('database.php');
    
    if(
        isset($_POST['tracking_id']) && !empty($_POST['tracking_id']) &&
        isset($_POST['destination']) && !empty($_POST['destination']) &&
        isset($_POST['origin']) && !empty($_POST['origin']) &&
        isset($_POST['sender']) && !empty($_POST['sender']) &&
        isset($_POST['receiver']) && !empty($_POST['receiver']) &&
        isset($_POST['datetime']) && !empty($_POST['datetime'])
    ){

        $tracking_id = $_POST['tracking_id'];
        $destination = $_POST['destination'];
        $origin = $_POST['origin'];
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];
        $datetime = $_POST['datetime'];

        $sql = "UPDATE shipping SET `destination` = '$destination', `origin` = '$origin', `sender` = '$sender', `receiver` = '$receiver', `datetime` = '$datetime' WHERE `tracking_id` = '$tracking_id'";
        $query = mysqli_query($db, $sql);
        echo mysqli_error($db);
        $num = mysqli_affected_rows($db);

        $message = "";
        $output = array();
        if($num > 0){
            $message = "Shipping entry successfully edited.";
        }else{
            $message = "An error occured. Shipping entry could not be successfully edited.";
        }
        
        echo '{"success": true, "message": "'. $message .'", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameters required are: tracking id, origin, destination, sender, receiver and datetime."}';

    }
    
?>