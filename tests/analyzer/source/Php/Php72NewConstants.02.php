<?php

// both wrong : No need for initial \
define('\\SQLITE3_DETERMINISTIC', 13);
define('\CURLOPT_DEFAULT_PROTOCOL', 23);
define(b'CURLOPT_STREAM_NOT_CURL', 33);

// both OK as a 7.2 collision
define('CURLSSLOPT_NO_REVOKE', 33);
define(b'CURLMOPT_PUSHFUNCTION', 33);

// Ignored as dynamic
define("CURLSSLOPT_NO_$b", 34);

// What ? 
define('', 35);

?>