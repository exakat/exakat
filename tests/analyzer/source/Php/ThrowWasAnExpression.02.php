<?php
throw $this->createNotFoundException();
// Evaluated as
throw ($this->createNotFoundException());
// Instead of
(throw $this)->createNotFoundException();
 
throw static::createNotFoundException();
// Evaluated as
throw (static::createNotFoundException());
 
throw $userIsAuthorized ? new ForbiddenException() : new UnauthorizedException();
// Evaluated as
throw ($userIsAuthorized ? new ForbiddenException() : new UnauthorizedException());
// Instead of
(throw $userIsAuthorized) ? new ForbiddenException() : new UnauthorizedException();
 
throw $maybeNullException ?? new Exception();
// Evaluated as
throw ($maybeNullException ?? new Exception());
// Instead of
(throw $maybeNullException) ?? new Exception();
 
throw $exception = new Exception();
// Evaluated as
throw ($exception = new Exception());
 
throw $exception ??= new Exception();
// Evaluated as
throw ($exception ??= new Exception());
 
throw $condition1 && $condition2 ? new Exception1() : new Exception2();
// Evaluated as
throw ($condition1 && $condition2 ? new Exception1() : new Exception2());
// Instead of
(throw $condition1) && $condition2 ? new Exception1() : new Exception2();

$condition || throw new Exception('$condition must be truthy')
  && $condition2 || throw new Exception('$condition2 must be truthy');
// Evaluated as
$condition || (throw new Exception('$condition must be truthy') && $condition2 || (throw new Exception('$condition2 must be truthy')));

throw new Exception('standalone');
?>