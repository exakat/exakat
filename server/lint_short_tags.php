<?php

$php = $argv[1];
$project_dir = $argv[2];
$tmpFileName = $argv[3];

$shell = "cd '{$project_dir}/code'; cat '$tmpFileName' | xargs -n1 -P2 {$php} -d error_reporting=-1 -d short_open_tag=0 -r \"echo count(token_get_all(file_get_contents(\\\$argv[1]))).\\\" \\\$argv[1]\n\\\";\" 2>>/dev/null ";

$resultNosot = shell_exec($shell) ?? '';
$tokens = (int) array_sum(explode("\n", $resultNosot));

$shell = "cd '{$project_dir}/code'; cat '$tmpFileName' | xargs -n1 -P2 {$php} -d error_reporting=0 -d short_open_tag=1 -r \"\\\$code = file_get_contents(\\\$argv[1]); echo substr_count(\\\$code, PHP_EOL).' '.count(token_get_all(\\\$code)).\\\" \\\$argv[1]\n\\\";\" 2>>/dev/null ";

$resultSot = shell_exec($shell) ?? '';
$details = explode("\n", trim($resultSot));
$details = array_map(function (string $x) { return explode(' ', $x);}, $details);
$tokenssot = (int) array_sum(array_column($details, 1));
$loc = (int) array_sum(array_column($details, 0));

$forStorage = array('REPLACE INTO hash  ("key", "value") VALUES ("tokens", '.$tokens.'),  ("loc", '.$loc.')');
// Short Open Tags always have more tokens than not-short open tags ones, unless they are identical.
if ($tokenssot === $tokens) {
    storeResults($project_dir, $forStorage);
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
    foreach($nosot as $file => $countNoSot) {
        if ($sot[$file] !== $countNoSot) {
            $file = trim($file, '.');
            $forStorage[] = 'INSERT INTO shortopentag ("id", "file") VALUES (NULL, \''.sqlite3::escapeString(trim($file, '.')).'\')';
        }
    }
}

storeResult($project_dir, $forStorage);

function storeResults($project_dir, array $list) {
    $id = dechex(random_int(0, \PHP_INT_MAX));
    file_put_contents($project_dir.'/.exakat/dump-'.$id.'.php', '<?php $queries = '.var_export($list, true).'; ?>');
}
?>