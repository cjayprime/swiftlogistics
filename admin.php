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
                    <input class="input" type="text" placeholder="Tracking ID" />
                    <input class="input" type="text" placeholder="Location" />
                    <input class="input" type="text" placeholder="Comment" />
                    <input class="input" type="date" value="" />
                </div>
                <input class="input button" style="margin-left: 0;" type="button" value="Add" />
                
                <div style="margin-top: 150px; width: 100%; margin-left: 0">
                    <span style="color: white; font-weight: bold;">Edit a shipping entry using it's tracking ID</span>
                    <input class="input" type="text" placeholder="Tracking ID" />
                    <input class="input" type="text" placeholder="Origin: Accra, Ghana" />
                    <input class="input" type="text" placeholder="Destination: Lagos, Nigeria" />
                    <input class="input" type="date" value="" />
                </div>
                <input class="input button" style="margin-left: 0;" type="button" value="Edit" />
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
            $('#create-entry').click(function(){

                console.log('Works')

                var self = $(this);
                self.html('<img src="img/spinner.gif" width="25" height="25" />');

                var origin = $('#create-origin').val();
                var destination = $('#create-destination').val();
                var datetime = $('#create-date').val();

                console.log(origin, destination, datetime)

                $('#create-trackingnumber').css({padding: 0}).html('');

                if(origin && destination && datetime){
                    $.ajax({
                        url: 'api/create.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            origin: origin,
                            destination: destination,
                            datetime: datetime
                        },
                        complete: function(){
                            self.html('Create');
                        },
                        success: function(response){
                            console.log(response, response.success , response.data , response.data.tracking_id)
                            if(response.success && response.data && response.data.tracking_id){
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
                    alert('Enter the Origin, Destination and Date.');
                }

            })//.click();

        </script>
    </body>
</html>