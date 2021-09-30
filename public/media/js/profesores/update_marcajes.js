let profesor = "";
let date = "";
let fecha = "";
let reason = "";

$(document).on('click', '.actualiza', function () {
    let profesor = $(this).attr('data-id');
    let date =$(this).attr('data-date');
    let hour = $(this).attr('data-hour');
    let inicio = $(this).attr('data-startHour');
    let fin = $(this).attr('data-endHour');
    let type = $(this).attr('data-type');
    let action = $(this).attr('data-action');
    let value = $(this).attr('data-value');

    data = {
        profesor: profesor,
        date: date,
        hour: hour,
        inicio: inicio,
        fin: fin,
        type: type,
        action: action,
        value: value
    };
    urlPath = '?ACTION=marcajes&OPT=update';

    $.ajax({
        url: urlPath,
        type: 'POST',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (response) {
            rData = JSON.parse(response);

            if (rData.success) {
                toastr["success"](rData.msg, "Correcto!");
                getRow(profesor, date, hour);
            } else {
                toastr["error"](rData.msg, "Error!");
                loadingOff();
            }
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!");
            loadingOff();
        }
    });
});

$(document).on('click', '.justify-day', function () {
    profesor = $(this).attr('data-id');
    date = $(this).attr('data-date');
    fecha = $(this).attr('data-fecha');
    reason = $('#reason_' + date).val();
    $('#justify-reason').val(reason);
    $('#justify-date').html(fecha);
    $('#justify-profesor').html(nombre);
    $('#modal-justify').modal('show');
    $('#modal-profesores').css('filter', 'brightness(0.5)');
});

$(document).on('hidden.bs.modal', '#modal-justify', function () {
    profesor = "";
    date = "";
    fecha = "";
    reason = "";
    $('#modal-justify').modal('hide');
    $('#modal-profesores').css('filter', 'none');
});


$(document).on('click', '#justify-confirm', function () {
    let reason = $('#justify-reason').val();
    urlPath = '?ACTION=marcajes&OPT=justifyDay';
    data = {
        profesor: profesor,
        date: date,
        reason: reason
    };

    if (date === "") {
        toastr["error"]("Error al obtener fecha de justificación.", "Error!");
        return;
    }

    if (profesor === "") {
        toastr["error"]("Error al obtener personal a justificar.", "Error!");
        return;
    }

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
                toastr["success"](rData.msg, "Correcto!");
                $('#modal-justify').modal('hide');
                $('#modal-profesores').modal('hide');
                click.click();
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

function getRow (profesor, date, hour){
    $.ajax({
        url: '?ACTION=marcajes&OPT=getRow',
        type: 'GET',
        data: {
            profesor: profesor,
            date: date,
            hour: hour,
        },
        success: function (response) {
            let rData = JSON.parse(response);

            if (rData.success) {
                $('#fila_'+profesor+'_'+date+'_'+hour).replaceWith(rData.data);
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
}