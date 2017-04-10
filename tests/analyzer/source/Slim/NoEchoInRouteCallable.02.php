<?php

$app = new Slim\App();

$app->get('/abd/', X::class);
$app->get('/abde/', Xwithecho::class);

class X {
    function run($a, $b, $c) {}
}

class Xwithecho {
    function run($a, $b, $c) {
        echo 'yes';
    }
}

?>
