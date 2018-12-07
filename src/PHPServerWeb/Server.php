<?php

namespace PHPServerWeb;

class Server
{
	const SERVER_PORT = '1234';
	const SERVER_LISTEN_ADDRESS = '0.0.0.0';

	private $factory = null;
	private $socket = null;
	private $http = null;

	public function __construct(Factory $factory)
	{  
		$this->socket = $factory->createServer('tcp://'.self::SERVER_LISTEN_ADDRESS.':'.self::SERVER_PORT);
		$this->http = $factory->createHttpProtocol();
		$this->factory = $factory;
	}

	public function run($numberOfMaxConnectionExpected = -1)
	{
		$loop = 0;
		while($loop != $numberOfMaxConnectionExpected)
		{
			$client = $this->socket->accept();
			$connection = $this->factory->createConnectionFromSocket($client);
			$request  = $connection->readRequest();
			$response = $this->http->handleRequest($request);
			$connection->sendResponseAndClose($response);
			$loop++;
		}
	}
}

