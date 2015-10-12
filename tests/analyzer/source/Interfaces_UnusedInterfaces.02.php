<?php

interface usedInterfaceInstanceof { function fui(); }
interface usedInterfaceImplements { function fui(); }
interface usedInterfaceImplements2 { function fui(); }
interface usedInterfaceTypehint { function fui(); }
interface usedInterfaceFPInstanceof { function fui(); }
interface usedInterfaceFPImplements { function fui(); }
interface usedInterfaceFPImplements2 { function fui(); }
interface usedInterfaceFPTypehint { function fui(); }

interface unusedInterface {    function funi(); }

class a implements usedInterfaceImplements { function fui() {} }
function b(usedInterfaceTypehint $a) {}
function c($ca) { 
    if ($ca instanceof usedInterfaceInstanceof) {} 
}
interface d extends usedInterfaceImplements2, \usedInterfaceFPImplements2 { function fui(); }



class a2 implements \usedInterfaceFPImplements { function fui() {} }
function b2(\usedInterfaceFPTypehint $a) {}
function c2($ca) { 
    if ($ca instanceof \usedInterfaceFPInstanceof) {} 
}

?>