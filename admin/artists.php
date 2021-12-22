<?php
    require_once(__DIR__ . "/../api/utils.php");
    redirectIfNot('admin', '/');
    require(__DIR__ . "/../header.php");
    require(__DIR__ . "/common.php");
    
    $file = basename(__FILE__);
    $entity = substr($file, 0, strlen($file) - 4);
?>
        <script src="/admin/js/<?= $entity ?>.js"></script>
    </body>
</html>