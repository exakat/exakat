<?php

class x {}
class y extends x {}
class z extends y {}
final class z2 extends y {}  // This class is not extended

?>