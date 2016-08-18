<?php

interface i {}
trait t {}

class xt implements t {}
class xi implements i {}
class xit implements i, t {}
class xti implements t, i {}

?>