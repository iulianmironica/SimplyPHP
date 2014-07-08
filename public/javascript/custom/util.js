/**
 * @author Iulian Mironica
 */

/**
 * @param {type} element
 * @param {type} remote
 * @param {type} prefetch
 * @param {type} limit
 * @returns {undefined}
 */
function typeahead(element, remote, prefetch, displayKeyName, limit) {

    if (limit === undefined)
        limit = 10;

    if (displayKeyName === undefined)
        displayKeyName = 'name';

    var data = new Bloodhound({
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.productId, d.classId, d.name, d.price);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        cache: true,
        limit: limit,
        // prefetch: prefetch,
        remote: remote + '/?query=%QUERY&limit=' + limit
    });

    data.initialize();

    var template = Handlebars.compile(
            '<div>{{' + displayKeyName + '}} <span style="text-align:right">{{price}} $</span> \n\
                    </div>'
            );

    $(element).typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        autoselect: true
    }, {
        displayKey: 'product',
        source: data.ttAdapter(),
        templates: {
            empty: ['<div class="empty-message">\n\
                <div class="text-left alert alert-warning">No items found for this keyword.</div>\n\
                </div>'].join(' '),
            suggestion: function(data) {
                // console.log(data);
                return template(data);
            }
        }
    });
}