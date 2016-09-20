<?php

fopen('http://www.php.net/','r+');
fopen("https://$site",'r+');
fopen('http://'.$site2.'/','r+');
fopen("./$site",'r+');

fopen('./'.SITE,'r+');

?>