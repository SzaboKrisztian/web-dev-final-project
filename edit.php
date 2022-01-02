<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require(__DIR__ . "/header.php");
?>
    <body>
        <h1>Edit account</h1>

        <?php require(__DIR__ . "/customer.php"); ?>

        <script src="/js/api.js"></script>
        <script src="/js/edit.js"></script>
    </body>
</html>