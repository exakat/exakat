<?php


namespace test;

class Foo {
  public function test() {
    try {
      something_that_might_break();
    } catch (Exception $e) {
        print "HERE\n";
    } catch (\Exception $e) {
        print "HERE\n";
    } catch (LocalException $e) {
        print "HERE\n";
    } catch (LocalSubException $e) {
        print "HERE\n";
    } catch (LocalSubSubException $e) {
        print "HERE\n";
    } catch (LocalNonException $e) {
        print "HERE\n";
    } catch (UndefinedClass $e) {
        print "HERE\n";
    } catch (OutOfBoundsException $e) {
        print "HERE\n";
    } catch (\OutOfBoundsException $e) {
        print "HERE\n";
    }
  }
}

class LocalException extends \Exception {}
class LocalSubException extends LocalException {}
class LocalSubSubException extends LocalSubException {}
class LocalNonException {}

function something_that_might_break() {
    print __METHOD__;
    throw new \Exception('a,a,');
}

$f = new foo();
$f->test();

?>