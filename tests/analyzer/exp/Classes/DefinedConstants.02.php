<?php

$expected     = array('X::parentInterfaceDefinedConstant',
                      'X::definedConstant',
                      'x::definedConstant',
                     );

$expected_not = array('x::undefinedConstant',
                      'X::undefinedConstant',
                      'x::undefinedconstant',
                      'X::undefinedconstant',
                      'X::definedconstant',
                      'x::definedconstant',
                     );

?>