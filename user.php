<?php
    echo "User";

    require(__DIR__ . "/logout.php");
?>

<button onclick="doGet()">Get user data</button>

<script>
    function doGet() {
        get('/customer').then(console.log);
    }
</script>