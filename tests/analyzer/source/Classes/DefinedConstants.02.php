<?php

class y extends y {
    const parentClassDefinedConstant = 3;
}


interface z extends z {
    const parentInterfaceDefinedConstant = 4;
}

class x extends x implements z {
    const definedConstant = 1;
    const unusedConstant = 2;
}

X::parentClassDefinedConstant;
X::parentClassUndefinedConstant;

X::parentInterfaceDefinedConstant;
X::parentInterfaceUndefinedConstant;

x::definedConstant;
X::definedConstant;
x::definedconstant;
X::definedconstant;

x::undefinedConstant;
X::undefinedConstant;
x::undefinedconstant;
X::undefinedconstant;



?>