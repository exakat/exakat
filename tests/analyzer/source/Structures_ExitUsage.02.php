<?php

if (defined('CONSTANTE')) { die('No access');}

if ($someCondition) {
    doSomething();
} else {
    exit('some exit');
}
die;

?>