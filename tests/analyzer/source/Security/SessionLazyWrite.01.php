<?php

class missingImplements implements sessionhandlerinterface {}

class mySessionHandler implements sessionhandlerinterface, SessionUpdateTimestampHandlerInterface {}


interface missingImplementsInterface extends sessionhandlerinterface {}

interface mySessionHandlerInterface extends sessionhandlerinterface, SessionUpdateTimestampHandlerInterface {}

?>