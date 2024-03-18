$(document).ready(function() {
    //custom checkbox js
    var pluginCsrf = window.Craft.csrfTokenValue
    function condenseResponseJSON(responseJSON) {
        let output = ['There was an error saving because the form itself did not pass validation checks. Please open up the form and try manually editing.'];
        // Handle the 'errors' section
        for (let errorKey in responseJSON.errors) {
            if (Array.isArray(responseJSON.errors[errorKey])) {
                responseJSON.errors[errorKey].forEach(error => {
                    output.push(errorKey + ': ' + error);
                });
            }
        }
        return output.join('\n');
    }
    function cellClick() {
        let entryId = $(this).attr('data-entry-id')
        let fieldname = $(this).attr('fieldname')
        let val = ''
        if ($(this).hasClass('unchecked')) {
            $(this).removeClass('unchecked');
            val = 1
        } else {
            $(this).addClass('unchecked');
        }
        let t= $(this)

        let formData = new FormData();
        formData.append('CRAFT_CSRF_TOKEN', pluginCsrf);
        formData.append('entryId', entryId);
        formData.append('action', 'entries/save-entry');
        formData.append('fields[' + fieldname + ']', val);
        //formData.append('title', 'ABC');
        $.ajax({
            type: "POST",
            url: "",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            //encode: true,
            success: function (response) {
                console.log('Success:', response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error:', jqXHR, textStatus, errorThrown);
                alert(condenseResponseJSON(jqXHR.responseJSON));

                if (t.hasClass('unchecked')) {
                    t.removeClass('unchecked');
                } else {
                    t.addClass('unchecked');
                }
            }
        });
    }
    function hookIntoCell() {
        $('.tableview table.data tbody tr').each(function( index ) {
            let tr = $(this)
            let entryId = tr.attr('data-id')
            $('.checkbox-icon', tr).each(function( index ) {
                $(this).attr('data-entry-id', entryId)
                $(this).off('click', cellClick)
                $(this).on('click', cellClick)
            })
        })

    }
    let icons = $('.tableview .checkbox-icon')
    if (icons.length) {
        hookIntoCell()
    } else {
        setTimeout(hookIntoCell,1000)
    }
    $('.elements').bind('DOMSubtreeModified',function(event) {
        hookIntoCell();
    });
});