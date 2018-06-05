var queuedRequests = [];
var plyrSpeeds = [0.75, 1, 1.25, 1.5, 2];

$(function () {
    if (typeof $.magnificPopup !== 'undefined') {
        initVideoPopups();
    }
});

function queueRequest(request) {
    queuedRequests.push(request);
}

function cancelQueuedRequests() {
    $.each(queuedRequests, function (key, value) {
        value.abort();
    });
    queuedRequests = [];
}

function bootstrapAlertHTML(alertType, dismissable, message) {
    var html = '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-' + alertType + ' alert-dismissible" role="alert">';

    if (dismissable) {
        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    }
    html += message + '</div>';

    return html;
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function loadingAnimation(containerSelector) {
    $(containerSelector).append(
        "<div id='loading-msg' class='col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center'>" +
        "<div class='cssload-container'>" +
        "<div class='cssload-speeding-wheel'></div>" +
        "</div>" +
        "</div>"
    );
}

function removeLoadingAnimation(elementId) {
    var $element = $('#loading-msg');
    if (isEmpty(elementId) === false) {
        $element = $(elementId).find('#loading-msg');
    }

    $element.remove();
}

function popupLoadingAnimation() {
    $.magnificPopup.close();
    $.magnificPopup.open({
        items: {
            src: '<div id="transition-animation-container"></div>',
            type: 'inline'
        },
        closeOnBgClick: false
    });

    loadingAnimation('div#transition-animation-container');
}

/*
 * Function for ask if it is empty
 */
function isEmpty(obj) {
    if (typeof obj === 'undefined' || obj === null || obj === '') return true;
    if (typeof obj === 'number' && isNaN(obj)) return true;
    return obj instanceof Date && isNaN(Number(obj));

}

/*
 * Get the smart tags header of the plugin
 */
function getSmartTagsHeader(callback) {
    if (!headerSmartTags) {
        $.ajax({
            url: routes.utils.smart_tags.header,
            type: "GET",
            data: {},
            async: true,
            dataType: "html"
        }).done(function (response) {
            headerSmartTags = response;
            callback();
        });
    } else {
        callback();
    }
}

/*
 * Initialize the smart tags plugin based on the received data on the given elements
 */
function initializeSmartTags(listSmartTags, elements, headerSmartTags, smartTagsMatcher, smartTagsCharacters) {
    if (listSmartTags.length) {
        for (var i = 0; i < elements.length; i++) {
            $(elements[i]).textcomplete('destroy');
            $(elements[i]).textcomplete([
                {
                    words: listSmartTags,
                    match: new RegExp(smartTagsMatcher),
                    search: function (term, callback) {
                        callback($.map(this.words, function (word) {
                            return word.search(new RegExp(term, "i")) !== -1 ? word : null
                        }));
                    },
                    index: 1,
                    replace: function (word) {
                        return smartTagsCharacters + word + smartTagsCharacters + '';
                    }
                }
            ], {
                header: function () {
                    return headerSmartTags;
                }
            });
        }
    }
}

/*
 * Scroll to div
 */
function scrollToElement(id) {
    $('html, body').animate(
        {
            scrollTop: $('#' + id).offset().top
        }, 'slow');
}

/*
 * Scroll to
 */
function scrollTo($element, offsetTop) {
    offsetTop = isEmpty(offsetTop) === false ? offsetTop : 0;

    $('html, body').animate(
        {
            scrollTop: $element.offset().top + offsetTop
        }, 'slow');
}

/*
 * Add unchecked checkboxes to form data
 */
function addUncheckedCheckboxesToForm(formId, formData) {
    $.each($('form#' + formId + ' input[type=checkbox]')
            .filter(function () {
                return $(this).prop('checked') === false
            }),
        function (idx, el) {
            var emptyVal = "off";
            formData += '&' + $(el).attr('name') + '=' + emptyVal;
        }
    );

    return formData;
}

function addPopoverCloseListener() {
    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
}

function linkOpenHtml($selector, callbackClose) {
    $selector.click(function (event) {
        event.preventDefault();

        var $this = $(this);

        $.ajax({
            url: $(this).attr('href'),
            type: "GET",
            data: {},
            async: true,
            dataType: "html"
        }).done(function (response) {
            $.magnificPopup.open({
                items: {
                    src: response,
                    type: 'inline'
                },
                callbacks: {
                    open: function () {
                        $.magnificPopup.instance.close = function () {
                            if (isEmpty(callbackClose) === false) {
                                callbackClose($this);
                            }
                            $.magnificPopup.proto.close.call(this);
                        };
                    }
                }
            });
        }).fail(function (jqXHR) {
            var $alert = $('div#alert-container');
            $alert.empty();
            $alert.append(bootstrapAlertHTML('danger', true, jqXHR.responseText));
        });
    });
}

function openPopup($sourceElement, $alertContainer, data) {
    $.ajax({
        url: $sourceElement.data('url'),
        type: "GET",
        data: data,
        async: true,
        dataType: "html"
    }).done(function (response) {
        $.magnificPopup.open({
            items: {
                src: response,
                type: 'inline'
            }
        });
    }).fail(function (jqXHR) {
        $alertContainer.empty();
        $alertContainer.append(bootstrapAlertHTML('danger', true, jqXHR.responseText));
    });
}

function populateSelect(origin, destiny) {
    var alertPopup = $('div#alerts-section-pop');
    var allowEmptyValue = origin.data('allow-empty-value');
    alertPopup.empty();

    $.ajax({
        url: origin.find(':selected').attr('href'),
        type: "GET",
        async: true,
        dataType: "json"
    }).done(function (response) {
        if (response.status === 'success') {
            destiny.empty();

            if (allowEmptyValue) {
                var emptyValueLabel = origin.data('empty-label');
                destiny.append('<option disabled selected>' + emptyValueLabel + '</option>');
            }

            i = 0;
            $.each(response.data, function (key, object) {
                destiny.append(
                    $('<option>')
                        .attr('value', object.id)
                        .attr('href', object.href)
                        .text(object.name)
                        .prop('outerHTML')
                );

                i++;

                if (i === response.data.length) {
                    destiny.trigger("each:finished");
                }
            });
        }
        else if (response.status === 'error') {
            alertPopup.append(bootstrapAlertHTML('danger', false, response.message));
        }
    }).fail(function () {
        alertPopup.append(bootstrapAlertHTML('danger', false, "Server error."));
    });
}

function initICheck($element) {
    if (isEmpty($element)) {
        $element = $('input[type=checkbox],input[type=radio]');
    }
    $element.iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%'
    });
}

