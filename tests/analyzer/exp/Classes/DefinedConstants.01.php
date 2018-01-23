<?php

$expected     = array('X::definedConstant',
                      'x::definedConstant',
                      'X::parentClassDefinedConstant',
                      'X::parentInterfaceDefinedConstant',
                     );

$expected_not = array('X::definedconstant',
                      'x::definedconstant',
                      'x::undefinedConstant',
                      'X::undefinedConstant',
                      'x::undefinedconstant',
                      'X::undefinedconstant',
                     );

?>