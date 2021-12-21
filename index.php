<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chinook</title>
    <script>
        // In my case, apache serves from /data/www/default, thus
        // the first 17 characters need to be skipped
        const folder = "<?= __DIR__ ?>";
        const apiRoot = `http://localhost${folder.endsWith('/') ? folder.substring(17, folder.length - 1) : folder.substring(17)}/api`;
    </script>
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