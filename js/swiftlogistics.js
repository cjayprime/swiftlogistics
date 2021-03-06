var continents = {
'Africa':  {price: 25, countries: ['Algeria','Angola','Benin','Botswana','Burkina Faso','Burundi','Cabo Verde','Cameroon','Central African Republic','Chad','Comoros','Democratic Republic of the Congo','Republic of the Congo','Cote d\'Ivoire','Djibouti','Egypt','Equatorial Guinea','Eritrea','Ethiopia','Gabon','Gambia','Ghana','Guinea','Guinea Bissau','Kenya','Lesotho','Liberia','Libya','Madagascar','Malawi','Mali','Mauritania','Mauritius','Morocco','Mozambique','Namibia','Niger','Nigeria','Rwanda','Sao Tome and Principe','Senegal','Seychelles','Sierra Leone','Somalia','South Africa','South Sudan','Sudan','Swaziland','Tanzania','Togo','Tunisia','Uganda','Zambia','Zimbabwe']},

'North America': {price: 20, countries: ['Antigua and Barbuda','Bahamas','Barbados','Belize','Canada','Costa Rica','Cuba','Dominica','Dominican Republic','El Salvador','Grenada','Guatemala','Haiti','Honduras','Jamaica','Mexico','Nicaragua','Panama','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Trinidad and Tobago','United States of America']},

'South America': {price: 25, countries: ['Argentina', 'Bolivia','Brazil','Chile','Colombia','Ecuador','Guyana','Paraguay','Peru','Suriname','Uruguay','Venezuela']},

'Europe': {price: 10, countries: ['Albania','Andorra','Armenia','Austria','Azerbaijan','Belarus','Belgium','Bosnia and Herzegovina','Bulgaria','Croatia','Cyprus','Czech Republic','Denmark','Estonia','Finland','France','Georgia','Germany','Greece','Iceland','Ireland','Italy','Kazakhstan','Kosovo','Latvia','Liechtenstein','Lithuania','Luxembourg','Macedonia','Malta','Moldova','Monaco','Montenegro','Netherlands','Norway','Poland','Portugal','Romania','Russia','San Marino','Serbia','Slovakia','Slovenia','Spain','Sweden','Switzerland','Turkey','Ukraine','United Kingdom','Vatican City']},

'Asia': {price: 10, countries: ['Armenia','Azerbaijan','Bahrain','Bangladesh','Bhutan','Brunei', 'Cambodia','China','Cyprus','Georgia','India','Indonesia','Iran','Iraq','Israel', 'Japan','Jordan','Kazakhstan','Kuwait','Kyrgyzstan','Laos','Lebanon','Malaysia','Maldives','Mongolia','Myanmar','Nepal','North Korea','Oman','Pakistan','Palestine','Philippines','Qatar','Russia','Saudi Arabia','Singapore','South Korea','Sri Lanka','Syria','Taiwan','Tajikistan','Thailand','Timor Leste','Turkey','Turkmenistan','United Arab Emirates','Uzbekistan','Vietnam','Yemen']},

'Australia': {price: 30, countries: ['Australia']}
};

