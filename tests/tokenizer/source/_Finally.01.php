<?php
function doSomething() {
    $resource = createResource();
    try {
        $result = useResource($resource);
        return $result;
    }
    catch (Exception $e) {
        log($e->getMessage());
        throw $e;
    }
    catch (Exception2 $e) {
        log($e->getMessage());
        throw $e;
    }
    catch (Exception3 $e) {
        log($e->getMessage());
        throw $e;
    }
    catch (Exception4 $e) {
        log($e->getMessage());
        throw $e;
    }
    catch (Exception5 $e) {
        log($e->getMessage());
        throw $e;
    }
    finally {
        releaseResource($resource);
    }
}

?>
