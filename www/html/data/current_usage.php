<?php
    // include configuration
    require("../../includes/data_config.php");

    // Set the JSON header
    header("Content-type: text/json");

    // total usage
    $total_dc1 = 0;
    $total_dc2 = 0;
    $total_dc3 = 0;
    $total_dc4 = 0;
    $total_ac1 = 0;
    $total_ac2 = 0;

    try {
        $limit = 1;
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

        $rows = array_splice($raw_rows, $limit);

        // see if system has been inactive
        $x = $rows[0]["time"] * 1;

        // get time and set limit to inactivity
        $curr_time = time() * 1000 + 8 * 60 * 60 * 1000;
        $is_inactive = $curr_time - $x < INACTIVITY_TIME;

        // separate usage and state
    	$result = [
    		"usage" => [
                "dc1" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["dc1"]) : 0
                ],
                "dc2" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["dc2"]) : 0
                ],
                "dc3" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["dc3"]) : 0
                ],
                "dc4" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["dc4"]) : 0
                ],
                "ac1" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["ac1"]) : 0
                ],
                "ac2" => [
                    "x" => $x,
                    "y" => $is_inactive ? ((float) $rows[0]["ac2"]) : 0
                ]
    		],
            "state" => [
                "dc1" => $is_inactive ? ((int) $rows[0]["dc1_state"]) : 1,
                "dc2" => $is_inactive ? ((int) $rows[0]["dc2_state"]) : 1,
                "dc3" => $is_inactive ? ((int) $rows[0]["dc3_state"]) : 1,
                "dc4" => $is_inactive ? ((int) $rows[0]["dc4_state"]) : 1,
                "ac1" => $is_inactive ? ((int) $rows[0]["ac1_state"]) : 1,
                "ac2" => $is_inactive ? ((int) $rows[0]["ac2_state"]) : 1
            ],
            "total" => [
                "dc1" => $total_dc1 / 1000,
                "dc2" => $total_dc2 / 1000,
                "dc3" => $total_dc3 / 1000,
                "dc4" => $total_dc4 / 1000,
                "ac1" => $total_ac1 / 1000,
                "ac2" => $total_ac2 / 1000
            ]
    	];
    }
    catch (Exception $e) {
    	$result = [
            "usage" => [
                "dc1" => (int) 0,
                "dc2" => (int) 0,
                "dc3" => (int) 0,
                "dc4" => (int) 0,
                "ac1" => (int) 0,
                "ac2" => (int) 0
            ],
            "state" => [
                "dc1" => (int) 0,
                "dc2" => (int) 0,
                "dc3" => (int) 0,
                "dc4" => (int) 0,
                "ac1" => (int) 0,
                "ac2" => (int) 0
            ],
            "total" => [
                "dc1" => 0,
                "dc2" => 0,
                "dc3" => 0,
                "dc4" => 0,
                "ac1" => 0,
                "ac2" => 0
            ]
        ];
    }

    echo json_encode(["data" => $result]);
?>
