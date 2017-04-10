<?php

$app = new \Slim\Slim();
$app->get('/a/');
$app->pat('/b/');
$app->delete();

$session = new \Slim\Session();
$session->delete();

?>