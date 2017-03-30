<?php
    // constants
    define("WINDOWS", 0);
    define("LINUX", 1);

    /**
     * Run python scripts depending on the OS.
     * OS parameter takes in strings only either
     */
    function python($filename, $OS = WINDOWS)
    {
        // read contents of configuration file
        $config_filename = __DIR__ . "/../config.json";
        $contents = file_get_contents($config_filename);
        if ($contents === false)
        {
            trigger_error("Could not read {$config_filename}", E_USER_ERROR);
        }

        // decode contents of configuration file
        $config = json_decode($contents, true);
        if (is_null($config))
        {
            trigger_error("Could not decode {$config_filename}", E_USER_ERROR);
        }

        // select python directory based on OS
        if ($OS == WINDOWS) {
            $python = $config['windows_python'];
        }
        else if ($OS == LINUX) {
            $python = "sudo" . $config['linux_python'];
        }
        else {
            trigger_error("Unknown operating system", E_USER_ERROR);
        }

        // var_dump("{$python} {$filename}");
        $result = shell_exec("{$python} {$filename}");
        return $result;
    }
?>