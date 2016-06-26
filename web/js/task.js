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

    $('.updateTask').bind('click', function() {
        $('#updateTaskDisplay').toggle();
    });

    $('#clickUpdateTask').bind('click', function () {
        var formSerializeTask = $('#formUpdateTask').serialize();
        
        $.ajax({
            url: $('#urlTask').val(),
            type: "post",
            data: formSerializeTask
        }).done(function (response, textStatus, jqXHR){
            if(response.status == "OK") {
                window.location.reload();
            }
        });
    });
});
