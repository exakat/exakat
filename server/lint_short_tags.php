<?php

$php = $argv[1];
$project_dir = $argv[2];
$tmpFileName = $argv[3];

$shell = "cd '{$project_dir}/code'; cat '$tmpFileName' | xargs -n1 -P5 {$php} -d error_reporting=-1 -d short_open_tag=0 -r \"echo count(token_get_all(file_get_contents(\\\$argv[1]))).\\\" \\\$argv[1]\n\\\";\" 2>>/dev/null ";

$resultNosot = shell_exec($shell) ?? '';
$tokens = (int) array_sum(explode("\n", $resultNosot));

$shell = "cd '{$project_dir}/code'; cat '$tmpFileName' | xargs -n1 -P5 {$php} -d error_reporting=0 -d short_open_tag=1 -r \"echo count(token_get_all(file_get_contents(\\\$argv[1]))).\\\" \\\$argv[1]\n\\\";\" 2>>/dev/null ";

$resultSot = shell_exec($shell) ?? '';
$tokenssot = (int) array_sum(explode("\n", $resultSot));

if ($tokenssot === $tokens) {
    die();
}

$nosot = explode("\n", trim($resultNosot));
$nosot2 = array();
foreach($nosot as $value) {
    if (strpos($value, ' ') === false) {
        continue;
    }
    list($count, $file) = explode(' ', $value);
    $nosot2[$file] = $count;
}
$nosot = $nosot2;
unset($nosot2);

$sot = explode("\n", trim($resultSot));
$sot2 = array();
foreach($sot as $value) {
    list($count, $file) = explode(' ', $value);
    $sot2[$file] = $count;
}
$sot = $sot2;
unset($sot2);

if (count($nosot) === count($sot)) {
    $shortOpenTag = array();
    foreach($nosot as $file => $countNoSot) {
        if ($sot[$file] !== $countNoSot) {
            $file = trim($file, '.');
            $shortOpenTag[] = 'INSERT INTO shortopentag ("id", "file") VALUES (NULL, \''.sqlite3::escapeString(trim($file, '.')).'\')';
        }
    }

    if (!empty($shortOpenTag)) {
        $id = dechex(random_int(0, \PHP_INT_MAX));
        file_put_contents($project_dir.'/.exakat/dump-'.$id.'.php', '<?php $queries = '.var_export($shortOpenTag, true).'; ?>');
    }
}

?>