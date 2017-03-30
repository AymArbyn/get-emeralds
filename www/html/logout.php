<?php
    // include configuration
    require("../includes/config.php");

    // logout current user
    logout();

    // return to login page
    redirect("/login/?ref=logout");
?>
