<input type="email" id="email" onchange="clearError()">
<input type="password" id="password" onchange="clearError()">
<div><p id="errorMsg"></p></div>

<button onclick="login()">Submit</button>

<script>
    function login() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email) {
            document.getElementById('email').classList.add('error');
        }

        if (!password) {
            document.getElementById('password').classList.add('error');
        }

        if (!email || !password) {
            document.getElementById('errorMsg').innerHTML = 'Email and password are both required.';
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
</script>