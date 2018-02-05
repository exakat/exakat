<?php

$a = "{$_SERVER['DOCUMENT_ROOT']}";
$a2 = "{$_SERVER['UNKNOWN_INDEX']}";


// Beware of print (it has an extra Parenthesis)
print($_SERVER['DOCUMENT_ROOT']);
print($_SERVER['UNKNOWN_INDEX']);

print($_GET['DOCUMENT_ROOT']);

shell_exec($_POST['DOCUMENT_ROOT']['a5']);

// Direct concatenation
shell_exec('ls '.$_SERVER['DOCUMENT_ROOT']);

shell_exec('ls '.$_SERVER['UNKNOWN_INDEX']);
shell_exec('ls '.$_GET['DOCUMENT_ROOT']);
shell_exec('ls '.$_POST['DOCUMENT_ROOT']);

// Direct concatenation
shell_exec('ls '.$_SERVER['DOCUMENT_ROOT']['a1']);

shell_exec('ls '.$_SERVER['UNKNOWN_INDEX']['a2']);
shell_exec('ls '.$_GET['DOCUMENT_ROOT']['a3']);
shell_exec('ls '.$_POST['DOCUMENT_ROOT']['a4']);
