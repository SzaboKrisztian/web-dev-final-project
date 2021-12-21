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
        console.log(apiRoot);
    </script>
</head>
<body>
</body>
</html>