<?php

    require('database.php');
    
    if(
        isset($_POST['shipping_log_id']) && !empty($_POST['shipping_log_id'])
    ){

        $shipping_log_id = $_POST['shipping_log_id'];

        $sql = "UPDATE shipping_log SET `status` = '0' WHERE `shipping_log_id` = '$shipping_log_id'";
        $query = mysqli_query($db, $sql);
        echo mysqli_error($db);
        $num = mysqli_affected_rows($db);

        $message = "";
        $output = array();
        if($num > 0){
            $message = "Shipping entry successfully deleted.";
        }else{
            $message = "An error occured. Shipping entry could not be successfully deleted.";
        }
        
        echo '{"success": true, "message": "'. $message .'", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameters required is: shipping_log_id."}';

    }
    
?>