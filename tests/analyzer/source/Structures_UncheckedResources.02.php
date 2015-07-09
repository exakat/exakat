<?php

function x() {
    $uncheckedDir = opendir('.');
    readdir($uncheckedDir); 

    $uncheckedDir2 = bzopen('.');
    bzclose($uncheckedDir2); 

    $uncheckedDir3 = fopen('.','r+');
    fclose($uncheckedDir3); 

    readdir(opendir('uncheckedDir4'));
    readdir2(opendir('uncheckedDir5'));
    readdir(opendir2('uncheckedDir6'));

    $pspell_new = pspell_new('asdfasdf');
    while($f = pspell_suggest($pspell_new)) {
        print "$f\n";
    }
}
?>