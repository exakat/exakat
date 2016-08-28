<?php

class za  {
    public function grandParentExists(){}        // yes
    public function grandParentNotDerived(){}    // yes
}

abstract class aa extends za {
    private function parentIsConcrete(){}        // yes
    protected function parentNotDerived(){}      // yes
    public function onlyInAA(){}      // yes
}

class ba extends aa {
    public function grandParentExists(){}         // No (devired from grand-parent)s
    public function parentIsConcrete(){}          // No (Derived from parent)
    public function onlyInBA(){}      // yes
}

class ca extends ba {
    public function grandParentExists(){}         // No (devired from grand-parent)s
    public function onlyInCA(){}      // yes
}

class da extends ca {
    public function grandParentExists(){}         // No (devired from grand-parent)s
    public function onlyInDA(){}      // yes
}

?>