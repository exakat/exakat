<?php

class za  {
    public function grandParentExists(){}        // yes
    public function grandParentNotDerived(){}    // yes

}
abstract class aa extends za {
    public abstract function parentIsAbstract(); // no (abstract)
    private function parentIsConcrete(){}        // yes
    protected function parentNotDerived(){}      // yes
}

class ba extends aa {
    public function grandParentExists(){}         // No (devired from grand-parent)s
    public function parentIsAbstract(){}          // No (derived from abstract parent)
    public function parentIsConcrete(){}          // No (Derived from parent)
    public function noParentMethod(){}            // Yes 
}

?>