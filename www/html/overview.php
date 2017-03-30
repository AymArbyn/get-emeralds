<?php
    // include configuration
    require("../includes/config.php");

    // add title, navigation, CSS, and scripts
    $values = [
        "title" => "System Overview",
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

    render("overview-graph.php", $values);
?>