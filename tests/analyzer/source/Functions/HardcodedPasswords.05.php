<?php 

const MYSQL_PASS = 'abc';

$not_a_password = 'abc';

class a {
    const MYSQL_PASS = 'abc';
}

mysql_connect('localhost', 'root', MYSQL_PASS);
mysql_connect('localhost', 'root', A::MYSQL_PASS);
mysql_connect('localhost', 'root', $not_a_password);
mysql_connect('localhost', 'root', null);
mysql_connect('localhost', 'root', 'abc');

 ?>