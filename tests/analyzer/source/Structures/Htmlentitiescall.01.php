<?php

htmlentities($miss_2);

htmlentities($miss_1, ENT_QUOTES);

htmlentities($miss_1_wrong_2, E_ALL);

htmlentities($wrong_3, ENT_QUOTES, 'UTF9');

htmlentities($allOK, ENT_COMPAT, 'UTF-8');
htmlentities($allOK, ENT_COMPAT, "UTF-8");

?>