<?php

$array = array(
'options' => array(
    'route'    => 'one',
    'constraints' => array(
        'moduleName' => '[a-zA-Z][a-zA-Z0-9_-]*',
    ),
  )
);

$array = array(
'options' => array(
    'route'    => 'two',
    'constraints' => array(
        'pageid'     => '[0-9]+',
        'expageid'   => '[0-9;]+',
    ),
  )
);

$array = array(
'options' => array(
    'route'    => 'error',
    'constraints' => array(
        'error'      => '[0-9;+',
    ),
  )
);
?>