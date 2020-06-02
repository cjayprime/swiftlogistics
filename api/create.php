<?php

    require('database.php');
    
    if(
        isset($_POST['destination']) && !empty($_POST['destination']) &&
        isset($_POST['origin']) && !empty($_POST['origin']) &&
        isset($_POST['sender']) && !empty($_POST['sender']) &&
        isset($_POST['receiver']) && !empty($_POST['receiver']) &&
        isset($_POST['datetime']) && !empty($_POST['datetime'])
    ){

        $tracking_id = mysqli_insert_id($db) + 1;
        $destination = $_POST['destination'];
        $origin = $_POST['origin'];
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];
        $datetime = $_POST['datetime'];

        $sql = "SELECT COUNT(*) AS total FROM shipping";
        $query = mysqli_query($db, $sql);
        $rows = mysqli_fetch_array($query, MYSQLI_ASSOC);
        $rows['total']++;
        echo mysqli_error($db);

        $sql = "INSERT INTO shipping(`tracking_id`, `destination`, `origin`, `sender`, `receiver`, `datetime`) VALUES(MD5('$rows[total]'), '$destination', '$origin', '$sender', '$receiver', '$datetime')";
        $query = mysqli_query($db, $sql);
        echo mysqli_error($db);
        $num = mysqli_affected_rows($db);

        $message = "";
        $output = array();
        if($num > 0){
            $message = "Shipping entry successfully created.";
            $output['tracking_id'] = md5($rows['total']);
        }else{
            $message = "An error occured. Shipping entry could not be successfully created.";
        }
        
        echo '{"success": true, "message": "'. $message .'", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameters required are: origin, destination, sender, receiver and datetime."}';

    }
    
?>