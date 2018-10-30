<?php

namespace {
    new Exception("A");
    new NotException("B");
    
}

namespace B {
    new DomainException("C");
    new \LengthException("D");
    
}

?>