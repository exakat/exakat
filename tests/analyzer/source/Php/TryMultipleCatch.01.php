<?php

try { 
    OneCatch(); 
} catch (FirstException $e) {

}

try { 
    TwoCatches(); 
} catch (FirstException $e) {
} catch (SecondException $e) {
}

try { 
    ThreeCatches(); 
} catch (FirstException $e) {
} catch (SecondException $e) {
} catch (ThirdException $e) {
}

?>