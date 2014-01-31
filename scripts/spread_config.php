<?php

$files = glob('projects/*');
$total = 0;
foreach($files as $file) {
    if ($file == 'projects/test') { continue; }
    if ($file == 'projects/default') { continue; }
    if (!is_dir($file)) { continue; }
    
    print "Copy to ".basename($file)."\n";
    copy("./projects/default/config.ini", "./$file/config.ini");
    $total ++;
}

print "\nCopied to $total files\n";

?>