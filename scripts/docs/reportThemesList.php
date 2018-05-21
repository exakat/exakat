<?php

$reports = glob('human/en/Reports/*.ini');
include './library/Exakat/Reports/Reports.php';
include './library/Exakat/Config.php';

foreach($reports as $report){
    $file = basename($report);
    $class = substr($file,0, -4);
    include "./library/Exakat/Reports/$class.php";
    
    $fullClass = "\Exakat\Reports\\$class";
    $theReport = new $fullClass(null, null);
    $themes = $theReport->dependsOnAnalysis();
    
    $ini = parse_ini_file($report);
    unset($ini['themes']);
    
    $iniFile = array();
    foreach($ini as $name => $value) {
        if (is_array($value)) {
            foreach($value as $v) {
                $value = str_replace('"', '\"', $v);
                $iniFile[] = "{$name}[] = \"$value\";";
            }
        } else {
            $value = str_replace('"', '\"', $value);
            $iniFile []= "$name = \"$value\";";
        }
    }

    if (empty($themes)) {
            $iniFile[] = "themes[] = \"\";";
    } else {
        foreach($themes as $t) {
            $value = str_replace('"', '\"', $t);
            $iniFile[] = "themes[] = \"$value\";";
        }
    }
    
    $iniFile = implode("\n", $iniFile)."\n";
    file_put_contents($report, $iniFile);
}

?>