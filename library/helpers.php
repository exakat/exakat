<?php

function display($text) {
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }
    
    if ($config->verbose) {
        print $text;
    }
}

function display_r($object) {
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }
    
    if ($config->verbose) {
        print_r( $object );
    }
}


?>