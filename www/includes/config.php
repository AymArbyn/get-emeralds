<?php
    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("helpers.php");
    require("user.php");

    // CS50 Library
    require("../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");

    // enable sessions
    session_start();

    // require authentication for all pages except login, logout, and register
    if (!in_array($_SERVER["PHP_SELF"],
            ["/login.php", "/logout.php", "/register.php"])) {
        if (empty($_SESSION["id"])) {
            redirect("/login/");
        }
    }

    else if (in_array($_SERVER["PHP_SELF"],
            ["/login.php"])) {
        if (!empty($_SESSION["id"])) {
            redirect("/");
        }
	}

    // only allow admins in admin pages
    if (in_array($_SERVER["PHP_SELF"], ["/register.php"])) {
        if (empty($_SESSION["type"])) {
            redirect("/");
        }
    }
?>
