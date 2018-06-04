$(function () {
    $("button#save-requirement").click(function () {
        makeRequirement();
    });

    $('select#serviceCategory').on('change', function (e) {

        var serviceCategory = $(this).val();

        var $serviceSelect = $('select#service');
        $serviceSelect.empty();

        $.ajax({
            url: $(this).data('url'),
            data: {service_category: serviceCategory},
            async: true,
            dataType: "json",
            type: 'GET'
        }).done(function (response) {
            $serviceSelect.append('<option value ="">Select</option>');
            $.each(response.data, function (index, value) {
                $serviceSelect.append('<option value ="' + value.id + '" data-cost-per-unit="' + value.cost_per_unit + '">' + value.name + ': $' + value.cost_per_unit + ' per (' + value.units + ')</option>');
            });
        });
    });

    $('select#service').on('change', function (e) {
        var costPerUnit = $(this).find(':selected').data('cost-per-unit');
        $('input#costPerUnit').val(costPerUnit);
        console.log(costPerUnit);
        calculateSubTotal();
    });

    $('input#qty').on('change', function (e) {
        calculateSubTotal();
    });
});

function calculateSubTotal() {
    var totalCost = $('input#qty').val() * $('input#costPerUnit').val();
    $('p#totalCostLabel').html('$' + totalCost);
    $('input#totalCost').val(totalCost);
    console.log(totalCost);
}

function makeRequirement() {
    var $data = $("form#requirement").serializeArray();
    var url = $("button#save-requirement").data('url');
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
