<?php

$coded_ext = glob('library/Analyzer/Extensions/*.php');
foreach($coded_ext as $k => $v) {
    $coded_ext[$k] = strtolower(substr(basename($v), 3, -4));
}

$ext = get_loaded_extensions();
foreach($ext as $k => $v) {
    $ext[$k] = strtolower($v);
}

$diff = array_diff($ext, $coded_ext);
print_r($diff);
print count($diff)." missing\n";

//print_r(get_extension_funcs("crypto"));
//print_r(get_defined_constants());
//print_r(get_declared_classes ());


?>