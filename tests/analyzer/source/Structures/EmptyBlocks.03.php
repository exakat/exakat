<?php

while ($a) : endwhile; 

while ($b) : 
    !$a++;
endwhile; 

while ($c) { !$a++; } 

while ($d) { } 

?>