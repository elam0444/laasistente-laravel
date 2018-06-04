$(function () {
    $("button#save-profile").click(function () {
        saveProfile();
    });

    var timestamp = '2017-09-24 01:00:05';
    var datetime = new Date(timestamp);
    hours = datetime.getHours(); //returns 0-23
    minutes = datetime.getMinutes(); //returns 0-59
    seconds = datetime.getSeconds(); //returns 0-59

    startTimer('increment');
    $("button#stop").click(function () {
        stopTimer();
    });

    // AUTO EXECUTE EVERY X ms
    var timeInterval = 2000;
    var timer = setInterval(executeThis, timeInterval);
    /* later
     //clearInterval(timer);*/

    $('select#address').on('change', function (e) {
        var addressId = $(this).val();
        getAddressForm(addressId);
    });

    $('button#newAddress').on('click', function (e) {
        var addressId = 0;
        $('input#newAddressFlag').val(1);
        getAddressForm(addressId);
    });

});

function getAddressForm(addressId) {

    $.ajax({
        url: $('select#address').data('url'),
        data: { address_id: addressId },
        async: true,
        dataType: "html",
        type: 'GET'
    }).done(function (response) {

        if (isEmpty(response) === false) {

            $('div#addressContent').html(response);

            $('select#addressNameSelect').on('change', function (e) {
                var addressNameSelected = $(this).val();
                var $addressName = $('input#addressName');
                $addressName.val(addressNameSelected);

                if (addressNameSelected === '0') {
                    $addressName.val('');
                    $addressName.show();
                } else {
                    $addressName.hide();
                }
            });

            // ADDRESS
            $('select#country').on('change', function (e) {

                var country = $(this).val();

                var $stateSelect = $('select#state');
                $stateSelect.empty();

                var $citySelect = $('select#city');
                $citySelect.empty();

                $.ajax({
                    url: $(this).data('url'),
                    data: { country: country },
                    async: true,
                    dataType: "json",
                    type: 'GET'
                }).done(function (response) {
                    $stateSelect.append('<option value ="">Select State</option>');
                    $.each(response.data, function (index, value) {
                        $stateSelect.append('<option value ="' + value + '">' + value + '</option>');
                    });
                });

                $.ajax({
                    url: $stateSelect.data('url'),
                    data: { country: country },
                    async: true,
                    dataType: "json",
                    type: 'GET'
                }).done(function (response) {
                    $citySelect.append('<option value ="">Select City</option>');
                    $.each(response.data, function (index, value) {
                        $citySelect.append('<option value ="' + value + '">' + value + '</option>');
                    });
                });

            });

        } else {
            $('div#addressContent').empty();
        }

    });
}

function saveProfile() {
    var $data = $("form#profile").serializeArray();
    var url = $("button#save-profile").data('url');
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

function executeThis() {
    //console.log('executed');
}

/* TIMER */
var seconds = 0, minutes = 0, hours = 0, timerInstance;
var stopWatch = $('span.stop-watch');
function startTimer(incrementOrDecrement) {
    if (incrementOrDecrement === 'increment') {
        timerInstance = setTimeout(increment, 1000);
    } else if (incrementOrDecrement === 'decrement') {
        timerInstance = setTimeout(decrement, 1000);
    }
}

function increment() {
    seconds++;
    if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }
    }

    stopWatch.text(
        (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" +
        (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" +
        (seconds > 9 ? seconds : "0" + seconds)
    );

    startTimer('increment');
}

function decrement() {
    if (seconds !== 0) {
        seconds--;
    }

    if (seconds === 0) {

        seconds = 59;
        if (minutes !== 0) {
            minutes--;
        }

        if (minutes === 0) {

            minutes = 59;
            if (hours !== 0) {
                hours--;
            }

        }
    }

    stopWatch.text(
        (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" +
        (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" +
        (seconds > 9 ? seconds : "0" + seconds)
    );

    startTimer('decrement');
}

function stopTimer() {
    clearTimeout(timerInstance);
}