<?php
    // include configuration
    require("../includes/config.php");

    // add title, navigation, CSS, and scripts
    $values = [
        "title" => "Log In &#124; GET:EMERALDS",
        "include_nav" => False,
        "include_side" => False,
        "css" => [
            "login.css"
        ],
        "js" => []
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
			else if ($err == "inv_usr") {
				$values["err_msg"] = "The username you entered is invalid";
			}
			else if ($err == "inv_pwd") {
				$values["err_msg"] = "Your username and password did not match";
			}
		}

        // else render form
        render("login-form.php", $values);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            redirect("/login/?err=blnk_usr");
        }
        else if (empty($_POST["password"]))
        {
            redirect("/login/?err=blnk_pwd");
        }

        // query database for user
        $rows = CS50::query("SELECT * FROM users WHERE username = ?", $_POST["username"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user"s input against hash that"s in database
            if (password_verify($_POST["password"], $row["hash"]))
            {
                // remember that user"s now logged in by storing user"s ID in session
                $_SESSION["id"] = $row["id"];
                $_SESSION["type"] = $row["type"];
                $_SESSION["username"] = $_POST["username"];

                // redirect to portfolio
                redirect("/");
            }

            redirect("/login/?err=inv_pwd");
        }

        // else show invalid username
        redirect("/login/?err=inv_usr");
    }

?>
