<?php
if ($alternative) : 
    $y++;
endif;

if ($nonalternative) { --$y; }

foreach($a as $b) : 
    $y++;
endforeach;

foreach($nonalternative as $b) { --$y; }

for($i = 0; $i < 10; $i++) : 
    $y++;
endfor;

for($nonalternative = 0; $nonalternative < 10; $nonalternative++) {
    $y++;
}

switch ($alternative) :
    case 2 : break; 
endswitch;

switch ($nonalternative) {
    case 1 : break; 
}

while($alternative) : 
    $y++;
endwhile;

while($nonalternative) {
    $y++;
}

?>