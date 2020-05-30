<?php

    require('database.php');
    
    if(isset($_POST['tracking_id']) && !empty($_POST['tracking_id'])){

        $tracking_id = $_POST['tracking_id'];

        $sql = "SELECT  `location`, `comment`, `destination`, `origin`, 
                        `shipping`.`datetime` AS shipping_date,
                        `shipping_log`.`datetime` AS shipping_log_date
                        FROM 
                        `shipping` INNER JOIN `shipping_log`
                            ON `shipping`.`shipping_id` = `shipping_log`.`shipping_id`
                            WHERE 
                                (
                                    `shipping`.`tracking_id` = '$tracking_id'
                                )
                            ORDER BY `shipping_log`.`datetime` DESC
                                ";
        $query = mysqli_query($db, $sql);
        $num = mysqli_num_rows($query);
        $output = array();

        if($num > 0){
            while($rows = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                array_push($output, $rows);
            }
        }
        
        echo '{"success": true, "message": "Tracking ID found.", "data": '. json_encode($output) .'}';

    }else{
        
        echo '{"success": false, "message": "POST parameter: tracking_id is missing."}';

    }
    
?>