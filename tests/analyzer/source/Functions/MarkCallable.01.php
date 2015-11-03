<?php

array_map('callableString', $array);
call_user_func($array, 'nonCallableString');
//array_map($array, $nonCallableVar);

array_filter($array, array('string', 'string'));
array_reduce(array('string', 'string'), $array);

preg_replace_callback('a', 'b', 'MyClass::myCallbackMethod');
sqlite_create_function('MyClass::myNonCallbackMethod', $b, $c);
sqlite_create_aggregate($a, 'MyClass2::myNonCallbackMethod', $c);


?>