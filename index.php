<?php require(__DIR__ . "/header.php"); ?>
<body>
    <?php

    if (isset($_SESSION['role'])) {
        require(__DIR__ . "/browse.php");
    } else {
        require(__DIR__ . "/login.php");
    }
    ?>
    <script src="js/api.js"></script>
</body>
</html>