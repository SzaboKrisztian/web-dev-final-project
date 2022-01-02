<?php
    require_once(__DIR__ . "/../api/utils.php");
    redirectIfNot('admin', '/');
    require(__DIR__ . "/../header.php");

    $file = basename(__FILE__);
    $entity = substr($file, 0, strlen($file) - 4);
?>
    <body>
        <p class="title">Manage <?=$entity?></p>
        <?php require(__DIR__ . "/common.php"); ?>

        <script src="/admin/js/<?= $entity ?>.js"></script>
    </body>
</html>