<?php

setcookie($a1); // ignored, as unsetting cookie

setcookie($a2, ''); // ignored, as unsetting cookie
setcookie($a3, null); // ignored, as unsetting cookie
setcookie($a3b, "a"); // reported

setcookie($a4, $b4); 
setcookie($a5, $b5, time() + 3600); 
setcookie($a6, $b6, time() + 3600, '/'); 
setcookie($a7, $b7, time() + 3600, '/', 'domain.com'); 
setcookie($a8, $b8, time() + 3600, '/', 'domain.com', $_SERVER['HTTPS']); 

// All good
setcookie($a9, $b9, time() + 3600, '/', 'domain.com', $_SERVER['HTTPS'], 1); 
?>