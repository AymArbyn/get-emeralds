<?php
    // configuration
    require("../includes/config.php");

    // add title, navigation, CSS, and scripts
    $values = [
        "title" => "Register New User",
        "include_nav" => True,
        "include_side" => True,
        "css" => [
            "bootstrap.min.css",
            "flat-ui.min.css",
            "style.css"
        ],
        "js" => [
            "vendors/jquery-1.12.0.min.js",
            "vendors/flat-ui/flat-ui.min.js",
            "vendors/flat-ui/application.js",
            "vendors/highcharts/highcharts.src.js",
            "vendors/highcharts/modules/exporting.js",
            "main.js"
        ]
    ];

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (isset($_GET["err"])) {
            $err = htmlspecialchars($_GET["err"]);
            if ($err == "blnk_usr") {
                $values["err_msg"] = "Please enter your username";
            }
            else if ($err == "blnk_pwd") {
                $values["err_msg"] = "Please enter your password";
            }
            else if ($err == "usr_exist") {
                $values["err_msg"] = "Username already registered";
            }
        }
        else if (isset($_GET["success"])) {
            $success = htmlspecialchars($_GET["success"]);
            if ($success == "true") {
                $values["err_msg"] = "User successfully registered!";
            }
        }

        // else render form
        render("registration-form.php", $values);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"])) {
            redirect("/register.php?err=blnk_usr");
        }
        else if (empty($_POST["password"])) {
            redirect("/register.php?err=blnk_pwd");
        }

        // query database for user
        $rows = CS50::query("SELECT * FROM users WHERE username = ?", $_POST["username"]);

        // if we found user, check password
        if (count($rows) == 1) {
            redirect("/register.php?err=usr_exist");
        }

        // query database to insert user
        $rows = CS50::query("INSERT INTO users (type, username, hash) VALUES (2, ?, ?)", $_POST["username"], crypt($_POST["password"]));

        redirect("/register.php?success=true");
    }
?>
