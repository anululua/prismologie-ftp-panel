$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    })

    $("#reset_folder").click(function () {
        $("#folderdialog").dialog("close");
    });

    $("#reset_files").click(function () {
        $("#filesdialog").dialog("close");
    });

    $("#reset").click(function () {
        $("#editdialog").dialog("close");
    });

    $('#edit_title').click(function () {
        var title_name = $(this).attr('title_name');
        $("#editdialog").find("#title").val(title_name);
        $("#editdialog").dialog("open");
    });


    $("#submit_folder").click(function () {

        event.preventDefault();

        folder_name = $("#folder_name").val();
        folder_path = $("#folder_path").val();

        $.ajax({
            url: '?r=folders/create',
            type: 'POST',
            data: {
                folder_name: folder_name,
                folder_path: folder_path
            },
            success: function (data) {
                location.reload();
                if (data) {} else {}
            }
        });

        return false;

    });


    $("form#data").submit(function (event) {

        event.preventDefault();

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: '?r=folders/file-upload',
            type: 'POST',
            cache: false,
            async: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function (data) {
                location.reload();
                if (data) {} else {}
            }
        });

        return false;
    });



    $("#edit_name").click(function () {

        event.preventDefault();

        name = $("#name").val();
        path = $("#path").val();
        title = $("#title").val();
        $.ajax({
            url: '?r=folders/edit',
            type: 'POST',
            data: {
                name: name,
                path: path,
                title: title
            },
            success: function (data) {
                alert(data);
                location.reload();
                if (data) {} else {}
            }
        });

        return false;

    });


});
