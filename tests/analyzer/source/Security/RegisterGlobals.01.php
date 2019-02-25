<?php

// Some loop
foreach($source1 as $k1 => $v1) {
    $$k1 = $v1;
}

// Some loop on GPC without Keys
foreach($_GET as $v2) {
    $$a2 = $v2;
}

// Some loop on GPC with Keys without $$k
foreach($_GET as $k3 => $v3) {
    $$v3 = 1;
}

// Some loop on GPC with $$k not set
foreach($_REQUEST as $k4 => $v4) {
    $x4 = $$k4;
}

// Some loop on GPC with $$k 
foreach($_REQUEST as $k5 => $v5) {
    $$k5 = $v5;
}

// Some loop on GPC with $$k 
foreach($_REQUEST as $k6 => $v6) {
    ${$k6} = $v6;
}

// With extract
extract($_FILES);
extract($_GET, EXTR_IF_EXISTS);
extract($_POST, EXTR_IF_EXISTS | EXTR_REFS);

extract($_REQUEST, EXTR_SKIP); // This is OK
extract($_REQUEST, EXTR_SKIP | EXTR_REFS); // This is OK
extract($args, EXTR_SKIP); // This is OK


// Old style
import_request_variables();

?>