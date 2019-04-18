<?php
include __DIR__ . '/public/index.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'app.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap/constants.php';
include dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'bootstrap/autoload.php';
include dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'autoload.php';
include dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'bootstrap/autoload.php';
include dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'bootstrap/autoload.php';
include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app/Extensions/util/PhpYenc.php';
include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources/views/themes/smarty.php';
include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
include NN_LIB . 'utility/SmartyUtils.php';

?>