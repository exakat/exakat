<?php

array_map(function ($a1) { return null;}, array_flip($tmp));

array_map(function ($a2) {}, array_flip($tmp));

array_map(function ($a3) {return; }, array_flip($tmp));

array_map(function ($a4) {return 2; }, array_flip($tmp));

array_map(function ($a5) {$a5++; }, array_flip($tmp));

?>