<?php

// OK, minimum spip inclusion
include 'inc_version.php';
require('../inc_version.php');
require_once _DIR_RESTREINT_ABS.'inc_version.php';

// KO
include_once('ecrire.php');

// OK, normal way
spip_include('editer.php');

?>
