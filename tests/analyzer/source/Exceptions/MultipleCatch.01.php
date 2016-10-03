<?php

try {  throw new Exception(); }
catch (Single $s) {}
catch (Single\Nsname $s) {}
catch (D1 | D2 $s) {}
catch (T1 | T2 | T3 $s) {}
catch (\Q1 | \Q2 | \Q3 | \Q4 $s) {}
finally {}
?>