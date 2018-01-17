<?php

$expected     = array('class inANew { /**/ } ',
                      'class inAExtends { /**/ } ',
                      'class inAImplements { /**/ } ',
                      'class inAImplements1 { /**/ } ',
                      'class inAImplements2 { /**/ } ',
                      'class inAImplements3 { /**/ } ',
                      'class inAStaticMethodcall { /**/ } ',
                      'class inAStaticProperty { /**/ } ',
                      'class inAStaticConstant { /**/ } ',
                      'class inAInstanceof { /**/ } ',
                      'class inAString { /**/ } ',
                      'class inATypehint { /**/ } ',
                     );

$expected_not = array('class unusedClass { /**/ } ',
                      'class someClass extends inAExtends implements inAImplements { /**/ } ',
                      'class someClass2 implements inAImplements1, inAImplements2, inAImplements3 { /**/ } ',
                      'class inAString2 { /**/ } ',
                      'class inAUseWithAlias { /**/ } ',
                     );

?>