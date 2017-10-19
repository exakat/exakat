<?php

$domain = 'myapp';
echo bindtextdomain($domain, '/usr/share/myapp/locale');

echo A::bindtextdomain($domain2, '/usr/share/myapp/locale2');
?>
