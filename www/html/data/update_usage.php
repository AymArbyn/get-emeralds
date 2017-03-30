<?php
    // include python function
    require(__DIR__ . "/../../includes/data_config.php");

    // Set the JSON header
    // header("Content-type: text/json");

    while (True) {
        try {
            $result = python(__DIR__ . "/../scripts/get_data_from_marc.py");
            $result = explode(" ", $result);

            // get time
            $x = time() * 1000 + 8 * 60 * 60 * 1000;
            $step = INACTIVITY_TIME;

            // fill with zeroes the time of inactivity
            $rows = CS50::query("SELECT * FROM data WHERE user_id = " . USER_ID . " ORDER BY time DESC LIMIT 1");

            // check if there IS data
            if (count($rows) > 0 && ($rows[0]["time"] + $step) < $x) {
                // initialize one query multple inserts
                $query = "INSERT INTO data (user_id, time, dc1, dc2, dc3, dc4, ac1, ac2, dc1_state, dc2_state, dc3_state, dc4_state, ac1_state, ac2_state) VALUES ";

                // add zeroes during inactivity time but limit values to 990 for SQL
                for ($i = $rows[0]["time"] + $step, $ctr = 0; $i <= $x && $ctr < 990; $i += $step) {
                    $query .= "(" . USER_ID . ", " . $i . ", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0), ";
                    // echo $ctr;
                    $ctr++;
                }

                // remove last comma
                $query = substr($query, 0, count($query) - 3);

                // do query
                echo($query);
                $rows = CS50::query($query);
            }

            // query database to insert user
            $x = time() * 1000 + 8 * 60 * 60 * 1000 + 1000;
            try {
                $rows = CS50::query("INSERT INTO data (user_id, time, dc1, dc2, dc3, dc4, ac1, ac2, dc1_state, dc2_state, dc3_state, dc4_state, ac1_state, ac2_state) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    USER_ID, /// change depending on the user
                    $x,
                    (float) $result[3] * 5 * !($result[9]),
                    (float) $result[4] * 5 * !($result[10]),
                    (float) $result[5] * 5 * !($result[11]),
                    (float) $result[6] * 5 * !($result[12]),
                    (float) $result[7] * 220 * !($result[13]),
                    (float) $result[8] * 220 * !($result[14]),
                    (int) $result[9],
                    (int) $result[10],
                    (int) $result[11],
                    (int) $result[12],
                    (int) $result[13],
                    (int) $result[14]
                );

                print(
                    $result[3] * 5 * !($result[9]) . " " .
                    $result[4] * 5 * !($result[10]) . " " .
                    $result[5] * 5 * !($result[11]) . " " .
                    $result[6] * 5 * !($result[12]) . " " .
                    $result[7] * 220 * !($result[13]) . " " .
                    $result[8] * 220 * !($result[14]) . " " .
                    $result[9] . " " .
                    $result[10] . " " .
                    $result[11] . " " .
                    $result[12] . " " .
                    $result[13] . " " .
                    $result[14] . "\n"
                );
            }
            catch (MyException $f) {
                continue;
            }
        }
        catch (MyException $e) {
            continue;
        }
        sleep(2);
    }
?>