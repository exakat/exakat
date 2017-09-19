<?php

$expected     = array('X::parentInterfaceDefinedConstant', 
                      'X::definedconstant', 
                      'x::definedConstant', 
                      'X::definedConstant', 
                      'x::definedconstant',
                     );

$expected_not = array('x::undefinedConstant',
                      'X::undefinedConstant',
                      'x::undefinedconstant',
                      'X::undefinedconstant',
                     );

?>