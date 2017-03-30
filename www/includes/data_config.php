<?php
    // display errors, warnings, and notices
    ini_set("display_errors", false);
    error_reporting(E_ALL);

    // requirements
    // require("helpers.php");
    require("python.php");
    require("user.php");

    // CS50 Library
    require(__DIR__ . "/../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");

    define("INACTIVITY_TIME", 24000);
    define("USER_ID", 1);

    // enable sessions
    session_start();
?>
