<?php

if (defined('CONSTANTE')) { die('No access');}
if (!defined('OTHER_CONSTANTE')) { die('No other access');}

if ($someCondition) {
    doSomething();
} else {
    exit('some exit');
}
die;

?>