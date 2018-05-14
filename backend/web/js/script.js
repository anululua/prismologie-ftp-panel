$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    })

    $("#submitReset").click(function () {

        folder_name = $("#folder_name").val();

        $.ajax({
            url: '?r=folders/create',
            type: 'POST',
            data: {
                folder_name: folder_name
            },
            success: function (data) {
                alert(data);

            }
        });

    });

});
