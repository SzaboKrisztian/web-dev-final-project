function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email) {
        document.getElementById('email').classList.add('error');
    }

    if (!password) {
        document.getElementById('password').classList.add('error');
    }

    if (!email || email === 'admin' || !password) {
        document.getElementById('errorMsg').innerHTML = 'Email and password are both required.';
        return;
    }

    if (email && password) {
        post('/login', { email, password })
            .then(() => document.location.reload());
    }
}

function clearError() {
    document.getElementById('email').classList.remove('error');
    document.getElementById('password').classList.remove('error');
    document.getElementById('errorMsg').innerHTML = '';
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-login').addEventListener('click', login);
});