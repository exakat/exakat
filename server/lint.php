<?php

$phpexec = $argv[1];
$phpversion = $argv[2];
$project_code = $argv[3];
$tmpFileName = $argv[4];
$tmp_dir = dirname($project_code).'/.exakat';

$shell = "cd {$project_code}; cat $tmpFileName" . ' | sed "s/>/\\\\\\\\>/g" | tr "\n" "\0" | xargs -0 -n1 -P2 -I {} sh -c "' . $phpexec . ' -l {} 2>&1 || true "';

$b =  hrtime(true);
$res = shell_exec($shell);
$e =  hrtime(true);

            $incompilables = array('DROP TABLE IF EXISTS compilation'.$phpversion,
<<<SQL
CREATE TABLE compilation{$phpversion} (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQL
);

        $total = 0;
        foreach(explode(PHP_EOL, $res) as $resFile) {
            if (trim($resFile) == '') {
                continue; // do nothing. All is fine.
            }

            if ($error  =  isError($resFile)) {
                ++$total;
                $incompilables[] = 'INSERT INTO compilation'.$phpversion.' ("error", "file", "line") VALUES (\''.sqlite3::escapeString($error['error']).'\',\''.sqlite3::escapeString($error['file']).'\','.(int) $error['line'].' )';
            }
        }

        $incompilables[] = 'REPLACE INTO hash ("key", "value") VALUES ("notCompilable'.$phpversion.'",'.$total.')';

    $id = dechex(random_int(0, \PHP_INT_MAX));

    file_put_contents($tmp_dir.'/dump-'.$id.'.php', '<?php $queries = '.var_export($incompilables, true).'; ?>');

    function isError($resFile) {
        if (substr($resFile, 0, 28) == 'No syntax errors detected in') {
            return false;
            // do nothing. All is fine.
        }

        if (substr($resFile, 0, 15) == 'Errors parsing ') {
            return false; // ignore this one
        }

        if (trim($resFile) == '') {
            return false; // do nothing. All is fine.
        }

        if (preg_match('#^(?:PHP )?Parse error: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return $error;
        }

        if (preg_match('#^(?:PHP )?Deprecated: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }
        
        if (preg_match('#^(?:PHP )?Fatal error: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return $error;
        }
    
        // Warnings are considered OK.
        if (preg_match('#^(?:PHP )?Warning: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }

        // Notice are considered OK.
        if (preg_match('#^(?:PHP )?Notice: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }

        if (preg_match('#^(?:PHP )?Strict Standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return $error;
        }

        print "\nCan't understand this php feedback : $resFile\n";

        return false;
    }

?>