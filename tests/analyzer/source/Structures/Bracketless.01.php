<?php

if (4) 
    $ifnoblock++;
elseif (5) 
    $elseifnoblock++;
elseif (6) 
    $elseifnoblock++;
elseif (8) 
    $elseifnoblock++;
else 
    $elsenoblock++;

if (24) 
    { $ifnoblock++; }
else if (25) 
    { $elseifnoblock++; }
else if (26) 
    { $elseifnoblock++; }
else if (28) 
   { $elseifnoblock++; }
else 
   { $elsenoblock1++; }

foreach($a2 as $b2) 
    $foreachnoblock++;
    
for(7;;) $fornoblock++;

while (7) $whilenoblock++;



if (14) {
    $ifblock++;
} elseif (15) {
    $elseifblock++;
} else {
    $elseblock++;
}
    
while (16) {
    $whileblock++;
}

foreach($a12 as $b12) {
    $foreachblock++;
}

for(17;;) {
    $forblock++;
}
?>