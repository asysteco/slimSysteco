let baja = "";

$(document).on('click', '.modal-baja', function(e) {
    e.preventDefault();
    $('#modal-profesores').removeClass('modal-fs');
    $('#modal-size').removeClass('modal-fs');
    $('#modal-size').removeClass('modal-lg');
    $('#modal-cabecera').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
    $('#modal-pie').attr('class', 'modal-footer');

    id = $(this).attr('profesor');
    nombre = $(this).attr('nombre');
    baja = $(this).attr('data-baja');
    cabecera = "<h5 class='modal-title'>Dar de alta a " + nombre + "</h5>";
    contenido = "<i>¿Seguro que desea realizar este cambio? Utilice esta opción solo si el profesor se da de alta.</i>";
    input = "<br><br><input id='baja-date' class='form-control' type='text' placeholder='Seleccione fecha de alta...' autocomplete='off'>";
    btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
    btn2 = "<button type='button' class='btn btn-success float-right baja'>Dar de Alta</button>";
    title = "";
    textarea = ""
    
    if (baja == '2') {
        cabecera = "<h5 class='modal-title'>Dar de baja a " + nombre + "</h5>";
        contenido = "<i>¿Seguro que desea realizar este cambio? Utilice esta opción solo si el profesor se da de baja.</i>";
        input = "<br><br><input id='baja-date' class='form-control' type='text' placeholder='Seleccione fecha de baja...' autocomplete='off'>";
        btn2 = "<button type='button' class='btn btn-success float-right baja'>Dar de Baja</button>";
        title = "<br><p>Puede añadir un motivo de justificación para esta baja.</p>";
        textarea = "<textarea id='baja-reason' class='long-text' style='resize: none;' cols='62' rows='10' placeholder='Escriba el motivo de justificación aquí'></textarea>"
    }

    $('#modal-cabecera').html(cabecera);
    $('#modal-contenido').html(contenido);
    $('#modal-contenido').append(input);
    $('#modal-contenido').append(title);
    $('#modal-contenido').append(textarea);
    $('#baja-date').datepicker({minDate: -5, maxDate: +5});
    $('#modal-pie').html(btn1);
    $('#modal-pie').append(btn2);
    $('#modal-pie').attr('class', 'modal-buttons-footer');
    $('#modal-profesores').modal('show');
    return;
});

$(document).on('click', '.baja', function() {
	let date = $('#baja-date').val();
	let reason = $('#baja-reason').val();

    if (!isGoodDate(date)) {
        toastr["error"]('Debe seleccionar una fecha válida.', "Error!");
        return;
    }

	let urlPath = "?ACTION=profesores&OPT=baja";
	let data = {
		profesor: id,
		baja: baja,
		date: date,
        reason: reason
	};

    $.ajax({
        url: urlPath,
        type: 'POST',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (response) {
            try {
                rData = JSON.parse(response);
            } catch(e) {
                toastr["error"]("Error inesperado...", "Error!");
                loadingOff();
                return;
            }

            if (rData.success) {
                toastr["success"](rData.msg + nombre, "Correcto!");
                setTimeout(() => { location.reload() }, 700);
                return;
            } else {
                toastr["error"](rData.msg, "Error!");
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!");
            loadingOff();
        }
    });
});

function isGoodDate(date){
    var dateRegex = /^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$/;
    return dateRegex.test(date);
}