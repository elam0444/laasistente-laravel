$(function () {
    initTable();
});

function initTable() {
    var $table = $('table#requirements-table');

    var $dataTable = $table.DataTable({
        scrollCollapse: true,
        processing: true,
        serverSide: true,
        searchDelay: 2000,
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        "order": [[1, "desc"]],
        ajax: {
            "url": $table.attr('href')
        },
        columns: [
            {data: 'requirement.created_at', name: 'requirement.created_at', type: 'date-dd-mmm-yyyy', 'searchable': true},
            {data: 'requirement.name', name: 'requirement.name', 'searchable': false},
            {data: 'requirement.description', name: 'requirement.description', 'searchable': false},
            {data: 'requirement.requirement_service', name: 'requirement.requirement_service', 'searchable': false}
        ],
        columnDefs: [
            {
                aTargets: [0],
                bSortable: true,
                class: 'dt-body-left',
                mRender: function (data, type, row) {
                    return $('<a>')
                        .attr({'href': '/requirement/' + row.id})
                        .text(row.created_at).prop('outerHTML');
                }
            },
            {
                aTargets: [1],
                bSortable: false,
                class: 'dt-body-left',
                mRender: function (data, type, row) {
                    var address = row.address.address + '-' + row.address.city + ', ' + row.address.country;
                    return $('<a>')
                        .attr({'href': '/user/' + row.user.hashed_id})
                        .text(row.user.first_name + ' ' + row.user.last_name + ':' + address + ' ').prop('outerHTML');
                }
            },
            {
                aTargets: [2],
                bSortable: false,
                class: 'dt-body-left',
                mRender: function (data, type, row) {
                    var text;
                    if (row.description.length > 30) {
                        text = row.description.substr(0, 30) + '...';
                    } else {
                        text = row.description;
                    }
                    return '<p data-toggle="tooltip" data-placement="right" title="' + row.description +'">' +
                        text + '</p>';
                }
            },
            {
                aTargets: [3],
                bSortable: false,
                class: 'dt-body-left',
                mRender: function (data, type, row) {
                    var services = '';
                    $.each(row.requirement_service, function (index, value) {
                        services = services + '<div class="col-lg-6"><a href="' + row.hashed_url + '">' + value.service.name +
                            ' $' + value.total_cost + '-' + value.qty +  '(' + value.service.units + ')' +
                            '</a></div><div class="col-lg-2">' + $('<span/>').html(value.select_status).text() + '</div>' +
                            '<div class="col-lg-3">' +  $('<span/>').html(value.select_associates).text() + '</div>' +
                            '<br>';
                    });
                    return services;
                }
            }
        ],
        "fnDrawCallback": function (data) {
            linkOpenHtml($('a.client-on-boarding-experience-link'), function() {
                $dataTable.ajax.reload(null, false);
            });

            $('[data-toggle="tooltip"]').tooltip();

            $('select.select-status').on('change', function() {
                setStatus($(this));
            });

            $('select.select-associate').on('change', function() {
                setAssociate($(this))
            });
        }
    });
}

function setStatus($selector) {
    var $alert = $('div#alert');
    $alert.empty();
    var status = $selector.val();

    $.ajax({
        url: $selector.data('url'),
        data: { status_id: status},
        async: true,
        dataType: "json",
        type: 'POST'
    }).done(function (response) {
        var type = 'danger';

        if (response.status === 'success') {
            type = 'success'
        }

        $alert.append(bootstrapAlertHTML(type, true, response.message));
    });
}

function setAssociate($selector) {
    var $alert = $('div#alert');
    $alert.empty();
    var associate = $selector.val();

    $.ajax({
        url: $selector.data('url'),
        data: { associate_id: associate},
        async: true,
        dataType: "json",
        type: 'POST'
    }).done(function (response) {
        var type = 'danger';

        if (response.status === 'success') {
            type = 'success'
        }

        $alert.append(bootstrapAlertHTML(type, true, response.message));
    });
}