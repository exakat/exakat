<?php

// 3 of them
ini_set ('session.name', 'mySession');
ini_set("session.cookie_httponly", 1); 
ini_set('session.gc_maxlifetime', 60 * 60);
session_start();

// Just one
$a++;
ini_set('session.save_path', '_sessions');
session_start();

// That's good too
$a++;
session_start();
ini_set('session.save_path', '_sessions');

// That's wrong : session name MUST be before
$a++;
session_start([WRONG]);
ini_set('session.name', '_sessions');

ini_set("session_name", 'asasdf');

?>