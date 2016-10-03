<?php

// OK
print esc_attr($x);
print(esc_html($x));

print $a.' b '.$c;

?>A<?= ($e.$g) 
?>A<?= (esc_html($e).esc_xml($g)) 
?><?= $G; ?>