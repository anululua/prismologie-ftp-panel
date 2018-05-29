function submitRowAsForm(idRow) {

    var user_id, utility_path, public_access, manage_utitlities;

    $("#" + idRow + " td").children().each(function () {

        if (this.type.substring(0, 6) == "select")
            user_id = this.value;
        if (this.type.substring(0, 6) == "hidden") {
            if (this.name == 'path')
                utility_path = this.value;
        }

        if (this.type.substring(0, 8) == "checkbox") {

            if (this.name == 'chk') {

                if ($(this).prop('checked')) {

                    manage_utitlities = true;

                    if (!user_id) {
                        $("#user_list").css({
                            "border": "1px solid red",
                            "background": "#FFCECE"
                        });
                        return false;
                    } else {
                        public_access = $("#" + idRow + " td input[name=check]").prop('checked');
                        ajaxUserAssignment(user_id, manage_utitlities, public_access, utility_path);
                    }
                } else {
                    manage_utitlities = false;
                    public_access = $("#" + idRow + " td input[name=check]").prop('checked');
                    ajaxUserAssignment(user_id, manage_utitlities, public_access, utility_path);
                }
            }
        }
        event.preventDefault();
    });
}

function ajaxUserAssignment(user_id, manage_utitlities, public_access, utility_path) {

    $.ajax({
        url: '?r=folders/assignments',
        type: 'POST',
        data: {
            user_id: user_id,
            manage_utitlities: manage_utitlities,
            public_access: public_access,
            utility_path: utility_path,
        },
        success: function (data) {
            location.reload();
        },
    });

    return false;
}

$(document).ready(function () {

    current_user = $("#currentUser").val();

    user_access = $('#user_assignment_access').val();

    if (user_access > 2) {
        $('#utility_table th:nth-child(3)').hide();
        $('#utility_table td:nth-child(3)').hide();
    }

    if (current_user == 'moderator') {
        $('#utility_table th:nth-child(3)').hide();
        $('#utility_table td:nth-child(3)').hide();
    }
    if (current_user == 'public') {
        $("#utility_creation").css({
            "display": "none"
        });

        $('#utility_table th:nth-child(3)').hide();
        $('#utility_table td:nth-child(3)').hide();
        $('#utility_table tr').find("a[id='del_utility']").css({
            "display": "none"
        });
        $('#utility_table tr').find("a[id='edit_utility']").css({
            "display": "none"
        });
    }

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
            }
        });

        return false;

    });


    $("#data").on('submit', function (e) {


        event.preventDefault();
        var formData = new FormData(this);

        file_count = $("#fileUpload").get(0).files.length;
        imageSize = 0;

        for (var i = 0; i < file_count; i++)
            imageSize = imageSize + $("#fileUpload").get(0).files[i].size;

        mb_size = (imageSize / 1048576).toFixed(3);

        if (mb_size > 1024) {
            $('#myflashwrapper').html('maximum file upload size is 1GB <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>').show();
            return false;
        }

        if (file_count > 20) {
            $('#myflashwrapper').html('max number of file uploads is 20 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>').show();
            return false;
        }

        formData.append('file_path', $('#file_path').val());

        $.ajax({
            url: '?r=folders/file-upload',
            type: 'POST',
            async: false,
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function (data) {
                location.reload();
            }
        });
        return false;
    });


    $("#edit_name").click(function () {
        event.preventDefault();

        name = $("#name").val(); //new name
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
