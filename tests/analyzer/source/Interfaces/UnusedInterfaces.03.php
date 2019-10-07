<?php

interface arg {}
interface returntype {}
interface withInstanceof {}
interface unused {}

function unused(arg $arg) : returntype {
    return $arg instanceof withInstanceof;
}


?>