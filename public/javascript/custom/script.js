/**
 * @author Iulian Mironica
 */

addItemEvent();
removeItemEvent();
searchItemEvent();
checkoutEvent();
clearBasketEvent();

function searchItemEvent() {
    disable('#search-form-button');

    typeahead('#search-form', window.baseUrl + '/service/autocomplete', window.baseUrl + '/service/prefetch', 'product');

    jQuery('#search-form').on('typeahead:selected', function(e, data) {
        enable('#search-form-button');
        // console.log(data);
        console.log($('#search-form').text());

        $('#search-form').keypress(function(event) {
            if (event.keyCode === 13) {
                showLoader();
                event.preventDefault();

                if (saveItem(data)) {
                    // Modal basket
                    appendItemInTable('#basket-modal-table', data);

                    // Mini basket
                    appendItemInTable('#basket-nav-table', data, true);

                    // Clear the input and disable add
                    $('.typeahead').typeahead('setQuery', '');
                    $('#search-form').val('');
                    disable('#search-form-button');

                } else {
                    log('Error upon add.');
                }
                hideLoader();
            }
        });

        $('#search-form-button').click(function() {
            showLoader();
            event.preventDefault();

            if (saveItem(data)) {
                // Modal basket
                appendItemInTable('#basket-modal-table', data);

                // Mini basket
                appendItemInTable('#basket-nav-table', data, true);

                // Clear the input and disable add
                // $('#search-form').typeahead('close');
                $('.typeahead').typeahead('setQuery', '');
                $('#search-form').val('');
                disable('#search-form-button');

            } else {
                log('Error upon add.');
            }
            hideLoader();
        });

    });
}

function checkoutEvent() {
    $('button#checkout').click(function() {
        showLoader();
        hideLoader();
    });
}

function removeItemEvent() {
    $('a span.glyphicon.glyphicon-remove').click(function() {
        showLoader();
        var data = JSON.parse($(this).attr('data-bind'));
        removeItem(data.productId);
    });
}

function addItemEvent() {
    $('p a.btn.btn-xs.btn-info').click(function() {
        showLoader();

        var data = JSON.parse($(this).attr('data-bind'));

        if (saveItem(data)) {
            // Modal basket
            appendItemInTable('#basket-modal-table', data);

            // Mini basket
            appendItemInTable('#basket-nav-table', data, true);

        } else {
            log('Error upon add.');
        }

    });
}

function appendItemInTable(selector, data, miniBasketTable) {

    var existingItems = $(selector + ' tbody > tr td.basket-item-price');

    var total = parseFloat(data.price);
    $(existingItems).each(function(index, value) {
        total = total + parseFloat($(value).text());
    });

    total = total.toFixed(2);
    // console.log("TOTAL: " + total);

    var elements;
    if (miniBasketTable !== undefined) {
        elements = "<tr class='item-" + data.productId + "'>\n\
                        <td>" + data.product + "</td>\n\
                        <td class='basket-item-price'>" + data.price + "</td>\n\
                    </tr>";
    } else {
        var index = parseInt(existingItems.length) + 1;
        elements = "<tr class='item-" + data.productId + "'>\n\
                        <td>" + index + "</td>\n\
                        <td>" + data.product + "</td>\n\
                        <td class='basket-item-price'>" + data.price + "</td>\n\
                        <td>\n\
                            <a class='remove-product'>\n\
                                <span class='glyphicon glyphicon-remove' data-bind='{ \"productId\": \"" + data.productId + "\" }'></span>\n\
                            </a>\n\
                        </td>\n\
                    </tr>";
    }

    $(selector + ' > tbody:last').append(elements);
    $('.total-amount').html(total);

    // Refresh the listener
    removeItemEvent();
}

function updateTotalPrice(selector) {
    var existingItems = $(selector + ' tbody > tr td.basket-item-price');

    var total = 0;
    $(existingItems).each(function(index, value) {
        total = total + parseFloat($(value).text());
    });

    $('.total-amount').html(total);
    return true;
}

function clearBasketEvent() {
    $('a#clear-basket').click(function() {
        log('Todo: clear basket');
        clearBasket();
    });
}

function clearBasket() {
    return $.ajax({
        url: window.baseUrl + '/service/clear',
        type: "POST",
        dataType: "JSON",
        async: false,
        success: function(data) {
            console.log(data);
            redirect();
        },
        error: function(data) {
            console.log(data);
            redirect();
        }
    });
}

function saveItem(data) {
    return $.ajax({
        url: window.baseUrl + '/service/save',
        type: "GET",
        dataType: "JSON",
        async: false,
        data: {
            productId: data.productId, classId: data.classId, department: data.department, product: data.product, price: data.price
        },
        success: function(data) {
            console.log(data);
            hideLoader();
            return true;
        },
        error: function(data) {
            console.log(data);
            hideLoader();
            return false;
        }
    });
}

function removeItem(productId, that) {
    return $.ajax({
        url: window.baseUrl + '/service/remove',
        type: "POST",
        dataType: "JSON",
        async: false,
        data: {
            productId: productId
        },
        success: function(data) {
            console.log(data);
            if (data.status === 'success') {
                // $(that).parent().parent().parent().remove();
                $('.item-' + productId).remove();
                updateTotalPrice('#basket-modal-table');
            }
            hideLoader();
        },
        error: function(data) {
            console.log(data);
            hideLoader();
        }
    });
}

function showLoader(element) {
    try {
        $('div#loader').show();
        var selector = $(element);
        if (selector !== undefined && element !== undefined) {
            selector.attr('disabled', 'disabled');
        }
        return true;
    } catch (e) {
        return false;
    }
}

function hideLoader(element) {
    try {
        setTimeout("jQuery('div#loader').hide();", 1000);
        var selector = $(element);
        if (selector !== undefined && element !== undefined) {
            $(element).removeAttr('disabled');
        }
        return true;
    } catch (e) {
        return false;
    }
}

function redirect(location) {
    if (location !== undefined) {
        window.location.href = location;
    } else {
        window.location.reload();
        showLoader();
    }
}

function disable(selector) {
    $(selector).addClass('disabled');
}

function enable(selector) {
    $(selector).removeClass('disabled');
}

function log(data) {
    console.log(data);
}