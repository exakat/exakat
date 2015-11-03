<?php

libxml_use_internal_errors(true);

$doc = simplexml_load_string($xmlstr);
$xml = explode("\n", $xmlstr);

if (!$doc) {
    $errors = libxml_get_errors();

    foreach ($errors as $error) {
        echo display_xml_error($error, $xml);
    }

    libxml_clear_errors();
}
?>