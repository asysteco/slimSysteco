let qrCode = '';

window.setInterval(() => {
    location.reload();
}, 300000);

let qrloginUrl = '/xhr/qrLogin';

document.addEventListener('keypress', function (e) {
    if (e.key === "Enter") {
        qrLogin(qrCode);
        return;
    }
    qrCode = qrCode + e.key;
});

async function qrLogin(loginToken) {
    if (!loginToken || /\s/g.test(loginToken)) {
        toastr["error"]("¡Código QR no válido!", defaultErrorTitle);
        return;
    }

    var qrLoginData = {
        adminCode: loginToken
    };

    qrCode = '';

    loadingOn();
    const response = await fetch(qrloginUrl, {
      method: 'POST',
      body: JSON.stringify(qrLoginData),
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.href = mainQrRoute;
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