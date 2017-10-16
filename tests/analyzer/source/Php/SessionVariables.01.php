<?php

if (isset($_SESSION['mySessionVariable'])) {
    $_SESSION['mySessionVariable']['counter']++;
} else {
    $_SESSION['mySessionVariable'] = array('counter'  => 1, 
                                           'creation' => time());
}

    $_SESSIONS['other'] = 3;
    A::$_SESSION['static'] = 4;

?>