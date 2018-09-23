<?php

$expected     = array('class unusedClass { /**/ } ',
                      'class inAString2 { /**/ } ',
                      'class inAUseWithAlias { /**/ } ',
                      'class inAUseWithAlias2 { /**/ } ',
                      'class inAExtends { /**/ } ',
                      'class inAImplements { /**/ } ',
                      'class inAImplements1 { /**/ } ',
                      'class inAImplements2 { /**/ } ',
                      'class inAImplements3 { /**/ } ',
                     );

$expected_not = array('class inANew { /**/ } ',
                      'class inAString { /**/ } ',
                      'class inAStaticMethodcall { /**/ } ',
                      'class inAStaticProperty { /**/ } ',
                      'class inAStaticConstant { /**/ } ',
                      'class inAInstanceof { /**/ } ',
                      'class inATypehint { /**/ } ',
                      'class someClass extends inAExtends implements inAImplements { /**/ } ',
                      'class someClass2 implements inAImplements1, inAImplements2, inAImplements3 { /**/ } ',
                     );

?>