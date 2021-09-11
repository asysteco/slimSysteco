$(document).ready(function () {
    $('#QR-lector').focus()
});
var val;
$('#QR-form').submit(function (e) {
    e.preventDefault(),
        val = $('#QR-lector').val(),
        $('#QR-lector').val(''),
        $('#output').load('?ACTION=fichar-qr&criptedval=' + encodeURI(val)),
        setTimeout(function () {
            $('#listado-guardias').load(location.href + ' #listado-guardias > *'),
                $('#output').html("<span id='empty'><h3>Acerque el código QR al lector...</h3></span>"),
                $('#QR-lector').focus()
        }, 1500);
});
window.setInterval(() => {
    location.reload();
}, 300000);