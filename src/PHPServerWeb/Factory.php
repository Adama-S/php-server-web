<?php

namespace PHPServerWeb;

class Factory
{
	static public function createHttpServer() : \PhpServerWeb\Server
	{
		return new Server(new Factory());
	}

	public function createServer($address) : \Socket\Raw\Socket
	{
		$factory = new \Socket\Raw\Factory();
		return $factory->createServer($address);
	}

	public function createConnectionFromSocket(\Socket\Raw\Socket $socket) : Connection
	{
		return new Connection($socket);
	}

	public function createHttpProtocol() : \PhpServerWeb\HttpProtocol
	{
		return new HttpProtocol();
	}


}