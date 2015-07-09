<?php

class b extends guzzlehttp\pool {
    public function methodHeritedFromComposer(){} 
}

class c extends b {
    public function methodSubHeritedFromComposer(){} 
}

class d extends b {
    public function methodSubSubHeritedFromComposer(){} 
}

class e extends f {
    public function methodHeritedFromUnknownClass(){} 
}

?>