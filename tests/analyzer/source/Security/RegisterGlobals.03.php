<?php
    // OK
    parse_str($a->b()->c(), $d);
    $c->parse_str($a->b()->c());
    
    $c->extract($_POST);

    // KO
    parse_str($a->b()->c());

    \extract($_GET);

?>