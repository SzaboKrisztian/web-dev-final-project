<button onclick="logout()">Log out</button>

<script>
    function logout() {
        get('/logout').then(() => document.location.reload());
    }
</script>