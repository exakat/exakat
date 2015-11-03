<?php

try { $a1++; } 
catch (Exception $e) { }
catch (Exception2 $e2) { }

try { $a2++; } 
catch (Exception $e) { }
catch (Exception2 $e2) { }
finally { $a++; }
?>