<?php

// \File is a CakePHP class
new File($poFile, true);

class foo implements CakeEventListener {
    use FileConfigTrait;
}

// Not Cake classes
new NotCakeFile($poFile, true);

class foo implements NotCakeCakeEventListener {
    use NotCakeFileConfigTrait;
}

?>