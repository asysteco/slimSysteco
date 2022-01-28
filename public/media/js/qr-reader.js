let qrCode = '';

window.setInterval(() => {
    location.reload();
}, 300000);

let qrloginUrl = '/xhr/checkIn';

document.addEventListener('keypress', function (e) {
    if (e.key === "Enter") {
        console.log(qrCode);
        checkIn(qrCode);
        return;
    }
    qrCode = qrCode + e.key;
});

async function checkIn(qrCode) {
    if (!qrCode || /\s/g.test(qrCode)) {
        toastr["error"]("¡Código QR no válido!", defaultErrorTitle);
        return;
    }

    var checkInData = {
        qrCode: qrCode
    };

    qrCode = '';

    loadingOn();
    const response = await fetch(qrloginUrl, {
      method: 'POST',
      body: JSON.stringify(checkInData),
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        toastr["success"](res.data.name, '¡Fichaje correcto!');
      } else {
        loadingOff();
        toastr["warning"]("¡Usuario no válido!", defaultErrorTitle);
      }
    })
    .catch(function () {
        loadingOff();
        toastr["error"](defaultCatchMessage, defaultErrorTitle)
    });
}