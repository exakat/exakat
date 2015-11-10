<?php
if (php_sapi_name() == 'cli') {

} else if (PHP_SAPI == 'litespeed') {

} else {
    $config['sapi'] = 'apache2handler';
}
?>
