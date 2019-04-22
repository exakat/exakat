<?php

$expected     = array(
                     );

$expected_not = array('dirname(__DIR__) . DIRECTORY_SEPARATOR . \'vendor\' . DIRECTORY_SEPARATOR . \'autoload.php\'',
                      'NN_LIB . \'utility/SmartyUtils.php\'',
                      'dirname(__DIR__) . DIRECTORY_SEPARATOR . \'app/Extensions/util/PhpYenc.php\'',
                      'dirname(__DIR__) . DIRECTORY_SEPARATOR . \'resources/views/themes/smarty.php\'',
                      '__DIR__ . DIRECTORY_SEPARATOR . \'app.php\'',
                      '__DIR__ . DIRECTORY_SEPARATOR . \'bootstrap/constants.php\'',
                      '__DIR__ . \'/public/index.php\'',
                      'dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . \'bootstrap/autoload.php\'',
                      'dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . \'bootstrap/autoload.php\'',
                      'dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . \'bootstrap/autoload.php\'',
                      'dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . \'bootstrap\' . DIRECTORY_SEPARATOR . \'autoload.php\'',
                     );

?>