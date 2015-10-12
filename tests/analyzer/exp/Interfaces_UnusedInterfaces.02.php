<?php

$expected     = array('interface unusedInterface',
                      'interface d extends \usedInterfaceFPImplements2, usedInterfaceImplements2');

$expected_not = array('interface usedInterfaceFPImplements',
                      'interface usedInterfaceImplements',
                      'interface usedInterfaceFPImplements2',
                      'interface usedInterfaceImplements2',
                      'interface usedInterfaceInstanceof',
                      'interface usedInterfaceFPInstanceof',
                      'interface usedInterfaceTypehint',
                      'interface usedInterfaceFPTypehint');

?>