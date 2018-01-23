<?php

const PROPERTY = 'constante_property';
const PROPERTY_ARRAY = 'constante_property_array';

$model->setVariable();
$model->setVariable('property', 1, 'third_and_ignored');
$model->setVariable($variable, 1);
$model->setVariable(PROPERTY, 1);

$model->setVariables();
$model->setVariables($variable);
$model->setVariables(array('propertyArray' => 1,
                           PROPERTY_ARRAY  => 2,
                           'notKeyValue'));
                           
$variable = array('outside');

$variable->someProperty;

 ?>