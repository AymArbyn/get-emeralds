<?php
    // include configuration
    require("../../includes/data_config.php");

    // Set the JSON header
    header("Content-type: text/json");

    // create empty arrays for readings
    $dc1 = [];
    $dc2 = [];
    $dc3 = [];
    $dc4 = [];
    $ac1 = [];
    $ac2 = [];

    // total usage
    $total_dc1 = 0;
    $total_dc2 = 0;
    $total_dc3 = 0;
    $total_dc4 = 0;
    $total_ac1 = 0;
    $total_ac2 = 0;

    try {
        // get time and set limit to inactivity
        $curr_time = time() * 1000 + 8 * 60 * 60 * 1000;

        // get data from database
        $limit = 60;
        $raw_rows = CS50::query("SELECT * FROM data WHERE user_id = " . $_SESSION["id"] . " ORDER BY time DESC");

        // get total of power usage
        foreach ($raw_rows as $k => $row) {
            $total_dc1 += $row["dc1"];
            $total_dc2 += $row["dc2"];
            $total_dc3 += $row["dc3"];
            $total_dc4 += $row["dc4"];
            $total_ac1 += $row["ac1"];
            $total_ac2 += $row["ac2"];
        }

        $rows = array_splice($raw_rows, 0, $limit);

        // see if system has been inactive
        $x = $rows[0]["time"] * 1;
        $is_inactive = $curr_time - $x < INACTIVITY_TIME;

        // fill up remaining spaces with zero
        $remaining = $limit - count($rows);
        for ($i = $remaining; $i > 0; $i--) {
                array_push($dc1, 0.0);
                array_push($dc2, 0.0);
                array_push($dc3, 0.0);
                array_push($dc4, 0.0);
                array_push($ac1, 0.0);
                array_push($ac2, 0.0);
        }

        // process data before returning as json
        for ($i = count($rows) - 1; $i >= 0; $i--) {
        	// array_push($dc1, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["dc1"]
         //    ]);
         //    array_push($dc2, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["dc2"]
         //    ]);
         //    array_push($dc3, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["dc3"]
         //    ]);
         //    array_push($dc4, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["dc4"]
         //    ]);
         //    array_push($ac1, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["ac1"]
         //    ]);
         //    array_push($ac2, [
         //        "x" => $rows[$i]["time"] * 1,
         //        "y" => (float) $rows[$i]["ac2"]
         //    ]);
            array_push($dc1, (float) $rows[$i]["dc1"]);
            array_push($dc2, (float) $rows[$i]["dc2"]);
            array_push($dc3, (float) $rows[$i]["dc3"]);
            array_push($dc4, (float) $rows[$i]["dc4"]);
            array_push($ac1, (float) $rows[$i]["ac1"]);
            array_push($ac2, (float) $rows[$i]["ac2"]);
        }
    }
    catch (Exception $e) {
        for ($i = 0; $i < 100; $i++) {
            array_push($dc1, 0.0);
            array_push($dc2, 0.0);
            array_push($dc3, 0.0);
            array_push($dc4, 0.0);
            array_push($ac1, 0.0);
            array_push($ac2, 0.0);
        }
    }

    echo json_encode([
        "data" => [
            "usage" => [
                "dc1" => $dc1,
                "dc2" => $dc2,
                "dc3" => $dc3,
                "dc4" => $dc4,
                "ac1" => $ac1,
                "ac2" => $ac2
            ],
            "state" => [
                "dc1" => $is_inactive ? $rows[0]["dc1_state"] : 1,
                "dc2" => $is_inactive ? $rows[0]["dc2_state"] : 1,
                "dc3" => $is_inactive ? $rows[0]["dc3_state"] : 1,
                "dc4" => $is_inactive ? $rows[0]["dc4_state"] : 1,
                "ac1" => $is_inactive ? $rows[0]["ac1_state"] : 1,
                "ac2" => $is_inactive ? $rows[0]["ac2_state"] : 1
            ],
            "total" => [
                "dc1" => $total_dc1 / 1000,
                "dc2" => $total_dc2 / 1000,
                "dc3" => $total_dc3 / 1000,
                "dc4" => $total_dc4 / 1000,
                "ac1" => $total_ac1 / 1000,
                "ac2" => $total_ac2 / 1000
            ]
        ]
    ]);
?>