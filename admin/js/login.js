function login() {
    const password = document.getElementById('password').value;
    if (password.length > 0) {
        post('/login', { email: 'admin', password })
            .then(() => document.location.href = '/admin/artists.php');
    }
}