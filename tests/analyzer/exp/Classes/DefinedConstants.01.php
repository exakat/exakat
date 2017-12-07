<?php

$expected     = array('X::definedconstant',
                      'x::definedconstant',
                      'X::definedConstant',
                      'x::definedConstant',
                      'X::parentClassDefinedConstant',
                      'X::parentInterfaceDefinedConstant',
                     );

$expected_not = array('x::undefinedConstant',
                      'X::undefinedConstant',
                      'x::undefinedconstant',
                      'X::undefinedconstant',
                     );

?>