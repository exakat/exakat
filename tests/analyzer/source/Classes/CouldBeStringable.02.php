<?php

interface i {
    function __toString() ;
}

interface j {}

interface k extends stringable {
    function __toString();
}

interface kk extends k {
    function __toString();
}

?>