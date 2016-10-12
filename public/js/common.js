$(function () {
    $('#datetimepickerFrom, #datetimepickerTo').datetimepicker({
        format: 'DD.MM.YYYY'
    });
});

function submitConsumerFilter(form, base_url) {
    var urlVars = getURLVars();
    var currUrl = '/consumer' + '?' + urlVars.join('&');
    
    var groupId = $(form).find('[name=groupId] :selected').val();
    var expirationDateTimeFrom = $(form).find('[name=expirationDateTimeFrom]').val();
    var expirationDateTimeTo = $(form).find('[name=expirationDateTimeTo]').val();

    var filter = [];

    if (parseInt(groupId)) {
        filter.push('groupId:' + groupId);
    }

    if (expirationDateTimeFrom) {
        filter.push('expirationDateTimeFrom:' + expirationDateTimeFrom);
    }

    if (expirationDateTimeTo) {
        filter.push('expirationDateTimeTo:' + expirationDateTimeTo);
    }

    if (filter.length) {
        currUrl += '&filter=' + filter.join(';');
    }
    
    location = currUrl;
}

function getURLVars() {
    var value = [];
    
    var query = String(document.location).split('?');
    
    if (query[1]) {
        var part = query[1].split('&');
        
        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');
            
            if (data[0] && data[1]) {
                if (data[0] == 'filter') {
                    continue;
                }
                value.push(data[0] + '=' + data[1]);
            }
        }
    }
    
    console.log(value);
    
    return value;
}