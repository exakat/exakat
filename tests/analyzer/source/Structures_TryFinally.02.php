<?php

try {
    $a++;
} catch (Exception1 $e) { 
} catch (Exception2 $e) { 
}

try {
    $a++;
} catch (Exception1 $e) { 
} catch (Exception2 $e) { 
} finally {
    $a = $b++;
}
?>