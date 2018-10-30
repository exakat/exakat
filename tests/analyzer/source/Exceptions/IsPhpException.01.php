<?php
new \invalidArgumentException('A');
new Exception('D');

new \B\invalidArgumentException('V');
new \NotPhpException('V');

new $e[1];
new $v;

?>