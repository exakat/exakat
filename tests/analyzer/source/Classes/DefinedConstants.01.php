<?php

class y {
    const parentClassDefinedConstant = 3;
}


interface z {
    const parentInterfaceDefinedConstant = 4;
}

class x extends y implements z {
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