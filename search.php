<?php

    $db = mysqli_connect('localhost', 'root', '', 'baysix_swiftlogistics');
    
    if(isset($_POST['tracking_number']) && !empty($_POST['tracking_number'])){

        $tracking_number = $_POST['tracking_number'];

        $sql = "SELECT  `location`, `comment`, `destination`, `origin`, 
                        `shipping`.`datetime` AS shipping_date,
                        `shipping_log`.`datetime` AS shipping_log_date
                        FROM 
                        `shipping` INNER JOIN `shipping_log`
                            ON `shipping`.`shipping_id` = `shipping_log`.`shipping_id`
                            WHERE 
                                (
                                    `shipping`.`tracking_number` = '$tracking_number'
                                )
                                ";
        $query = mysqli_query($db, $sql);
        echo mysqli_error($db);
        $num = mysqli_num_rows($query);
        $output = array();

        if($num > 0){
            while($rows = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                array_push($output, $rows);
            }
        }
        
        echo '{"success": true, "message": "Tracking number found.", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameter: tracking_number is missing."}';

    }
    
?>