/*
 * Initialize the company autocompleter
 */
function initializeCompanyTypeahead($container, $input, $companyId) {
    $container = typeof $container !== 'undefined' ? $container : $('div#bloodhound');
    $input = typeof $input !== 'undefined' ? $input : $('div#bloodhound input.typeahead');
    $companyId = typeof $companyId !== 'undefined' ? $companyId : $('#company-id');

    var companiesSource = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: $container.attr('href') + '?search=%QUERY',
            wildcard: '%QUERY',
            filter: function (response) {
                return $.map(response.data, function (company) {
                    return {
                        name: company.name,
                        email: company.email,
                        id: company.hashed_id
                    };
                });
            }
        },
        sufficient: 1
    });

    $input.typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 4
        },
        {
            name: 'companies',
            source: companiesSource,
            display: function (company) {
                return formatCompanyName(company);
            },
            templates: {
                suggestion: function (company) {
                    var email = '';

                    if (company.email) {
                        email = '(' + company.email + ')';
                    }

                    return '<p>' + company.name + ' ' + email + '</p>';
                }
            },
            limit: 10
        }
    ).on('typeahead:selected', function ($e, company) {
        $companyId.val(company.id);
    });

    $input.on('input', function () {
        $companyId.val('');
    });
}

/*
 * Initialize the campaign autocompleter
 */
