<?php

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