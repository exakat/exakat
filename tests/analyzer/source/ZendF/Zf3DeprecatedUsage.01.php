<?php

// Deprecated class
$a = new Zend\Authentication\Adapter\DbTable();

// deprecated method : 
$b->setLibOption();

// deprecated constant : 
Zend\Db\Sql::JOIN_OUTER_LEFT;
Zend\Db\Sql::JOIN_LEFT;

// deprecated trait
class foo {
    use Zend\EventManager\ProvidesEvents;
}

// deprecated interface
class foo2 implements Zend\EventManager\SharedEventAggregateAwareInterface {}

// deprecated property
$a->allowEmpty = 2;

?>