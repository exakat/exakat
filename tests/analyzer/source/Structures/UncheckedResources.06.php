<?php

        $b = array();
        $file = fopen($project_list, "r");
        while ($file && !feof($file))
            $b[] = trim(fgets($file));
        fclose($file);

        $file2 = fopen($project_list, "r");
        if ($file2)
            $b[] = trim(fgets($file));

        $file3 = fopen($project_list, "r");
        $b[] = trim(fgets($file));

?>