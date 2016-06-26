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

    $('#updateTaskList').bind('click', function() {
        $('#updateFormTaskList').toggle();
    });

    $('#clickUpdateTaskList').bind('click', function() {
        var formSerializeTaskList = $('#formUpdate').serialize();
 
        $.ajax({
            url: $('#url').val(),
            type: "post",
            data: formSerializeTaskList
        }).done(function (response, textStatus, jqXHR){
            if(response.status == "OK") {
                window.location.reload();
            }
        });
    });
});
