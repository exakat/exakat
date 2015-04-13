<?php

$expected     = array('parent::$zi', // private
                      );

$expected_not = array('parent::$y', 
                      'parent::y( )', 
                      'parent::$zu', // public
                      'parent::$zo', // protected
                      );

?>