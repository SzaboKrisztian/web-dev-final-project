<?php
    echo(isset($_COOKIE['myKooky']) ? 'myKooky: ' . $_COOKIE['myKooky'] : 'No kooky :(');
?>
<br>
<a href="/cookie2.php">Set cookie</a>