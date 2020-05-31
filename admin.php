<?php

if(!isset($_GET['token']) || $_GET['token'] !== 'caquwiIjw34iw19dked99jnnksws'){
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Swift Logistics | Admin</title>
        <!-- Reset css-->
        <link href="css/reset.css" rel="stylesheet">
        
        <style>
            #create, #edit{
                position: absolute;
                height: 100%;
                width: 50%;
                top: 0;
                overflow: auto;
                padding-bottom: 50px;
                background: grey;
            }

            .heading{
                color: white;
                font-size: 30px;
                padding: 30px;
            }

            .input{
                width: 100%;
                height: 50px;
                margin-top: 20px;
                border: 0;
                border-radius: 20px;
                font-size: 20px;
                padding-left: 10px;
            }

            .button{
                width: 150px;
                font-size: 20px;
                margin-left: 10%;
                cursor: pointer;
                border-radius: 10px;
            }

            td,
            th {
                border: 1px solid rgb(190, 190, 190);
                padding: 10px;
            }

            tr:nth-child(even) {
                background-color: #eee;
            }

            tr:nth-child(odd) {
                background-color: #a6aaa6;
                color: black;
            }

            th[scope="col"] {
                background-color: #696969;
                color: #fff;
            }

            th[scope="row"] {
                background-color: #d7d9f2;
            }

            caption {
                padding: 10px;
                caption-side: bottom;
            }

            table {
                border-collapse: collapse;
                border: 2px solid rgb(200, 200, 200);
                letter-spacing: 1px;
                font-family: sans-serif;
                font-size: 12px;
                width: 100%;
            }

            #table-control{
                border: 0;
                padding: 5px;
                margin-bottom: 10px;
                background-color: red;
                color: #FFF;
                float: right;
                border-radius: 25px;
                width: 25px;
                height: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            #table-container{
                background-color: #22313f;
                top: 5%;
                left: 5%;
                height: 90%;
                width: 90%;
                position: fixed;
                z-index: 100000;
                overflow: auto;
                padding: 10px;
                border-radius: 10px;
                display: none;
            }

            .sub-container{
                width: 80%;
                margin-left: 10%;
                padding-bottom: 50px;
            }

            #create-trackingnumber{
                background: black;
                color: white;
                margin-top: 25px;
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="js">

        <div id="create" style="
            left: 0;
            border-left: 1px solid #000;
            background: #a1a1a1;
        ">
            <span class="heading">Create & view</span>
                <div class="sub-container">
                    <div style="margin-top: 50px; width: 100%; margin-left: 0">
                    <span style="color: white; font-weight: bold;">Create a new shipping entry</span>
                    <input class="input" type="text" placeholder="Origin: Accra, Ghana" id="create-origin" />
                    <input class="input" type="text" placeholder="Destination: Lagos, Nigeria" id="create-destination" />
                    <input class="input" type="date" value="" id="create-date" />
                    <div id="create-trackingnumber"></div>
                    <button class="input button" style="margin-left: 0px;" type="button" id="create-entry">Create</button>
                </div>

                <div style="margin-top: 50px; width: 100%; margin-left: 0">
                    <span style="color: white; font-weight: bold;">View all shipping logs of a shipping entry by tracking ID</span>
                    <input class="input" type="text" placeholder="Tracking ID" id="search-value" />
                </div>
                <input class="input button search-admin" style="margin-left: 0;" type="button" value="View" id="search" />
            </div>

        </div>
        <div id="edit" style="
            left: 50%;
            background: #555;
        ">
            <span class="heading">Add & edit</span>
            <div class="sub-container">
                <div style="margin-top: 50px; width: 100%; margin-left: 0">
                    <span style="color: white; font-weight: bold;">Add a shipping log to a shipping entry using it's tracking ID</span>
                    <input class="input" type="text" placeholder="Tracking ID" id="add-trackingID" />
                    <input class="input" type="text" placeholder="Location" id="add-location" />
                    <input class="input" type="text" placeholder="Comment"  id="add-comment"/>
                    <input class="input" type="date" value="" id="add-date" />
                </div>
                <input class="input button" style="margin-left: 0;" type="button" value="Add" id="add-entry" />
                
                <div style="margin-top: 150px; width: 100%; margin-left: 0">
                    <span style="color: white; font-weight: bold;">Edit a shipping entry using it's tracking ID</span>
                    <input class="input" type="text" placeholder="Tracking ID" id="edit-trackingID" />
                    <input class="input" type="text" placeholder="Origin: Accra, Ghana" id="edit-origin" />
                    <input class="input" type="text" placeholder="Destination: Lagos, Nigeria" id="edit-destination" />
                    <input class="input" type="date" value="" id="edit-date" />
                </div>
                <input class="input button" style="margin-left: 0;" type="button" value="Edit" id="edit-entry" />
            </div>
        </div>

        <div id="table-container">
            <button id="table-control">x</button>
            <div style="
                color: #FFF;
                font-size: 20px;
            ">Shipping Logs</div>
            <table id="table">
                <tr>
                    <th scope="col">Comment</th>
                    <th scope="col">Location</th>
                    <th scope="col">Time</th>
                    <th scope="col">Actions</th>
                </tr>
            </table>
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="js/swiftlogistics.js"></script>
        <script>
            $('#create-entry, #add-entry, #edit-entry').click(function(){

                var self = $(this);
                var type = '';
                var data = {};
                var url = '';

                if(self.is($('#create-entry'))){
                    type = 'create';
                    url = 'create.php';
                    var origin = $('#create-origin').val();
                    var destination = $('#create-destination').val();
                    var datetime = $('#create-date').val();
                    data = {
                        origin: origin,
                        destination: destination,
                        datetime: datetime
                    };
                }else if(self.is($('#add-entry'))){
                    type = 'add';
                    url = 'add.php';
                    var trackingID = $('#add-trackingID').val();
                    var location = $('#add-location').val();
                    var comment = $('#add-comment').val();
                    var datetime = $('#add-date').val();
                    data = {
                        tracking_id: trackingID,
                        location: location,
                        comment: comment,
                        datetime: datetime,
                    }
                }else if(self.is($('#edit-entry'))){
                    type = 'add';
                    url = 'edit.php';
                    var trackingID = $('#edit-trackingID').val();
                    var origin = $('#edit-origin').val();
                    var destination = $('#edit-destination').val();
                    var datetime = $('#edit-date').val();
                    data = {
                        tracking_id: trackingID,
                        origin: origin,
                        destination: destination,
                        datetime: datetime,
                    }
                }

                self.html('<img src="img/spinner.gif" width="25" height="25" />');
                $('#create-trackingnumber').css({padding: 0}).html('');

                console.log(data)

                if(
                    // origin && destination && datetime && 
                    Object.keys(data).filter(dataKey => !!data[dataKey]).length
                ){
                    $.ajax({
                        url: 'api/' + url,
                        method: 'POST',
                        dataType: 'json',
                        data: data,
                        complete: function(){
                            self.html(type.substr(0, 1).toUpperCase() + type.substr(1));
                        },
                        success: function(response){
                            console.log(response)
                            if(response.success && response.data && response.data.tracking_id){
                                if(type === 'create')
                                $('#create-trackingnumber').css({padding: 5}).html('Tracking ID: ' + response.data.tracking_id);
                            }
                            alert(response.message);
                        },
                        error: function(xhr){
                            console.log(xhr.responseText)
                            alert('An error occured. Try again.')
                        },
                    });
                }else{
                    self.html('Create');
                    alert('Enter the ' + Object.keys(data).join(', '));
                }

            });

        </script>
    </body>
</html>