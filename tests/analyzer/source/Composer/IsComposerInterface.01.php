<?php

class a implements GuzzleHttp\ToArrayInterface {}

class b implements NotGuzzleHttp\ToArrayInterface {}

class c implements NotGuzzleHttp\NotToArrayInterface {}

?>