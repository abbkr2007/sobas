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
                    $('#branch').append(
                        '<option value="">Select Branch</option>',
                    );
                    $.each(data, function (key, value) {
                        $('#branch').append(
                            '<option value="' +
                                value.id +
                                '">' +
                                value.name +
                                '</option>',
                        );
                    });
                },
            });
        } else {
            $('#branch').empty();
            $('#branch').append('<option value="">Select Branch</option>');
        }
    });
});

// Inline editing functionality
$(document).ready(function () {
    $('#dataTable').on('blur', '.editable', function () {
        var id = $(this).data('id');
        var column = $(this).data('column');
        var value = $(this).text();

        $.ajax({
            url: '/users/inline-update', // Remove Laravel blade syntax from JS file
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Get CSRF token from meta tag
                id: id,
                column: column,
                value: value,
            },
            success: function (response) {
                if (!response.success) {
                    alert('Update failed!');
                }
            },
            error: function () {
                alert('Error updating!');
            },
        });
    });
});

/*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
