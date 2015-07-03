<?php

array_map('callableString', $array);
array_map(array('callableClass', 'callableMethod'), $array);
call_user_func('MyClass::myCallbackMethod');

array_map($array, $string);

array_values('noCallableString', $arrayValues); // bad code, indeed.

?>