<?php

interface usedInterface {}
interface unusedInterface {}

class a implements usedInterface{}
if ($a instanceof usedInterface) {}

?>