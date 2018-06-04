$(function () {
    $("button#save-service").click(function () {
        saveService();
    });
});


function saveService() {
    var $data = $("form#service").serializeArray();
    var url = $("button#save-service").data('url');
    var $alert = $('div#alert');

    $.ajax({
        url: url,
        type: "POST",
        data: $data,
        async: true,
        dataType: "json"
    }).done(function (response) {
        $alert.empty();
        if (response.status === 'success') {
            $alert.html(bootstrapAlertHTML('success', true, response.message));
        }
        else if (response.status === 'error') {
            var message = response.message;
            if (response.data) {
                message += '<br>';
                jQuery.each(response.data, function (index, value) {
                    message += value + '<br>';
                });
            }
            $alert.html(bootstrapAlertHTML('danger', true, message));
        }
    }).fail(function () {
        $alert.html(bootstrapAlertHTML('danger', true, "Server error."));
    });
}
