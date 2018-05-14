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


  $("#submit_folder").click(function () {
    folder_name = $("#folder_name").val();
    $.ajax({
      url: '?r=folders/create',
      type: 'POST',
      data: {
        folder_name: folder_name
      },
      success: function (data) {
        if (data) {
          //alert(data);
        } else {
          //alert(data);
        }
      }
    });
    $("#folderdialog").dialog("close");
  });

  $("#submit_files").click(function () {
    event.preventDefault();
    file_data = $('#fileUpload')[0].files[0];
    $.ajax({
      url: '?r=folders/file-upload',
      type: 'POST',
      processData: false, // Don't process the files
      contentType: false,
      data: {
        file_data: file_data
      },
      success: function (data) {
        alert(data);
        if (data) {
          //alert(data);
        } else {
          //alert(data);
        }
      }
    });
    $("#filesdialog").dialog("close");
  });

});