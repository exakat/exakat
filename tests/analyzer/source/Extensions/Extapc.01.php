<?php
echo "Let's do something with success", PHP_EOL;

apc_store('anumber', 42);

echo apc_fetch('anumber'), PHP_EOL;

echo apc_dec('anumber'), PHP_EOL;
echo apc_dec('anumber', 10), PHP_EOL;
echo apc_dec('anumber', 10, $success), PHP_EOL;

var_dump($success);

echo "Now, let's fail", PHP_EOL, PHP_EOL;

apc_store('astring', 'foo');
apc_stores('foo', 'bar');

$ret = apc_dec('astring', 1, $fail);

var_dump($ret);
var_dump($fail);
?>