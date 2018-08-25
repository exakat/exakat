<?php
class clsKO extends \SplFileObject {
    public function __construct()    {    }
}

class clsOK extends \SplFileObject {
    public function __construct()    { 
        parent::__construct();
   }
}

class clsKO2 extends \SplFileObject {
    public function __construct()    { 
        elephant::__construct();
   }
}


class clsKO3 extends \SplFileObject {
    public function __construct()    { 
        parent::__constructor();
   }
}

class clsKO4 extends \SplFileObject {
    public function __construct()    { 
        self::__construct();
   }
}

class clsKO5 extends \SplFileObject {
    public function __construct()    { 
        $this->__construct();
   }
}

?>