function initializeCampaignTypeahead($container, $input, $campaignId) {
    $container = typeof $container !== 'undefined' ? $container : $('div#bloodhound');
    $input = typeof $input !== 'undefined' ? $input : $('div#bloodhound input.typeahead');
    $campaignId = typeof $campaignId !== 'undefined' ? $campaignId : $('#campaign-id');

    var campaignsSource = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: $container.attr('href') + '?search=%QUERY',
            wildcard: '%QUERY',
            filter: function (response) {
                return $.map(response.data, function (campaign) {
                    return {
                        route: campaign.route,
                        name: campaign.name,
                        id: campaign.hashed_id
                    };
                });
            }
        },
        sufficient: 1
    });

    $input.typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 4
        },
        {
            name: 'campaigns',
            source: campaignsSource,
            display: function (campaign) {
                var email = '';

                if (campaign.email) {
                    email = campaign.email;
                }

                return campaign.name + ' ' + email;
            },
            templates: {
                suggestion: function (campaign) {
                    var email = '';

                    if (campaign.email) {
                        email = '(' + campaign.email + ')';
                    }

                    return '<p>' + campaign.name + ' ' + email + '</p>';
                }
            },
            limit: 10
        }
    ).on('typeahead:selected', function ($e, campaign) {
        $campaignId.val(campaign.id);
    });

    $input.on('input', function () {
        $campaignId.val('');
    });
}

function formatCompanyName(company) {
    var email = '';

    if (company.email) {
        email = '(' + company.email + ')';
    }

    return company.name + ' ' + email;
}

function resetFormElement(element) {
    element.wrap('<form>').closest('form').get(0).reset();
    element.unwrap();
}

function getValue(value, defaultValue) {
    if (isEmpty(value) === false) {
        return value;
    }

    return defaultValue;
}

function loadHtml($selector, route, callback) {
    $.ajax({
        url: route,
        type: "GET",
        data: {},
        async: true,
        dataType: "html"
    }).done(function (response) {
        $selector.html(response);

        if (isEmpty(callback) === false) {
            callback();
        }
    });
}

/*
 * Initialize the HTML editor (TinyMCE)
 */
function initializeTinyMCE(selector, height, menuBar, plugins, toolbar1) {
    tinymce.remove(selector);
    tinymce.init({
        selector: selector,
        height: height,
        skin: "lightgray",
        menubar: menuBar,
        plugins: plugins,
        toolbar1: toolbar1,
        statusbar: false,
        convert_urls: false,
        content_css: "../../../css/tinymce/theme.css"
    });
}

/*
 * Count the number of characters of text on a TinyMCE editor
 */
function countCharacters(id) {
    var body = tinymce.get(id).getBody();
    var content = tinymce.trim(body.innerText || body.textContent);
    return content.length;
}

/*
 * Initialize the HTML editor (TinyMCE) for the script section
 */
