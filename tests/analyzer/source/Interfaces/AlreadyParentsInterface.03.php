<?php

// interface i is not defined in this file

interface j extends i {}

class a extends i {}
class b extends a implements i {}
class c extends b implements j {}
class d extends c implements i {}
class e extends d {}

?>