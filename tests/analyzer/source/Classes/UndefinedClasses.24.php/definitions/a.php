<?php


namespace A2 {
class ca {}

interface ia {}

trait ta { }

class_alias('\A2\ca', '\A\ca');
}

namespace {
class c2 {}

interface i2 {}

trait t2 { }

class_alias('\c2', '\c');

}

namespace B2 {
class cb {}

interface ib {}

trait tb { }

class_alias('\B2\cb', '\B\cb');

}