function initializeTinyMCEScript(selector, height, plugins, toolbar1, selectorCharacterCounter, callback) {
    tinymce.remove('textarea#' + selector);
    tinymce.init({
        selector: 'textarea#' + selector,
        height: height,
        menubar: false,
        toolbar1: toolbar1,
        skin: "lightgray",
        statusbar: false,
        plugins: plugins,
        style_formats: [
            {title: 'Normal', inline: 'span'},
            {title: 'Italic', inline: 'i'},
            {title: 'Highlight', inline: 'span', styles: {background: '#f4b41a'}},
            {title: 'Green', inline: 'span', styles: {color: '#3bab49'}}
        ],
        setup: function (editor) {
            var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
            var maxCharacters = $(editor.getElement()).attr('maxlength');

            editor.on('keydown', function (event) {
                if (allowedKeys.indexOf(event.keyCode) !== -1) return true;
                if (countCharacters(selector) + 1 > maxCharacters) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
                return true;
            });
        },
        init_instance_callback: function (editor) {
            var count = countCharacters(selector);
            var maxCharacters = $(editor.getElement()).attr('maxlength');
            $('div#' + selectorCharacterCounter).html(count + '/' + maxCharacters);

            editor.on('keyup', function (event) {
                var count = countCharacters(selector);
                $('div#' + selectorCharacterCounter).html(count + '/' + maxCharacters);

                if (count >= maxCharacters) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

            if (isEmpty(callback) === false) {
                callback();
            }
        },
        paste_preprocess: function (plugin, args) {
            var editor = tinymce.get(tinymce.activeEditor.id);
            var maxCharacters = $(editor.getElement()).attr('maxlength');

            var len = editor.contentDocument.body.innerText.length;
            var text = args.content;
            if (len + text.length > maxCharacters) {
                args.content = text.substr(0, maxCharacters - len);
            } else {
                var count = countCharacters(selector);
                $('div#' + selectorCharacterCounter).html(count + '/' + maxCharacters);
            }
        },
        content_css: "../../../css/tinymce/theme.css"
    });
}

/*
 * Build the columns parameter for DataTable
 */
function buildDataTableColumns(tableDefinition) {
    var columns = [];

    $.each(tableDefinition.columns, function (key, value) {
        var column = tableDefinition.columns[key];
        var searchable = column.searchable || false;
        var active = column.active || true;
        var name = column.source || key;
        if (active) {
            columns.push({data: key, name: name, searchable: searchable});
        }
    });

    return columns;
}

/*
 * Build the columns definition parameter for DataTable
 */
function buildDataTableColumnsDefinition(tableDefinition) {
    var columns = [];
    var position = 0;

    $.each(tableDefinition.columns, function (key, value) {

        var column = tableDefinition.columns[key];
        var sortable = column.sortable || false;
        var active = column.active || true;
        var className = column.className || '';
        var render = column.render || function (data, type, row) {
                return row[key] || '';
            };
        if (active) {
            columns.push({
                aTargets: [position],
                bSortable: sortable,
                mRender: render,
                className: className
            });
        }
        position++;
    });

    return columns;
}

function initVideoPopups() {
    var $videoPopupLinks = $('.video-popup-link');
    $videoPopupLinks.magnificPopup({
        items: [
            {
                type: 'iframe'
            }],
        gallery: {
            enabled: false
        },
        type: 'video',
        callbacks: {
            elementParse: function (item) {
                item.src = this.st.el.data('video-url');
            }
        }
    });

    $videoPopupLinks.each(function () {
        if ($(this).data('auto-trigger') === true) {
            $(this).click();
        }
    });
}

function initTour(elements) {
    var steps = [];
    $.each(elements, function (index, value) {
        if (value.data('hint-title') !== "" && typeof value.data('hint-title') !== 'undefined') {
            var step = {};
            step["title"] = value.data('hint-title');
            step["content"] = value.data('hint-content');
            step["target"] = value[0];
            step["placement"] = value.data('hint-position');;
            steps.push(step);
        }
    });

    var tour = {
        id: "tour",
        steps: steps
    };

    if (tour.steps.length > 0) {
        hopscotch.startTour(tour);
    }
}

function triggerScheduleCallPopup() {
    var $popup = $('a.schedule-call-popup');

    if ($popup.length > 0 && isEmpty(Cookies.get('popupTriggered')) === true) {
        $popup.click(function () {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: "GET",
                data: {},
                async: true,
                dataType: "html"
            }).done(function (response) {
                $.magnificPopup.open({
                    items: {
                        src: response,
                        type: 'inline'
                    },
                    callbacks: {
                        open: function () {
                            $('a#schedule-meeting').click(function () {
                                $.magnificPopup.close();
                            });
                        },
                        close: function () {
                            Cookies.set('popupTriggered', true, {expires: 1});
                        }
                    }
                });
            });
        });

        if ($popup.data('schedule-call') === true) {
            $popup.click();
        }
    }
}

function checkScrollSection($sectionForScroll, $sectionContent) {
    var heightSectionForScroll = $sectionForScroll.outerHeight(true);
    var heightContent = $sectionContent.outerHeight(true);

    if (heightContent > heightSectionForScroll) {
        $sectionForScroll.css({
            'overflow-y': 'scroll'
        });
    } else {
        $sectionForScroll.css({
            'overflow-y': 'hidden'
        });
    }
}

function isKeyPressedANumber(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    return !(charCode > 31 && ((charCode < 48 || charCode > 57) || (charCode >= 96 && charCode <= 105)) );
}

function initDownloadReport($downloadButton, $form) {
    var $alert = $('div#alert-container');

    $downloadButton.unbind('click');
    $downloadButton.click(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: "GET",
            data: $form.serialize(),
            async: true,
            dataType: "json"
        }).done(function (response) {
            if (response.status === 'success') {
                $form.attr('action', response.data.url).submit();
            }
            else if (response.status === 'error') {
                var message = response.message;
                if (response.data) {
                    message += '<br>';
                    jQuery.each(response.data, function (index, value) {
                        message += value + '<br>';
                    });
                }

                $alert.html(bootstrapAlertHTML('danger', false, message));
            }
        }).fail(function (jqXHR) {
            $alert.html(bootstrapAlertHTML('danger', true, jqXHR.responseText));
        });
    });
}
