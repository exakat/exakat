<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/
namespace Psr\Container {
// Paste Psr interfaces here.
}

namespace {

$interfaces = get_declared_interfaces();

foreach($interfaces as $interface) {
    $interface = new ReflectionClass($interface);
    if ($interface->isInternal()) { continue; }

    $interfaceArray = array('name'      => $interface->getName(),
                            'namespace' => $interface->getNamespaceName(),
                            'methods'   => array());
    
    if ($interface->getInterfaces()) {
        $interfaceArray['extends'] = array();
        
        foreach($interface->getInterfaces() as $extends) {
            $interfaceArray['extends'][] = $extends->getName();
        }
    }
    
    
    foreach($interface->getMethods() as $method) {
        $methodArray = array('name'       => $method->getName(),
                             'parameters' => array()   );
        
        foreach($method->getParameters() as $parameter) {
            $parameterArray = array('name'       => '$'.$parameter->getName());
            
            if ($parameter->isDefaultValueAvailable()) {
                $parameterArray['default'] = $parameter->getDefaultValue();
            }
    
            if ($parameter->hasType()) {
                $parameterArray['type'] = (string) $parameter->getType();
            }
            
            $methodArray['parameters'][] = $parameterArray;
        }
        
        $interfaceArray['methods'][] = $methodArray;
    }
    
    $array['interfaces'][] = $interfaceArray;
}

echo json_encode($array, JSON_PRETTY_PRINT);
}
?>