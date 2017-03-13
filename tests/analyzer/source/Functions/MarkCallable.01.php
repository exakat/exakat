<?php

array_map('callableString', $array1);
call_user_func($array2, 'nonCallableString');
//array_map($array, $nonCallableVar);

array_filter($array3, array('string', 'string'));
array_reduce(array('string', 'string'), $array4);

preg_replace_callback('a', 'MyClass::myCallbackMethod', 'b');
sqlite_create_function('MyClass::myNonCallbackMethod', $b, $c1);
sqlite_create_aggregate($a1, 'MyClass2::myNonCallbackMethod', $c2);
sqlite_create_aggregate($a2, 'MyClass2::myNonCallbackMethod', c3, d4);

?>