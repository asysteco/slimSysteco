

let loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', function (event) {
    event.preventDefault();
    sendLogin();
});

function sendLogin() {

    let formElement = new FormData(loginForm);
    console.log(formElement.values());
}