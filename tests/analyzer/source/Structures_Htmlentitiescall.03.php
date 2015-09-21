<?php

htmlentities($miss_2);

htmlentities($miss_11, ENT_QUOTES, 'UTF-8');
htmlentities($miss_12, \ENT_QUOTES, 'UTF-8');

htmlentities($miss_13, ENT_QUOTES | ENT_COMPAT, 'UTF-8');
htmlentities($miss_14, \ENT_QUOTES | \ENT_COMPAT, 'UTF-8');

htmlentities($miss_15, ENT_QUOTES | \ENT_COMPAT, 'UTF-8');
htmlentities($miss_16, ENT_QUOTES | \ENT_COMPAT, 'UTF-8');

?>