<?php

$expected     = array('interface usedInterfaceFPImplements',
                      'interface usedInterfaceImplements',
                      'interface usedInterfaceFPImplements2',
                      'interface usedInterfaceImplements2');

$expected_not = array('interface usedInterfaceUnused',
                      'interface usedInterfaceInstanceof',
                      'interface usedInterfaceFPInstanceof',
                      'interface usedInterfaceTypehint',
                      'interface usedInterfaceFPTypehint');

?>