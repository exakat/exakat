<?php

trigger_error('this is a mistake');

USER_ERROR('this is another mistake', E_USER_NOTICE);

$a->trigger_error('this is a method');

?>