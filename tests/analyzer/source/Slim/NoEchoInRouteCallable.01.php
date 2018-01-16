<?php

$app = new Slim\App();

$app->get('/abd/', function ($echo) { echo $echo; });
$app->get('/abde/', function ($withEcho) { $a = $withEcho; });

?>
