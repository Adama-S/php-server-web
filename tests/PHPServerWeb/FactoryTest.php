<?php

namespace PHPServerWeb;

use PHPUnit\Framework\TestCase;

final class FactoryTest extends TestCase
{

	function testFactoryCreateHttpServerReturnAServer()
	{
        $this->assertInstanceOf('PHPServerWeb\Server', Factory::createHttpServer());
	}

	function testFactoryCreateServerReturnASocket()
	{
		$factory = new Factory();
        $this->assertInstanceOf('Socket\Raw\Socket', $factory->createServer('tcp://0.0.0.0:12345'));
	}

	function testFactoryCreateConnectionFromSocketReturnAConnection()
	{
    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
    		->getMock();

		$factory = new Factory();
        $this->assertInstanceOf('PHPServerWeb\Connection', $factory->createConnectionFromSocket($mockedSocket));
	}

	function testFactoryCreateConnectionFromSocketFailIfASocketIsNotPassed()
	{
		$factory = new Factory();
		$this->expectException(\TypeError::class);
        $factory->createConnectionFromSocket('iAmNotASocket');
	}

	function testFactoryCreateHttpProtocolReturnAProtocol()
	{
		$factory = new Factory();
        $this->assertInstanceOf('PHPServerWeb\HttpProtocol', $factory->createHttpProtocol());
	}
}