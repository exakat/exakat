<?php

$expected     = array('X::undefinedconstant',
                      'x::undefinedconstant',
                      'X::undefinedConstant',
                      'x::undefinedConstant',
                      'X::parentClassUndefinedConstant',
                      'X::parentInterfaceUndefinedConstant',
                     );

$expected_not = array('X::definedconstant',
                      'x::definedconstant',
                      'X::definedConstant',
                      'x::definedConstant',
                      'X::parentClassDefinedConstant',
                      'X::parentInterfaceDefinedConstant',
                     );

?>