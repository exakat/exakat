<?php

// This has no noDelimiter but it may crash the query
include dirname(__FILE__).'/include.PHP';
include (dirname(__FILE__)).'/include.phP';
include (dirname(__FILE__)).'/include.pHP';

include 'include.php';
include 'include.PHP';

const MY_INCLUDE = 'include.PHP';
include(MY_INCLUDE);

?>