<?php

function x() {
$uncheckedDir = opendir('.');
readdir($uncheckedDir); 

$uncheckedDir2 = opendir('.');
closedir($uncheckedDir2); 

$uncheckedDir3 = opendir('.');
rewinddir($uncheckedDir3); 
$uncheckedDir = opendir('.');

readdir(opendir('uncheckedDir4'));
readdir2(opendir('uncheckedDir5'));
readdir(opendir2('uncheckedDir6'));

$uncheckedDir7 = opendir('asdfasdf');
while($f = readdir($uncheckedDir7)) {
    print "$f\n";
}



$checkedDir1 = opendir('.');
if (!is_resource($checkedDir1)) {}
rewinddir($checkedDir1); 

$checkedDir2 = opendir('.');
if (!$checkedDir2) {}
rewinddir($checkedDir2); 

// wrong for the comparison!
if ($checkedDir3 = opendir('.')) { 
    readdir($checkedDir3);
}

$checkedDir4 = opendir('.');
while(false !== ($r = readdir($checkedDir4))) {}

//Structures/UncheckedResources
}
?>

?>