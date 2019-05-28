<?php

const WITH_SLASH = '/sb';
const WITHOUT_SLASH = 'asb';

$a = __DIR__.WITHOUT_SLASH;
$a = __DIR__.WITH_SLASH;

$a = 'a'.__DIR__.WITHOUT_SLASH;
$a = 'a'.__DIR__.WITH_SLASH;

?>