<?php
        foreach($exts1 as $ext) {
            if (!empty($e['d'][0])) {
                $b[] = $ext['constants'];
            }
        }

        foreach($exts2 as $ext) {
            $ini = parse_ini_file($ext);

            if (!empty($e['d'][0])) {
                $b[] = $ini['constants'];
            }
        }

?>