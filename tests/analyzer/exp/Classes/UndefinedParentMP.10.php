<?php

$expected     = array('parent::$zi', // private
                      'parent::$y', 
                      'parent::y( )', 
                      );

$expected_not = array('parent::$zu', // public
                      'parent::$zo', // protected
                      );

?>