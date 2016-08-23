<?php

$expected     = array('parent::$zu', // public
                      'parent::$zo', // protected
                      );

$expected_not = array('parent::$zi', // private
                      'parent::$y', 
                      'parent::y( )', 
                      );
?>