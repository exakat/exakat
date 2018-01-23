<?php

// both wrong : No need for initial \
define('\\SQLITE3_DETERMINISTIC', 13);
define('\CURLOPT_DEFAULT_PROTOCOL', 23);
define('\\CURLOPT_STREAM_WEIGHT', 43);
define(b'CURLOPT_STREAM_NOT_CURL', 33); // Not a ext/curl constant

// both OK as a 7.2 collision
define('CURLSSLOPT_NO_REVOKE', 33);
define(b'CURLMOPT_PUSHFUNCTION', 33);

// Ignored as dynamic
define("CURLSSLOPT_NO_$b", 34);

// What ? 
define('', 35);

?>