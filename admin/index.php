<?php
    require(__DIR__ . "/../header.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
    <label for="">Password</label>
    <input type="password" id="password">
    <div><p id="errorMsg"></p></div>

    <button onclick="login()">Submit</button>

    <script src="/js/api.js"></script>
    <script src="/admin/js/login.js"></script>