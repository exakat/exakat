<?php

interface i {}
interface i1 extends i2 {}
interface i3 extends i1, i2 {}
interface i4 extends i1, i2, i3 {}

