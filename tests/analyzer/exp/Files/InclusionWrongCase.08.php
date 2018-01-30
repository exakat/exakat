<?php

$expected     = array('require_once \'./FICHIER.php\'',
                     );

$expected_not = array('include APP.\'/fichier.php\'',
                      'include APP.\'/fich{$ier}.php\'',
                      'include "$path/fichier.php"',
                      'require_once \'./fichier.php\'',
                     );

?>