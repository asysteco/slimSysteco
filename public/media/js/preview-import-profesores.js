$(document).ready(function (e) {
  $("#frmCSVImport").on('submit',(function(e) {
    e.preventDefault();
    url = $(this).attr('action');
    opt = 'preview';
    urlPath = url+opt;
    usedMethod = $(this).attr('method');
    $.ajax({
    url: urlPath,
    type: usedMethod,
    data:  new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    beforeSend : function()
    {
      loadingOn("Cargando horarios CSV...");
    },
    success: function(data) {
      if (data.match('error-cabecera')) {
        toastr["error"]("Error de cabecera, comprueba el formato del fichero.", "Error!")
      } else if (data.match('error-file')) {
        toastr["error"]("Error de fichero, puede que esté dañado", "Error!")
      } else {
        $('#file-content-modal').modal('show'),
        $('#file-content-preview').html(data)
      }
      loadingOff();
    },
    error: function(e) {
        $("#err").html(e).fadeIn();
      }          
    });
  }));

  $('.import-data').on('click', function() {
      form = $("#frmCSVImport");
      url = $(form).attr('action');
      opt = 'import-csv';
      urlPath = url+opt;
      usedMethod = $(form).attr('method');
      $.ajax({
        url: urlPath,
        type: usedMethod,
        data:  new FormData($("#frmCSVImport")[0]),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend : function() {
          loadingOn("Importando Profesores...");
          $('#file-content-modal').modal('hide');
        },
        success: function(data) {
          if (data.match('Error-importar')) {
            toastr["error"]("Error al importar fichero.", "Error!")
          } else if (data.match('Error-csv')) {
            toastr["error"]("El fichero CSV contiene datos erróneos.", "Error!")
          } else {
            toastr["success"]("¡Datos importados con éxito!", "Correcto!");
            setTimeout(function(){location.reload()}, 700);
          }
          loadingOff();
        },
        error: function(e) {
          $("#err").html(e).fadeIn();
        }          
        });
  });
});