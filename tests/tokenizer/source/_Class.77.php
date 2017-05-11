<?php

namespace A;

interface a {}
class b {}
interface d {}

class c extends namespace\b implements namespace\a, namespace\d {}

?>