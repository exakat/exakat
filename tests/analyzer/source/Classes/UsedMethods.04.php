<?php


class A {
    public function usedStaticallyInArrayMethod() {}
    public function usedStaticallyInStringMethod() {}
    public function usedWithThisMethod() {}
    public function unusedMethod() {}

	protected function process() {
	    array_map(array($this, 'usedWithThisMethod'), $b);
        array_filter($array, array('A', 'usedStaticallyInArrayMethod'));
        preg_replace_callback('regex', 'A::usedStaticallyInStringMethod', $variable);

        sqlite_create_aggregate('handler0', 'handler1', 'handler2', array('A::undefinedMethod'));
	}
}

?>