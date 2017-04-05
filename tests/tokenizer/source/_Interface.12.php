<?php

interface A {}

interface B extends A {}

class C implements B, A {}