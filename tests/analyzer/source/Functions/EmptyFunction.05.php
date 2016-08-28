<?PHP

CLASS ZA  {
    PUBLIC FUNCTION GRANDPARENTEXISTS(){}        // YES
    PUBLIC FUNCTION GRANDPARENTNOTDERIVED(){}    // YES

}

ABSTRACT CLASS AA EXTENDS ZA {
    PUBLIC ABSTRACT FUNCTION PARENTISABSTRACT(); // NO (ABSTRACT)
    PRIVATE FUNCTION PARENTISCONCRETE(){}        // YES
    PROTECTED FUNCTION PARENTNOTDERIVED(){}      // YES
}

class ba extends aa {
    public function grandParentExists(){}         // No (devired from grand-parent)s
    public function parentIsAbstract(){}          // No (derived from abstract parent)
    public function parentIsConcrete(){}          // No (Derived from parent)
    public function noParentMethod(){}            // Yes 
}

?>