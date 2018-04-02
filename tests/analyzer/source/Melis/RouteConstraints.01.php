<?php

$array = array(
'options' => array(
    'route'    => 'NoConstraints/module[/:xxx]/pageid',
  )
);

$array = array(
'options' => array(
    'route'    => 'MissingSome/AddingSome[/:moduleName]/pageid[/:pageid]/exclude-pageid[/:expageid]',
    'constraints' => array(
        'moduleName' => '[a-zA-Z][a-zA-Z0-9_-]*',
        'moduleName2' => '[a-zA-Z][a-zA-Z0-9_-]*',
    ),
  )
);


$array = array(
'options' => array(
    'route'    => 'GoodNumber/module[/:moduleName]/pageid[/:moduleName2]/exclude-pageid/',
    'constraints' => array(
        'moduleName' => '[a-zA-Z][a-zA-Z0-9_-]*',
        'moduleName2' => '[a-zA-Z][a-zA-Z0-9_-]*',
    ),
  )
);
?>