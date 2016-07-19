<?php

// OK, (server_ are tainted) 
"http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . SOME_URL;
"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}". SOME_URL;

// All from HTTP
"http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['HTTP_PORT'] . SOME_URL;
"http://{$_SERVER['HTTP_HOST']}:{$_SERVER['HTTP_PORT']}". SOME_URL;


?>