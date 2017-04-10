<?php

$app = new Slim\Slim();

// '/admin/' is a route. 
$app->get('/admin/', function ($x) { /* do something(); */ });

// '/contact/'.$email is a dynamic route. 
$app->post('/contact/'.$email.'/{id}', function ($x) { /* do something(); */ });

// Not to be found
$session->get('cookie');

?>