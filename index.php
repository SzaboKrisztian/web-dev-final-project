<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chinook</title>
    <?php require_once(__DIR__ . "/defineApiRoot.php"); ?>
    <script src="js/api.js"></script>
</head>
<body>
    <h1>Index</h1>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    echo("<pre>" . var_dump($_SESSION) . "</pre>");

    if (isset($_SESSION['role'])) {
        require(__DIR__ . "/" . $_SESSION['role'] . ".php");
    } else {
        require(__DIR__ . "/login.php");
    }
    ?>
</body>
</html>