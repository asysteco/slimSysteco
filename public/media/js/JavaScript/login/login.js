let loginUrl = '/xhr/login';

loginForm.addEventListener('submit', function (event) {
    event.preventDefault();
    sendLogin();
});

async function sendLogin() {
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    if (!username || !password) {
        toastr["warning"]("Debe completar todos los campos para iniciar sesión", defaultAlertTitle);
        return;
    }

    var loginData = {
        username: username,
        password: password
    };
    
    const response = await fetch(loginUrl, {
      method: 'POST',
      body: JSON.stringify(loginData),
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.href = mainRoute;
      } else {
        toastr["warning"]("Iniciales y/o contraseña incorrectos", defaultErrorTitle);
      }
    })
    .catch(function () {
      toastr["error"](defaultCatchMessage, defaultErrorTitle)
    });
}