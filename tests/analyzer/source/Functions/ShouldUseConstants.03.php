<?php

// NOT OK
setlocale(1, 'ko1');
setlocale("1", 'ko2');
setlocale('1', 'ko3');
setlocale(null, 'ko4');
setlocale(FILE_APPEND | 1, 'ko5');
setlocale(\FILE_APPEND + LOCK_EX, 'ko6');

// OK
setlocale(FILE_USE_INCLUDE_PATH, 'ok1');
setlocale(\FILE_USE_INCLUDE_PATH, 'ok2');
setlocale(FILE_APPEND | LOCK_EX, 'ok3');
setlocale(FILE_APPEND | \LOCK_EX, 'ok4');
setlocale(\FILE_APPEND | LOCK_EX, 'ok5');
setlocale(\FILE_APPEND | (LOCK_EX | FILE_APPEND), 'ok7');

setlocale($variable, 'ok8');
setlocale($object->property, 'ok9');


?>