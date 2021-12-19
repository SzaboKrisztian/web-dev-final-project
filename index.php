<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello</title>
</head>
<body>
    <h1>O, hai!</h1>

    <pre>
        <?php
        require_once(__DIR__ . '/models/track.php');

        $tracks = TrackDAO::getInstance();
        $test = $tracks->findAll();
        print_r($test);
        ?>
    </pre>
</body>
</html>