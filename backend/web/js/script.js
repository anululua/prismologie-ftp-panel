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

    $('#edit_title').click(function () {
        var title_name = $(this).attr('title_name');
        $("#editdialog").find("#title").val(title_name);
        $("#editdialog").dialog("open");
    });


    $("#submit_folder").click(function () {

        event.preventDefault();

        folder_name = $("#folder_name").val();
        folder_path = $("#folder_path").val();

        if (!folder_name) {
            $("#folder_name").css({
                "border": "1px solid red",
                "background": "#FFCECE"
            });
            return false;
        }


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

        name = $("#name").val(); ///new name
        path = $("#path").val();
        old_title = $("#old_title").val(); //prev name

        if (!name) {
            $("#name").css({
                "border": "1px solid red",
                "background": "#FFCECE"
            });
            return false;
        }

        $.ajax({
            url: '?r=folders/edit',
            type: 'POST',
            data: {
                name: name,
                path: path,
                old_title: old_title
            },
            success: function (data) {
                location.reload();
                if (data) {} else {}
            }
        });

        return false;

    });


    $('#SettingsModal').on('show.bs.modal', function (e) {
        var path = $(e.relatedTarget).data('path');
        var old_title = $(e.relatedTarget).data('title');
        $("#SettingsModal #path").val(path);
        $("#SettingsModal #old_title").val(old_title);
    });



});
