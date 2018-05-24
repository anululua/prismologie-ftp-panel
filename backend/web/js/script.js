        function submitRowAsForm(idRow) {

            var user_id, utility_path, public_access, manage_utitlities;

            $("#" + idRow + " td").children().each(function () {

                if (this.type.substring(0, 6) == "select")
                    user_id = this.value;
                if (this.type.substring(0, 6) == "hidden") {

                    if (this.name == 'path') {
                        utility_path = this.value;
                    }
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
                            ajaxUserAssignment(null, manage_utitlities, public_access, utility_path);
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
                    if (data) {} else {}
                },
                error: function (data) {
                    console.log(data);
                }
            });

            return false;
        }

        $(document).ready(function () {

            current_user = $("#currentUser").val();

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
                        if (data) {} else {}
                    }
                });

                return false;

            });


            $("#data").on('submit', function (event) {

                event.preventDefault();

                var files = $("#fileUpload").get(0).files;
                var formData = new FormData();

                $(files).each(function (index, file) {
                    formData.append('file[]', file);
                });

                formData.append('file_path', $('#file_path').val());

                for (var key of formData.entries()) {
                    //console.log(key[0] + ', ' + key[1]);
                }
                //exit;

                $.ajax({
                    url: '?r=folders/file-upload',
                    type: 'POST',
                    async: false,
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        console.log(data);
                        //location.reload();
                        if (data) {} else {}
                    }
                });


            });

            /*$("#data").submit(function (e) {

    event.preventDefault();
    var formData = new FormData(this);
    formData.append('file_path', $('#file_path').val());
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
});*/



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
