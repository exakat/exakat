<?php

//https://github.com/grpc/grpc/blob/master/examples/php/greeter_client.php

require dirname(__FILE__).'/vendor/autoload.php';
// The following includes are needed when using protobuf 3.1.0
// and will suppress warnings when using protobuf 3.2.0+
@include_once dirname(__FILE__).'/helloworld.pb.php';
@include_once dirname(__FILE__).'/helloworld_grpc_pb.php';
function greet($name)
{
    $client = new Helloworld\GreeterClient('localhost:50051', [
        'credentials' => Grpc\ChannelCredentials::createInsecure(),
    ]);
    $request = new Helloworld\HelloRequest();
    $request->setName($name);
    list($reply, $status) = $client->SayHello($request)->wait();
    $message = $reply->getMessage();
    return $message;
}
$name = !empty($argv[1]) ? $argv[1] : 'world';
echo greet($name)."\n";

?>