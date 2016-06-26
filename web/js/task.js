$(document).ready(function() {
    $('#buttonTask').bind('click', function() {

        var formSerialize = $('#addTask').serialize();

        $.ajax({
            url: $('#url').val(),
            type: "post",
            data: formSerialize
        }).done(function (response, textStatus, jqXHR){
            if(response.status == "OK") {
                window.location.reload();
            }
        });
    });
});
