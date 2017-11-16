<?php

// Normal usage
echo count(array(1,2,3,4))." items\n";

// Error emiting usage
echo count('1234')." chars\n";

// Error emiting usage
echo count($unsetVar)." elements\n";

?>