<?php

namespace A {
    class XX { }
    
    new XX();
    new \A\XX();
    
    new XX;
    new \A\XX();
    
    new xx();
    new \a\xx();
    
    new xx;
    new \a\xx();
}
?>