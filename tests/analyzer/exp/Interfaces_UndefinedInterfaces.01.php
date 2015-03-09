<?php

$expected     = array('interface usedInterfaceInstanceof',
                      'interface usedInterfaceFPInstanceof',
                      'interface usedInterfaceTypehint',
                      'interface usedInterfaceFPTypehint');

$expected_not = array('interface usedInterfaceUnused',
                      'interface usedInterfaceFPImplements',
                      'interface usedInterfaceImplements',
                      'interface usedInterfaceFPImplements2',
                      'interface usedInterfaceImplements2');

?>