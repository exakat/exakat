<?php

class a { 
    const C = 1,
          C1 = a::C1,
          C3 = a::c3,
          c4 = a::C4,
          
          C12 = a::C22,
          C2 = a::C2 + 2,
          
          D1 = a::D1 + 1,
          D3 = a::d3 + 2,
          d4 = a::D4 + 3,
          
          E = 3;
} 

print a::C1;

?>