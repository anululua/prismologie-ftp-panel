$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    function addItem() {
        $(this).dialog("close");
        alert($("#item-name-input").val() + " has been added");
    }

});
