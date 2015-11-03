<?php

// NOT OK
file_put_contents('file.txt', 'ko1', 1);
file_put_contents('file.txt', 'ko2', "1");
file_put_contents('file.txt', 'ko3', '1');
file_put_contents('file.txt', 'ko4', null);
file_put_contents('file.txt', 'ko5', FILE_APPEND | 1);
file_put_contents('file.txt', 'ko6', \FILE_APPEND + LOCK_EX);
file_put_contents('file.txt', 'ko7', \FILE_APPEND | (LOCK_EX & (FILE_APPEND | 1)));

// OK
file_put_contents('file.txt', 'ok1', FILE_USE_INCLUDE_PATH);
file_put_contents('file.txt', 'ok2', \FILE_USE_INCLUDE_PATH);
file_put_contents('file.txt', 'ok3', FILE_APPEND | LOCK_EX);
file_put_contents('file.txt', 'ok4', FILE_APPEND | \LOCK_EX);
file_put_contents('file.txt', 'ok5', \FILE_APPEND | LOCK_EX);
file_put_contents('file.txt', 'ok6', \FILE_APPEND | (LOCK_EX | FILE_APPEND));

file_put_contents('file.txt', 'ok7', $variable);
file_put_contents('file.txt', 'ok8', $array[1]);
file_put_contents('file.txt', 'ok9', $object->property);
file_put_contents('file.txt', 'ok10', Classe::$Property);
file_put_contents('file.txt', 'ok11', Classe::Method());
file_put_contents('file.txt', 'ok12', Classe::constante);


?>