$(document).ready(function(){
    // Continent & Country
    var continentSelect = $('#continent');
    var changeCountries = function(continent){
        var countrySelect = $('#country').html('');
        var countries = continents[continent].countries;
        for(var j = 0; j < countries.length; j++){
            countrySelect.append('<option>' + countries[j] + '</option');
        }
    };
    var weightInput = $('#weight');
    var weightUnitSelect = $('#weight-unit');
    var poundToKilograms = 0.453592;
    var calculateWeight = function(){
        var weight = weightInput.val();
        if(/^\d+$/.test(weight)){
            var continent = continentSelect.val();
            var priceInPounds = continents[continent].price;
            var price = weightUnitSelect.val() === 'lbs' ? priceInPounds : (weight / poundToKilograms) * priceInPounds;
            $('#shipping').text('$' + (weight * price));
        }else{
            weightInput.val(parseFloat(weightInput.val()) || '');
            console.log('You can only enter a number for the weight');
        }
    }

    for(var continent in continents){
        continentSelect.append('<option>' + continent + '</option');
    }

    continentSelect.change(function(e){
        changeCountries(e.target.value);
        calculateWeight();
    }).change();
    weightInput.keyup(calculateWeight);
    weightUnitSelect.change(calculateWeight);


    $('#table-control').click(function(){
        $(this).parent('div').toggle();
    });

    $('#search').click(function(e){
        e.preventDefault();

        var self = $(this);
        self.html('<img src="img/spinner.gif" width="25" height="25" />');

        $.ajax({
            url: 'api/search.php',
            method: 'POST',
            dataType: 'json',
            data: {
                tracking_id: $("#search-value").val()
            },
            complete: function(){
                self.html('Track Your Product');
            },
            success: function(response){
                console.log(response)
                if(response.success && response.data.length > 0){
                    var table = $('#table').show();
                    $('#tracking-error').remove();
                    table.find('tr:not(:eq(0))').remove();

                    var currentDate = '';
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    for(var i = 0; i < response.data.length; i++){
                        var row = '';
                        var data = response.data[i];
                        if($('.search-admin').length > 0){
                            row = '\
                                <tr>\
                                    <th scope="row">'+ data.comment +'</th>\
                                    <td>'+ data.location +'</td>\
                                    <td>'+ data.shipping_log_date.split(' ')[1] +'</td>\
                                    <td><div style="display: flex; justify-content: center;"><button data-id="'+ data.shipping_log_id +'" class="delete-log" style="padding: 5px;">Delete</button></div></td>\
                                </tr>\
                            ';
                        }else{
                        
                            var timeParts = data.shipping_log_date.split(' ')[1].split(':');
                            var range = parseInt(timeParts[0]) < 12 ? 'AM' : 'PM';

                            $('#sender').text(data.sender);
                            $('#receiver').text(data.receiver);
                            $('#origin').text(data.origin);
                            $('#destination').text(data.destination);

                            if(currentDate != data.shipping_log_date.split(' ')[0]){
                                if(!currentDate){
                                    $('#table').html('');
                                }
                                
                                currentDate = data.shipping_log_date.split(' ')[0];
                                var date = new Date(currentDate);
                                var year = date.getFullYear();
                                var month = months[date.getMonth()];
                                var monthDay = date.getDate();
                                var weekDay = days[date.getDay()];
                                row = '\
                                    <tr>\
                                        <th scope="col">'+ weekDay + ', ' + month + ' ' + monthDay + ', ' + year + '</th>\
                                        <th scope="col">Location</th>\
                                        <th scope="col">Time</th>\
                                    </tr>\
                                    <tr>\
                                        <th scope="row">'+ data.comment +'</th>\
                                        <td>'+ data.location +'</td>\
                                        <td>'+ parseInt(timeParts[0]) + ':' + parseInt(timeParts[1]) + ' ' + range + '</td>\
                                    </tr>\
                                ';
                            }else{
                                row = '\
                                    <tr>\
                                        <th scope="row">'+ data.comment +'</th>\
                                        <td>'+ data.location +'</td>\
                                        <td>'+ parseInt(timeParts[0]) + ':' + parseInt(timeParts[1]) + ' ' + range + '</td>\
                                    </tr>\
                                ';
                            }
                        }
                        table.append(row);
                    }
                    $('#table-control').click();

                    $('.delete-log').click(function(){
                        var self = $(this);
                        var text = self.text();
                        var id = $(this).data('id');

                        self.html('<img src="img/spinner.gif" width="25" height="25" />');
                        $.ajax({
                            url: 'api/delete.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {shipping_log_id: id},
                            complete: function(){
                                self.html(text.substr(0, 1).toUpperCase() + text.substr(1));
                            },
                            success: function(response){
                                alert(response.message);
                            },
                            error: function(xhr){
                                console.log(xhr.responseText)
                                alert('An error occured. Try again.')
                            },
                        });
                    });
                }else{

                    var text = 'An error occcured';
                    if(!response.success)text = 'Tracking ID not found';
                    else if(response.data && response.data.length === 0)text = 'There are  no shipping logs to view yet.';

                    $('#table-control').click();
                    var table = $('#table').hide();
                    $('#tracking-error').remove();
                    table.parent('div').append('<div id="tracking-error" style="\
                        width: 80%;\
                        height: 50%;\
                        margin-left: 10%;\
                        margin-top: 5%;\
                        display: flex;\
                        justify-content: center;\
                        align-items: center;\
                        font-size: 20px;\
                        color: red;\
                    ">' + text + '</div>');

                }
            },
            error: function(xhr){
                console.log(xhr.responseText)
            },
        });

    });
});