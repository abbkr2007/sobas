<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>;
    /*------------------------------------------
            --------------------------------------------
            Country Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
    

    $(document).ready(function () {
        $('#zone').change(function () {
            var zoneID = $(this).val();
            if (zoneID) {
                $.ajax({
                    url: '/users/get-branches/' + zoneID,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#branch').empty();
                        $('#branch').append('<option value="">Select Branch</option>');
                        $.each(data, function (key, value) {
                            $('#branch').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#branch').empty();
                $('#branch').append('<option value="">Select Branch</option>');
            }
        });
    });


    /*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/

