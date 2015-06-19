<?php

try {}
catch (A $a) {  }
catch (B $b) { $b = 2; }
catch (C $c) { throw $c; }

?>